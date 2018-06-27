<?php if (!defined('THINK_PATH')) exit();?><link  href="/Public/Admin/css/index3.0.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="/Public/Admin/js/jquery.e-calendar.js"></script>  
<link rel="stylesheet" href="/Public/Admin/css/jquery.e-calendar.css"/>
<script type="text/javascript">
$(document).ready(function () {
    $('#calendar').eCalendar({
        weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        textArrows: {previous: '<', next: '>'},
        
    });
});
</script>
<div class="">
<style>
.fleft {float:left;}
.fright {float:right;}
.flbox {width:32%;position:relative;}
.frbox {width:66%;position:relative;}
.clearfix {clear:both;overflow:hidden;}
.pd {padding: 15px;}
.mt {margin-top:15px;}
.show-num-mod {
    margin: 25px 15px 0px 0px;
}
.columns-mod .bd{background-color:#fff;overflow: hidden !important;}
.columns-mod .summary_bd{height:300px; margin-left: 70px;}

.columns-mod .hd a.menu-opt-area{cursor:pointer;background:url(/Public/Admin/images/menu_opt.png) no-repeat; position:absolute; right:0px; top:0px; display:block; width:30px; height:30px;}
.columns-mod .hd a.menu-opt-area:hover{ background-position:-30px 0px; text-decoration: none; border-bottom: 0px;}
.shortcut li {width: 20%;
    float: left;
    text-align: center;
    height: 120px;margin-top:5px;}
.shortcut li a {display:block;padding-top:16px;color:#404040;}
.shortcut li a:hover {border:none;color:#3EAFE1;}
.shortcut li img {width:50px;height:50px;}
.shortcut li font {    display: block;
    width: 100%;
    text-align: center;
    margin-top: 10px;}

.systemsinfo th,.systemsinfo td {font-size: 14px;
    height: 40px;
    line-height: 40px;}    
.systemsinfo th{
    text-align: left;    
    padding-left: 5%;
    width:18%;
    font-weight: normal;
    color: #777;
}
.systemsinfo td {    
    text-align: left;
    width:60%;
    padding-left: 2%;color: #333;}
.wait table {width:100%;text-align:left !important;}
.wait td {line-height:30px;}
.wait th {line-height:30px;height: 30px;text-align:left;}
.wait a{color:#3EAFE1;text-decoration:none;}
.wait a:hover {cursor:pointer;}
.wait td:first-child{width:28%;}
.wait td:nth-child{width:60%;}
.wait td:last-child{width:20%;}
.wait .tb_con td:last-child{width: 25%;text-align: left;}
.wait .tb_con td:last-child a span{width: 205px;display: block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
.wait .tb_con th:last-child{text-align: left;}
.personal{box-sizing:border-box;}
.personalinfo i img{ margin-top:10px;}
.personalinfo i{ display:block; width:80px; height:80px; background:#f1f5f7; border-radius:80px; text-align:center; vertical-align:middle;     border: 1px solid #DCDDDF;}
.personal .personalinfo span{}
.personalinfo{
	float: left;
    border-right: 1px solid #DCDDDF;
    padding: 20px 5% 20px 0px;
}
.ulstyle{margin: 6% 0 0 5%;float: left;}
.ulstyle li span{color: #777;}
.personal .personalinfo span:first-child{font-size:16px;}
.personal .personalinfo span:last-child{font-size:16px;color:#3EAFE1;}
.personal li {line-height:28px; font-size:14px; color:#222;}
.personal  .per_l{width:80%; padding:8% 0px 0px 18%;float:left;}
@media only screen and (max-width: 1440px) {
	.personal  .per_l{padding: 14% 0px 0px 14%;}
}
.personal  .per_r{ width:50%; float:right; border-left: 1px dashed #ddd; height:255px;}
.personal .per_wel  { 
    border-radius: 20px;
    margin-top: 20px;}

.datatit{    height: 40px;
    line-height: 40px;
    border-bottom: 1px dashed #ddd;
    width: 252px;
    margin: 0 auto;
    margin-bottom: 10px;
    margin-top: 10px; }
    
.datatit img{    margin-top: 5px;
    margin-right: 10px;
    float: left;}
.shortcutfram {border-radius:4px;}
.layui-layer-setwin a {border:none;}
.warntable{width:100%; margin-top: 18px;}
.warntable th, .warntable td{ height: 30px; line-height: 30px; text-align: center;  }
/*.warntable td a{ color: #3EAFE1; }*/

.bd_list{
    /*text-align: center;*/
    width: 166px;
    margin-top: 50px;
    float: left;
}
@media only screen and (max-width: 1600px) {
	.columns-mod .summary_bd{margin-left: 65px;}
	.bd_list{width: 135px;}
}
@media only screen and (max-width: 1440px) {
	.columns-mod .summary_bd{margin-left: 52px;}
	.bd_list{width: 118px;}
}
.wait .tb_con_r td:first-child{padding-left: 20px;width: 35%;}
.wait .tb_con_r th:first-child{padding-left: 25px;}

.bd_list_p span{font-weight: bold;font-size: 30px;}
.bd_list_p span b{font-size: 18px;position: absolute;margin-left: -15px;}
.bd_list_p span b.square{border: 4px solid #04BECD;border-radius: 2px;opacity: 0.3;margin-top: 7px;}
.bd_list_p span.color_ge b.square{border: 4px solid #D073F1;}
.tb_con{width: 43%;height: 100%;border-right: 1px solid #cdcdcd;float: left;overflow-y: auto;}
.tb_con_r{width: 55%;height: 100%;float: left;margin-left: 10px;overflow: hidden;}
.orange_txt{color: #FE8B2A;}
.orange_a{color: #FE8B2A !important;text-decoration: none !important;}
.color_ren{color: #04BECD;}
.color_ge{color: #D073F1;}
.color_yuan{color: #3B95C7;}
</style>

<!--第一部分-->
<div class="container-span top-columns cf overviewinfo">
	
</div>
<!--第一部分结束-->
<!--第二部分-->
<div class="clearfix">
    <div class="fleft flbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>个人信息<div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">个人信息</span>
                    <span class="">显示当前登录的管理员相关信息</span>
                </li>
            </ul>
        </div></h5>
                <div class="title-opt">
                </div>
            </div>

               <div class="bd personal">
            <div class="per_l clearfix">
                <div class="personalinfo">
                    <i><img src="/Public/Admin/images/per.png"></i>
                    <div class="per_wel"><span>您好</span>,<span><?php echo ($data["nickname"]); ?></span></div>
                </div>
                <ul class="ulstyle">
                    <li><span>所属角色：</span><?php echo ($data["group"]); ?></li>
                    <li><span>上次登录时间：</span><?php echo date('Y-m-d H:i:s',$data['last_login_time']);?></li>
                    <li><span>上次登录IP：</span><?php echo long2ip($data['last_login_ip']);?></li>
                    <li><span>累计登录次数：</span><?php echo ($data["login"]); ?>次</li>
                </ul>
                </div>
                
                
               <!--日历-->
               <!--  <div class="per_r">
                
                <h3 class="datatit"><i><img src="/Public/Admin/images/dataicon.png"></i>日历</h3>
                <div id="calendar"></div>
             
                </div> -->
                
                <!--日历结束-->
            </div>


            <!-- <div class="bd personal">
                <div class="personalinfo">
                    <i></i>
                    <div><span>您好</span>,<span><?php echo ($data["nickname"]); ?></span>，欢迎使用溪谷软件管理系统</div>
                </div>
                <ul>
                    <li>当前角色：<?php echo ($data["group"]); ?></li>
                    <li>上次登录时间：<?php echo date('Y-m-d H:i:s',$data['last_login_time']);?></li>
                    <li>上次登录IP：<?php echo long2ip($data['last_login_ip']);?></li>
                    <li>累计登录次数：<?php echo ($data["login"]); ?>次</li>
                </ul>
            </div> -->
        </div>
    </div>
    <div class="fright frbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>快捷功能<div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">快捷功能</span>
                    <span class="">用于快捷功能操作入口（<!-- 系统默认20个功能， -->可自由设置10个作首页显示）</span>
                </li>
            </ul>
        </div></h5>
                <div class="title-opt">
                </div>
                <a title="设置快捷图标" class="menu-opt-area jkuaijie"></a>
            </div>
            <div class="bd shortcut">
                <ul>
                    <?php $_result=get_kuaijie(1);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$my): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U($my['url']);?>"><img src="<?php echo get_cover($my['value'],'path');?>" /><font><?php echo ($my["title"]); ?></font></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <script src="/Public/static/layer/layer.js" type="text/javascript"></script>
        <script>
        $('.jkuaijie').click(function(){
            url="<?php echo U('kuaijie');?>";
            var index = layer.open({
                type: 2,
                title: false,
                closeBtn: 0, //不显示关闭按钮
                shade: [0],
                area: ['1px', '1px'],
                offset: 'rb', //右下角弹出
                time: 1, // 秒后自动关闭   这里设置成1ms 不显示过度页面
                anim: 2,
                content: ['', 'no'], //iframe的url，no代表不显示滚动条
                end: function () { //
                    layer.open({
                        type: 2,
                        skin:'shortcutfram',
                        title: false,
                        shadeClose: true,
                        id:'shortcutfram',
                        offset:'260px',
                        shade: 0.1,
                        maxmin: false, //开启最大化最小化按钮
                        area:'1240px',
                        content: [url,'no']//iframe的url
                    });
                }
            });
            
        });
        </script> 
    </div>
</div>
<!--第二部分结束-->


<!--第三部分-->
<div class="clearfix mt">
    <div class="fleft flbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>本月统计<!-- <div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">提示事项</span>
                    <span class="">提示当前系统需要操作的事项（此处显示内容为非必须操作事项）</span>
                </li>
            </ul>
        </div> --></h5>
                <div class="title-opt">
                </div>
            </div>
            <div class="bd summary_bd">         

            <div class="bd_list">
              <p  class="bd_list_p"><span class="color_ren"><b class="square"></b><?php echo ((isset($info["user"]) && ($info["user"] !== ""))?($info["user"]):0); ?></span>人</p>
              <p style="line-height: 30px;">注册玩家</p>
            </div>
             <div class="bd_list">
             	<p  class="bd_list_p"><span class="color_ge"><b class="square"></b><?php echo ((isset($info["game"]) && ($info["game"] !== ""))?($info["game"]):0); ?></span>个</p>
              <p style="line-height: 30px;">新增游戏</p>
            </div>
            <div class="bd_list">
              <p  class="bd_list_p"><span class="color_ren"><b class="square"></b><?php echo ((isset($info["promote"]) && ($info["promote"] !== ""))?($info["promote"]):0); ?></span>人</p>
              <p style="line-height: 30px;">新增推广员</p>
            </div>
             <div class="bd_list">
              <p  class="bd_list_p"><span class="color_yuan" style="font-size: 25px;"><b>&yen;</b><?php echo ((isset($info["samount"]) && ($info["samount"] !== ""))?($info["samount"]):0); ?></span>元</p>
              <p style="line-height: 30px;">游戏充值</p>
            </div>
             <div class="bd_list">
              <p  class="bd_list_p"><span class="color_yuan" style="font-size: 25px;"><b>&yen;</b><?php echo ($info['damount']); ?></span>元</p>
              <p style="line-height: 30px;">平台币充值</p>
            </div>
             <div class="bd_list">
              <p  class="bd_list_p"><span class="color_yuan" style="font-size: 25px;"><b>&yen;</b><?php echo ($info['spmount']); ?></span>元</p>
              <p style="line-height: 30px;">推广员充值</p>
            </div>

            </div>
        </div>
    </div>
    <div class="fright frbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>待办事项<div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">代办事项</span>
                    <span class="">显示当前系统待办事项（此时待办内容为必须操作事项）</span>
                </li>
            </ul>
        </div></h5>
                <div class="title-opt">
                </div>
            </div>
            <div class="bd wait" style="height:300px;">
            <div class="tb_con">
                <table cellpadding="1" cellspacing="1" class="warntable">
                    <thead><tr><th>提示板块</th><th>提示内容</th></tr></thead>
                    <tbody class="athover">
                    <?php if(is_array($tishi[gac])): $i = 0; $__LIST__ = $tishi[gac];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gac): $mod = ($i % 2 );++$i;?><tr><td>【原包管理】</td><td><a href="<?php echo U('GameSource/lists');?>" target="_blank"><span>[<?php echo ($gac["game_name"]); ?>]游戏原包尚未上传</span></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php if(is_array($tishi[prolc])): $i = 0; $__LIST__ = $tishi[prolc];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prolc): $mod = ($i % 2 );++$i;?><tr><td>【代充额度】</td><td><a href="<?php echo U('Promote/pay_limit');?>" target="_blank"><span>[<?php echo ($prolc["account"]); ?>]代充额度不足</span></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php if(is_array($tishi[rebc])): $i = 0; $__LIST__ = $tishi[rebc];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rebc): $mod = ($i % 2 );++$i;?><tr><td>【返利设置】</td><td><a href="<?php echo U('Rebate/lists');?>" target="_blank"><span>[<?php echo ($rebc["game_name"]); ?>]充值返利已到期</span></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php if(is_array($tishi[giftc])): $i = 0; $__LIST__ = $tishi[giftc];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$giftc): $mod = ($i % 2 );++$i;?><tr><td>【礼包列表】</td><td><a href="<?php echo U('Giftbag/lists');?>" target="_blank"><span>[<?php echo ($giftc["game_name"]); ?>]<?php echo ($giftc["giftbag_name"]); ?>数量不足</span></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php if(is_array($tishi[pgiftc])): $i = 0; $__LIST__ = $tishi[pgiftc];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pgiftc): $mod = ($i % 2 );++$i;?><tr><td>【推广员礼包】</td><td><a href="<?php echo U('PromoteGift/lists');?>" target="_blank"><span>[<?php echo ($pgiftc["game_name"]); ?>]<?php echo ($pgiftc["giftbag_name"]); ?>数量不足</span></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>  
            </div>

            <div class="tb_con_r">
                <table cellpadding="1" cellspacing="1" style="text-align: center;margin-top: 18px;">
                    <thead><tr><th>待办路径</th><th>待办内容</th><th>待办数量</th></tr></thead>
                    <tbody class='athover'>
                    <tr><td>【推广员管理】</td><td>推广员申请待审核数</td><td ><a href="<?php echo U('Promote/lists');?>" class="orange_a"><span class="orange_txt"><?php echo ($daiban["pcount"]); ?></span></a></td></tr>
                    <tr><td>【游戏审核】</td><td>推广员申请游戏待审核数</td><td ><a href="<?php echo U('Apply/lists');?>" class="orange_a"><span class="orange_txt"><?php echo ($daiban["gameapply"]); ?></span></a></td></tr>
                    <tr><td>【站点审核】</td><td>全站推广待审核数</td><td ><a href="<?php echo U('ApplyUnion/lists');?>" class="orange_a"><span class="orange_txt"><?php echo ($daiban["domainapply"]); ?></span></a></td></tr>
                    <tr><td>【推广员提现】</td><td>推广员提现待审核数</td><td ><a href="<?php echo U('Query/withdraw');?>" class="orange_a"><span class="orange_txt"><?php echo ($daiban["withc"]); ?></span></a></td></tr>
                    <tr><td>【游戏充值】</td><td>游戏充值待补单数</td><td ><a href="<?php echo U('Spend/lists');?>" class="orange_a"><span class="orange_txt"><?php echo ($daiban["spenc"]); ?></span></a></td></tr>
                    <!-- <tr><td>【站内通知】</td><td>推广员包待更新数</td><td ><a href="<?php echo U('Msg/lists');?>"><span><?php echo ($daiban["msgc"]); ?></span></a></td></tr> -->
                    </tbody>
                </table>
            </div>
                
            </div>
        </div>
    </div>
</div>
<!--第三部分结束-->



<!--第四部分-->
<div class="clearfix mt">
    <div class="fleft flbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>系统信息<div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">系统信息</span>
                    <span class="">显示当前版本以及相关信息</span>
                </li>
            </ul>
        </div></h5>
                <div class="title-opt">
                </div>
            </div>
            <div class="bd systemsinfo" style="height:370px;">
                <table style="width: 100%;margin-top: 20px;">
<tbody>
<tr><th>系统名称</th> <td>溪谷软件游戏管理系统</td></tr>
<tr><th>系统版本号</th> <td>v5.0</td></tr>
<tr><th>操作系统</th> <td><?php echo (PHP_OS); ?></td></tr>
<tr><th>运行环境</th> <td><?php echo ($_SERVER['SERVER_SOFTWARE']); ?></td></tr>
<tr><th>MYSQL版本</th> 
    <?php $system_info_mysql = M()->query("select version() as v;"); ?>
<td><?php echo ($system_info_mysql["0"]["v"]); ?></td></tr>
<tr><th>上传限制</th> <td><?php echo ini_get('upload_max_filesize');?></td></tr>
<tr><th>更新时间</th> <td>2017年12月22日</td></tr>
<tr><th>开发公司</th> <td>徐州梦创信息科技有限公司</td></tr>
</tbody>
</table>
            </div>
        </div>
    </div>
    <div class="fright frbox">
        <div class="columns-mod">
            <div class="hd cf blockinfo">
                <h5>七天数据统计<div class="question">
            <i class="question_mark">?</i>
            <ul class="question_content">
                <li class="question_title">功能以及数据相关说明</li>
                <li class="question_list">
                    <span class="">七天数据统计</span>
                    <span class="">显示当前系统七天内玩家注册数和充值流水数（可点击自由开启关闭）</span>
                </li>
            </ul>
        </div></h5>
                <div class="title-opt">
                </div>
            </div>
                <div class="" style="height:370px;width:100%;">
                    <div id="z_chart" style="height:100%;position:relative"><div id="chart" style="height:100%;"></div></div>
                </div>
								<style>.k-chart-tooltip {margin-left:-60px;}</style>
                <script type="text/javascript">
                    var min = '<?php echo ($info["pay"]["min"]); ?>';
                    var data = [<?php echo ($info["pay"]["data"]); ?>];
                    var max = '<?php echo ($info["pay"]["max"]); ?>';
                    var cate = [<?php echo ($info["pay"]["cate"]); ?>];

                    var min1 = '<?php echo ($info["reg"]["min"]); ?>';
                    var data1 = [<?php echo ($info["reg"]["data"]); ?>];
                    var max1 = '<?php echo ($info["reg"]["max"]); ?>';
                    var cate1 = [<?php echo ($info["reg"]["cate"]); ?>];
                    jQuery(function(){jQuery("#chart").kendoChart({
                        legend:{"position":"top","labels":{"font":"12px  DejaVu Sans"}},
                        series:[{"type":"line","data":data,"name":"七天流水（单位：元）","color":"#f8a20f","axis":"temp"},{"type":"line","data":data1,"name":"七天注册（单位：个）","color":"green","axis":"temp"}],
                        valueAxes:[{"name":"temp","min":min,"max":(max-max1>0?max:max1)}],
                        categoryAxis:[{"categories":cate,"axisCrossingValue":[0,2],"justified":true,"line":{"visible":false},"majorGridLines":{"visible":false}}],
                        tooltip:{"visible":true,"format":"{0}","shared":true}
                    });});
                </script>
           
        </div>
    </div>
</div>
<!--第四部分结束-->

<div class="clear"></div>
</div>
</block>