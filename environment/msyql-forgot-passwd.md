## mysql忘记root密码

#### 修改配置文件
在`/etc/my.cnf`的`[mysqld]`加上`skip-grant-tables`
```
$ vim /etc/my.cnf

# 在[mysqld]下加入
skip-grant-tables
```

#### 重启mysql并设置密码
```
$ sytemctl restart mysqld  # centos7+

# 直接免密码root登录

# 重置密码
# mysql5.6
mysql> update mysql.user set 
    -> password = password('new-password')
    -> where user='root';
# mysql5.7 密码字段为authentication_string
mysql> update mysql.user set
    -> authentication_string = password('new-password')
    -> where user='root'; 
```

#### 修改配置文件
修改`/etc/my.cnf`,删掉前面步骤的`skip-grant-tables`
重启mysql