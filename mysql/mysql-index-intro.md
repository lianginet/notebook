#### 索引
即特定的MySQL字段进行一些特定的算法排序，帮助MySQL高效获取数据的数据结构

MySQL数据库支持多种索引类型，如BTree索引，哈希索引，全文索引等
**HASH**
通过建立特征值,然后根据特征值来快速查找
速度更快，但只能用于 =、<=>、IN操作符;优化器不能用于order by;任何查找操作必须是索引的完整列
**BTree**
列记录都是按照顺序排列的，可以优化用于比较或者范围查找操作(=, >, >=, <, <=, between, in)，以及用于group by, order by，而且对于字符串类型的索引，最左前缀字符串也可以充分利用索引，如like ‘admin%’会解释成 ‘admin’ <= key_col < ‘admil’

#### 索引类型
**index 普通索引**
1) MySQL的基本索引，无限制
2) 对于字符串类型，可以指定索引前缀长度

**unique 唯一索引**
唯一值，可以为NULL

** primary key 主键索引**
特殊的唯一索引，不允许有空值 - 一表只能有一个主键

**fulltext index 全文索引**「作用不大」
全文索引,针对值中的某个单词,比如一篇文章中的某个词

#### 索引操作
**索引创建**

```
# 表创建完之后创建
alter table table_name add index index_name(column, [column1,...])
alter table table_name add unique index_name(column)

# 表创建完之后创建
create table table_name (
    ......
    primary key (id),
    unique key index_name (column),
    key index_name (column)
);
```

**索引删除**
```
1) drop index index_name on table_name
2) alter table table_name drop index index_name
```
**索引查看**
```
show index from table_name \G;
```

#### 索引技巧
1 对 **where, on, group by, order by** 中出现的列使用索引
2 对较小数据列使用索引，可使索引文件更小,同时内存中也可以装载更多的索引键
3 较长字符串使用前缀索引
4 不建立过多索引, 除了增加额外的磁盘空间外,对于DML操作的速度影响很大,因为其每增删改一次就得更新索引
5 使用组合索引，注意顺序
6 维度高的列创建索引
数据列中**不重复值**出现的个数越大, 维度就越高;要为维度高的列创建索引,如性别和年龄,那年龄的维度高于性别，性别不适合创建索引，其维度过低

#### 不走索引的SQL
```
# 以下SQL不走索引
select * from users where name like '%tom'; # like 'tom%' 走索引
select * from users where age + 1 = 25;  # 索引列参与运算
select * from users where left(`create_time`,4) < 1990; # 使用函数，同上
select * from users where aa = 1; # 如果aa定义为字符串，则不走索引
# 当name age email都建立了索引，才走索引「查询中避免使用or」
select * from users where name='xx' or age = 22 or email='email@gmail.com';
```

#### 索引弊端
虽然索引提高了查询速度，当时却增加额外的磁盘空间,但是会降低DML操作的速度,因为其每增删改一次就得更新索引
```
PS: 一般情况下， 查询操作远大与DML操作
在大数据导入时,可以先删除索引,再批量插入数据,最后再添加索引
```