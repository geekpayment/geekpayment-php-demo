<?php
require_once "GeekPay.Config.php";
require_once "GeekPay.Exception.php";

/**
 *
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：
 * 计算/设置/获取签名、输出json格式的参数、从json读取数据对象等
 * @author Leijid
 *
 */
class GeekPayDataBase
{
    protected $pathValues = array();

    protected $queryValues = array();

    protected $bodyValues = array();


    public function getBodyValue($key)
    {
        return $this->bodyValues[$key];
    }

    /**
     * 设置随机字符串，不长于30位。推荐随机数生成算法
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->queryValues['nonce_str'] = $value;
    }

    /**
     * 获取随机字符串，不长于30位。推荐随机数生成算法的值
     * @return string 值
     **/
    public function getNonceStr()
    {
        return $this->queryValues['nonce_str'];
    }

    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function isNonceStrSet()
    {
        return array_key_exists('nonce_str', $this->queryValues);
    }

    /**
     * 设置签名，详见签名生成算法
     * @param $url string url不带参
     * @return string
     */
    public function setSign($url)
    {
        $sign = $this->makeSign($url);
        $this->queryValues['sign'] = $sign;
        $this->queryValues['sign_type'] = 'RSA2';
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return string 值
     **/
    public function getSign()
    {
        return $this->queryValues['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return true 或 false
     **/
    public function isSignSet()
    {
        return array_key_exists('sign', $this->queryValues);
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toQueryParams()
    {
        $buff = "";
        foreach ($this->queryValues as $k => $v) {
            if ($v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 格式化参数格式化成json参数
     */
    public function toBodyParams()
    {
        return json_encode($this->bodyValues, JSON_UNESCAPED_SLASHES);
    }

    /**
     * 格式化签名参数
     * @param $url  string 请求url(不带参数)
     * @param $nonceStr string 随机字符串
     * @param $bodyValue array 签名对象
     * @return false|string
     */
    public function toSignParams($url, $nonceStr, $bodyValue)
    {
        $tmpBody = $bodyValue;
        $tmpBody['url'] = $url;
        $tmpBody['sign_type'] = 'RSA2';
        $tmpBody['nonce_str'] = $nonceStr;
        ksort($tmpBody);
        $buff = json_encode($tmpBody,JSON_UNESCAPED_SLASHES);
        return $buff;
    }

    /**
     * 生成签名
     * @param $url string 请求url(不带参)
     * @return string 签名，本函数不覆盖sign成员变量，如要设置签名需要调用setSign方法赋值
     */
    public function makeSign($url)
    {
        //签名步骤一：构造签名参数
        $signBase = $this->toSignParams($url, $this->getNonceStr(), $this->bodyValues);
        //签名步骤二：SHA256withRSA签名
        $priv_key_id = self::getPrivateKey();
        error_log("privkey:$priv_key_id");
        $sign = openssl_sign($signBase, $sign, $priv_key_id, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
        error_log("sign_base=$signBase\r\n\r\nsign=$sign");
        return $sign;
    }

    public function validSign($url, $bodyValue, $sign)
    {
        if (!is_string($sign)) {
            return false;
        }
        $signBase = $this->toSignParams($url, $bodyValue['nonce_str'], $bodyValue);
        $pub_key_id = self::getPublicKey();
        error_log("public_key:$pub_key_id");
        return (bool)openssl_verify($signBase, base64_decode($sign), $pub_key_id, OPENSSL_ALGO_SHA256);
    }

    private function getPrivateKey()
    {
        return openssl_pkey_get_private("file://" . GeekPayConfig::getPrivateKeyFile());
    }

    private function getPublicKey()
    {
        return openssl_pkey_get_public("file://" . GeekPayConfig::getPublicKeyFile());
    }

    /**
     * 获取设置的path参数值
     */
    public function getPathValues()
    {
        return $this->pathValues;
    }

    /**
     * 获取设置的query参数值
     */
    public function getQueryValues()
    {
        return $this->queryValues;
    }

    /**
     * 获取设置的body参数值
     */
    public function getBodyValues()
    {
        return $this->bodyValues;
    }
}

/**
 *
 * 接口调用结果类
 * @author Leijid
 *
 */
class GeekPayResults extends GeekPayDataBase
{

    /**
     *
     * 使用数组初始化
     * @param string $json
     */
    public function fromJson($json)
    {
        $resp = json_decode($json, true);
        $this->bodyValues = $resp['data'];
    }

    /**
     * 将json转为array
     * @param string $json
     * @return array
     *
     * 返回信息:
     * return_code          return_msg
     * --------------------------------------
     * --------------------------------------
     */
    public static function init($json)
    {
        $obj = new self();
        error_log("GeekPayment response:$json");
        $obj->fromJson($json);
        return $obj->getBodyValues();
    }
}


/**
 * 统一下单对象
 * @author Leijid
 */
class GeekPayUnifiedOrder extends GeekPayDataBase
{

    /**
     * 设置商户支付订单号，同一商户唯一
     * @param string $value
     **/
    public function setOrderId($value)
    {
        $this->pathValues['order_id'] = $value;
    }

    /**
     * 获取商户支付订单号
     * @return string 值
     **/
    public function getOrderId()
    {
        return $this->pathValues['order_id'];
    }

    /**
     * 判断商户支付订单号是否存在
     * @return true 或 false
     **/
    public function isOrderIdSet()
    {
        return array_key_exists('order_id', $this->pathValues);
    }

    /**
     * 设置订单标题
     * @param string $value
     **/
    public function setTitle($value)
    {
        $this->bodyValues['title'] = $value;
    }

    /**
     * 获取订单标题
     * @return string 值
     **/
    public function getTitle()
    {
        return $this->bodyValues['title'];
    }

    /**
     * 判断订单标题是否存在
     * @return true 或 false
     **/
    public function isTitleSet()
    {
        return array_key_exists('title', $this->bodyValues);
    }

    public function attachParam($key, $value)
    {
        $this->bodyValues[$key] = $value;
    }

    /**
     * 设置金额，单位为货币最小单位
     * @param string $value
     **/
    public function setPrice($value)
    {
        $this->bodyValues['price'] = $value;
    }

    /**
     * 获取金额，单位为货币最小单位
     * @return 值
     **/
    public function getPrice()
    {
        return $this->bodyValues['price'];
    }

    /**
     * 判断金额是否存在
     * @return true 或 false
     **/
    public function isPriceSet()
    {
        return array_key_exists('price', $this->bodyValues);
    }

    /**
     * 设置支付渠道，Alipay为支付宝，Wechat为微信，大小写敏感
     * 默认值: Wechat
     * 允许值: Wechat, Alipay
     **/
    public function setChannel($value)
    {
        $this->bodyValues['channel'] = $value;
    }

    /**
     * 获取支付渠道
     * 默认值: Wechat
     * 允许值: Wechat, Alipay
     * @return 值
     **/
    public function getChannel()
    {
        return $this->bodyValues['channel'];
    }

    /**
     * 判断支付渠道是设置
     * @return true 或 false
     **/
    public function isChannelSet()
    {
        return array_key_exists('channel', $this->bodyValues);
    }

    /**
     * 设置支付异步通知url,不填则不会推送支付通知
     * @param string $value
     **/
    public function setNotifyUrl($value)
    {
        $this->bodyValues['notify_url'] = $value;
    }

    /**
     * 获取支付通知url
     * @return string 值
     **/
    public function getNotifyUrl()
    {
        return $this->bodyValues['notify_url'];
    }

    /**
     * 判断支付通知url是否存在
     * @return true 或 false
     **/
    public function isNotifyUrlSet()
    {
        return array_key_exists('notify_url', $this->bodyValues);
    }

    /**
     * 设置同步回调URL，支付完成后跳转此页面
     * @param $value
     */
    public function setReturnUrl($value)
    {
        $this->bodyValues['return_url'] = $value;
    }

    /**
     * 获取同步回调URL
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->bodyValues['return_url'];
    }

    /**
     * 判断同步回调url是否存在
     * @return bool
     */
    public function isReturnUrlSet()
    {
        return array_key_exists('return_url', $this->bodyValues);
    }

    /**
     * 设置操作人员标识
     * @param string $value
     **/
    public function setOperator($value)
    {
        $this->bodyValues['operator'] = $value;
    }

    /**
     * 获取操作人员标识
     * @return 值
     **/
    public function getOperator()
    {
        return $this->bodyValues['operator'];
    }

    /**
     * 判断操作人员标识是否存在
     * @return true 或 false
     **/
    public function isOperatorSet()
    {
        return array_key_exists('operator', $this->bodyValues);
    }

}

/**
 * 线下支付订单
 * @author Leijid
 */
class GeekPayMicropayOrder extends GeekPayUnifiedOrder
{
    /**
     * 设置设备ID
     * @param string $value
     **/
    public function setDeviceId($value)
    {
        $this->bodyValues['device_id'] = $value;
    }

    /**
     * 获取设备ID
     * @return 值
     **/
    public function getDeviceId()
    {
        return $this->bodyValues['device_id'];
    }

    /**
     * 判断设备ID是否存在
     * @return true 或 false
     **/
    public function isDeviceIdSet()
    {
        return array_key_exists('device_id', $this->bodyValues);
    }

    /**
     * 设置扫描用户微信客户端得到的支付码
     * @param string $value
     **/
    public function setAuthCode($value)
    {
        $this->bodyValues['auth_code'] = $value;
    }

    /**
     * 获取扫描用户微信客户端得到的支付码
     * @return 值
     **/
    public function getAuthCode()
    {
        return $this->bodyValues['auth_code'];
    }

    /**
     * 判断扫描用户微信客户端得到的支付码是否存在
     * @return true 或 false
     **/
    public function isAuthCodeSet()
    {
        return array_key_exists('auth_code', $this->bodyValues);
    }
}

/**
 * 线下QRCode支付单
 */
class GeekPayRetailQRCode extends GeekPayUnifiedOrder
{
    /**
     * 设置设备ID
     * @param string $value
     **/
    public function setDeviceId($value)
    {
        $this->bodyValues['device_id'] = $value;
    }

    /**
     * 获取设备ID
     * @return 值
     **/
    public function getDeviceId()
    {
        return $this->bodyValues['device_id'];
    }

    /**
     * 判断设备ID是否存在
     * @return true 或 false
     **/
    public function isDeviceIdSet()
    {
        return array_key_exists('device_id', $this->bodyValues);
    }
}

/**
 * 查询订单状态对象
 * @author Leijid
 */
class GeekPayOrderQuery extends GeekPayDataBase
{
    /**
     * 设置商户支付订单号，同一商户唯一
     * @param string $value
     **/
    public function setOrderId($value)
    {
        $this->pathValues['order_id'] = $value;
    }

    /**
     * 获取商户支付订单号
     * @return string 值
     **/
    public function getOrderId()
    {
        return $this->pathValues['order_id'];
    }

    /**
     * 判断商户支付订单号是否存在
     * @return true 或 false
     **/
    public function isOrderIdSet()
    {
        return array_key_exists('order_id', $this->pathValues);
    }
}

/**
 * 申请退款对象
 * @author Leijid
 */
class GeekPayApplyRefund extends GeekPayDataBase
{
    /**
     * 设置商户支付订单号，同一商户唯一
     * @param string $value
     **/
    public function setOrderId($value)
    {
        $this->pathValues['order_id'] = $value;
    }

    /**
     * 获取商户支付订单号
     * @return string 值
     **/
    public function getOrderId()
    {
        return $this->pathValues['order_id'];
    }

    /**
     * 判断商户支付订单号是否存在
     * @return true 或 false
     **/
    public function isOrderIdSet()
    {
        return array_key_exists('order_id', $this->pathValues);
    }

    /**
     * 设置商户退款单号
     * @param string $value
     **/
    public function setRefundId($value)
    {
        $this->pathValues['refund_id'] = $value;
    }

    /**
     * 获取商户退款单号
     * @return string 值
     **/
    public function getRefundId()
    {
        return $this->pathValues['refund_id'];
    }

    /**
     * 判断商户退款单号是否存在
     * @return true 或 false
     **/
    public function isRefundIdSet()
    {
        return array_key_exists('refund_id', $this->pathValues);
    }

    /**
     * 设置退款金额，单位是货币最小单位
     * @param string $value
     **/
    public function setAmount($value)
    {
        $this->bodyValues['amount'] = $value;
    }

    /**
     * 获取退款金额
     * @return 值
     **/
    public function getFee()
    {
        return $this->bodyValues['fee'];
    }

    /**
     * 判断退款金额是否存在
     * @return true 或 false
     **/
    public function isFeeSet()
    {
        return array_key_exists('fee', $this->bodyValues);
    }
}

/**
 * 查询退款状态对象
 * @author Leijid
 */
class GeekPayQueryRefund extends GeekPayDataBase
{
    /**
     * 设置商户支付订单号，同一商户唯一
     * @param string $value
     **/
    public function setOrderId($value)
    {
        $this->pathValues['order_id'] = $value;
    }

    /**
     * 获取商户支付订单号
     * @return string 值
     **/
    public function getOrderId()
    {
        return $this->pathValues['order_id'];
    }

    /**
     * 判断商户支付订单号是否存在
     * @return true 或 false
     **/
    public function isOrderIdSet()
    {
        return array_key_exists('order_id', $this->pathValues);
    }

    /**
     * 设置商户退款单号
     * @param string $value
     **/
    public function setRefundId($value)
    {
        $this->pathValues['refund_id'] = $value;
    }

    /**
     * 获取商户退款单号
     * @return string 值
     **/
    public function getRefundId()
    {
        return $this->pathValues['refund_id'];
    }

    /**
     * 判断商户退款单号是否存在
     * @return true 或 false
     **/
    public function isRefundIdSet()
    {
        return array_key_exists('refund_id', $this->pathValues);
    }
}

/**
 * 查询订单对象
 * @author Leijid
 */
class GeekPayQueryOrders extends GeekPayDataBase
{
    /**
     * 设置订单创建日期，'yyyyMMdd'格式，澳洲东部时间，不填默认查询所有订单
     * @param string $value
     **/
    public function setDate($value)
    {
        $this->queryValues['date'] = $value;
    }

    /**
     * 获取订单创建日期
     * @return 值
     **/
    public function getDate()
    {
        return $this->queryValues['date'];
    }

    /**
     * 判断订单创建日期是否存在
     * @return true 或 false
     **/
    public function isDateSet()
    {
        return array_key_exists('date', $this->queryValues);
    }

    /**
     * 设置订单状态
     * ALL:全部订单，包括未完成订单和已关闭订单
     * PAID:只列出支付过的订单，包括存在退款订单
     * REFUNDED:只列出存在退款订单
     * 默认值: ALL
     * 允许值: 'ALL', 'PAID', 'REFUNDED'
     * @param string $value
     **/
    public function setStatus($value = 'ALL')
    {
        $this->queryValues['status'] = $value;
    }

    /**
     * 获取订单状态
     * @return 值
     **/
    public function getStatus()
    {
        return $this->queryValues['status'];
    }

    /**
     * 判断订单状态是否存在
     * @return true 或 false
     **/
    public function isStatusSet()
    {
        return array_key_exists('status', $this->queryValues);
    }

    /**
     * 设置页码，从1开始计算
     * 默认值: 1
     * @param int $value
     **/
    public function setPage($value = 1)
    {
        $this->queryValues['page'] = $value;
    }

    /**
     * 获取页码
     * @return 值
     **/
    public function getPage()
    {
        return $this->queryValues['page'];
    }

    /**
     * 判断页码是否存在
     * @return true 或 false
     **/
    public function isPageSet()
    {
        return array_key_exists('page', $this->queryValues);
    }

    /**
     * 设置每页条数
     * 默认值: 10
     * @param int $value
     **/
    public function setLimit($value = 10)
    {
        $this->queryValues['limit'] = $value;
    }

    /**
     * 获取每页条数
     * @return 值
     **/
    public function getLimit()
    {
        return $this->queryValues['limit'];
    }

    /**
     * 判断每页条数是否存在
     * @return true 或 false
     **/
    public function isLimitSet()
    {
        return array_key_exists('limit', $this->queryValues);
    }
}

/**
 * 查询对账单对象
 * @author Astro
 */
class GeekPayQueryTransactions extends GeekPayDataBase
{
    /**
     * 设置日期，'yyyyMMdd'格式，澳洲东部时间，必填
     * @param string $value
     **/
    public function setDate($value)
    {
        $this->queryValues['date'] = $value;
    }

    /**
     * 获取订单创建日期
     * @return 值
     **/
    public function getDate()
    {
        return $this->queryValues['date'];
    }

    /**
     * 判断订单创建日期是否存在
     * @return true 或 false
     **/
    public function isDateSet()
    {
        return array_key_exists('date', $this->queryValues);
    }


}