<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/5
 * Time: 上午11:35
 */

require_once "./conf/db.php";

date_default_timezone_set("Asia/Shanghai");

$fh_id = $_POST['fh_id'];
$order_no = $_POST['order_no'];
$type = $_POST['type'];
$pay_status = 'NONE';
$pay_channel = $_POST['pay_channel'];
$money = $_POST['money'];
$host_name = $_POST['host_name'];
$host_birthday = $_POST['host_birthday'];
$phone = $_POST['phone'];
$member = $_POST['member'];
$address = $_POST['address'];
$huixiang = $_POST['huixiang'];
$create_time = date("Y-m-d H:i:s" , time());


$sql = "INSERT INTO {$dbprefix}order(
            fh_id,
            order_no,
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
        )
        VALUES
            (
                '{$fh_id}',
                '{$order_no}',
                '{$type}',
                '{$pay_status}',
                '{$pay_channel}',
                '{$money}',
                '{$host_name}',
                '{$host_birthday}',
                '{$phone}',
                '{$member}',
                '{$address}',
                '{$huixiang}',
                '{$create_time}'
            )";

$result = $db->query($sql);

if( $result ){
    $ret['error_code'] = 0;
    $ret['msg'] = "创建订单成功!";
}else{
    $ret['error_code'] = 1;
    $ret['msg'] = "创建订单失败!";
}

echo json_encode($ret);



