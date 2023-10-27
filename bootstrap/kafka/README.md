# 腾讯云kafka消息队列

目前试了无法延迟执行任务，只用于及时生成订单

- 安装php官方`ext-rdkafka`扩展
- window包：`http://pecl.php.net/package/rdkafka/4.1.2/windows`
- 下载包含Non Thread Safe的包
- 把librdkafka.dll  放入php.exe所在的目录
- 把php_rdkafka.dll 放入ext目录
- 重启php
- linux包：找度娘

- 安装扩展，执行 `composer require enqueue/rdkafka`
- 移除扩展，执行 `composer remove enqueue/rdkafka`

