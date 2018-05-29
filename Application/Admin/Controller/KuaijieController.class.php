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

class KuaijieController extends AdminController {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row=10;
        /* 获取频道列表 */
        $map['status']  = 1;
        $list = M('Kuaijieicon')
              ->where($map)
              ->order('id asc')
              ->page($page,$row)
              ->select();
        $count = M('Kuaijieicon')->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list', $list);
        $this->meta_title = '快捷方式管理';
        $this->display();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            if(!$_POST['title']||!$_POST['url']){
                $this->error('快捷方式名称或url未填写');
            }
            $Kuaijieicon = M('Kuaijieicon');
            $data = $Kuaijieicon->create();
            if(is_array($_POST['config'])){
                $_POST['value']=reset($_POST['config']);
            }else{
                $_POST['value']=$_POST['config'];
            }
            if(!$_POST['value']){
                $this->error('图片未上传');
            }
            if($data){
                $id = $Kuaijieicon->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_Kuaijieicon', 'Kuaijieicon', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error('数据不能为空');
            }
        } else {
            $this->meta_title = '新增快捷方式';
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
            if(!$id){
                $this->error('参数错误');
            }
            if(!$_POST['title']||!$_POST['url']){
                $this->error('快捷方式名称或url未填写');
            }
            if(is_array($_POST['config'])){
                $_POST['value']=reset($_POST['config']);
            }else{
                $_POST['value']=$_POST['config'];
            }
            if(!$_POST['value']){
                $this->error('图片未上传');
            }
            $Kuaijieicon = M('Kuaijieicon');
            $data = $Kuaijieicon->create();
            if($data){
                $sta=$Kuaijieicon->where(array('id'=>$id))->save($data);
                if($sta!==false){
                    //记录行为
                    action_log('update_Kuaijieicon', 'Kuaijieicon', $data['id'], UID);
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
            $info = M('Kuaijieicon')->find($id);
            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑快捷方式';
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
        if(M('Kuaijieicon')->where($map)->delete()){
            //记录行为
            action_log('update_Kuaijieicon', 'Kuaijieicon', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 导航排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Kuaijieicon')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '导航排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Kuaijieicon')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}