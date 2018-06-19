<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class GameTypeController extends ThinkController {
    const model_name = 'GameType';

    public function lists(){
        $list_data=M('game_type','tab_')->field('id,type_name,status_show,op_nickname,create_time,order_id')->order('order_id desc')->select();
        //$extend['order']="order_id desc ";
        //parent::lists(self::model_name,$_GET["p"],$extend);
        //var_dump($list_data);
        $this->assign('list_data',$list_data);
        $this->display();

    }

    public function add($model='')
    {
        parent::add($model);
    }
    
    public function edit($model='',$id=0)
    {
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

    public function chgculumn(){
        $res = D('GameType')->chgculumn();
        if($res==1){
            $data['status'] = 1;
            $data['msg'] = '修改成功';
        }elseif($res==0){
            $data['status'] = 2;
            $data['msg'] = '分类不存在';
        }elseif($res==-1){
            $data['status'] = 0;
            $data['msg'] = '缺少字段';
        }else{
            $data['status'] = 0;
            $data['msg'] = '修改失败';
        }
        $this->ajaxReturn($data);
    }
}
