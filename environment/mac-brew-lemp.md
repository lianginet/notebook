#### Nginx
###### 安装
```
# Search
$ brew search nginx
# Install
$ brew install nginx
```
###### 配置

```
# 修改nginx默认端口为80
$ vim /usr/local/etc/nginx/nginx.conf
$ ....

# nginx监听80端口需要root权限
$ sudo chown root:wheel /usr/local/Cellar/nginx/[版本号]/bin/nginx
$ sudo chmod u+s /usr/local/Cellar/nginx/[版本号]/bin/nginx

# 启动
$ nginx # 或者 brew services start nginx

#测试配置是否有语法错误
$ nginx -t

# 重新加载配置|重启|停止|退出 nginx
$ nginx -s reload|reopen|stop|quit

#使用launchctl来启动|停止
$ launchctl unload ~/Library/LaunchAgents/homebrew.mxcl.nginx.plist
$ launchctl load -w ~/Library/LaunchAgents/homebrew.mxcl.nginx.plist

# 基本nginx站点
server {
    listen       80;
    server_name  localhost;

    charset utf-8;

    access_log /usr/local/var/log/nginx/localhost.access.log;
    error_log /usr/local/var/log/nginx/localhost.error.log;

    root /path/to/youSites;
    location / {
        index  index.php index.html index.htm;
        # try_files $uri /$uri index.php?$args;
    }

    error_page  404              /404.html;

    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   html;
    }

    # 可单独提取出来，再引入
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

#### Mysql
```
# Search
$ brew search mysql
# Install
$ brew install mysql
# Exec mysql_secure_installation
$ mysql_secure_installation

# 查看mysql帮助
$ mysqld --help --verbose | more
...
Default options are read from the following files in the given order: # mysql 默认配置读取顺序
/etc/my.cnf /etc/mysql/my.cnf /usr/local/etc/my.cnf ~/.my.cnf
...

# 查看mysql默认配置样例
$ ls $(brew --prefix mysql)/support-files/my-*
/usr/local/opt/mysql/support-files/my-default.cnf
# 移动到/usr/local/etc/my.cnf
```

#### PHP
```
# Install php56 or php70 or php71
$ brew install php56
# 通过php-version控制多版本的php
$ brew install php-version
# PS: php-version使用在其他版本
```

基本开发足够，其余优化后续   
未完待续..