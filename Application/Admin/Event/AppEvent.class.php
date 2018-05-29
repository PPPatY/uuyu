<?php
// +----------------------------------------------------------------------
// | 徐州梦创信息科技有限公司—专业的游戏运营，推广解决方案.
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.vlcms.com  All rights reserved.
// +----------------------------------------------------------------------
// | Author: kefu@vlcms.com QQ：97471547
// +----------------------------------------------------------------------
namespace Admin\Event;
use Think\Controller;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AppEvent extends Controller {

    public function add_source(){
        $model = D('App');
        $plist=A("Plist");
        $data = $model->create();
        $data['file_size'] = round($data['file_size']/pow(1024,2),2)."MB";
        $data['file_url']  = $data['file_url']."/".$data['file_name'];
        $data['op_id'] = UID;
        $data['op_account'] = session("user_auth.username");
        $data['create_time'] = NOW_TIME;
        $res = $model->add($data);
        if($res){
            $this->update_game_size($data);
            $this->success('添加成功',U('App/lists'));
        }
        else{
            $this->error('添加失败');
        }
    }

    /**
    *修改游戏原包
    */
    public function update_source($id = null,$file_name){
        $id || $this->error('id不能为空');
        $model = D('App');
        $plist=A("Plist");
        $data = $model->create();
        $url=$data['file_url'];
        $data['file_size'] = round($data['file_size']/pow(1024,2),2)."MB";
        $data['file_url']  = $data['file_url']."/".$data['file_name'];
        $data['id'] = $id;
        $data['op_id'] = UID;
        $data['op_account'] = session("user_auth.username");
        $data['create_time'] = NOW_TIME;
        $data['bao_name'] = $_POST['bao_name'];
        $data['remark'] = $_POST['remark'];
        $data['version'] = $_POST['version'];
        if($data['file_name']==$file_name){
            $this->error('修改失败',U('App/lists'));
        }else{
            if($data['app_version']==0){
                $tt=$plist->create_plist_app($data['app_version'],$id,$data['bao_name'],$data['file_url']);
                $data['plist_url']=$tt;
                $ss['plist_url'] = $plist_url;
            }
            $res = $model->save($data);
            if($res){
                @unlink($url."/".$file_name);
                // $this->update_game_size($data);
                $this->success('修改成功',U('App/lists'));
            }else{
                $this->error('修改失败',U('App/lists'));
            }
        }
    }

    protected function update_game_size($param=null){
        $model = D('Game');
        $data['game_size'] = $param['file_size'];
        $model->where($map)->save($data);
    }

   
   
}
