##### Memcached
- 开源  高性能   分布式内存对象缓存系统
- key-value存储系统, 用来存储小块的任意数据（字符串、对象）
- 通过缓存数据库查询结果，减少数据库访问次数，以提高动态Web应用的速度、提高可扩展性。  

##### 特征
- 协议简单(基于文本行的协议)
- 基于libevent的事件处理
- 内置内存存储方式
- Memcached不相互通信的分布式

```
注: 
由于数据保存在内置的内存存储空间中，所以重启memcached会导致全部数据消失;
内容容量达到指定值之后，就基于LRU算法自动删除不使用的缓存;
Memcached服务器端没有分布式功能，这完全取决于客户端的实现。
```
 
##### 启动参数
- -p 使用端口，默认11211
- -m 最大内存大小，默认64M
- -vv 用very verbose模式启动，调试信息和错误输出到控制台
- -d 作为daemon在后台启动

##### 安装/启动
```
$ sudo apt-get install memcached # 安装
$ memcached -p 11211 64m -vv  # 显示了调试信息。这样就在前台启动了memcached，监听TCP端口11211，最大内存为64M。调试信息的内容大部分是关于存储的信息
$ memcached -p 11211 64m -d   # 后台服务程序运行
```

##### 连接实例
```
$ telnet HOST PORT
// 实例
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
set foo 0 0 3
bar
STORED
get foo
VALUE foo 0 3
bar
END
quit
```

##### set 命令
将 **value** 存储在指定的 **key** 中
如果set的key已经存在，该命令可以更新该key所对应的原来的数据，也就是实现更新的作用
```
# 语法格式
set key flags exptime bytes [noreply]
value
```
- **key**  key-value的key
- **flags** 可以包括键值对的整型参数，客户机使用它存储关于键值对的额外信息 
- **exptime** 在缓存中保存键值对的时间（秒为单位，0 永远）
- **bytes** 存储字节数
- **noreply** （可选）告知服务器不需要返回数据
- **value** key-value的value

实例:
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.

set fmter 0 100 9
memcached
STORED      # 数据设置成功则输出STORED,保存失败则输出ERROR

get fmter
VALUE fmter 0 9
memcached
END
quit
```

##### add 命令
将 **value** 存储在指定的 **key** 中
如果 add 的 key 已经存在，则不会更新数据，之前的值将仍然保持相同，并且您将获得响应 **NOT_STORED**
```
# 语法格式 [同set]
add key flags exptime bytes [noreply]
value
```

##### replace 命令
替换已存在的 **key** 的 **value**。
如果 key 不存在，则替换失败，并且您将获得响应 **NOT_STORED**。
```
# 语法格式 [同set]
replace key flags exptime bytes [noreply]
value
```

##### append 命令
向已存在 **key** 的 **value** 后面追加数据  
输出说明:
 **STORED** 保存成功后输出。
 **NOT_STORED** 该键在 Memcached 上不存在。
 **CLIENT_ERROR** 执行错误。
```
# 语法格式 [同set]
append key flags exptime bytes [noreply]
value
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.

set fmt 0 100 9 
memcached
STORED

get fmt
VALUE fmt 0 9
memcached
END

append fmt 0 100 5
redis
STORED

get fmt
VALUE fmt 0 14
memcachedredis
END
quit
```

##### prepend 命令
向已存在 **key** 的 **value** 前面追加数据  
输出说明:
 **STORED** 保存成功后输出。
 **NOT_STORED** 该键在 Memcached 上不存在。
 **CLIENT_ERROR** 执行错误。
```
# 语法格式 [同set]
prepend key flags exptime bytes [noreply]
value
```

##### CAS 命令
CAS（Check-And-Set/Compare-And-Swap） 命令用于执行一个"检查并设置"的操作
仅在当前客户端最后一次取值后，该key 对应的值没有被其他客户端修改的情况下， 才能够将值写入
检查是通过cas_token参数进行的， 这个参数是memcached指定给已经存在的元素的一个唯一的64位值
```
# 语法格式 [同set]
cas key flags exptime bytes unique_cas_token [noreply]
value
```
- **unique_cas_token** 通过 gets 命令获取的一个唯一的64位值

实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
set fmt 0 100 9
memcached
STORED

gets fmt
VALUE fmt 0 9 9
memcached
END

cas fmt 0 100 5 9
redis
STORED

get fmt
VALUE fmt 0 5
redis
END
quit
```
输出信息说明
**STORED** 保存成功后输出。
**ERROR** 保存出错或语法错误。
**EXISTS** 在最后一次取值后另外一个用户也在更新该数据。
**NOT_FOUND** Memcached 服务上不存在该键值。

##### get 命令
获取存储在 **key** 中的 **value** ,若key不存在,返回空
```
# 语法格式
get key [key1, key2, ...]
```

##### gets 命令
获取带有 cas_token存 的 **value**,若key不存在,返回空
```
# 语法格式
gets key [key1, key2, ...]
```

##### delete 命令
删除已存在的 key
```
# 语法格式
delete key [noreply]
delete key [key1, key2, ...]
```
返回参数
**DELETED** 删除成功
**ERROR** 语法错误/删除失败
**NOT_FOUND** key不存在

