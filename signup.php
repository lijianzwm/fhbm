<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/7/4
 * Time: 下午1:56
 */

/**
 * fh_fahui
 * id,
 * name,
 * starttime,
 * description
 *
 * fh_offer_type
 * id
 * name
 * money
 *
 * fh_order
 * id
 * type
 * money
 * host
 * member
 * huixiang
 * address
 *
 *
 */

require_once "./conf/db.php";

$defaultSelectedYear = 1980;//生日默认选中1980年
$fhId = 1;//法会id

$sql = "SELECT
            name
        FROM
            fh_fahui
        WHERE
            id = '$fhId'";

$result = $db->query($sql);
if( $result && $row = $result->fetch_array() ){
    $fhName = $row['name'];
}else{
    die("无此法会!");
}

//授权,并获取openid
$code = $_GET["code"];
if( !$code ){
    $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING']);
    $urlObj["appid"] = WxPayConfig::APPID;
    $urlObj["redirect_uri"] = "$baseUrl";
    $urlObj["response_type"] = "code";
    $urlObj["scope"] = "snsapi_userinfo";
    $urlObj["state"] = "STATE"."#wechat_redirect";
    $bizString = "";
    foreach ($urlObj as $k => $v){
        if($k != "sign"){
            $bizString .= $k . "=" . $v . "&";
        }
    }
    $bizString = trim($bizString, "&");
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    Header("Location: $url");
    exit();
}
$userinfo = getUserInfo($code);
if( isset($userinfo['errcode'])){
    die("授权失败!");
}
$openid = $userinfo['openid'];



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>准提心脉·法会报名</title>
    <link rel="stylesheet" href="./css/frozen.css">

</head>
<body ontouchstart="">
<header class="ui-header ui-header-positive ui-border-b">
    <h1><?php echo $fhName; ?></h1>
