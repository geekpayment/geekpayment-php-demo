<?php

/**
 *    配置账号信息
 * @author yixian
 */
class GeekPayConfig
{
    //=======【基本信息设置】=====================================
    //
    /**
     * TODO: DEMO从环境变量注入，您可以直接将常量填进去
     * GeekPayment信息配置
     *
     * APPID: 绑定支付的APPID（必须配置）
     * PRIVATE_KEY: 对接方自行生成的RSA密钥对私钥（公钥提交给GeekPayment)
     * PUBLIC_KEY: GeekPayment发回的验证公钥
     *
     */
    public static function getAppId()
    {
        return getenv('GEEKPAYMENT_APPID');
    }
    const APPID = '20190103IZERVZLWFX';

    /**
     * 对接方自行生成的RSA密钥对私钥（公钥提交给GeekPayment)
     */
    public static function getPrivateKeyString()
    {
        return getenv('GEEKPAYMENT_PRIVATE_KEY');
    }
    const PRIVATE_KEY = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAL1hZ9c6LXL1Zfvu4Q6DRmipF0y4aWYb+ZsBvte3/MIH67G432meToypUcmj5mrD8ZkUq+vs/p0QffvtmiQChUco+eop3MqaYR2CDQBTV7M81yK1G5a6MJ9TTSJ0J+l1rDLjF8/zl7XFt2/6QnBy+IqXt4mQKzc+D058/xu04+mRAgMBAAECgYBBjQAw9uG5a2ioCfjbmaPa4UB/3UsaPW+p8DI7H+O805oJE6k9OT8OICN/rJw6c2Via83QZWGCQy2gcI9MjJTNwY3rEjsWsyBMB/EWuVHIjlseXkaDESHLr2W5BXXM4DxeDyH+/6Ha7TqAEnYMfQ0+C0s7NXNc22seCm9DGRa7gwJBAPrkU8R+CwK1PIamaJiqvrlsJZ4B1sFdQL/74Qo0mXX9l882mrhsTXstv4/x8vtQZjZTiIOIaA851M0E98cDeP8CQQDBPHmZmh7u8iIhB7AHANQT9/SmazhFX/8yMpsRQB8mcyKHWvUOYU4V/SxYNiajPN/1JptSeU9c994Y5H6xQo1vAkA3AhK/tSby3Au+NgQe3OjePLDuuNZ+JNUKgs4vb6Lp9MTxILbBkrVWOYyToee1ZEZUyPHYbPLry7E1lk5BKyi/AkAQ7uq9Aht51vAUsubDBliPU2g1+SlaMvpa/MJH9bWFGgvJjrfwxaghFyl+pWgnX9tEUVjFFmpU/EeTKNrNT8azAkEA6K97aNy3Cn2PsCTT1ilQoW7aStpWWmnZXm/nRiBLObZWnBKhJPF7wfCG7d8rKaogUpnOMVPbmViqy2mhu/cmeQ==';

    /**
     * GeekPayment发回的验证公钥
     */
    public static function getPublicKeyString()
    {
        return getenv('GEEKPAYMENT_PUBLIC_KEY');
    }
    const PUBLIC_KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnz9f19x7nLvpK2Ebi+muZS7ukyMcbes/0VRrKuyhLDfEASeopmJG8XiHVyOcZz28EjcOX9iqwh3h6ZcHzUS4zS86yikaP2ZyfykrydJXpE/CQsSJ8PtrubYaIfoD76Eoz6kv1PtrY6OgVp9HzIDyfNbky5y+NLe6RGW9nj7xrR4vXwqL9CVOvrFuJibxC866zlzckNiYWOrkvsy727Fcl8Af5um84ooUy8FFY9LBKvSaoZjrpIB5qvoj9HplO6HnwsZuSZR3eLxAIyZAWM3UXU5r4KlodfxMCi5a40qLbm1K6+QrLE1pR0VymaAp4j3Av4sIahxRSn2pUtmSZ7CScQIDAQAB';

    const GEEK_HOST = 'https://api.geekpayment.com';

    //=======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    const CURL_PROXY_HOST = "0.0.0.0";//"192.168.0.1";
    const CURL_PROXY_PORT = 0;//8080;

    const MICROAPP_WX_APPID = "";//微信小程序的appid
    const MICROAPP_WX_OPENID = "";//微信小程序访问用户的openid，生产环境应该通过微信小程序授权机制获取

    const APP_WX_APPID = "";//微信开发者平台的appid，使用微信SDK方式发起支付需要此参数
}
