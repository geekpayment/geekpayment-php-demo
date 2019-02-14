API文档[https://apidoc.geekpayment.com/]

# geekpayment-php-demo
接入GeekPayment的PHP DEMO

当前demo仅完成下单、查账、退款、退款查询、关单的接入演示，具体业务接入以及数据持久化还请自行完成。

强烈建议支付单号不要直接使用商品订单单号，避免用户支付失败后无法重新发起支付。

## 环境要求：
php5以上，我们的测试环境为php7.2

依赖组件：
php-gd, openssl, php-curl

## 配置：
修改lib/GeekPay.Config.php
更新getAppId,getPublicKeyFile,getPrivateKeyFile
3个方法的return值

平台进件后会分配一个appid，以及一个pem格式的RSA公钥，用于签名验证，
这个公钥保存到pem文件后将路径填入getPublicKeyFile方法，注意添加file:/头

此外还需要对接方自行生成一对RSA密钥对，建议2048位，将私钥pem文件路径写入getPrivateKeyFile方法，
并将公钥提交给GeekPayment完成公钥交换
