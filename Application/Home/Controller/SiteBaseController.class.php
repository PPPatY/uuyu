<?php

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class SiteBaseController extends BaseController {

    //站点申请
    public function index(){
        $model = D('SiteBase');
        $data = $model->get_promote_data();
        $this->assign('data',$data);
        $this->meta_title = "基本信息";
        $this->display();
    }

    /**
     * 添加/编辑
     */
    public function save(){
        $model = D('SiteBase');
        $data = $model->create();
        if(empty($data)){
            $this->error($model->getError());
        }else{
            if(!empty($data['id'])){
                $res = $model->save();
            }else{
                $res = $model->add();
            }
            if($res !== false){
                $this->success('保存成功！');
            }else{
                $this->error('保存失败：'.$model->getError());
            }
        }
    }
}