

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>极客支付样例-通用支付</title>
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

//初始化日志
$logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
$log = Log::Init($logHandler, 15);

$channel = $_GET["channel"];
$input = new GeekPayUnifiedOrder();
$input->setOrderId('TEST' . date("YmdHis"));
$input->setTitle("test");
$input->setPrice("100");
$input->setNotifyUrl("https://demophp.geekpayment.com/example/notify.php");
$input->setReturnUrl('https://demophp.geekpayment.com/cn/example/success.php?order_id=' . strval($input->getOrderId()));
$input->setOperator("123456");
//支付下单
$result = GeekPayApi::commonOrder($input);

//跳转
?>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1.00</span>元</b></font><br/><br/>
<div align="center">
    <button
        style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;"
        type="button"
        onclick="redirect('<?php echo $result['pay_url']; ?>')">
        立即支付
    </button>
</div>
<pre><?php echo json_encode($result) ?></pre>
</body>
</html>
