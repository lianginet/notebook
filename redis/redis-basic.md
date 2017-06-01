#### 简介
高性能的key-value数据库。
Redis 与其他 key - value 缓存产品有以下**三个特点: **
- **数据的持久化** 可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用；Redis运行在内存中但是可以持久化到磁盘
- **丰富的数据类型** Redis不仅仅支持简单的key-value类型的数据，同时还提供list，set，zset，hash等数据结构的存储，Redis的数据类型都是基于基本数据结构的同时对程序员透明，无需进行额外的抽象
- **数据备份** Redis支持数据的备份，即master-slave模式的数据备份

#### Redis优点
- **异常快速**: Redis是非常快的，每秒可以执行大约110000设置操作，81000个/每秒的读取操作
- **丰富的数据类型**: Redis支持二进制案例的 Strings, Lists, Hashes, Sets 及 Ordered Sets 数据类型操作
- **原子** :  所有 Redis 的操作都是原子，从而确保当两个客户同时访问 Redis 服务器得到的是更新后的值（最新值）
- **MultiUtility工具**: Redis是一个多功能实用工具，可以在很多如: 缓存，消息传递队列中使用（Redis原生支持发布/订阅），在应用程序中，如: Web应用程序会话，网站页面点击数等任何短暂的数据

#### 数据类型
Redis支持五种数据类型 string（字符串），hash（哈希），list（列表），set（集合）及zset(sorted set:有序集合)

#### 数据备份/恢复
```
# 备份数据
edis 127.0.0.1:6379> SAVE 
OK

# 获取Redis目录
redis 127.0.0.1:6379> CONFIG GET dir
1) "dir"
2) "/usr/local/redis/bin"

# 恢复数据
127.0.0.1:6379> BGSAVE
Background saving started
```

#### Redis安全[设置密码]
通过Redis的配置文件设置密码参数，这样客户端连接到Redis服务则需要密码验证，提高安全性
```
# 查看当前密码设置
127.0.0.1:6379> CONFIG get requirepass
1) "requirepass"
2) ""
# 配置密码
127.0.0.1:6379> CONFIG set requirepass "redis"
OK
# 查看密码设置
127.0.0.1:6379> CONFIG get requirepass
1) "requirepass"
2) "redis"
```
设置密码后，客户端连接 redis 服务需要密码验证才能执行命令
```
# 输入密码
127.0.0.1:6379> AUTH 'redis'
OK
```

#### 性能测试
```
# 语法格式
$ redis-benchmark [option] [option value]
```
|选项 | 描述 | 默认值 |
|:-:|:-|:-:|
| -h | 指定服务器主机名 | 127.0.0.1 |
| -p | 指定服务器端口 | 6379 |
| -s | 指定服务器 socket | - |
| -c | 指定并发连接数 | 50 |
| -n | 指定请求数 | 10000 |
| -d | 以字节的形式指定 SET/GET 值的数据大小 |    2 |
| -k | 1=keep alive 0=reconnect | 1 |
| -r | SET/GET/INCR 使用随机 key, SADD 使用随机值 | - |
| -P | 通过管道传输 <numreq>请求 | 1 |
| -q | 强制退出 redis。仅显示 query/sec 值 | - |
| --csv | 以 CSV 格式输出 | - |
| -l | 生成循环，永久执行测试 | - |
| -t | 仅运行以逗号分隔的测试命令列表 | - |
| -I(大写i) | Idle 模式。仅打开 N 个 idle 连接并等待| - |
```
# 同时执行10000个请求来检测性能
$ redis-benchmark -n 100000
```

#### Redis命令参考
[Redis命令参考](http://redisdoc.com/)