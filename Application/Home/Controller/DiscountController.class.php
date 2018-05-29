<?php

namespace Home\Controller;
use OT\DataDictionary;
use Admin\Model\ApplyModel;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class DiscountController extends BaseController {

    const MODEL_NAME = 'PromoteWelfare';

    public function index($p=0){
        $map['promote_id'] = PID;
        $map['recharge_status'] = 1;
        empty(I('game_id')) || $map['game_id'] = I('game_id');
        $extend['map'] = $map;
        $this->meta_title = "我的福利";
        parent::data_lists($p,self::MODEL_NAME,$extend);
    }
    
    public function child_promote($p=0) {
        $child_promote = get_zi_promote_id(PID);
        if(empty($child_promote)){
            $this->display();
        }elseif(empty(I('promote_id')) || in_array(I('promote_id'),explode(',',$child_promote))){
            $map['promote_id'] = empty(I('promote_id')) ? ['in',$child_promote] : I('promote_id');
            empty(I('game_id')) || $map['game_id'] = I('game_id');
            $map['recharge_status'] = 1;
            $extend['map'] = $map;
						$this->meta_title = "子渠道福利";
            parent::data_lists($p,self::MODEL_NAME,$extend);
        }
    }

}