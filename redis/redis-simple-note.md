#### 发布/订阅
Redis 发布订阅(pub/sub)是一种消息通信模式: 发送者(pub)发送消息，订阅者(sub)接收消息。
Redis 客户端可以订阅任意数量的channel。
当有新消息通过 PUBLISH 命令发送给频道 channel时， 这个消息就会被发送给订阅它的客户端。
##### 实例
创建订阅频道
```
127.0.0.1:6379> SUBSCRIBE redisChannel
Reading messages... (press Ctrl-C to quit)
1) "subscribe"
2) "redisChannel"
3) (integer) 1
```
打开新的终端，发送消息
```
# 查看所有的频道
127.0.0.1:6379> PUBSUB CHANNELS
1) "redisChannel"

# 发送消息
$ redis-cli
127.0.0.1:6379> PUBLISH redisChannel "The first message!"
(integer) 1
```
此时原来的终端会收到消息
```
127.0.0.1:6379> SUBSCRIBE redisChannel
Reading messages... (press Ctrl-C to quit)
1) "subscribe"
2) "redisChannel"
3) (integer) 1
# 接收到的消息
1) "message"
2) "redisChannel"
3) "The first message!"
```

#### 事务
Redis 事务可以一次执行多个命令， 并且带有以下两个重要的保证: 
- **事务是一个单独的隔离操作** 事务中的所有命令都会序列化、按顺序地执行。事务在执行的过程中，不会被其他客户端发送来的命令请求所打断。
- **事务是一个原子操作** 事务中的命令要么全部被执行，要么全部都不执行。

一个事务从开始到执行会经历以下三个阶段:
- 开始事务
- 命令入队
- 执行事务

###### 实例
```
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> SET redis 'cache-technique'
QUEUED
127.0.0.1:6379> GET redis
QUEUED
127.0.0.1:6379> SADD set baidu google yahoo
QUEUED
127.0.0.1:6379> SMEMBERS set
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) "cache-technique"
3) (integer) 3
4) 1) "google"
   2) "baidu"
   3) "yahoo"

# DISCARD  取消事务
# UNWATCH  取消WATCH命令对所有key的监视
# WATCH    命令用于监视key,若事务执行之前key被其他命令所改动，那么事务将被打断
```

#### 连接命令
```
# AUTH password 验证密码是否正确
# ECHO message 打印字符串
# PING 查看服务是否运行
# QUIT 关闭当前连接 
# SELECT index 切换到指定的数据库
```