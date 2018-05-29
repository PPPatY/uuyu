<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class KefuController extends AdminController {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = intval(C('LIST_ROWS')) ? :10;
        /* 获取频道列表 */
        $map['status']  = 1;
        $map['istitle']  = 2;
        $list = M('Kefuquestion','tab_')
              ->where($map)
              ->order('id desc')
              ->page($page,$row)
              ->select();
        $count = M('Kefuquestion','tab_')->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list', $list);
        $this->meta_title = '客服问题';
        $this->display();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            if($_POST['istitle']==1){
                $_POST['istitle']=1;
                unset($_POST['zititle'],$_POST['zititleurl'],$_POST['contend']);
            }else{
                $_POST['istitle']=2;
            }
            foreach ($_POST as $key => $value) {
               if($value==''){
                    $this->error('请认真填写信息');exit;
               }
            }
            $Kefuquestion = M('Kefuquestion','tab_');
            $_POST['title']=$_POST['ztitle'];
            unset($_POST['id']);
            $data = $Kefuquestion->create();
            if($data){

                $id = $Kefuquestion->add($data);

                if($id){
                    $result=$Kefuquestion->where(array('id'=>$id))->setField('zititleurl',$id);
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_Kefuquestion', 'Kefuquestion', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error('数据不能为空');
            }
        } else {
            $this->meta_title = '新增客服问题';
            $this->display();
        }
    }

    /**
     * 编辑频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        $id=$_REQUEST['id'];
        if(IS_POST){
            if($_POST['istitle']==1){
                $_POST['istitle']=1;
                unset($_POST['zititle'],$_POST['zititleurl'],$_POST['contend']);
            }else{
                $_POST['istitle']=2;
            }
            foreach ($_POST as $key => $value) {
               if($value==''){
                    $this->error('请认真填写信息');exit;
               }
            }
            $Kefuquestion = M('Kefuquestion','tab_');
            $_POST['ztitle']=$_POST['title'];
            unset($_POST['tid']);
            $data = $Kefuquestion->create();
            $data['zititleurl']=$id;
            if($data){
                $sta=$Kefuquestion->where(array('id'=>$id))->save($data);
                if($sta!==false){
                    //记录行为
                    action_log('update_Kefuquestion', 'Kefuquestion', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error('数据不能为空');
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Kefuquestion','tab_')->find($id);
            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $idinfo = M('Kefuquestion','tab_')->where(array('title'=>$info['title'],'istitle'=>1))->find();
            $this->assign('info', $info);
            $this->assign('idinfo', $idinfo);
            $this->meta_title = '编辑客服问题';
            $this->display();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Kefuquestion','tab_')->where($map)->delete()){
            //记录行为
            action_log('update_Kefuquestion', 'Kefuquestion', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}