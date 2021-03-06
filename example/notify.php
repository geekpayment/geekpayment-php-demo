<?php
ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../lib/GeekPay.Api.php";
require_once "../lib/GeekPay.Data.php";
require_once 'Log.php';

//初始化日志
$logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

//回调参数读取
$response = json_decode(file_get_contents('php://input', 'r'), true);
$input = new GeekPayDataBase();
$input->setNonceStr($response['nonce_str']);
$url='https://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
$input->setSign($url);
if ($input->getSign() == $response['sign']) {//验证成功
    //请在这里加上商户的业务逻辑程序代
    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //商户订单号
    $order_id = $response['mch_order_id'];
    //GeekPay订单号
    $geek_order_id = $response['order_id'];
    //订单金额，单位是最小货币单位
    $order_amt = $response['price'];
    //支付金额，单位是最小货币单位
    $pay_amt = $response['pay_amount'];
    //订单创建时间，格式为'yyyy-MM-dd HH:mm:ss'
    $create_time = $response['create_time'];
    //订单支付时间，格式为'yyyy-MM-dd HH:mm:ss'
    $pay_time = $response['pay_time'];

    //读取商户系统订单信息
    //判断支付金额是否和订单金额相等

    //更新订单状态

    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    $result = array('return_code' => 'SUCCESS');
    $log::DEBUG('notify ok');
    echo json_encode($result);
} else {//验证失败
    echo "fail";
}
?>