<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>极客支付样例-线下支付</title>
</head>
<?php
require_once "../lib/GeekPay.Api.php";
require_once 'Log.php';

//初始化日志
$logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach ($data as $key => $value) {
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

if (isset($_REQUEST["auth_code"]) && $_REQUEST["auth_code"] != "") {
    $auth_code = $_REQUEST["auth_code"];
    $input = new GeekPayMicropayOrder();
    $input->setOrderId('TEST' . date("YmdHis"));
    $input->setTitle("test");
    $input->setPrice("100");
    $input->setNotifyUrl("https://demophp.geekpayment.com/example/notify.php");
    $input->setDeviceId("18651874535");
    $input->setAuthCode($auth_code);

    //支付下单
    $result = GeekPayApi::micropayOrder($input);

    /**
     * 注意：
     * 1、提交被扫之后，返回系统繁忙、用户输入密码等错误信息时需要循环查单以确定是否支付成功
     * 2、多次（一般10次）确认都未明确成功时需要调用撤单接口撤单，防止用户重复支付
     */
    $orderInput = new GeekPayOrderQuery();
    $orderInput->setOrderId($input->getOrderId());
    for ($i = 0; $i < 10; $i++) {
        $orderResult = GeekPayApi::orderQuery($orderInput)->getBodyValues();
        if ($orderResult['order_status'] == 'PAY_SUCCESS') {
            printf('支付成功：<br>');
            printf_info($orderResult);
            //测试支付完成自动退款
            $refundInput = new GeekPayApplyRefund();
            $refundInput->setOrderId($input->getOrderId());
            $refundInput->setRefundId('TESTREFUND' . date("YmdHis"));
            $refundInput->setAmount($input->getPrice());
            printf_info('退款:<br>');
            printf_info(GeekPayApi::refund($refundInput)->getBodyValues());
            exit();
        }
    }


}

?>
<body>
<form action="#" method="post">
    <div style="margin-left:2%;">商品描述：</div>
    <br/>
    <input type="text" style="width:96%;height:35px;margin-left:2%;" readonly value="线下测试样例-支付"
           name="auth_code"/><br/><br/>
    <div style="margin-left:2%;">支付金额：</div>
    <br/>
    <input type="text" style="width:96%;height:35px;margin-left:2%;" readonly value="1.00元"
           name="auth_code"/><br/><br/>
    <div style="margin-left:2%;">付款码：</div>
    <br/>
    <input type="text" style="width:96%;height:35px;margin-left:2%;" name="auth_code"/><br/><br/>
    <div align="center">
        <input type="submit" value="提交支付"
               style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;"
               type="button"/>
    </div>
</form>
</body>
</html>