#### Ubuntu16.04 + Nginx + MariaDB + PHP7

##### nginx安装
```
$ sudo apt-get update
$ sudo apt-get install nginx
```
查看nginx安装状态
```
$ systemctl status nginx
```
测试安装: 在浏览器输入http://localhost 或者 http://127.0.0.1  
出现 Welcome to nginx! 则安装成功.
  
##### 安装MariaDB
```
$ sudo apt-get install mariadb-server mariadb-client
```
操作命令
```
$ sudo systemctl status mysql # 查看状态
$ sudo systemctl start mysql  # 启动mariadb
$ sudo systemctl stop mysql  # 停止mariadb
```
运行MariaDB初始化安全脚本
```
$ sudo mysql_secure_installation
```
该命令会设置mysql的root密码,并删除anonymous用户，禁用root远程登录并删除test数据库;这是MariaDB数据库安全的基本要求。

##### 安装PHP7
ubuntu16.04自带php7源
运行以下命令安装php7.0以及常用扩展
```
$ sudo apt-get install php7.0-fpm php7.0-mysql php7.0-common php7.0-mbstring php7.0-gd php7.0-json php7.0-mcrypt php7.0-cli php7.0-curl libapache2-mod-php7.0
```
操作命令
```
$ sudo systemctl status php7.0-fpm  # 查看状态
$ sudo systemctl start/stop/restart/reload php7.0-fpm  # 启动或停止或重启
```

##### 配置nginx
```
$ sudo vim /etc/nginx/sites-available/default
```
设置如下[本地环境]
```
server {
    listen 80;
    listen [::]:80;
 
    root /home/www/html;
    index index.php index.html index.htm index.nginx-debian.html;
 
    server_name localhost;
 
    location / {
        try_files $uri $uri/ =404;
    }
 
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }
 
    location ~ /\.ht {
        deny all;
    }
}
```
检测nginx配置是否语法
```
$ sudo nginx -t
```
重启
```
$ sudo systemctl reload nginx
```

##### 测试PHP是否正常运行
```
$ sudo vim /home/www/info.php
```
 输入
```
<?php
   echo phpinfo();
```
浏览器输入
```
http://localhost/info.php
```
输出PHP相关信息.