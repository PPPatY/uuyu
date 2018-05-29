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



/**

 * 后台用户控制器

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */

class MixPayController extends ThinkController {



    const model_name = 'MixPay';



    public function lists(){

       if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){

            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));

            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);

        }

       if(isset($_REQUEST['partner_account'])){

            if($_REQUEST['partner_account']=='全部'){

                unset($_REQUEST['partner_account']);

            }else{

                $map['partner_account'] = array('like','%'.$_REQUEST['partner_account'].'%');

                unset($_REQUEST['partner_account']);

            }

        }

        $extend=array();

        $extend['map']=$map;
        parent::lists("MixPay",$p,$extend['map']);

    }



    public function add(){

        $model = M('Model')->getByName(self::model_name);

        parent::add($model["id"]);

    }



    public function edit($id=0){

        $id || $this->error('请选择要编辑的用户！');

        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/

        parent::edit($model['id'],$id);

    }



    public function del($model = null, $ids=null){

        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/

        parent::del($model["id"],$ids);

    }

}