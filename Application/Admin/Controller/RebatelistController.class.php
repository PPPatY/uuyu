<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class RebatelistController extends ThinkController {
    const model_name = 'RebateList';

    /**
    *返利设置列表
    */
    public function lists(){
        if(isset($_REQUEST['user_account'])){
            $map['account']=array('like','%'.trim($_REQUEST['user_account']).'%');
            $res=M('user','tab_')->where($map)->field('id')->select();
            if($res){
                foreach ($res as $key => $value) {
                    $asd[]=implode(",",$value);
                }
                $map['user_id']=array('in',implode(',',$asd));
            }else{
                $map['user_id']=-1;
            }
            
            unset($map['account']);
            unset($_REQUEST['user_account']);
        }
        empty(I('game_id')) || $map['game_id'] = I('game_id');
        $total=D(self::model_name)->field('sum(pay_amount) pay_amount,sum(ratio_amount) ratio_amount')->where($map)->find();
				$map1 = $map;
				$map1['_string']='create_time '.total(1);				
				$ttotal = D(self::model_name)->field('sum(pay_amount) pay_amount,sum(ratio_amount) ratio_amount')->where($map1)->find();
				$map1['_string']='create_time '.total(5);
				$ytotal = D(self::model_name)->field('sum(pay_amount) pay_amount,sum(ratio_amount) ratio_amount')->where($map1)->find();
				
				
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        parent::lists(self::model_name,$_GET["p"],$map);
    }
    
    public function del($model = null, $ids=null) {
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }
}
