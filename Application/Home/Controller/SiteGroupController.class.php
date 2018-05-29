<?php

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class SiteGroupController extends BaseController {

    //站点申请
    public function index($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = 10;

        $model = D('SiteGroup');
        $map['promote_id'] = PID;
        $data = $model->where($map)->order('create_time desc')->page($page,$row)->select();
        /* 查询记录总数 */
        $count = $model->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('data',$data);
        $this->meta_title = "QQ群";
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $model = D('SiteGroup');
            $res = $model->saveData();
            if($res !== false){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败:'.$model->getError());
            }
        }else {
            $map['promote_id'] = PID;
            $map['pack_url'] = ['neq', ''];
            $game = D('SiteGame')->get_promote_data();
            $this->assign('game', $game);
            $this->meta_title = "添加QQ群";
            $this->display();
        }
    }

    public function edit($id=0){
        $model = D('SiteGroup');
        if(IS_POST){
            $res = $model->saveData();
            if($res !== false){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败:'.$model->getError());
            }
        }else {
            $map['promote_id'] = PID;
            $map['pack_url'] = ['neq', ''];
            $game = D('SiteGame')->get_promote_data();
            $data = $model->find($id);
            $this->assign('data',$data);
            $this->assign('game', $game);
            $this->meta_title = "编辑QQ群";
            $this->display();
        }
    }

    public function del($id){
        $model = D('SiteGroup');
        $map['id'] = $id;
        $map['promote_id'] = PID;
        $res = $model->where($map)->delete();
        $this->redirect('SiteGroup/index');
    }

}