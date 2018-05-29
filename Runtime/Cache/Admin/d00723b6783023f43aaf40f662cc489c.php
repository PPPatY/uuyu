<?php if (!defined('THINK_PATH')) exit();?><div class="fr">

<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if($vo["url"] == $currentUrl): ?>class="tabchose"<?php endif; ?>  href="<?php echo U($vo["url"]);?>"><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
</div>