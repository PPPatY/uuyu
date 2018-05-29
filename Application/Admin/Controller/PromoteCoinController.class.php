<?php

namespace Admin\Controller;
use Think\Model;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PromoteCoinController extends ThinkController {

	const model_name = 'PromoteCoin';

    public function send_lists($p=0){
        $extend['type'] = 1;
        $extend['source_id'] = 0;
        
        if(I('get.promote_type') !=''){
            $extend['promote_type'] = I('promote_type');
            unset($_REQUEST['promote_type']);
        }
        if(I('get.promote_id') !=''){
            $extend['promote_id'] = I('promote_id');
            unset($_REQUEST['promote_id']);
        }
				$map = $extend;
        $map['create_time'] = total(1,false);
        $sum['to_day'] = D(self::model_name)->where($map)->sum('num');
        $map['create_time'] = total(5,false);
        $sum['yst_day'] = D(self::model_name)->where($map)->sum('num');
        $sum['all_num'] = D(self::model_name)->where($extend)->sum('num');
        $this->assign('sum',$sum);
        parent::order_lists(self::model_name,$_GET["p"],$extend);
    }

    public function deduct_lists($detype='',$p=0){
        if($detype==2){
            $extend['type'] = 2;
            $extend['source_id'] = 0;
            if(I('get.promote_type') !=''){
                $extend['promote_type'] = I('promote_type');
                unset($_REQUEST['promote_type']);
            }
            if(I('get.promote_id') !=''){
                $extend['promote_id'] = I('promote_id');
                unset($_REQUEST['promote_id']);
            }
            $map = $extend;
            $map['create_time'] = total(1,false);
            $sum['to_day'] = D(self::model_name)->where($map)->sum('num');
            $map['create_time'] = total(5,false);
            $sum['yst_day'] = D(self::model_name)->where($map)->sum('num');
            $sum['all_num'] = D(self::model_name)->where($extend)->sum('num');
            $this->assign('sum',$sum);
            parent::order_lists(self::model_name,$_GET["p"],$extend);
        }else{
            $extend['type'] = 2;
            $extend['source_id'] = 0;
            $extend['coin_type'] = 0;//平台币
            if(I('get.user_account') !=''){
                $extend['user_account'] = array('like','%'.I('user_account').'%');
                unset($_REQUEST['user_account']);
            }
            $map = $extend;
            $map['create_time'] = total(1,false);
            $sum['to_day'] = M('UserCoin','tab_')->where($map)->sum('num');
            $map['create_time'] = total(5,false);
            $sum['yst_day'] = M('UserCoin','tab_')->where($map)->sum('num');
            $sum['all_num'] = M('UserCoin','tab_')->where($extend)->sum('num');
            $this->assign('sum',$sum);
            parent::order_lists('UserCoin',$_GET["p"],$extend);
        }
    }

    public function bdptb_deduct_lists($detype='',$p=0){
        $extend['type'] = 2;
        $extend['source_id'] = 0;
        $extend['coin_type'] = -1;//绑币
        $map = $extend;
        $map['create_time'] = total(1,false);
        $sum['to_day'] = M('UserCoin','tab_')->where($map)->sum('num');
        $map['create_time'] = total(5,false);
        $sum['yst_day'] = M('UserCoin','tab_')->where($map)->sum('num');
        $sum['all_num'] = M('UserCoin','tab_')->where($extend)->sum('num');
        $this->assign('sum',$sum);
        parent::order_lists('UserCoin',$_GET["p"],$extend);
    }

    /**
     * 发放平台币
     */
    public function send(){
        $this->add(1,'send_lists');
    }

    public function deduct(){
        $this->add(2,'PromoteCoin/deduct_lists/detype/'.$_REQUEST['detype']);
    }
    public function bdptb_deduct(){
        $this->add(2,'bdptb_deduct_lists',1);
    }

    public function add($type,$url,$isbd=0)
    {
        $model = M('Model')->getByName(self::model_name);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            if($_REQUEST['detype']==2){//推广员
                if(!$_POST['promote_id']){
                    $this->error('请选择推广员');
                }
                if(!$_POST['num']){
                    $this->error('请输入收回数量');
                }
                if(!preg_match("/^[1-9]\d*$/", $_POST['num'])){
                    if($type!=2){
                        $this->error('发放数量不正确');
                    }else{
                        $this->error('收回数量不正确');
                    }
                }
                //验证二级密码
                if(!$_POST['second_pwd']){
                    $this->error('请输入二级密码');
                }
                $pwd = I('second_pwd');
                $res = D('Member')->check_sc_pwd($pwd);
                if(!$res){
                    $this->error('二级密码错误');
                }
                $res = D('PromoteCoin')->create();
                
                if (!$res){
                    $this->error(D('PromoteCoin')->getError());
                }
                //平台币修改
                $promote = D('promote');
                $res = $promote->edit_promote_balance_coin(I('promote_id'),I('num'),$type);
                if($res){
                    $this->success('操作成功！', U($url.'?model='.$model['name']));
                } else {
                    $this->error($promote->getError());
                }
            }else{//玩家
                if(!$_POST['account']){
                    $this->error('请输入玩家账号');
                }
                if(!$_POST['num']){
                    $this->error('请输入收回数量');
                }
                if(!preg_match("/^[1-9]\d*$/", $_POST['num'])){
                    $this->error('收回数量不正确');
                }
                //验证二级密码
                if(!$_POST['second_pwd']){
                    $this->error('请输入二级密码');
                }
                $pwd = I('second_pwd');
                $res = D('Member')->check_sc_pwd($pwd);
                if(!$res){
                    $this->error('二级密码错误');
                }
                $res = D('UserCoin')->create();
                if (!$res){
                    $this->error(D('UserCoin')->getError());
                }
                if($isbd){
                    //绑定平台币修改
                    $User = D('UserPlay');
                    $res = $User->edit_user_balance_coin(I('account'),I('game_id'),I('num'),$type);
                }else{
                    //平台币修改
                    $User = D('User');
                    $res = $User->edit_user_balance_coin(I('account'),I('num'),$type);
                }
                if($res){
                    $this->success('操作成功！', U($url.'?model='.$model['name']));
                } else {
                    $this->error($User->getError());
                }
            }
        } else {
        	$_REQUEST['promote_type'] == 1? $action_name = "给一级推广员发放" : $action_name = "给二级推广员发放";

            $fields = get_model_attribute($model['id']);
            
            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->action_name = $action_name;
            $this->meta_title = '平台币操作';
            $this->display($model['template_add']?$model['template_add']:'');
        }
    }

    /**
     * 平台币转移记录
     */
    public function record($p=0){
        $extend['promote_type'] = 2;
        $extend['source_id'] = ['neq',0];
        if(isset($_REQUEST['promote_id'])){//二级
            $extend['promote_id'] = I('promote_id');
            unset($_REQUEST['promote_id']);
        }
        if(isset($_REQUEST['source_id'])){//一级
            $extend['source_id'] = I('source_id');
            unset($_REQUEST['source_id']);
        }
        $map = $extend;
        $map['create_time'] = total(1,false);
        $sum['to_day'] = D(self::model_name)->where($map)->sum('num');
        $map['create_time'] = total(5,false);
        $sum['yst_day'] = D(self::model_name)->where($map)->sum('num');
        $sum['all_num'] = D(self::model_name)->where($extend)->sum('num');
        $this->assign('sum',$sum);
        parent::order_lists(self::model_name,$_GET["p"],$extend);
    }
}
