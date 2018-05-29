<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class GamehejiController extends ThinkController {
    const model_name = 'Gameheji';

    public function lists(){
        parent::lists(self::model_name,$_GET["p"],$extend);
    }

    public function add($model='')
    {
        if(IS_POST){
            if(empty($_POST['game_heji'])){
                $this->error('未添加游戏');
            }
            $_POST['game_heji'] = implode(',',$_POST['game_heji']);
        }
        parent::add($model);
    }
    
    public function edit($model='',$id=0)
    {
        if(IS_POST){
            if(empty($_POST['game_heji'])){
                $this->error('未添加游戏');
            }
            $_POST['game_heji'] = implode(',',$_POST['game_heji']);
        }
        parent::edit($model,$id);
    }

    public function del($model = null, $ids=null)
    {
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }

    public function set_status()
    {
        parent::set_status(self::model_name);
    }
}
