## scws 安装
#### 获取源码并解压到当前目录
```
$ wget -q -O - http://www.xunsearch.com/scws/down/scws-1.2.3.tar.bz2 | tar xjf -
```
#### 编译安装
```
$ cd scws-1.2.3
$ ./configure
$ make & make install

# 下载解压词库
cd /usr/local/scws/etc
$ wget -q -O - http://www.xunsearch.com/scws/down/scws-dict-chs-gbk.tar.bz2 | tar xjf -
$ wget -q -O - http://www.xunsearch.com/scws/down/scws-dict-chs-utf8.tar.bz2 | tar xjf -
```

## 安装PHP扩展
```
# 进入到源码目录的phpext
$ cd phpext

# 执行phpize
$ phpize
# 如果出现错误
# Can't find PHP headers in /usr/include/php
# The php-devel package is required for use of this command.
# 则执行 yum install php-devel

# 执行
$ ./configure --with-scws=/usr/local
# 若有错误
# configure: error: Cannot find php-config. Please use --with-php-config=PAT
# 则带上参数 --with-php-config="php安装目录"/bin/php-config

# 编译安装
$ make & make install

# 返回如下信息则安装成功
Installing shared extensions:     /opt/remi/php55/root/usr/lib64/php/modules/

# 在php.ini中加入扩展
# 注意请检查 php.ini 中的 extension_dir
[scws]
extension = scws.so
scws.default.charset = utf8
scws.default.fpath = /usr/local/scws/etc

# scws添加自定义词库
# 在scws安装目录下的etc下新建txt文件(utf8格式)，scws中add_dict到该文件的具体路径 eg：
$so = scws_new();
// scws路径和规则
$so->set_dict(ini_get("scws.default.fpath").'/dict.utf8.xdb');
$so->add_dict(ini_get("scws.default.fpath").'/dict_user.txt', SCWS_XDICT_TXT);  //dict_user.txt为个人自定义词库
$so->set_rule(ini_get("scws.default.fpath").'/rules.utf8.ini');
```