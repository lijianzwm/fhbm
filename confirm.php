<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/5
 * Time: 上午9:41
 */

$payChannel = 'WXPAY';//手机版默认微信支付!

$fhId = $_POST['fh_id'];
$fhName = $_POST['fh_name'];
$sxItem = $_POST['sx_item_name'];
if ($sxItem == "any") {
    $money = $_POST['money'];
} else {
    $money = $_POST['sx_item'];
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
        <button class="ui-btn-lg ui-btn-primary" onclick="createOrder()">
            微信支付
        </button>
        <br/ >
        <button class="ui-btn-lg" onclick="refill()">
            <p style="color:grey;">返回重填</p>
        </button>
    </div>
</section>
<script src="./lib/zepto.min.js"></script>
<script>

    function refill(){
        window.location.href="signup.php";
    }

    function createOrder(){
        alert("createOrder");
        $.ajax({
            url: "CreateOrder.php",
            data: {
                type : '<?php echo $sxItem=="any"?"随喜不限":$sxItem; ?>',
                money : '<?php echo $money; ?>',
                pay_channel : '<?php echo $payChannel; ?>',
                host_name : '<?php echo $hostName; ?>',
                host_birthday : '<?php echo $hostBirth; ?>',
                member : '<?php echo $members; ?>',
                address : '<?php echo $address; ?>',
                huixiang : '<?php echo $huixiang; ?>'
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                alert(JSON.stringify(data));
            },
            error: function () {
                alert("创建订单请求失败!");
            }
        });
    }

</script>
</body>
</html>
