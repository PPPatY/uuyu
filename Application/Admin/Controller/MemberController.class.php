<?php

namespace Admin\Controller;
use User\Api\MemberApi as MemberApi;
class MemberController extends ThinkController {
        /**
    *玩家列表信息
    */
    public function user_info($p=0){
        $hav = '';
        if($_REQUEST['group']){
            $this->assign('group',1);
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_way in '.'(3,4,5,6)';
            unset($_REQUEST['group']);
        }else{
            $this->assign('group',0);
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_way in '.'(0,1,2)';
            unset($_REQUEST['group']);
        }
        if(isset($_REQUEST['promote_id'])){
        	empty($hav) || $hav .= ' AND ';
        	if($_REQUEST['promote_id'] == "0"){
        		$hav .= "tab_user.promote_id like '%".I('promote_id')."%'";
        	}else{
        		$hav .= "tab_user.promote_id =".I('promote_id');
        	}
        	unset($_REQUEST['promote_id']);
        }

        if(isset($_REQUEST['user_id']) && is_numeric($_REQUEST['user_id'])){
            empty($hav) || $hav .= ' AND ';
						$userid = I('user_id','');
						if (strlen($uid)>3) {
							$userid = str_replace('1000','',$userid);							
						}
            $hav .= "tab_user.id =".$userid;
            unset($_REQUEST['user_id']);
        }
        if(isset($_REQUEST['account'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= "tab_user.account like '%".I('account')."%'";
            unset($_REQUEST['account']);
        }
        if(isset($_REQUEST['register_way'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_way ='.I('register_way');
            unset($_REQUEST['register_way']);
        }
        if(isset($_REQUEST['time_start']) && isset($_REQUEST['time_end'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_time BETWEEN '.strtotime(I('time_start')).' AND '.(strtotime(I('time_end'))+24*60*60-1);
            unset($_REQUEST['time_start']);unset($_REQUEST['time_end']);
        }elseif(isset($_REQUEST['time_start'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_time >= '.strtotime(I('time_start'));
            unset($_REQUEST['time_start']);
        }elseif(isset($_REQUEST['time_end'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_time <= '.(strtotime(I('time_end'))+24*60*60-1);
            unset($_REQUEST['time_end']);
        }
        if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'tab_user.register_time BETWEEN '.strtotime(I('start')).' AND '.strtotime(I('end'));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(!empty(I('line_day'))){
            $day = strtotime(date('Y-m-d')) - intval(I('line_day')) * 86400;
            empty($hav) || $hav .= ' AND ';
            $hav .= $day.'> tab_user.login_time';
        }
        if(!empty(I('recharge_total'))){
            empty($hav) || $hav .= ' AND ';
            $hav .= 'recharge_total > '.I('recharge_total');
        }
        if(isset($_REQUEST['status'])){
        	empty($hav) || $hav .= ' AND ';
        	$hav .= 'tab_user.lock_status = '.I('status');
        }
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = intval(C('LIST_ROWS')) ? :10;
        //排序
        $order = '';
        if (I('recharge_status') == 1) {
            $order = 'recharge_total,';
        } elseif (I('recharge_status') == 2) {
            $order = 'recharge_total desc,';
        }
        if (I('balance_status') == 1) {
            $order .= 'balance,';
        } elseif (I('balance_status') == 2) {
            $order .= 'balance desc,';
        }
        $order .= 'tab_user.id desc';
        $data = M('user','tab_')->field('tab_user.*,cumulative as recharge_total')
            ->having($hav)
            ->page($page,$row)
            ->order($order)
            ->select();
        //计数
        $count_sql = M('user','tab_')
            ->having($hav)
            ->order('tab_user.id desc')
            ->select(false);
        $count_sql = 'select count(*) as s from'.$count_sql.' a';
        $count = M()->query($count_sql);
        $count = $count['0']['s'];

        $model = M('Model')->getByName('user');
        //分页
        if($count > 10){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST%  %LINK_PAGE%  %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list_data', $data);
        $this->assign('model', $model);
        $this->meta_title = '玩家列表';
        $this->display();
    }
    //设置状态
    public function set_status($model='User'){
        if(isset($_REQUEST['model'])){
            $model=$_REQUEST['model'];
            unset($_REQUEST['model']);
        }
        parent::set_status($model);
    }
    public function edit($id=null){
        if(IS_POST){
            $member = new MemberApi();
            $data = $_REQUEST;
            if(empty($data['password'])){unset($data['password']);}
            $res = $member->updateInfo($data);
            if($res !== false){
                if(C('UC_SET')==1&&!empty($data['password'])){
                    $data_uc=$uc->get_uc(get_user_account($id));
                    if(is_array($data_uc)){
                        $uc_id=$uc->uc_edit($data_uc[1],"11",$data['password'],"",1);
                    }
                }
                $this->success('修改成功',U('user_info'));
            }
            else{
                $this->error('修改失败');
            }

        }
        else{
            $user = A('User','Event');
            $data=$user->user_entity($id);
            $data['shop_address'] = json_decode($data['shop_address'],true);
            $this->assign('data',$data);
            $this->meta_title = '玩家列表';
            $this->display();
        }

    }

    public function bind_balance($p=1){
        $map['user_id']=$_REQUEST['id'];
        $data = M("user_play","tab_")
            // 查询条件
            ->where($map)
            ->group('user_account,game_name')
            /* 执行查询 */
            ->select();
        $this->assign('list_data', $data);
        $this->display();
    }

    public function balance($p=1){
        $map['user_id']=$_REQUEST['id'];
        $data = M("user","tab_")
            // 查询条件
            ->where($map)
            /* 执行查询 */
            ->select();
        $this->assign('list_data', $data);
        $this->display();
    }

    public function user_balance($id,$isbd=0,$type=false){
        if($type){
            if(!$isbd){
                $map['account'] = $id;
            }else{
                $map['user_account'] = $id;
                $map['game_id'] = $_REQUEST['game_id'];
            }
        }else{
            if(!$isbd){
                $map['id'] = $id;
            }else{
                $map['id'] = $id;
                $map['game_id'] = $_REQUEST['game_id'];
            }
        }
        if($isbd){
            $data = M("user_play","tab_")
                ->field('user_id,user_account,bind_balance')
                ->join('tab_user on tab_user_play.user_id = tab_user.id')
                // 查询条件
                ->where($map)
                /* 执行查询 */
                ->find();
        }else{
            $data = M("user","tab_")
                ->field('id,account,balance')
                // 查询条件
                ->where($map)
                /* 执行查询 */
                ->find();
        }
        if(empty($data)){
            $res['status'] = 0;
            $res['msg']  = '用户不存在';
        }else{
            $res['status'] = 1;
            $res['msg']  = '查询成功';
            $res['data']  = $data;
        }
        $this->ajaxReturn($res,'json');
    }
    /**
     * 系统非常规MD5加密方法
     * @param  string $str 要加密的字符串
     * @return string
     */
    function think_ucenter_md5($str, $key = 'ThinkUCenter'){
        return '' === $str ? '' : md5(sha1($str) . $key);
    }

    public function checkpwd(){
        $res = D('Member')->check_sc_pwd(I('second_pwd'));
        if($res){
            $this->ajaxReturn(array("status"=>1,"msg"=>"成功"));
        }
        else{
            $this->ajaxReturn(array("status"=>-1,"msg"=>"二级密码错误"));
        }
    }

    public function balance_edit(){
        $res = D('Member')->check_sc_pwd($_REQUEST['second_pwd_']);
        if($res){
             $map['id']=$_REQUEST['id'];
            $data=array(
            'balance' => $_REQUEST['balance']
            );
            $user=M('user','tab_')->where($map)->find();
            $pro=M("user","tab_")->where($map)->setfield('balance',$_REQUEST['balance']);
            if($pro!==false){
                $data=array(
                    'user_id' => $_REQUEST['id'],
                    'user_account' => get_user_account($_REQUEST['id']),
                    'game_id' =>'',
                    'game_name' =>'',
                    'prev_amount' =>$user['balance'],
                    'amount' =>$_REQUEST['balance'],
                    'type' => 0,
                    'op_id' =>UID,
                    'op_account' =>get_admin_name(UID),
                    'create_time' => time()
            );
            M('balance_edit','tab_')->add($data);
            $this->ajaxReturn(array("status"=>1,"msg"=>"成功"));
        }
        else{
            $this->ajaxReturn(array("status"=>-1,"msg"=>"失败"));
        }
        }
        else{
            $this->ajaxReturn(array("status"=>-1,"msg"=>"二级密码错误"));
        }
       
    }

    public function bind_balance_edit($p=1){
        $map['id']=$_REQUEST['id'];
        $map['user_id']=$_REQUEST['user_id'];
        $map['game_id']=$_REQUEST['game_id'];
        $res = D('Member')->check_sc_pwd($_REQUEST['second_pwd']);
        if($res){
            $user_play = M('user_play','tab_')->where($map)->find();
            $pro = M("user_play","tab_")
                ->where($map)
                ->setField('bind_balance',$_REQUEST['bind_balance']);
            if($pro!==false){
                $data=array(
                'user_id' => $_REQUEST['user_id'],
                'user_account' => get_user_account($_REQUEST['user_id']),
                'game_id' => $_REQUEST['game_id'],
                'game_name' => get_game_name($_REQUEST['game_id']),
                'prev_amount' => $user_play['bind_balance'],
                'amount' => $_REQUEST['bind_balance'],
                'type' => 1,
                'op_id' => UID,
                'op_account' => get_admin_name(UID),
                'create_time' => time()
                );
                M('balance_edit','tab_')->add($data);
                $this->ajaxReturn(array("status"=>1,"msg"=>"成功"));
            }
            else{
                $this->ajaxReturn(array("status"=>0,"msg"=>"失败"));
            }
        }else{
            $this->ajaxReturn(array("status"=>0,"msg"=>"二级密码错误"));
        }
    }

    public function chax($p=1)
    {
        $map['user_account']=get_user_account($_REQUEST['id']);
        $map['pay_status']=1;
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row    = 10;
        $data = M("spend","tab_")
            // 查询条件
            ->where($map)
            /* 默认通过id逆序排列 */
            ->order('pay_time desc')
            /* 数据分页 */
            ->page($page, $row)
            /* 执行查询 */
            ->select();

        /* 查询记录总数 */
        $count =M("spend","tab_")->where($map)->count();
         //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $total=null_to_0(D('spend')->where($map)->sum('pay_amount'));
        $ttotal=null_to_0(D('spend')->where('pay_time'.total(1))->where($map)->sum('pay_amount'));
        $ytotal=null_to_0(D('spend')->where('pay_time'.total(5))->where($map)->sum('pay_amount'));
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        $this->assign('list_data', $data);
        $this->display();
    }
    public function changephone(){
        if(preg_match('/^[1][358][0-9]{9}/',$_REQUEST['phone'])){
            $userdata = M('User','tab_')->field('id')->where(array('phone'=>$_REQUEST['phone']))->find();
            if($userdata){
                $this->ajaxReturn(array("status"=>0,"msg"=>"手机号已存在！"));
            }
            $map['id']=$_REQUEST['id'];
            $pro = M("User","tab_")
                ->where($map)
                ->setField('phone',$_REQUEST['phone']);
            if($pro!==false){
                $this->ajaxReturn(array("status"=>1,"msg"=>"手机修改成功"));
            }else{
                $this->ajaxReturn(array("status"=>0,"msg"=>"手机修改失败"));
            }
        }else{
            $this->ajaxReturn(array("status"=>0,"msg"=>"手机输入错误"));
        }
    }
     public function denglu($p=1){
        $map['user_id']=$_REQUEST['id'];
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row    = 10;
        $data = M("user_login_record","tab_")
            // 查询条件
            ->where($map)
            /* 默认通过id逆序排列 */
            ->order('login_time desc')
            /* 数据分页 */
            ->page($page, $row)
            /* 执行查询 */
            ->select();
        /* 查询记录总数 */
        $count =M("user_login_record","tab_")->where($map)->count();
         //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list_data', $data);
        $this->display();
    }
    /**
    *用户登陆记录
    */
    public function login_record($p=1){
        if(isset($_REQUEST['game_name'])){
            $extend['game_name'] = $_REQUEST['game_name'];
            unset($_REQUEST['game_name']);
        }
         if(isset($_REQUEST['login_ip'])){
            $map['login_ip']=$_REQUEST['login_ip'];
            unset($_REQUEST['login_ip']);
        }

				
				if(!empty($_REQUEST['time_start'])&&!empty($_REQUEST['time_end'])) {
					$map['login_time'] =array('BETWEEN',array(strtotime($_REQUEST['time_start']),strtotime($_REQUEST['time_end'])+24*60*60-1));
          unset($_REQUEST['time_start']);unset($_REQUEST['time_end']);
				} elseif (!empty($_REQUEST['time_start'])&&empty($_REQUEST['time_end'])) {
					$map['login_time']=array('egt',strtotime($_REQUEST['time_start']));
				} elseif (empty($_REQUEST['time_start'])&&!empty($_REQUEST['time_end'])) {
					$map['login_time']=array('elt',strtotime($_REQUEST['time_end'])+24*60*60-1);
				} else{
        	$map['login_time'] = array('neq',0);
        }
				
				
				
				
        if(isset($_REQUEST['account'])){
            $map['user_account']=array('like','%'.trim($_REQUEST['account']).'%');
            unset($_REQUEST['account']);
        }
        $extend=array();
        $extend['map']=$map;
        parent::lists("UserLoginRecord",$p,$extend['map']);
    }
    /**
    *用户登陆记录
    */
    public function user_play_role($p=1){
        $row =  intval(C('LIST_ROWS')) ? : 10;

        if(isset($_REQUEST['game_id'])){
            $map['r.game_id'] = $_REQUEST['game_id'];
            unset($_REQUEST['game_id']);
        }
        if(isset($_REQUEST['account'])){
            $map['r.user_account']=array('like','%'.trim($_REQUEST['account']).'%');
            unset($_REQUEST['account']);
        }
        $mode = D('UserPlayInfo');
        $data = $mode->role_info($map,'r.id desc',$p,$row);
        $this->assign('list_data',$data['data']);
        $count = $data['count'];
        parent::showPage($count,$row);
        $this->meta_title= '角色查询';
        $this->display();
    }
    public function del($model = null, $ids=null){
        $map=array();
        if(isset($_REQUEST['id'])){
            $map['id']=$_REQUEST['id'];
            $data=M('user_login_record','tab_')
            ->where($map)->delete();
            $this->success('删除成功！',U('login_record'),2);
        }else{
            $this->error('请选择要操作的数据！');
        }
    }
     public function delprovide($ids)
    {
      $list=M("user_login_record","tab_");
      $map['id']=array("in",$ids);
      $map['status']=0;
        $delete=$list->where($map)->delete();
        if($delete!==false){
            $this->success("批量删除成功！",U("login_record"));
        }else{
        $this->error("批量删除失败！",U("login_record"));
        }
    }

    /**
     * 锁定玩家
     * @param $id
     * @param $lock_status
     */
    public function lock_status($id,$lock_status){
        $map['id'] = $id;
        $res = M('user','tab_')->where($map)->setField(['lock_status'=>$lock_status]);
        if($res){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！');
        }

    }
    public function uc_index($p=1){
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_id']=get_game_id($_REQUEST['game_name']);
            unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
            $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['account'])){
            $map['user_account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }
        $row = 10;
        $data = M('ucenter_login','tab_')
                ->where($map)
                ->order('id desc')
                ->page($p, $row)
                ->select();
            $count =M('ucenter_login','tab_')->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->meta_title = 'uc用户登陆列表';
        $this->assign('list_data', $data);
        $this->display($model['template_list']);
    }
}