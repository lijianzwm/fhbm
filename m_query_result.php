<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/6
 * Time: 上午9:02
 */

require_once "./conf/db.php";

$phone = $_REQUEST['phone'];

$sql = "SELECT
            order_no,
            name,
            type,
            pay_status,
            pay_channel,
            money,
            host_name,
            host_birthday,
            phone,
            member,
            address,
            huixiang,
            create_time
        FROM
            fh_fahui,
            fh_order
        WHERE
            phone='$phone'
        AND fh_id = fh_fahui.id
        ORDER BY
            create_time DESC";

$result = $db->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>查询结果</title>
    <link rel="stylesheet" href="./css/frozen.css">
</head>
<body ontouchstart="">
<header class="ui-header ui-header-positive ui-border-b">
    <h1>查询结果</h1>
</header>

<section class="ui-container">
    <?php
        while( $row = $result->fetch_array() ){
            $row['member'] = str_replace("; ", "<br/>", $row['member']);
            $payStatus = $row['pay_status'] == "PAYSUCCESS" ? "<strong style='color:green;'>支付成功</strong>" : "<strong style='color:red;'>支付失败</strong>";
            switch( $row['pay_channel']){
                case "WXPAY":
                    $payChannel = "微信支付";
                    break;
                case "ALIPAY":
                    $payChannel = "支付宝支付";
                    break;
                default :
                    die("支付方式错误!");
                    break;
            }
            echo "<br />";
            echo "<table class='ui-table ui-border'>";
            echo "<tbody>";
            echo "<tr><td>法会名称</td>";
            echo "<td>".$row['name']."</td></tr>";
            echo "<tr><td>随喜类型</td>";
            echo "<td>".$row['type']."</td></tr>";
            echo "<tr><td>支付状态</td>";
            echo "<td>".$payStatus."</td></tr>";
            echo "<tr><td>支付渠道</td>";
            echo "<td>".$payChannel."</td></tr>";
            echo "<tr><td>支付金额</td>";
            echo "<td>".number_format($row['money'], 2 )." 元</td></tr>";
            echo "<tr><td>斋主姓名</td>";
            echo "<td>".$row['host_name']."</td></tr>";
            echo "<tr><td>斋主生日</td>";
            echo "<td>".$row['host_birthday']."</td></tr>";
            echo "<tr><td>电话号码</td>";
            echo "<td>".$row['phone']."</td></tr>";
            echo "<tr><td>家庭成员</td>";
            echo "<td>".$row['member']."</td></tr>";
            echo "<tr><td>居住地址</td>";
            echo "<td>".$row['address']."</td></tr>";
            echo "<tr><td>回向内容</td>";
            echo "<td>".$row['huixiang']."</td></tr>";
            echo "<tr><td>随喜时间</td>";
            echo "<td>".$row['create_time']."</td></tr>";
            echo "</tbody></table>";
        }
    ?>
    <div class="ui-btn-wrap">
        <button class="ui-btn-lg ui-btn" onclick="history.back()">
            返回
        </button>
    </div>
</section>
<script src="./lib/zepto.min.js"></script>
<script src="./js/frozen.js"></script>
<script>

</script>
</body>
</html>




