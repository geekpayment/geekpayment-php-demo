<?php
require_once "GeekPay.Exception.php";
require_once "GeekPay.Config.php";
require_once "GeekPay.Data.php";

/**
 *
 * 接口访问类，包含所有极客支付API列表的封装，类中方法为static方法，
 * 每个接口有默认超时时间（除提交扫码支付为15s，其他均为10s）
 * @author Leijid
 *
 */
class GeekPayApi
{

    /**
     *
     * 原生QR下单，nonce_str不需要填入
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result GeekPayResults 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function qrOrder($inputObj, $timeOut = 15)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/native_qr_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response, $url);
        return $result;
    }

    /**
     *
     * 通用下单，nonce_str不需要填入
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result array GeekPayResults 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function commonOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/qr_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 快捷支付下单，nonce_str不需要填入
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result array GeekPayResults 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function cashierOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/cashier_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 线下支付订单，nonce_str不需要填入
     * @param GeekPayMicropayOrder $inputObj
     * @param int $timeOut
     * @throws GeekPayException
     * @return $result 成功时返回，其他抛异常
     */
    public static function micropayOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/retail_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 线下QRCode支付单，nonce_str、time不需要填入
     * @param GeekPayRetailQRCode $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function retailQRCodeOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/retail_qr_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 小程序支付单，nonce_str、time不需要填入(暂不支持)
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function microAppOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/microapp_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * App嵌入支付单，nonce_str、time不需要填入
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function appOrder($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/app_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * H5支付单，nonce_str、time不需要填入
     * @param GeekPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function h5Order($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/h5_orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查询订单，nonce_str、time不需要填入
     * @param GeekPayOrderQuery $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function orderQuery($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/orders/$orderId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 申请退款，nonce_str、time不需要填入
     * @param GeekPayApplyRefund $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function refund($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $refundId = $inputObj->getRefundId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/orders/$orderId/refunds/$refundId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::putJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 查询退款状态，nonce_str、time不需要填入
     * @param GeekPayQueryRefund $inputObj
     * @param int $timeOut
     * @return array $result 成功时返回，其他抛异常
     * @throws GeekPayException
     */
    public static function refundQuery($inputObj, $timeOut = 10)
    {
        $appid = GeekPayConfig::getAppId();
        $orderId = $inputObj->getOrderId();
        $refundId = $inputObj->getRefundId();
        $url = GeekPayConfig::GEEK_HOST . "/apps/$appid/orders/$orderId/refunds/$refundId";
        $inputObj->setNonceStr(self::getNonceStr());//随机字符串
        $inputObj->setSign($url);
        $response = self::getJsonCurl($url, $inputObj, $timeOut);
        $result = GeekPayResults::init($response);
        return $result;
    }

    /**
     *
     * 产生随机字符串，不长于30位
     * @param int $length
     * @return string $str 产生的随机字符串
     */
    public static function getNonceStr($length = 30)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 以get方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @return bool|string
     * @throws GeekPayException
     */
    private static function getJsonCurl($url, $inputObj, $second = 30)
    {
        error_log("requesting url [GET]$url");
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //如果有配置代理这里就设置代理
        if (GeekPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && GeekPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, GeekPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, GeekPayConfig::CURL_PROXY_PORT);
        }
        $url .= '?' . $inputObj->toQueryParams();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        //GET提交方式
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new GeekPayException("curl出错，错误码:$error");
        }
    }

    /**
     * 以put方式提交json到对应的接口url
     *
     * @param string $url
     * @param object $inputObj
     * @param int $second url执行超时时间，默认30s
     * @return bool|string
     * @throws GeekPayException
     */
    private static function putJsonCurl($url, $inputObj, $second = 30)
    {
        error_log("requesting url [PUT]$url");
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if (GeekPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && GeekPayConfig::CURL_PROXY_PORT != 0
        ) {
            curl_setopt($ch, CURLOPT_PROXY, GeekPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch, CURLOPT_PROXYPORT, GeekPayConfig::CURL_PROXY_PORT);
        }
        $url .= '?' . $inputObj->toQueryParams();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        //PUT提交方式
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputObj->toBodyParams());
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_error($ch);
            curl_close($ch);
            throw new GeekPayException("curl出错，错误码:$error");
        }
    }
}

