<?php
/**
 * Created by PhpStorm.
 * User: millet_li
 * Date: 16/9/27
 * Time: 上午1:18
 */
ini_set('date.timezone', 'Asia/Shanghai');
require_once "../lib/GeekPay.Api.php";
require_once 'Log.php';

$input = new GeekPayOrderQuery();
$input->setOrderId($_GET["order_id"]);
$res = GeekPayApi::orderQuery($input);
if (!$res->getBodyValue('result_code')=='PAY_SUCCESS'){
    echo '支付失败';
    exit();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>极客支付成功</title>
</head>
<body>
<p style="font-size: 30px">Order <?php echo $_GET['order_id']; ?> Paid</p>
<!-- 根据订单号查询金额及支付时间 -->
<p style="font-size: 20px">Price: CNY 1.00 </p>
<p style="font-size: 20px">Pay Time:<?php echo date('Y-m-d H:i:s', time()); ?></p>
</body>
</html>
