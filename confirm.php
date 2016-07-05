<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/5
 * Time: 上午9:41
 */

require_once "./pay/wxpay/WxPay.JsApiPay.php";

date_default_timezone_set("Asia/Shanghai");

$payChannel = 'WXPAY';//手机版默认微信支付!

if( !isset($_POST['openid'] ) ){
    die("未传入openid!");
}

$openid = $_POST['openid'];
$fhId = $_POST['fh_id'];
$fhName = $_POST['fh_name'];
$sxItem = $_POST['sx_item'];
if ($sxItem == "any") {
    $money = intval($_POST['money']);
} else {
    $money = intval($_POST['sx_item']);
}
$hostName = $_POST['host_name'];
$hostBirth = $_POST['host_birthday'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$huixiang = $_POST['huixiang'];
$members = isset($_POST['members']) ? $_POST['members'] : "";

$memberArr = array();

if ($members != "") {
    $memberArr = explode('; ', $members);
}
$order_no = time().mt_rand(10,100);

//生成订单信息
$notifyUrl = "http://" . $_SERVER['HTTP_HOST'] . "/fhbm/pay/wxpay/notify.php";
$tools = new JsApiPay();
$input = new WxPayUnifiedOrder();
$input->SetProduct_id($sxItem);
$input->SetGoods_tag($fhName);
$input->SetBody("准提心脉法会随喜");
$input->SetAttach("准提心脉法会随喜");
$input->SetTotal_fee($money*100);
$input->SetOut_trade_no($order_no);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($notifyUrl);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openid);

$order = WxPayApi::unifiedOrder($input);

if( !isset($order) ){
    die("订单生成失败!");
}


if(!array_key_exists("appid", $order) || !array_key_exists("prepay_id", $order) || $order['prepay_id'] == ""){
    echo "<script>alert('参数错误,点击重新下单!'); window.location.href='m_yishan.php';</script>";
}
$jsApiParameters = $tools->GetJsApiParameters($order);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>确认信息</title>
    <link rel="stylesheet" href="./css/frozen.css">
</head>
<body ontouchstart="">
<header class="ui-header ui-header-positive ui-border-b">
    <h1>确认信息</h1>
</header>

<section class="ui-container">
    <br/>
    <table class="ui-table ui-border">
        <tbody>
        <tr>
            <td>法会名称</td>
            <td><?php echo $fhName; ?></td>
        </tr>
        <tr>
            <td>随喜项目</td>
            <td><?php echo $sxItem=="any"?"随喜不限":$sxItem; ?></td>
        </tr>
        <tr>
            <td>随喜金额</td>
            <td><strong style="color:red;"><?php echo number_format($money, 2)."元"; ?></strong></td>
        </tr>
        <tr>
            <td>斋主姓名</td>
            <td><?php echo $hostName; ?></td>
        </tr>
        <tr>
            <td>斋主生日</td>
            <td><?php echo $hostBirth; ?></td>
        </tr>
        <tr>
            <td>手机号码</td>
            <td><?php echo $phone; ?></td>
        </tr>
        <tr>
            <td>居住地址</td>
            <td><?php echo $phone; ?></td>
        </tr>
        <?php
        foreach ($memberArr as $member) {
            echo "<tr><td>家庭成员</td><td>" . $member . "</td></tr>";
        }
        ?>
        <tr>
            <td>回向</td>
            <td><?php echo $phone; ?></td>
        </tr>
        </tbody>
    </table>
    <br/>
    <div class="ui-btn-wrap">
        <button class="ui-btn-lg ui-btn-primary" id="wxpay_button" onclick="createOrder()">
            微信支付
        </button>
        <br/ >
        <button class="ui-btn-lg" onclick="refill()">
            <p style="color:grey;">返回重填</p>
        </button>
    </div>


    <!-- 创建订单载入画面 -->
    <div class="ui-loading-block" id="now_loading">
        <div class="ui-loading-cnt">
            <i class="ui-loading-bright"></i>
            <p id="loading_text">创建订单...</p>
        </div>
    </div>
    <script type="text/javascript" class="demo-script"></script>

    <!-- 订单创建失败提示 -->
    <div class="ui-dialog">
        <div class="ui-dialog-cnt">
            <header class="ui-dialog-hd ui-border-b">
                <h3>错误!</h3>
                <i class="ui-dialog-close" data-role="button" onclick="closeDialog()"></i>
            </header>
            <div class="ui-dialog-bd">
                <h4>订单创建失败!</h4>
            </div>
            <div class="ui-dialog-ft">
                <button type="button" data-role="button" onclick="closeDialog()">取消</button>
                <button type="button" data-role="button" onclick="retryCreateOrder()">重试</button>
            </div>
        </div>
    </div>
    <script class="demo-script"></script>

</section>

<script src="./lib/zepto.min.js"></script>
<script>

    function refill(){
        window.location.href="signup.php";
    }

    function createOrder(){
        $("#now_loading").addClass("show");
        $("#loading_text").text("创建订单...");
        $.ajax({
            url: "CreateOrder.php",
            data: {
                fh_id : <?php echo $fhId; ?>,
                order_no : <?php echo $order_no; ?>,
                type : '<?php echo $sxItem=="any"?"随喜不限":$sxItem; ?>',
                money : '<?php echo $money; ?>',
                pay_channel : '<?php echo $payChannel; ?>',
                host_name : '<?php echo $hostName; ?>',
                host_birthday : '<?php echo $hostBirth; ?>',
                phone : '<?php echo $phone; ?>',
                member : '<?php echo $members; ?>',
                address : '<?php echo $address; ?>',
                huixiang : '<?php echo $huixiang; ?>'
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                $("#now_loading").removeClass("show");
                if( data.error_code ){
                    $(".ui-dialog").addClass("show");
                }else{
                    callpay();
                }
            },
            error: function () {
                alert("创建订单请求失败!");
            }
        });
    }

    function retryCreateOrder(){
        $(".ui-dialog").removeClass("show");
        createOrder();
    }

    function closeDialog(){
        $(".ui-dialog").removeClass("show");
    }

    function updatePayStatus(){
        $.ajax({
            url: "./pay/wxpay/UpdatePayStatus.php",
            data: {
                out_trade_no : '<?php echo $order_no; ?>'
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data.error_code) {
                    alert(data.msg+"点击重试!");
                    updatePayStatus();
                }else{
                    $("#now_loading").removeClass("show");
                    alert(data.msg);
                    window.location.href="success.php";
                }
            },
            error: function () {
                alert("更新订单状态请求失败,点击重试!");
                updatePayStatus();
            }
        });
    }

    //调用微信JS api 支付
    function jsApiCall(){
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                if( !res.err_code ){
                    $("#now_loading").addClass("show");
                    $("#loading_text").text("处理中...");
                    updatePayStatus();
                }else{
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }


</script>
</body>
</html>
