###### 建表
```
# index_a表只有主键，无其他索引
mysql> create table index_a (
    -> id int unsigned not null auto_increment,
    -> title varchar(64) not null,
    -> name varchar(16) not null,
    -> stock int not nul default 0,
    -> create_time int not null default 0,
    -> primary key (id)
    -> );
Query OK, 0 rows affected (0.36 sec)
```
```
# index_b表有主键和单列索引
mysql> create table  index_b (
    ->     id int unsigned not null auto_increment,
    ->     title varchar(64) not null,
    ->     name varchar(16) not null,
    ->     sn varchar(12) not null default '',
    ->     stock int not null default 0,
    ->     create_time int not null default 0,
    ->     primary key (id),
    ->     unique key name(name) using btree,
    ->     unique key sn(sn) using btree
    -> );
Query OK, 0 rows affected (0.58 sec)
```
```
# index_c表有主键和组合索引
mysql> create table  index_c (
    ->     id int unsigned not null auto_increment,
    ->     title varchar(64) not null,
    ->     name varchar(16) not null,
    ->     sn varchar(12) not null default '',
    ->     stock int not null default 0,
    ->     create_time int not null default 0,
    ->     primary key (id),
    ->     KEY name_sn_index(name, sn) using btree
    -> );
Query OK, 0 rows affected (0.48 sec)
```
###### PHP脚本插入数据
```
<?php
// 三个表分别插入100w条数据
set_time_limit(0);
// 断掉连接 后台挂起
// fastcgi_finish_request();

$host = 'localhost';
$db   = 'samp_db';
$user = 'samp';
$pass = 'samp';

$conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);

$sth = $conn->beginTransaction();

$sql = 'insert into index_a
    (title, name, sn, stock, create_time)
    values (?,?,?,?,?)';  # index_a另外换成index_b index_c
$sth = $conn->prepare($sql);

$time = strtotime('2010-01-01');

echo date('H:i:s') . '<br>';
for ($i = 1; $i < 1000000; $i++) {
    // 设置当前第几个
    $num = substr($i + 1000000, 1, 6);
    // 插入数据
    $sth->execute([
        'title_' . $num,
        'name_' . $num,
        'sn_' . $num,
        rand(1000, 2000),
        $time,
    ]);
    $time += 50;
    // 每1w条提交一次
    if ($i % 10000 == 0) {
        $conn->commit();
        $conn->beginTransaction();
    }
}
$conn->commit();
echo date('H:i:s');
```
###### 索引简单测试
```
# 通过指定name分别查询
# a表name无索引
# b表name有单列索引
# c表name有组合索引
mysql> set profiling=1;
Query OK, 0 rows affected (0.00 sec)

mysql> select * from index_a where name='name_876543';
......
mysql> select * from index_b where name='name_876543';
......
mysql> select * from index_c where name='name_876543';
......

mysql> show profiles;
+----------+------------+------------------------------------------------+
| Query_ID | Duration   | Query                                          |
+----------+------------+------------------------------------------------+
|        1 | 1.70158355 | select * from index_a where name='name_876543' |
|        2 | 0.00110591 | select * from index_b where name='name_876543' |
|        3 | 0.02820820 | select * from index_c where name='name_876543' |
+----------+------------+------------------------------------------------+
3 rows in set (0.00 sec)
```
```
# 通过title查询
# a b c三表title均无索引
mysql> select * from index_a where title = 'title_456789';
......
mysql> select * from index_b where title = 'title_456789';
......
mysql> select * from index_c where title = 'title_456789';
......

mysql> show profiles;  # 只显示相关
+----------+------------+----------------------------------------------------+
| Query_ID | Duration   | Query                                              |
+----------+------------+----------------------------------------------------+
|        4 | 0.51155937 | select * from index_a where title = 'title_456789' |
|        5 | 0.63892961 | select * from index_b where title = 'title_456789' |
|        6 | 0.54366214 | select * from index_c where title = 'title_456789' |
+----------+------------+----------------------------------------------------+
6 rows in set (0.00 sec)
```
```
# 测试like
# a表name无索引
# b表name有单列索引
# c表name有组合索引
mysql> select * from index_a where name like '%www';
......
mysql> select * from index_b where name like '%www';
......
mysql> select * from index_c where name like '%www';
......
mysql> select * from index_a where name like 'www%';
......
mysql> select * from index_b where name like 'www%';
......
mysql> select * from index_c where name like 'www%';
......

mysql> show profiles;
+----------+------------+----------------------------------------------+
| Query_ID | Duration   | Query                                        |
+----------+------------+----------------------------------------------+
|        1 | 3.31670231 | select * from index_a where name like '%www' |
|        2 | 3.24503318 | select * from index_b where name like '%www' |
|        3 | 3.53191539 | select * from index_c where name like '%www' |
|        4 | 3.58034354 | select * from index_a where name like 'www%' |
|        5 | 0.00045643 | select * from index_b where name like 'www%' |
|        6 | 0.00040764 | select * from index_c where name like 'www%' |
+----------+------------+----------------------------------------------+
6 rows in set (0.00 sec)
# 综上: MySQL对于like 'www%'索引有效，对于'%www'索引无效。
```
```
# 测试or
# a 无索引
# b name sn都有索引
# c (name, sn) 组合索引
mysql> select * from index_a where name='name_556677' or sn = 'sn_667788';
......
mysql> select * from index_b where name='name_556677' or sn = 'sn_667788';
......
mysql> select * from index_c where name='name_556677' or sn = 'sn_667788';
......

mysql> show profiles;
+----------+------------+--------------------------------------------------------------------+
| Query_ID | Duration   | Query                                                              |
+----------+------------+--------------------------------------------------------------------+
|        1 | 0.59950080 | select * from index_a where name='name_556677' or sn = 'sn_667788' |
|        2 | 0.00161993 | select * from index_b where name='name_556677' or sn = 'sn_667788' |
|        3 | 0.60054383 | select * from index_c where name='name_556677' or sn = 'sn_667788' |
+----------+------------+--------------------------------------------------------------------+
3 rows in set (0.00 sec)
# 综上: or需要条件都加索引才命中[bc表对比，ac表对比]
```
```
mysql> select * from index_a where name in ('name_111111', 'name_333333');
......
mysql> select * from index_b where name in ('name_111111', 'name_333333');
......
mysql> select * from index_c where name in ('name_111111', 'name_333333');
......
2 rows in set (0.00 sec)

mysql> show profiles;
+----------+------------+--------------------------------------------------------------------+
| Query_ID | Duration   | Query                                                              |
+----------+------------+--------------------------------------------------------------------+
|        1 | 0.63480784 | select * from index_a where name in ('name_111111', 'name_333333') |
|        2 | 0.00107901 | select * from index_b where name in ('name_111111', 'name_333333') |
|        3 | 0.00122203 | select * from index_c where name in ('name_111111', 'name_333333') |
+----------+------------+--------------------------------------------------------------------+
3 rows in set (0.00 sec)
# 综上: in可以命中索引
```
```
# 测试范围符号(>,>=,<,<=,between)
# 先为index_b的stock加上索引
mysql> alter table index_b add index stock(stock);
Query OK, 0 rows affected (5.09 sec)                
Records: 0  Duplicates: 0  Warnings: 0

MariaDB [samp_db]> select count(1) from index_a where stock > 1900;
......
MariaDB [samp_db]> select count(1) from index_b where stock > 1900;
......
MariaDB [samp_db]> select count(1) from index_c where stock > 1900;
......
MariaDB [samp_db]> select count(1) from index_a where stock between 1000 and 1050;
......
MariaDB [samp_db]> select count(1) from index_b where stock between 1000 and 1050;
......
MariaDB [samp_db]> select count(1) from index_c where stock between 1000 and 1050;
......

MariaDB [samp_db]> show profiles;
+----------+------------+----------------------------------------------------------------+
| Query_ID | Duration   | Query                                                          |
+----------+------------+----------------------------------------------------------------+
|        4 | 1.09335718 | select count(1) from index_a where stock > 1900                |
|        5 | 0.05711385 | select count(1) from index_b where stock > 1900                |
|        6 | 0.31060459 | select count(1) from index_c where stock > 1900                |
|        7 | 0.34946092 | select count(1) from index_a where stock between 1000 and 1050 |
|        8 | 0.03301252 | select count(1) from index_b where stock between 1000 and 1050 |
|        9 | 0.31163826 | select count(1) from index_c where stock between 1000 and 1050 |
+----------+------------+----------------------------------------------------------------+
9 rows in set (0.00 sec)
# 通过时间对比，以及explain，比较符，between可以命中索引[不全面]
```
```
# 测试and
MariaDB [samp_db]> select * from index_a where name='name_555666' and sn='sn_777888';
......
MariaDB [samp_db]> select * from index_b where name='name_555666' and sn='sn_777888';
......
MariaDB [samp_db]> select * from index_c where name='name_555666' and sn='sn_777888';
......
MariaDB [samp_db]> show profiles;
+----------+------------+-------------------------------------------------------------------+
| Query_ID | Duration   | Query                                                             |
+----------+------------+-------------------------------------------------------------------+
|        1 | 0.61768279 | select * from index_a where name='name_555666' and sn='sn_777888' |
|        2 | 0.00109431 | select * from index_b where name='name_555666' and sn='sn_777888' |
|        3 | 0.00079209 | select * from index_c where name='name_555666' and sn='sn_777888' |
+----------+------------+-------------------------------------------------------------------+
3 rows in set (0.00 sec)
# 综上: 单列以及组合所以可以命中 「最左前缀匹配原则」
```