

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>极客支付样例-APP支付</title>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
    <style>
	  .lnk{
	      display:block;
		  width:210px;
		  height:50px;
		  border-radius:15px;
		  background-color:#fe6714;
		  color:#fff;
		  text-align:center;
		  font-size:16px;
		  line-height:50px;
		  text-decoration:none;
		  margin:15px auto;
	  }
	</style>
</head>
<body>
<br/>
<?php
ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../lib/GeekPay.Api.php";
require_once 'Log.php';
header("Content-Type:text/html;charset=utf-8");


function printf_info($data)
{
    foreach ($data as $key => $value) {
        echo "<font color='#f00;'>$key</font> : $value <br/>";
    }
}
//初始化日志
$logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);
if(!isset($_GET["channel"])){
  ?>
  <a href="sdk.php?channel=Wechat" class="lnk" >微信</a>
  <a href="sdk.php?channel=Alipay" class="lnk" >支付宝</a>
<?php
}else{
$channel = $_GET["channel"];
$input = new GeekPayUnifiedOrder();
if($channel=='Wechat'){
	//微信要求提供开发者平台的appid(必须)
	$input->attachParam("appid",GeekPayConfig::APP_WX_APPID);
}else{
	//支付宝要求提供操作系统和客户端版本号(可选)
	$input->attachParam("system","iphone");
	$input->attachParam("version","1.0.0");
}
$input->setOrderId('TEST' . date("YmdHis"));
$input->setTitle("test");
$input->setPrice("100");
$input->setChannel($channel);
$input->setNotifyUrl("https://demophp.geekpayment.com/example/notify.php");
$input->setOperator("123456");
//支付下单
$result = GeekPayApi::appOrder($input);

?>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1.00</span>元</b></font><br/><br/>
<div align="center">
    <?php
	printf_info($result);
	?>
</div>
<?php

  }
?>
</body>
</html>
