<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class MendController extends ThinkController {
    
    public function lists($p=1){
        if(isset($_REQUEST['account'])){
            $map['account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }
        $map['otherModel']='渠道补链';
        parent::lists("user",$p,$map);
    }

    public function edit($id = null)
    {
        if (IS_POST) {
            if ($_POST['promote_id'] == $_POST['prmoote_id_to']) {
                $this->error('没有更换操作');
            }
            $create = $_REQUEST;
            $map['id'] = $create['user_id'];
            $map_['user_id'] = $create['user_id'];
            $data['promote_id'] = $create['prmoote_id_to'];
            $data['promote_account'] = get_promote_name($create['prmoote_id_to']);
            $user_data = $data;
            $promote = M('promote', 'tab_')->where(array("id"=>I('prmoote_id_to')))->find();
            if ($promote['parent_id'] != "0") {
                $promote = M('promote', 'tab_')->where(array("id"=>$promote['parent_id']))->find();
            }
            $user_data['is_union']=$promote['is_union'];
            $user_data['parent_id'] = $promote['id'];

            if(empty($promote)){
                $user_data['parent_id'] = 0;
            }
            $user = M('user', 'tab_')->where($map)->save($user_data);
            $user_ = M('UserPlay', 'tab_')->where($map_)->save($data);
            $spend = M('Spend', 'tab_')->where($map_)->where(array('is_check'=>array('in','1,2')))->save($data);
            $depost = M('Deposit', 'tab_')->where($map_)->save($data);
            $Bind_spend = M('Bind_spend', 'tab_')->where($map_)->save($data);
            $create['user_account'] = get_user_account($create['user_id']);
            $create['promote_account'] = get_promote_name($create['promote_id']);
            $create['promote_id_to'] = $create['prmoote_id_to'];
            $create['promote_account_to'] = get_promote_name($create['prmoote_id_to']);
            $create['create_time'] = time();
            $create['op_id'] = UID;
            $create['op_account'] = session('user_auth.username');
            $create['remark'] = $_REQUEST['remark'];
            $mend = M('mend', 'tab_')->add($create);
            if ($mend) {
                $this->success('补链成功', U('lists'), 2);
            }
        } else {
            $user = A('User', 'Event');
            $user_data = $user->user_entity($id); 
            $user_data || $this->error("用户数据异常");
            $mend = M('Mend','tab_');
            $mend_data = $mend->where(array('user_id' => $id))->find();
            $user_data['remark']=$mend_data['remark'];
            $this->assign("data", $user_data);
            $this->meta_title = '编辑推广补链';
            $this->display();
        }
    }
    public function record($p=0)
    {
        if(isset($_REQUEST['account'])){
            if ($_REQUEST['account']=='全部') {
                unset($_REQUEST['account']);
            }
            $map['user_account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }
        parent::lists("Mend",$p,$map);
    }

}