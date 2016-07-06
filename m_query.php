<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/5
 * Time: 下午8:27
 */

require_once "./conf/db.php";

$sql = "SELECT
            id,
            name
        FROM
            fh_fahui
        ORDER BY
            endtime DESC";

$fhResult = $db->query($sql);

if( !$fhResult ){
    die("当前无法会信息!");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>随喜查询</title>
    <link rel="stylesheet" href="./css/frozen.css">
</head>
<body ontouchstart="">
<header class="ui-header ui-header-positive ui-border-b">
    <h1>随喜查询</h1>
</header>
<section class="ui-container">
    <form action="m_query_result.php" method="post" >

        <div class="ui-tooltips ui-tooltips-guide" id="tips">
            <div class="ui-tooltips-cnt ui-border-b">
                <i></i>输入报名时填写的手机号码即可查询<a class="ui-icon-close" onclick="hideTips()"></a>
            </div>
        </div>

        <div class="ui-form-item ui-border-b">
            <label>手机号码</label>
            <input type="text" name="phone" placeholder="请输入手机号...">
        </div>
        <div class="ui-form-item ui-border-b">
            <label>选择法会</label>
            <div class="ui-select">
                <select name="fh_id">
                    <?php
                        $cnt = 0;
                        while( $row = $fhResult->fetch_array() ){
                            if( !$cnt ){
                                echo "<option id='".$row['id']."' selected>".$row['name']."</option>";
                            }else{
                                echo "<option id='".$row['id']."'>".$row['name']."</option>";
                            }
                            ++$cnt;
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="ui-btn-wrap">
            <button class="ui-btn-lg ui-btn-primary">
                查询
            </button>
        </div>
    </form>
    <br/>
</section>
<script src="./lib/zepto.min.js"></script>
<script>

    function hideTips() {
        $("#tips").hide();
    }

</script>
</body>
</html>
