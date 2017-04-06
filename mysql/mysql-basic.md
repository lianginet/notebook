####连接
```
# 语法 mysql -h 127.0.0.1 -u root -p  无密码省略-p
$ sudo mysql -u root -p
Enter password: 
```
#### database管理
```
# create
mysql> create database samp_db default
    ->character set utf8
    ->collate utf8_general_ci;

# show
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| .....              |
| samp_db            |
+--------------------+

# use
mysql> use samp_db;

# select database()
mysql> select database();
+------------+
| database() |
+------------+
| samp_db    |
+------------+

# drop
mysql> create database test1;
mysql> drop database test1;
```

#### table管理
均在sam_db下操作
```
# create
# create 直接创建表
mysql> create table user(
    -> id int auto_increment primary key,
    -> name varchar(16) not null,
    -> age int not null,
    -> birthday datetime
    -> );

# create 利用已有表创建
mysql> create table new_user select * from user;
# show
mysql> show tables;

# desc
mysql> desc user;
+----------+-------------+------+-----+---------+----------------+
| Field    | Type        | Null | Key | Default | Extra          |
+----------+-------------+------+-----+---------+----------------+
| id       | int(11)     | NO   | PRI | NULL    | auto_increment |
| name     | varchar(16) | NO   |     | NULL    |                |
| age      | int(11)     | NO   |     | NULL    |                |
| birthday | datetime    | YES  |     | NULL    |                |
+----------+-------------+------+-----+---------+----------------+

# alter
# alter修改表的编码
mysql> alter table user convert to character set utf8;
# alter 添加列
mysql> alter table user add integral int;
# alter 修改字段
mysql> alter table user modify integral varchar(12);  # 修改类型
mysql> alter table user change integral level tinyint(1); # 修改名称
# alter 删除字段
mysql> alter table user drop column level;
# alter 设置null/not null
mysql> alter table user modify age int(3) null;
# rename
mysql> rename table user to users;
```

#### 数据操作
增删改查数据: 
```
# 增
mysql> insert into user values (null, 'tom', 23, '1994-10-24');
mysql> insert into user (name, age, birthday) values ('sam', 24, '1993-10-24'),('sam2', 25, '1992-10-24');

# 删
mysql> delete from user where name='tom';

# 改
mysql> update user set age=23 where name='sam';

# 查
mysql> select name,age from user;
```

#### 视图
视图是从一个或多个表导出的虚拟表
```
# 创建视图
mysql> create view user_view (
    -> name,age
    -> ) as select name,age from user;

# 创建或替换视图
mysql> create or replace view user_view (
    -> user_id,
    -> user_name,
    -> user_age
    -> ) as select id, name, age from user;

mysql> show tables;
+-------------------+
| Tables_in_samp_db |
+-------------------+
| ....              |
| user_view         |
+-------------------+

mysql> select * from user_view;
+---------+-----------+----------+
| user_id | user_name | user_age |
+---------+-----------+----------+
|       2 | sam       |       23 |
+---------+-----------+----------+

# 插入数据
# 操作视图即操作数据表
mysql> insert into user_view values (null, 'fmt', 22);

mysql> select * from user_view;
+---------+-----------+----------+
| user_id | user_name | user_age |
+---------+-----------+----------+
|       2 | sam       |       23 |
|       3 | fmt       |       22 |
+---------+-----------+----------+

mysql> select * from user;
+----+------+------+---------------------+
| id | name | age  | birthday            |
+----+------+------+---------------------+
|  2 | sam  |   23 | 1993-10-24 00:00:00 |
|  3 | fmt  |   22 | NULL                |
+----+------+------+---------------------+

# 删除视图
mysql> drop view user_view;
```

#### 用户管理
```
# 创建用户
# 语法
# create user 'username'@'host' identified by 'password'
# host用'%' 则代表可以从任意远程登录

mysql> create user 'samp'@'localhost' identified by 'samp';
```
```
# 授权
# 语法
# grant privileges on database.table to 'username'@'host'
# privileges - 用户的操作权限(select, update等 all所有权限)
# database.table 可使用*设置所有，如samp_db.*  *.*

mysql> grant select on samp_db.* to samp@localhost;

# 使用samp登录测试权限
mysql> use samp_db;

mysql> update user set birthday='1991-10-24' where name='fmt';
ERROR 1142 (42000): UPDATE command denied to user 'samp'@'localhost' for table 'user'

# 授权所有
mysql> grant all on samp_db.* to samp@localhost;
mysql> use samp_db;

mysql> update user set birthday='1991-10-24' where name='fmt';
Query OK, 1 row affected (0.09 sec)

# 用以上命令授权的用户无法给其他用户授权
# 若要带上授权权限，则使用命令
# grant privileges on database.table to 'username'@'host' with grant option;
```
```
# 密码设置/更改 
# 1.非要设置/修改的用户登录
# mysql5.7 密码字段为authentication_string(5.6前为password)
# SET PASSWORD FOR 'username'@'host' = PASSWORD('password');
# 2 当前登录用户
# set password = password('password');

# samp用户登录
mysql> set password = password('samp_db');

# root登录
mysql> set password for samp@localhost = password('samp');

# 销毁用户权限
revoke privilege on database.table from 'username'@'host';

# 删除用户
drop user username@host
```

#### 表的复制/备份/还原
```
# 复制表结构
# 1.含主键等信息的完整表
mysql> create table new_user like user;

# 2.只有表结构，没有主键等信息
> create table new_user1 select * from user;
# 或 create table new_user1 as (select * from user);

mysql> create table new_user2 select * from user where 1=2;  # 不会复制数据

# 表数据复制
mysql> select * from new_user;

mysql> insert into new_user select * from user;

# 查看表的创建语句
> show create table user;

# 清空表
mysql> truncate new_user1;

# 备份数据库
$ sudo mysqldump -u root -p 'passwd' > /data/backups/samp.sql
```
```
# 还原数据库
mysql> create database samp_db1;
mysql> use samp_db1
mysql> source /data/backups/samp.sql
```