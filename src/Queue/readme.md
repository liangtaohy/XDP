# Queue文档

## Event使用注意事项

一般情况下，Event处理器需要实现接口`interface EventHandler`。接口定义参见`Xdp\Contract\Queue\EventHandler`。

Event Handler可以定义自己的处理器，要求处理器遵守以下参数约定：

* $event - 事件对象
* $payload - string 数据