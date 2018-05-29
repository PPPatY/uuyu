<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="keywords" content="<?php echo C('CH_SET_META_KEY');?>" />
		<meta name="description" content="<?php echo C('CH_SET_META_DESC');?>" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<?php if(ACTION_NAME == index and CONTROLLER_NAME == Index): ?><title><?php echo seo_replace(C('channel_index.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_index.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_index.seo_description');?>">
		<?php elseif(ACTION_NAME == game_list and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_game_list.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_game_list.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_game_list.seo_description');?>">
		<?php elseif(ACTION_NAME == detail and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_news_detail.seo_title'),array('title'=>$info['title']),'channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_news_detail.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_news_detail.seo_description');?>">
		<?php elseif(ACTION_NAME == more_lists and CONTROLLER_NAME == Article): ?>
			<title><?php echo seo_replace(C('channel_news_list.seo_title'),'','channel');?></title>
			<meta name="keywords" content="<?php echo C('channel_news_list.seo_keyword');?>">
			<meta name="description" content="<?php echo C('channel_news_list.seo_description');?>">
		<?php else: ?>
			<title><?php echo C('CH_SET_TITLE');?></title><?php endif; ?>
		<title><?php echo C('CH_SET_TITLE');?></title>
      <link href="<?php echo get_cover(C('CH_SET_ICO'),'path');?>" type="image/x-icon" rel="shortcut icon">
   
      <link href="/Public/Home/css/20170913/common.css" rel="stylesheet" >
      <script src="/Public/Home/js/jquery-1.11.1.min.js"></script>
      <script src="/Public/static/layer/layer.js" ></script>
      
      
    <link href="/Public/Home/css/20170913/news.css" rel="stylesheet">

      
	</head>
	<body>
    <div class="header">
      <div class="inner clearfix">
          <div class="logo">
              <a href="<?php echo U('index/index');?>">
                  <img src="<?php echo get_cover(C('CH_SET_LOGO'),'path');?>" alt="logo">
              </a>
              <i></i>
              <span class="text"><span>H5推广</span><span>联盟中心</span></span>
          </div>
          <ul class="nav clearfix">
            <?php $__NAV__ = M('Channel')->field(true)->where("status=1")->order("sort asc")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): ?><li>
                      <a href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>"><span><?php echo ($nav["title"]); ?></span></a>
                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </ul>
      </div>
    </div>
     
    <div class="trunk">
		
    <div class="banner">
      <div class="inner"></div>
    </div>
    <div class="page-list article-more_list-list">
      <div class="inner clearfix">
        <div class="wrap">
          <div class="trunk-tab">
            <ul class="article-menu clearfix" >
              <li class="<?php if(($_GET['type']) != "zx"): ?>active<?php endif; ?>"><a href="<?php echo U('Article/more_lists');?>" class="article-menu-item" title="公告中心" >公告中心</a></li>
              <li class="<?php if(($_GET['type']) == "zx"): ?>active<?php endif; ?>"><a href="<?php echo U('Article/more_lists',array('type'=>'zx'));?>" class="article-menu-item" title="资讯中心" >资讯中心</a></li>
            </ul>
          </div>
          <div class="trunk-list">
            <div class="list">
                <ul class="article-list">
                  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span class="dot"></span><a href="<?php echo U('detail',array('id'=>$vo['id']));?>" target="_blank" title="<?php echo ($vo['title']); ?>"><?php echo msubstr($vo['title'],0,70);?></a><span class="time"><?php echo (date('Y-m-d H:i',$vo["create_time"])); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="page"><?php echo ($_page); ?></div>
          </div>
        </div>
      </div>
    </div>

    </div>
		
    <div class="footer">
        <div class="inner" style="margin-top: 27px;">
          <p><span>客服电话：<?php echo C('CH_SET_SERVER_TEL');?></span><span>客服邮箱：<?php echo C('CH_SET_SERVER_EMAIL');?></span><span>服务时间：09:00 - 18:00</span></p>
          <p class="footer_text">
          <span class="footer_text1">网络备案：<?php echo C('CH_SET_FOR_THE_RECORD');?></span>
          <span class="footer_text2">网络文化经营许可证编号：<?php echo C('CH_SET_LICENSE');?></span>
          <span class="footer_text3"><?php echo C('CH_SET_COPYRIGHT');?></span></p>
        </div>
    </div>
    
		<script type="text/javascript">
			(function(){
				var ThinkPHP = window.Think = {
					"ROOT"   : "", //当前网站地址
					"APP"    : "/index.php?s=", //当前项目地址
					"PUBLIC" : "/Public", //项目公共目录地址
					"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
					"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
					"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
				}
			})();
		</script>
    <script src="/Public/Home/js/20170913/jquery.form.js"></script>
    <script src="/Public/Home/js/20170913/jquery.validate.min.js"></script>
    <script src="/Public/Home/js/20170913/jquery.md5.js"></script>
    <script src="/Public/Home/js/20170913/common.js"></script>
    
<?php if(I('type') == 'zx' || $category['name'] == 'tui_zx'): ?><script>highlight_subnav('<?php echo U("Home/Article/more_lists/type/zx");?>');</script>
<?php else: ?>
<script>highlight_subnav('<?php echo U("Article/more_lists");?>');</script><?php endif; ?>

	</body>
</html>