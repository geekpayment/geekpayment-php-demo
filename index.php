<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/demo.css" rel="stylesheet">
    <title>极客收单支付样例</title>
</head>
<body>
<nav id="header" class="navbar navbar-dark navbar-full">
    <div class="container">
        <div class="navbar-header geek-nav">
            <a class="navbar-brand" href="https://www.geekpayment.com/">
                <img src="//static.geekpayment.com/resources/images/geek_horizon_white.svg" alt="" class="navbar--logo">
            </a>
            <div class="navbar--line"></div>
            <a class="navbar-brand">
                <img src="static/images/alipaylogo.png" alt="" class="navbar--logo">
            </a>
            <a class="navbar-brand">
                <img src="static/images/WeChatPay_Cn_Small.png" alt="" class="navbar--logo">
            </a>
        </div>

    </div>
</nav>
<div class="container block-container border">
    <div class="row border-bottom">
        <div class="col api border-right">
            <div class="icon mx-auto">
                <img src="static/images/yuansheng.png" class="mx-auto">
            </div>
            <div class="title">原生扫码支付</div>
            <div class="text-center">
                <a class="btn btn-success btn-sm" href="example/qr.php?channel=wechat">微信</a>
                <button class="btn btn-primary btn-sm" disabled aria-disabled="true" title="暂不支持">支付宝</button>
            </div>
        </div>
        <a class="col api border-right" href="example/commonOrder.php">
            <div class="icon mx-auto">
                <img src="static/images/tongyongzhifufangshi.png" class="mx-auto">
            </div>
            <div class="title">通用支付</div>
            <div class="desc">微信/支付宝扫码或直接在微信/支付宝客户端内支付</div>
        </a>
        <a class="col api border-right" href="example/micropay.php">
            <div class="icon mx-auto">
                <img src="static/images/xianxia.png" class="mx-auto">
            </div>
            <div class="title">线下支付</div>
            <div class="desc">POS机扫消费者付款码</div>
        </a>
        <a class="col api" href="example/retail.php">
            <div class="icon mx-auto">
                <img src="static/images/xianxia.png" class="mx-auto">
            </div>
            <div class="title">线下QR Code支付</div>
            <div class="desc">收银系统生成一次性收款码</div>
        </a>
    </div>
    <div class="row border-bottom">
        <a class="col api" href="example/cashier.php">
            <div class="icon mx-auto">
                <img src="static/images/dingdanchaxun.png" class="mx-auto">
            </div>
            <div class="title">快捷支付</div>
            <div class="desc">跳转第三方收银台，银联/微信/支付宝</div>
        </a>
    </div>
    <div class="row">
        <a class="col api border-right" href="example/orderquery.php">
            <div class="icon mx-auto">
                <img src="static/images/dingdanchaxun.png" class="mx-auto">
            </div>
            <div class="title">订单查询</div>
            <div class="desc">确认订单支付状态</div>
        </a>
        <a class="col api border-right" href="example/refund.php">
            <div class="icon mx-auto">
                <img src="static/images/dingdantuikuan.png" class="mx-auto">
            </div>
            <div class="title">退款</div>
            <div class="desc">发起退款</div>
        </a>
        <a class="col api border-right" href="example/refundquery.php">
            <div class="icon mx-auto">
                <img src="static/images/tuikuanchaxun.png" class="mx-auto">
            </div>
            <div class="title">退款查询</div>
            <div class="desc">确认退款状态</div>
        </a>
        <a class="col api disabled">
            <div class="icon mx-auto">
                <img src="static/images/jingqingqidai.png" class="mx-auto">
            </div>
            <div class="title">敬请期待</div>
        </a>
    </div>
</div>
<div class="container footer">
    <div class="text-center text-gray">@2019 GeekPayment</div>
</div>
</body>
</html>