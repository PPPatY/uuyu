<?php if (!defined('THINK_PATH')) exit();?><div class="fr">
	<?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if($vo["id"] == $cateID): ?>class="tabchose"<?php endif; ?>  href="<?php echo U('Article/index',array('cate_id'=>$vo['id']));?>"><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
</div>