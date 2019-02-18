<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>GeekPay支付样例-线下QRCode支付</title>
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

$input = new GeekPayRetailQRCode();
$input->setOrderId('TEST' . date("YmdHis"));
$input->setTitle("test");
$input->setPrice("1");
$input->setNotifyUrl("https://demophp.geekpayment.com/example/notify.php");
$input->setDeviceId("18651874535");
$input->setOperator("123456");

//支付下单
$result = GeekPayApi::retailQRCodeOrder($input)->getBodyValues();
$url2 = $result["pay_url"];

?>
<body>
<form action="#" method="post">
    <img alt="扫码支付" src="qrcode.php?data=<?php echo urlencode($url2); ?>" style="width:150px;height:150px;"/>
</form>
</body>
</html>