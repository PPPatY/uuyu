<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class TipsController extends ThinkController {
    const model_name = 'Tips';

    public function real_name_auth(){
        if(IS_POST){
            $data = D(self::model_name)->create();
            if($data!==false){
                $data['end_time'] = strtotime($data['end_time']);
                $res = D(self::model_name)->save($data);
                if($res!==false){
                    $this->success('保存成功');
                }else{
                    $this->error('保存失败');
                }
            }else{
                $this->error(D(self::model_name)->getError());
            }
        }else{
            $map['obj'] = 1;
            $data = D(self::model_name)->field("*,if(obj=1,'未实名认证用户','') as objname,if(tip_type=1,'用户每次登录游戏','') as tipname")->where($map)->find();
            $this->assign('data',$data);
            $this->meta_title = '实名认证设置';
            $this->display();
        }
    }

    public function set_status()
    {
        parent::set_status(self::model_name);
    }
}
