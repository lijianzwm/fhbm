<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/6
 * Time: 上午10:42
 */

require_once "./conf/db.php";

$fhId = $_REQUEST['fh_id'];
$fhName = $_REQUEST['fh_name'];

$signupUrl = "m_signup.php?fh_id=".$fhId."&fh_name=".$fhName;//注册页面
$queryUrl = "m_query.php";//查询页面



?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>法会报名</title>
    <link rel="stylesheet" href="./css/frozen.css">
</head>
<body ontouchstart="">
<header class="ui-header ui-header-positive ui-border-b">
    <i class="ui-icon-return" onclick="history.back()"></i><h1>法会报名</h1><button class="ui-btn">回首页</button>
</header>
<section class="ui-container">
    <br/>
    <div class="ui-flex ui-flex-pack-center">
        <div><?php echo $fhName; ?></div>
    </div>
    <div class="ui-btn-wrap">
        <button class="ui-btn-lg ui-btn-primary" onclick="signup()">
            法会报名
        </button>
    </div>
    <div class="ui-btn-wrap">
        <button class="ui-btn-lg ui-btn-primary" onclick="query()">
            法会查询
        </button>
    </div>
</section>
<script src="./lib/zepto.min.js"></script>
<script src="./js/frozen.js"></script>
<script>
    function query(){
        window.location.href= '<?php echo $queryUrl; ?>';
    }

    function signup(){
        window.location.href= '<?php echo $signupUrl; ?>';
    }

</script>
</body>
</html>
