#### es1搭建
```zsh
$ docker run -d --name es1 -p 9200:9200 -v ~/docker/es1:/usr/share/elasticsearch/config elasticsearch
# PS: elasticsearch相关版本
# Version: 5.2.2, Build: f9d9b74/2017-02-24T17:26:45.835Z, JVM: 1.8.0_121
```
elasticsearch.yml
```yml
cluster.name: "cluster"  
node.name: node-1
node.master: true
node.data: true
network.host: 0.0.0.0
discovery.zen.ping.unicast.hosts: ["172.17.0.2", "172.17.0.3"]
discovery.zen.minimum_master_nodes: 1
# 允许跨域  es-head
http.cors.enabled: true
http.cors.allow-origin: /.*/
```

#### es2搭建
```zsh
$ docker run -d --name es2 --link es1 -v ~/docker/es2:/usr/share/elasticsearch/config elasticsearch
# PS: elasticsearch相关版本
# Version: 5.2.2, Build: f9d9b74/2017-02-24T17:26:45.835Z, JVM: 1.8.0_121
```
elasticsearch.yml
```yml
cluster.name: "cluster"
node.name: node-2
node.master: true
node.data: true
network.host: 0.0.0.0
discovery.zen.ping.unicast.hosts: ["172.17.0.2", "172.17.0.3"]
discovery.zen.minimum_master_nodes: 1
# 允许跨域  es-head
http.cors.enabled: true
http.cors.allow-origin: /.*/
```

#### docker安装es-head插件
```zsh
$ docker run -p 9100:9100 -d --name es-head mobz/elasticsearch-head:5
```

#### 测试
##### es集群查看
浏览器输入 `http://127.0.0.1:9200/_cluster/health` 显示：
```zsh
{
    "cluster_name": "cluster",
    "status": "green",
    "timed_out": false,
    "number_of_nodes": 2,
    "number_of_data_nodes": 2,
    "active_primary_shards": 5,
    "active_shards": 10,
    "relocating_shards": 0,
    "initializing_shards": 0,
    "unassigned_shards": 0,
    "delayed_unassigned_shards": 0,
    "number_of_pending_tasks": 0,
    "number_of_in_flight_fetch": 0,
    "task_max_waiting_in_queue_millis": 0,
    "active_shards_percent_as_number": 100
}
```

##### es-head
浏览器输入 `http://127.0.0.1:9100` 可查看es-head相关界面
