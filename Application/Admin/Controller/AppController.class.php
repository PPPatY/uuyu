<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AppController extends ThinkController {
	const model_name = 'App';

    public function lists(){
    	parent::lists(self::model_name,$_GET["p"]);
    }

    public function add($value='')
    {
    	if(IS_POST){
            if(empty($_POST['file_name'])){
                $this->error('未上传游戏原包');
            }
            $d = D('App')->find();
            $source = A('App','Event');
            if(empty($d)){
                $source->add_source();
            }
            else{
            $this->error('游戏已存在原包',U('App/lists'));
            }
    	}
    	else{
            $this->meta_title = '新增游戏原包';
    		$this->display();
    	}
    	
    }
    
    public function del($model = null, $ids=null){
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
        $souce=M("App","tab_");
        $map['id']=array("in",$ids);
        $list=$souce->where($map)->select();
        foreach ($list as $key => $value) {
            @unlink($value['file_url']);
        }
        if($souce->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function edit($id){
         $map['id']=$id;
         $map['app_version']=$_REQUEST['app_version'];
        if(IS_POST){
            if(empty($_POST['file_name'])){
                $this->error('未上传游戏原包');
            }
            $app_version=$_REQUEST['app_version'];
            $d = D('App')->where($map)->find();
            $source = A('App','Event');
            if(empty($d)){
                $source->add_source();
            }
            else{
                $source->update_source($d['id'],$d['file_name']);
            }
        }
        else{
            $d = M('App',"tab_")->where($map)->find();
            $this->assign("data",$d);
            $this->display();
        }
        
    }
}
