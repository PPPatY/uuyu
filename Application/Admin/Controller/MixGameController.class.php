<?php



namespace Admin\Controller;

use User\Api\UserApi as UserApi;

/**

 * 后台首页控制器

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */

class MixGameController extends ThinkController {

    const model_name = 'game';



    /**

    *游戏信息列表

    */

    public function lists(){

        if(isset($_REQUEST['game_name'])){

            if($_REQUEST['game_name']=='全部'){

                unset($_REQUEST['game_name']);

            }else{

                $extend['game_name'] = $_REQUEST['game_name'];

                unset($_REQUEST['game_name']);

            }

        }

        if(isset($_REQUEST['partner_account'])){

            $extend['partner_account'] = array('like','%'.$_REQUEST['partner_account'].'%');

            unset($_REQUEST['partner_account']);

        }

        parent::lists("mix_apply",$_GET["p"],$extend);

    }





    public function edit($id=0){

        $id || $this->error('请选择要编辑的用户！');

        $model = M('Model')->getByName("mix_apply"); /*通过Model名称获取Model完整信息*/

        parent::edit($model['id'],$id);

    }





    /**

    *游戏信息列表

    */

    public function setlists(){

        if(isset($_REQUEST['game_name'])){

            if($_REQUEST['game_name']=='全部'){

                unset($_REQUEST['game_name']);

            }else{

                $extend['game_name'] = $_REQUEST['game_name'];

                unset($_REQUEST['game_name']);

            }

        }

        if(isset($_REQUEST['game_appid'])){

            $extend['game_appid'] = array('like','%'.$_REQUEST['game_appid'].'%');

            unset($_REQUEST['game_appid']);

        }

        $this->model_name['title']="混服设置";

        parent::lists(self::model_name,$_GET["p"],$extend);

    }





    public function set_status(){

        $_REQUEST['ids'] || $this->error('请选择要操作的数据');
        $map['id']=array('in',$_REQUEST['ids']);

        if(isset($_REQUEST['field'])){

        $mixuser=M('game','tab_')->where($map)->setField($_REQUEST['field'],$_REQUEST['status']);

        }else{

        $mixuser=M('MixApply','tab_')->where($map)->setField("status",$_REQUEST['status']);

        }

        if($mixuser){

        $this->success('设置成功');

        }else{

        $this->error('设置失败');

        }

    }

}

