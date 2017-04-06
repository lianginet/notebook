#### 1. 生成ssh-key
```bash
$ ssh-keygen -t rsa -C 'lianginet@gmail.com'
```

#### 2. 复制到服务器
```bash
$ scp ~/.ssh/id_rsa.pub user@hostname:~/.ssh/
```

#### 3. 登录到服务器：
```bash
$ cd ~/.ssh
$ cat id_rsa.pub >> authorized_keys 
```

#### 4. 本地配置
```bash
$ vim ~/.ssh/config
# 输入以下内容
Host          alias     # 别名
HostName      hostname  # ip或者域名
Port          22        # 默认22，根据实际填写
User          root      # 登录用户名
IdentityFile  ~/.ssh/id_rsa # 公钥文件对应的私钥
# 若是有其他服务器，则空一行，添加新的服务器信息
```

#### 登录
```bash
$ ssh alias
```
