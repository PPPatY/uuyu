<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/19
 * Time: 13:38
 */

namespace Admin\Controller;

class AjaxController extends ThinkController{

    /**
     * 获取配置项
     * @param $category
     */
    public function getConfigCategory($category){
        switch ($category){
            case 1:$result = C('PC_CONFIG_CATEGORY_LIST');break;
            // case 2:$result = C('CHANNEL_CONFIG_GROUP_LIST');break;
            case 2:$result = C('APP_CONFIG_GROUP_LIST');break;
            case 4:$result = C('APP_CONFIG_GROUP_LIST');break;
            // case 5:$result = C('WAP_CONFIG_GROUP_LIST');break;
            // case 6:$result = C('APP_CONFIG_GROUP_LIST');break;
            default:$result = C('CONFIG_GROUP_LIST');
        }

        $this->AjaxReturn($result);
    }

    /**
     * 获取区服列表
     * @param $game_id
     */
    public function getServer($game_id=""){
        $data = M('server','tab_')->where(['game_id'=>$game_id])->select();
        $this->AjaxReturn($data);
    }
		
		public function getPromote($sid=0){				
        $data = M("Promote","tab_")->where(['status'=>1,'parent_id'=>$sid])->select();
        $this->AjaxReturn($data);
    }

    /**
     * 获取游戏折扣
     * @param $game_id
     */
    public function getGameDiscount($game_id){
        $data = M('Game','tab_')->find($game_id);
        $res['discount'] = $data['discount'];
        $this->AjaxReturn($res);
    }
}