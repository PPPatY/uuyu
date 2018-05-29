<?php
namespace Media\Controller;
use Think\Controller;
use Common\Api\GameApi;
use Common\Model\GameModel;
use Common\Model\UserPlayModel;
use Common\Model\UserModel;
use Common\Model\DocumentModel;
use Common\Model\AdvModel;
use Common\Model\ServerModel;

/**
* 首页
*/
class ArticleController extends BaseController {

    public function detail($id=''){
    	empty($id)&&$this->ajaxReturn(array('code'=>0,'msg'=>'缺少文章id'));
        $model = new DocumentModel();
        $data = $model->articleDetail($id);
        if($data===false){
        	$this->ajaxReturn(array('code'=>0,'msg'=>'文章不存在'));
        }
        $this->assign('data',$data);
        $this->display();
    }
}  
