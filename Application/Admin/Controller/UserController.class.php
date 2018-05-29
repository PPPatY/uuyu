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
use Com\Wechat;
use Com\WechatAuth;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $nickname       =   I('nickname');
        if(isset($_REQUEST['status'])){
            $map['status']  =  $_REQUEST['status'];
        }
        if(is_numeric($nickname)){
            $map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }else{
            $map['nickname']    =   array('like', '%'.(string)$nickname.'%');
        }

        $list   = $this->lists('Member', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '管理员列表';
        $this->display();
    }

    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display('updatenickname');
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname(){
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User   =   new UserApi();
        $uid    =   $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member =   D('Member');
        $data   =   $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
            $user               =   session('user_auth');
            $user['username']   =   $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display('updatepassword');
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api    =   new UserApi();
        $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display('editaction');
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add($username = '', $password = '', $repassword = '', $email = '',$second_pwd=''){

        if(IS_POST){ 
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和确认密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password, $email,'',$second_pwd);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                $data['uid'] = $uid;
                $data['group_id'] = I('auth');
                M('auth_group_access')->data($data)->add();
                if(!M('Member')->add($user)){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',U('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $list=D('AuthGroup')->select();
            $this->assign('lists',$list);
            $this->meta_title = '新增管理员';
            $this->display();
        }
    }
/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string 
 */
function think_ucenter_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}
    public function edit($id){
        if(IS_POST){
            if(isset($_POST['bind_wx'])){
                if($_POST['bind_wx']=='unbind_wx'){
                    $info['admin_openid']='';
                }else{
                    $info['admin_openid']=session('admin_openid');
                    if($info['admin_openid']==''){
                        $this->error('请在30分钟内扫描并关注微信公众号！');
                    }
                }
            }
            if($_POST['auth']==''&&$id!=1){
                $this->error('请选择角色类型！');
            }
            $Member=D('UcenterMember');
            $mem=D('Member');
            $au=D('AuthGroupAccess');
            $map['id']=$id;
            $maps['uid']=$id;
            $info['username']=$_POST['username'];
            $in['nickname']=$_POST['username'];
            $pwd=$_POST['password'];
            $spwd=$_POST['second_pwd'];
            $rpwd=$Member->where(array('id'=>$id))->find();
            $oldpwd=$rpwd['password'];
            $oldspwd=$rpwd['second_pwd'];
            $User = new UserApi;
            $info['password']= $pwd==$oldpwd?$oldpwd:$this->think_ucenter_md5($pwd,UC_AUTH_KEY);
            $info['second_pwd']= $spwd==$oldspwd?$oldspwd:$this->think_ucenter_md5($spwd,UC_AUTH_KEY);
            $info['email']=$_POST['email'];
            $ss['group_id']=$_POST['auth'];
            $ss['houtai']=$_POST['houtai'];
            $smember=$Member->where($map)->save($info);
            $meb=$mem->where($maps)->save($in);
            if ($au->where(array('uid' => $id))->find()) {
                if ($ss['group_id'] == '') {
                    unset($ss['group_id']);
                }
                $ag = $au->where(array('uid' => $id))->save($ss);
            } else {
                $ss['uid'] = $id;
                $ag = $au->add($ss);
            }
            
            if($smember !== false||$meb||$ag){
                $this->success('修改成功!',U('User/index'));
            }else{  
                $this->error('修改失败！',U('User/index'));
            }
        }else{
            $map['id']=$_GET['id'];
            $Member=D('UcenterMember')->where($map)->find();
            $au=D('AuthGroupAccess')->where(array('uid'=>$_GET['id']))->find();
            $this->assign("authid",$au["group_id"]);
            $this->assign("houtai",$au["houtai"]);
            $list=D('AuthGroup')->select();
            $username=$_POST['username'];
            $password=$_POST['password'];
            $this->assign('lists',$list);
            $this->assign('list',$Member);
            $this->assign('sd',$group);
            $this->display();
        }
    }
    public function bdwx(){
        $map['id']=UID;
        $Member=D('UcenterMember')->field('id,username,admin_openid,openid_sign')->where($map)->find();
        $this->assign('id',$map['id']);
        $this->assign('list',$Member);
        $this->display();
    }
    public function updatelist($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = intval(C('LIST_ROWS')) ? :10;
        if(isset($_REQUEST['op_account'])){
            if ($_REQUEST['op_account']=='全部') {
                unset($_REQUEST['op_account']);
            }else{
                $map['op_account']  =  trim($_REQUEST['op_account']);
                unset($_REQUEST['op_account']);
            }
        }
        if(isset($_REQUEST['game_name'])){
            $map['game_name']  =  trim($_REQUEST['game_name']);
            unset($_REQUEST['game_name']);
        }
        if(isset($_REQUEST['account'])){
            $map['user_account']  =  array('like','%'.str_replace('%','\%',$_REQUEST['account']).'%');
            unset($_REQUEST['account']);
        }
        if(isset($_REQUEST['huobi'])&&$_REQUEST['huobi']!=''){
            $map['type']  = $_REQUEST['huobi'];
            unset($_REQUEST['huobi']);
        }

				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
				
        $list=M('balance_edit','tab_')
            ->where($map)
            ->order('create_time desc')
            ->page($page, $row)
            ->select();
        $count = M('balance_edit','tab_')->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list',$list);
        $this->meta_title = '账户修改记录';
        $this->display('updatelist');
    }
    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '管理员账号长度必须在16个字符以内！'; break;
            case -2:  $error = '管理员账号被禁止注册！'; break;
            case -3:  $error = '管理员账号被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            case -12: $error = '二级密码长度必须在6-30个字符之间！';break;
            default:  $error = '未知错误';
        }
        return $error;
    }
    public function get_openid(){
        $User = new UserApi;
        if($_POST['id']>999){
            $this->ajaxReturn(array('status'=>0,'msg'=>'管理员id不能大于999'));
        }
        $data = $User->verifyPwd($_POST['id'], $_POST['pwd']);
        if(!$data){
            $this->ajaxReturn(array('status'=>0,'msg'=>'密码错误，请重新选择'));
        }
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $result=auto_get_access_token(dirname(__FILE__).'/access_token_validity.txt');
        if($result['is_validity']){
            session('token',$result['access_token']);
            $auth  = new WechatAuth($appid, $appsecret,$result['access_token']);
        }else{
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            $token['expires_in_validity']=time()+$token['expires_in'];
            wite_text(json_encode($token),dirname(__FILE__).'/access_token_validity.txt');
            session('token',$token['access_token']);
        }
        $times=date('s',time());
        // $scene_id=strrev($_POST['id'].$times);
        $scene_id=$_POST['id'].$times.$_POST['type'];
        session('scene_id',$scene_id);
        $ticket = $auth->qrcodeCreate($scene_id,600);//10分钟
        if($ticket==''){
            $return=array('status'=>0,'data'=>'获取ticket失败！');
        }
        $qrcode = $auth->showqrcode($ticket['ticket']);
        $return=array('status'=>1,'data'=>$qrcode);
        $this->ajaxReturn($return);
    }
    function checkpwd(){
        $User = new UserApi;
        $data = $User->verifyPwd($_POST['id'], $_POST['pwd']);
        $this->ajaxReturn(array('data'=>$data));
    }
    function checkOpenidpic(){
        sleep(2);
        $data=M('ucenter_member')->where(array('id'=>$_REQUEST['id']))->find();
        if($data['openid_sign']==session('scene_id')){
            $this->ajaxReturn(array("status"=>1));
        }else{
            $this->ajaxReturn(array("status"=>0));
        }
    }

    public function delete($id){
        M()->startTrans();
        $res1 = M('member')->delete($id);
        $res2 = M('ucenter_member')->delete($id);
        $map['uid'] = $id;
        $res3 = M('auth_group_access')->where($map)->delete();
        if($res1 && $res2 && $res3){
            M()->commit();
            $this->success('删除成功');
        }else{
            M()->rollback();
            $this->error('删除失败'.M()->getError());
        }
    }

    public function rolelist()
    {
        if (isset($_REQUEST['game_name'])) {
            $map['game_name'] = trim($_REQUEST['game_name']);
            unset($_REQUEST['game_name']);
        }
        if (isset($_REQUEST['server_name'])) {
            $map['server_name'] = trim($_REQUEST['server_name']);
            unset($_REQUEST['server_name']);
        }
        if (isset($_REQUEST['role_name'])) {
            $map['role_name'] = trim($_REQUEST['role_name']);
            unset($_REQUEST['role_name']);
        }
        empty(I('user_account')) || $map['user_account'] = ['like',"%".I('user_account')."%"];
        $list = $this->lists(M('user_play_info', 'tab_'), $map);
        $this->assign('list', $list);
        $this->meta_title = '角色数据';
        $this->display();

    }

    /**
     * 更新游戏角色数据
     * @param $id
     */
    public function user_update($ids){
        $res = D('User')->update_user_player($ids);
        $this->success("更新成功：{$res['suc']}个，失败：{$res['ero']}");
    }

    /**
     * 更新游戏角色数据
     * @param $id
     */
    public function age(){
        if (IS_POST){
            $data = $_POST;
            $a = new ToolController();
            $re = $a->save($data);
            $this->success('保存成功');
        }
        $data = C('age_prevent');
        $this->assign('data',$data);
        $this->display();
    }
}