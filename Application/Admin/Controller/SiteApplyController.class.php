<?php

namespace Admin\Controller;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class SiteApplyController extends ThinkController {
	const model_name = 'SiteApply';

    public function lists(){
    	parent::lists(self::model_name,$_GET["p"]);
    }

    public function del($model = null, $ids=null){
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $model = D(self::model_name);
        $map['id']=array("in",$ids);
        if($model->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function check($ids=null){
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $model = D(self::model_name);
        $map['id']=array("in",$ids);
        if($model->where($map)->setField(['status'=>1])){
            $this->success('审核成功');
        } else {
            $this->error('审核失败！');
        }
    }
}