##### incr/decr 命令
对已存在的 **key**的数字值进行自增或自减操作
操作的数据必须是十进制的32位无符号整数
```
# 语法格式
incr key increment_value
derc key decrement_value
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
set fmt 0 100 2
15
STORED

incr fmt 8
23

decr fmt 7
16
```
返回参数
**NOT_FOUND** key不存在
**CLIENT_ERROR** 自增值不是对象(数字)
**ERROR** 其他错误，如语法错误

##### stats 命令
输出 Memcached 服务信息
```
# 语法格式
stats
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
stats
STAT pid 9541              # memcached的服务端进程id
STAT uptime 12165      # 服务器已运行时间(秒)
STAT time 1467873285  # 服务器当前UNIX时间戳
STAT version 1.4.25 Ubuntu   # memcache版本
STAT libevent 2.0.21-stable  
STAT pointer_size 64               # 操作系统指针大小
STAT rusage_user 0.264000    # 进程累计用户时间
STAT rusage_system 0.212000  # 进程累计系统时间
STAT curr_connections 5    # 当前连接数量
STAT total_connections 14  # Memcached运行以来连接总数
STAT connection_structures 6  # Memcached分配的连接结构数量
STAT reserved_fds 20  
STAT cmd_get 27        # get命令请求次数
STAT cmd_set 18        # set命令请求次数
STAT cmd_flush 0       # flush命令请求次数
STAT cmd_touch 0      # touch命令请求次数
STAT get_hits 19         # get命令命中次数
STAT get_misses 8      # get命令未命中次数
STAT delete_misses 0  # delete命令未命中次数
STAT delete_hits 1       # delete命令命中次数
STAT incr_misses 0     # incr命令未命中次数
STAT incr_hits 1          # incr命令命中次数
STAT decr_misses 0    # decr命令未命中次数
STAT decr_hits 1         # decr命令命中次数
STAT cas_misses 2      # cas命令未命中次数
STAT cas_hits 1           # cas命令命中次数
STAT cas_badval 2       # 使用擦拭次数
STAT touch_hits 0        # touch命令命中次数
STAT touch_misses 0   # touch命令未命中次数
STAT auth_cmds 0       # 认证命令处理的次数
STAT auth_errors 0      # 认证失败数目
STAT bytes_read 934   # 读取总字节数
STAT bytes_written 906  # 发送总字节数
STAT limit_maxbytes 67108864   # 分配的内存总大小（字节）
STAT accepting_conns 1   #  服务器是否达到过最大连接（0/1）
STAT listen_disabled_num 0  # 失效的监听数
STAT time_in_listen_disabled_us 0
STAT threads 4              #  当前线程数
STAT conn_yields 0        # 连接操作主动放弃数目
STAT hash_power_level 16  
STAT hash_bytes 524288
STAT hash_is_expanding 0
STAT malloc_fails 0
STAT bytes 218          # 当前存储占用的字节数
STAT curr_items 3      # 当前存储的数据总数
STAT total_items 12   # 启动以来存储的数据总数
STAT expired_unfetched 0
STAT evicted_unfetched 0
STAT evictions 0      # LRU释放的对象数目
STAT reclaimed 0    # 已过期的数据条目来存储新数据的数目
STAT crawler_reclaimed 0
STAT crawler_items_checked 0
STAT lrutail_reflocked 0
END
```

##### stats items 命令
显示各个 slab 中 item 的数目和存储时长(最后一次访问距离现在的秒数)
```
# 语法格式
stats items
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
stats items
STAT items:1:number 3
STAT items:1:age 11655
STAT items:1:evicted 0
STAT items:1:evicted_nonzero 0
STAT items:1:evicted_time 0
STAT items:1:outofmemory 0
STAT items:1:tailrepairs 0
STAT items:1:reclaimed 0
STAT items:1:expired_unfetched 0
STAT items:1:evicted_unfetched 0
STAT items:1:crawler_reclaimed 0
STAT items:1:crawler_items_checked 0
STAT items:1:lrutail_reflocked 0
END
```

##### stats slabs 命令
用于显示各个slab的信息，包括chunk的大小、数目、使用情况等
```
# 语法格式
stats slabs
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
stats slabs
STAT 1:chunk_size 96
STAT 1:chunks_per_page 10922
STAT 1:total_pages 1
STAT 1:total_chunks 10922
STAT 1:used_chunks 3
STAT 1:free_chunks 10919
STAT 1:free_chunks_end 0
STAT 1:mem_requested 218
STAT 1:get_hits 19
STAT 1:cmd_set 18
STAT 1:delete_hits 1
STAT 1:incr_hits 1
STAT 1:decr_hits 1
STAT 1:cas_hits 1
STAT 1:cas_badval 2
STAT 1:touch_hits 0
STAT active_slabs 1
STAT total_malloced 1048512
END
```

##### stats sizes 命令
显示所有item的大小和个数
该信息返回两列，第一列是 item 的大小，第二列是 item 的个数
```
# 语法格式
stats sizes
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
stats sizes
STAT 96 3
END
```

##### flush_all 命令
清理缓存中的所有 **key-value** 对
可选参数 **time**，用于在指定的时间后执行清理缓存操作
```
# 语法格式
flush_all [time] [noreply]
```
实例
```
$ telnet 127.0.0.1 11211
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.
set fmt 0 0 9 
memcached
STORED

get fmt
VALUE fmt 0 9
memcached
END

flush_all
OK

get fmt
END
```