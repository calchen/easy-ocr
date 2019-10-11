# easy-ocr

用于 Laravel 框架的 OCR 服务

## 安装
不低于 Laravel 5.7

```shell
$ composer require calchen/easy-ocr:^1.0
```

## Laravel 配置方法

由于设置了 Laravel providers 自动加载，所以不需要额外操作。

## Lumen 配置方法

在 `bootstrap/app.php` 中增加：
```php
$app->register(Calchen\EasyOcr\ServiceProvider::class);
```