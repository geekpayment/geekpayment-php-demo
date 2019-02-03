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
        return self::loadParams()['appid'];
    }

    /**
     * 对接方自行生成的RSA密钥对私钥（公钥提交给GeekPayment)
     */
    public static function getPrivateKeyString()
    {
        return self::loadParams()['private_key'];
    }

    /**
     * GeekPayment发回的验证公钥
     */
    public static function getPublicKeyString()
    {
        return self::loadParams()['public_key'];
    }

    private static function loadParams()
    {
        $cfg_path = $_SERVER['API_PARAMS'];
        $cfg = parse_ini_file($cfg_path, true);
        return $cfg['geekpayment'];
    }

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
