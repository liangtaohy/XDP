# Redis

## driver

指Redis驱动，如Phpredis模块。

driver命名需要遵循以下的规范：

* driver名称是对应Connector的前辍，如driver = 'PhpRedis', 则其Connector为'PhpRedisConnector'。

如果要修改driver到connector的映射规则，请修改方法 `protected function connector()`。

## php extension

* [PhpRedis安装](https://pecl.php.net/package/redis)