# XdpMail

## 目录结构
```
├── Adapter    存放适配器
│   ├── MailAdapter.php  适配器父类
│   ├── PhpMailerAdapter.php PhpMailer适配器
│   └── SwiftMailAdapter.php SwiftMail适配器
├── Exception  异常
│   └── MailException.php email异常
├── MailFactory.php 工厂方法
├── Mailer.php
└── README.md  说明文档
```
---
## 调用方式

```php
1、$adapter = (new MailFactory())->mailer(); 
2、也可以直接去调用SwiftMailAdapter(不建议)
3、默认为 SwiftMailAdapter；可以在mailer()方法中设置
4、因为工厂类实现的是静态方法，所以如果需要重新设置config则 $adapter->setConfig($config);
```
---

## demo

```php
$swift = new PhpMailerAdapter(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->text('test email');
        $swift->subject('test');
        $swift->send();
```

```php
$swift = PhpMailerAdapter::getInstance(self::$config);
        $swift->from(self::$config['username'],self::$config['name']);
        $swift->to('1013816137@qq.com','石文远');
        $swift->html('<h1>xdp</h1><p>test email</p>');
        $swift->subject('test');
        $swift->send();
```

```php
$adapter = (new MailFactory())->mailer();
$adapter->to('1013816137@qq.com','石文远');
$adapter->text('test email');
$adapter->subject('test');
$adapter->attachment("/Users/shiwenyuan/Desktop/软件采购与实施合同.docx");
$adapter->send();
```