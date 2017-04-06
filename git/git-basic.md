#### 配置全局信息
```
git config --global user.name "username"
git config --global user.email "useremail@gmail.com"
```
#### 公钥
```
# 生成公钥
ssh-keygen -t rsa -C "useremail@gmail.com"
# 查看公钥
$ cat ~/.ssh/id_rsa.pub
# 复制内容填写到github或者coding等仓库上
```
#### 文件忽略
```
# 忽略和取消忽略已入库文件
git update-index --assume-unchanged FILENAME
git update-index --no-assume-unchanged FILENAME
```