</header>
<section class="ui-container">
    <div class="ui-form ui-border-t">
        <form action="confirm.php" method="post" id="form">
            <div class="ui-form-item ui-border-b">
                <label>
                    法会名称
                </label>
                <input type="text" disabled value="<?php echo $fhName; ?>">
                <input type="hidden" name="fh_name" value="<?php echo $fhName; ?>">
                <input type="hidden" name="fh_id" value="<?php echo $fhId; ?>">
                <input type="hidden" name="openid" value="<?php echo $openid; ?>">
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    法会选项
                </label>
                <div class="ui-select">
                    <select id="sx_item" name="sx_item" onchange="autoFill()">
                        <option value="300">小斋-300</option>
                        <option value="2000">大斋2000</option>
                        <option value="any">随喜不限</option>
                    </select>
                </div>
                <input type="hidden" name="sx_item_name" id="sx_item_name" value="小斋300"/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    随喜金额
                </label>
                <input type="text" name="money" id="money" placeholder="请输入随喜金额..." disabled value="300">
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    斋主姓名
                </label>
                <input type="text" name="host_name" id="host_name" placeholder="请输入斋主姓名..."
                       onfocus="fillUserInfo('host')">
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    斋主生日
                </label>
                <input type="text" name="host_birthday" id="host_birthday"/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    手机号码
                </label>
                <input name="phone" id="phone" type="text" placeholder="请输入手机号码...">
            </div>
            <ul id="family_member">

            </ul>
            <input id="family_member_str" name="members" value=""/>

            <div class="ui-form-item ui-form-item-textarea ui-border-b" id="add_member_div">
                <label>
                    家庭成员
                </label>
                <li style="float:right;" onclick="fillUserInfo('member')">
                    <i class="ui-icon-add"></i>
                </li>
            </div>

            <div class="ui-form-item ui-form-item-textarea ui-border-b">
                <label>
                    居住地址
                </label>
                <textarea name="address" id="address" placeholder="请输入居住地址..."></textarea>
            </div>

            <div class="ui-form-item ui-form-item-textarea ui-border-b">
                <label>
                    回向
                </label>
                <textarea name="huixiang" id="huixiang" placeholder="愿一切众生离苦得乐,究竟成佛"></textarea>
            </div>
        </form>
        <div class="ui-btn-wrap" onclick="submitForm()">
            <button class="ui-btn-lg ui-btn-primary">
                提交
            </button>
        </div>
    </div>

    <div class="ui-dialog" id="user_info">
        <div class="ui-dialog-cnt">
            <header class="ui-dialog-hd ui-border-b">
                <h3 id="dialog_title">请填写信息</h3>
            </header>
            <div class="ui-form-item ui-border-b">
                <label id="user_title">
                    斋主姓名
                </label>
                <input type="text" id="user_info_name" placeholder="请输入姓名...">
            </div>
            <div class="ui-form-item ui-border-b">
                <label>出生年月</label>
                <div class="ui-select-group">
                    <div class="ui-select">
                        <select id="birth_year" onchange="generateDay()">
                            <?php
                            $curYear = intval(date("Y", time()));
                            for ($i = 1900; $i <= $curYear; $i++) {
                                if ($i == $defaultSelectedYear) {
                                    echo "<option id='" . "by_" . $i . "' selected>" . $i . "</option>";
                                } else {
                                    echo "<option id='" . "by_" . $i . "'>" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="ui-select">
                        <select id="birth_month" onchange="generateDay()">
                            <option id="bm_1" selected>01</option>
                            <option id="bm_2">02</option>
                            <option id="bm_3">03</option>
                            <option id="bm_4">04</option>
                            <option id="bm_5">05</option>
                            <option id="bm_6">06</option>
                            <option id="bm_7">07</option>
                            <option id="bm_8">08</option>
                            <option id="bm_9">09</option>
                            <option id="bm_10">10</option>
                            <option id="bm_11">11</option>
                            <option id="bm_12">12</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>出生日期</label>
                <div class="ui-select">
                    <select id="birth_day" onchange="generateLunarCalendar()">
                    </select>
                </div>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    生肖属相
                </label>
                <input type="text" id="shengxiao" disabled>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>
                    农历生日
                </label>
                <input type="text" id="lunar_text" disabled>
            </div>
            <div class="ui-dialog-ft">
                <button type="button" data-role="button" onclick="closeUserInfo()">取消</button>
                <button type="button" data-role="button" id="confirm_button">确定</button>
            </div>
        </div>
    </div>

</section>
<script src="./lib/zepto.min.js"></script>
<script src="./lib/calendar.js"></script>
<script src="./js/frozen.js"></script>
<script>

    memberCount = 0;
    memberDivCount = 0;

    $(document).ready(
        function () {
            generateDay();
        }
    );

    //随着年份和月份的不同,自动生成每个月的天数
    function generateDay() {
        var year = $("#birth_year option").eq($("#birth_year").attr("selectedIndex")).text();
        var month = parseInt($("#birth_month option").eq($("#birth_month").attr("selectedIndex")).text());
        var day = new Date(year, month, 0).getDate();
        $("#birth_day option").remove();
        var dayDom = $("#birth_day");
        for (var i = 1; i <= day; i++) {
            var d = i < 10 ? "0" + i : i;
            if (i == 1) {
                dayDom.append("<option id='bd_" + i + "' selected>" + d + "</option>");
            } else {
                dayDom.append("<option id='bd_" + i + "'>" + d + "</option>");
            }

        }
        generateLunarCalendar();
    }

    //生成农历
    function generateLunarCalendar() {
        var year = $("#birth_year option").eq($("#birth_year").attr("selectedIndex")).text();
        var month = parseInt($("#birth_month option").eq($("#birth_month").attr("selectedIndex")).text());
        var day = parseInt($("#birth_day option").eq($("#birth_day").attr("selectedIndex")).text());
        var lunar = calendar.solar2lunar(year, month, day);
        $('#lunar_text').val(lunar.gzYear + '年' + lunar.IMonthCn + lunar.IDayCn);
        $('#shengxiao').val(lunar.Animal)
    }

    //根据选项自动填入金额以及项目名称
    function autoFill() {
        var money = $("#sx_item option").eq($("#sx_item").attr("selectedIndex")).val();
        $("#sx_item_name").val($("#sx_item option").eq($("#sx_item").attr("selectedIndex")).text());
        if (money == "any") {
            $("#money").removeAttr("disabled");
            $("#money").val("");
        } else {
            $("#money").removeAttr("disabled");
            $("#money").attr("disabled", "disabled");
            $("#money").val(money);
        }
    }

    function fillUserInfo(type) {

        $('#confirm_button').unbind("click"); //移除click

        var nameId, birthId;

        if (type == "host") {

            nameId = type + "_name";
            birthId = type + "_birth";
            $("#user_title").html("斋主姓名");
            $("#dialog_title").html("填写斋主信息");
            $("#confirm_button").click(function () {
                addUserInfo('host');
            });
        } else if (type == "member") {

            if (memberCount > 4) {
                alert("最多只能添加 4 位家庭成员!");
                return;
            }

            $("#user_info_name").val("");

            nameId = type + "_" + memberCount + "_name";
            birthId = type + "_" + memberCount + "_birth";
            $("#user_title").html("成员姓名");
            $("#dialog_title").html("填写家庭成员信息");
            $("#confirm_button").click(function () {
                addUserInfo('member_' + memberCount);
            });
        }

        $("#user_info").addClass("show");
        $("#user_info_name").focus();
    }

    function closeUserInfo() {
        $("#user_info").removeClass("show");
    }

    function addUserInfo(type) {

        var name = $("#user_info_name").val();
        var birthday = $("#lunar_text").val() + " (" + $("#shengxiao").val() + ")";

        if (type == 'host') {
            $("#host_name").val(name);
            $("#host_birthday").val(birthday);
        } else {

            if (memberCount >= 4) {
                alert("最多只能添加 4 位家庭成员!");
                return;
            }

            memberCount++;
            memberDivCount++;

            var divId = "member_div_" + memberDivCount;
            var removeId = "member_remove_" + memberDivCount;
            memberDom = $("#family_member");
            memberDom.append(
                "<li class='ui-form-item ui-border-b' id='" + divId + "'>" +
                "<label>家庭成员</label>" +
                "<input type='text' value='" + name + " " + birthday + "' />" +
                "<a id='" + removeId + "' class='ui-icon-close'></a>" +
                "</li>"
            );
            $("#" + removeId).click(function () {
                removeMember(divId);
            });

            if (memberCount == 4) {
                $("#add_member_div").hide();
            }
        }

        closeUserInfo();

    }

    function removeMember(id) {
        $("#" + id).remove();
        if (memberCount >= 4) {
            $("#add_member_div").show();
        }
        memberCount--;
    }

    function submitForm() {

        //随喜金额
        var money = $("#money").val();
        if ( money == "") {
            alert("请填写随喜金额!");
            return;
        }else{
            if( !money.match(/^[0-9]*$/) ){
                alert("金额必须为数字!");
                return;
            }
        }

        //斋主姓名
        if ($("#host_name").val() == "") {
            alert("请填写斋主姓名!");
            return;
        }

        //斋主生日
        if ($("#host_birthday").val() == "") {
            alert("请填写斋主生日!");
            return;
        }

        //手机号码
        var phone = $("#phone").val();
        if ( phone == "") {
            alert("请填写手机号码!");
            return;
        }else{
            if( !phone.match(/^[1][0-9]{10}$/) ){
                alert("手机号码格式错误!");
                return;
            }
        }

        //将家庭成员拼接成一个字符串,放到input隐藏域中
        var family_members = "";
        for (var i = 0; i < memberCount; ++i) {
            if( i > 0 ){
                family_members = family_members + "; ";
            }
            family_members = family_members + $("#family_member input").eq(i).val();
        }
        $("#family_member_str").val(family_members);

        //居住地址,,限制居住地址长度
        var address = $("#address").val();
        if ( address== "") {
            alert("请填写居住地址!");
            return;
        }else{
            if( address.length > 300 ){
                alert("居住地址过长!");
                return;
            }
        }

        //如果回向没有内容,添加默认值,限制回向长度
        var huixiang = $("#huixiang").val();
        if (huixiang == "") {
            huixiang = "愿一切众生离苦得乐,究竟成佛";
        }else{
            if( huixiang.length > 300 ){
                alert("回向内容过长!");
                return;
            }
        }
        $("#huixiang").val(huixiang);

        //提交表单
        $("#form").submit();
    }


</script>
</body>
</html>
