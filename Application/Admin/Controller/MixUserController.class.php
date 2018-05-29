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
class MixUserController extends ThinkController {

    const model_name = 'MixUser';

    public function lists(){
        if(isset($_REQUEST['partner_account'])){
            if($_REQUEST['partner_account']=='全部'){
                unset($_REQUEST['partner_account']);
            }else{
                $map['partner_account'] = array('like','%'.$_REQUEST['partner_account'].'%');
                unset($_REQUEST['partner_account']);
            }
        }
        if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
            $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);
        }

        $extend=array();
        $extend['map']=$map;
        parent::lists("MixUser",$p,$extend['map']);
    }

}