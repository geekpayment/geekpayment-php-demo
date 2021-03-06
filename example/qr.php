<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>极客支付样例-扫码</title>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
    <style>
        .lnk {
            display: block;
            width: 210px;
            height: 50px;
            border-radius: 15px;
            background-color: #fe6714;
            color: #fff;
            text-align: center;
            font-size: 16px;
            line-height: 50px;
            text-decoration: none;
            margin: 15px auto;
        }
    </style>
</head>
<body>
<?php
ini_set('date.timezone', 'Asia/Shanghai');
require_once "../lib/GeekPay.Api.php";
require_once "./utils.php";
header("Content-Type:text/html;charset=utf-8");
/**
 * 流程：
 * 1、创建QRCode支付单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，GeekPayment服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
//获取扫码
if (!isset($_GET["channel"])) {
    ?>
    <a href="qr.php?channel=wechat" class="lnk">微信</a>
    <a href="qr.php?channel=alipay" class="lnk">支付宝</a>
    <?php
} else {
    $channel = $_GET["channel"];
    $input = new GeekPayUnifiedOrder();
    $input->setOrderId('TEST' . date("YmdHis"));
    $input->setTitle("test");
    $input->setChannel($channel);
    $input->setPrice("100");
    $input->setNotifyUrl("https://demophp.geekpayment.com/example/notify.php");
    $input->setReturnUrl('https://demophp.geekpayment.com/example/success.php?order_id=' . strval($input->getOrderId()));
    $input->addGoods('测试商品');
    $input->setCustomerId('customer001');
    $input->setCustomerIp(get_real_ip());

    $result = GeekPayApi::qrOrder($input)->getBodyValues();
    $url2 = $result["qr_code"];
    $code_url = $result['code_img_url']

    ?>
    <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">方式一、扫码支付</div>
    <br/>
    <img alt="扫码支付" src="<?php echo $code_url; ?>" style="width:150px;height:150px;"/>
    <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">方式二、跳转到极客支付</div>
    <br/>
    <button onclick="redirect('<?php echo $result['pay_url']; ?>')">跳转
    </button>
    <div>
        Request Body:
        <pre><?php echo json_encode($input->getBodyValues(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
    </div>
    <div>Response:
        <pre><?php echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
    </div>
    <?php
}
?>
</body>
</html>