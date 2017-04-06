#### 安装
```
# Mac  Homebrew
# 
$ brew update
$ brew install yarn  # 或其他方式

# 添加环境变量
# 添加 export PATH="$PATH:`yarn global bin`" 到 .profile 或 .zshrc 或 .bashrc

# 查看版本
$ yarn --version   # yarn -V

# Linux
# 
# Debian/Ubuntu
$ curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
$ echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
$ sudo apt-get update && sudo apt-get install yarn

# CentOS/Fedora/RHEL
$ sudo wget https://dl.yarnpkg.com/rpm/yarn.repo -O /etc/yum.repos.d/yarn.repo
$ sudo yum install yarn

# Alternatives
$ curl -o- -L https://yarnpkg.com/install.sh | bash
```

#### 使用
```zsh
# 初始化项目
$ yarn init

# 全局安装
$ yarn global add [package]

# 添加/升级依赖
$ yarn add/upgrade [package]
$ yarn add/upgrade [package][@version]
$ yarn add/upgrade [package][@tag]

# 删除依赖
$ yarn remove [package]

# 安装项目的依赖[根据package.json]
$ yarn  # 或者 yarn install

# 依赖类型
# yarn add [package] [--dev/-D 或 --peer/-P  或  --optional/-O 或 --exact/-E 或 --tilde/-T]

# 其他命令
https://yarnpkg.com/en/docs/cli
https://yarnpkg.com/zh-Hans/docs/cli (中文)
```