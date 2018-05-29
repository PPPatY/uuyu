<?php

// +----------------------------------------------------------------------

// | OneThink [ WE CAN DO IT JUST THINK IT ]

// +----------------------------------------------------------------------

// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.

// +----------------------------------------------------------------------

// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>

// +----------------------------------------------------------------------



namespace Admin\Controller;

use User\Api\UserApi;

use User\Api\MixpartnerApi;



/**

 * 后台用户控制器

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */

class MixPartnerController extends ThinkController {



    const model_name = 'MixPartner';



    public function lists(){

        $map = array();

        $extend=array();

        $extend['map']=$map;

        parent::lists("MixPartner",$p,$extend['map']);

    }



    public function add(){

        if(IS_POST){

            $data=array('account'=>$_REQUEST['account'],'password'=>$_REQUEST['password'],'status'=>$_REQUEST['status'],'contact'=>$_REQUEST['contact'],'real_name'=>$_REQUEST['real_name'],'id_card'=>$_REQUEST['id_card'],'bank'=>$_REQUEST['bank'],'bank_card'=>$_REQUEST['bank_card'],'domain'=>$_REQUEST['domain'],'transfe'=>$_REQUEST['transfe'],'note'=>$_REQUEST['note'],'pay_key'=>$_REQUEST['pay_key'],'login_key'=>$_REQUEST['login_key']);

            $user = new MixpartnerApi();

            $res = $user->mixuser_add($data);

            if($res>0){

                $this->success("添加成功",U('lists'));

            }

            else{

                $this->error($res,U('lists'));

            }

        }

        else{

            $this->display();

        }

    }



    public function edit($id=0){
        $id || $this->error('请选择要编辑的用户！');

        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        if(IS_POST){
            if($_POST['password']){
                $use=new UserApi();
            }else{
                unset($_POST['password']);
            }
            
        }
        parent::edit($model['id'],$id);

    }



    public function del($model = null, $ids=null){

        $map['id']=array('in',$ids);

        $MixPartner=M('MixPartner','tab_')->where($map)->delete();

        if($MixPartner){

        $this->success('删除成功');

        }else{

        $this->error('删除失2败');

        }

    }

    public function set_status(){

        $map['id']=array('in',$_REQUEST['ids']);

        $MixPartner=M('MixPartner','tab_')->where($map)->setField('status',$_REQUEST['status']);

        if($MixPartner){

        $this->success('设置成功');

        }else{

        $this->error('设置失败');

        }

    }

}