<?php

namespace Admin\Controller;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PromoteWelfareController extends ThinkController {

	const model_name = 'PromoteWelfare';
    public function lists(){
        $extend = '';
        if(isset($_REQUEST['promote_account']) && $_REQUEST['promote_account']!=''){
            $extend['promote_id']  =  $_REQUEST['promote_account'];
            unset($_REQUEST['promote_account']);
        }
        
        empty(I('game_id')) || $extend['game_id'] = I('game_id');
    	parent::order_lists(self::model_name,$_GET["p"],$extend);
    }

    public function add(){
    	$model = M('Model')->getByName(self::model_name);
    	parent::add($model["id"]);
    }

    public function edit($id=0){
		$id || $this->error('请选择要编辑的用户！');
		$model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
		parent::edit($model['id'],$id);
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }

}
