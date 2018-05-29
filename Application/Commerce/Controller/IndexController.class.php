<?php
namespace Commerce\Controller;
use Think\Controller;
use User\Api\UserApi;
class IndexController extends \Think\Controller{
    public function index(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
        
        $this->display('login');
    }
    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login(){
        header("Content-Type: text/html;charset=utf-8"); 
        /* 检测验证码 TODO: */
        if (empty(I('post.account')))$this->ajaxReturn(array('status'=>-1,'msg'=>'账号不能为空'));
        if (empty(I('post.pwd')))$this->ajaxReturn(array('status'=>-1,'msg'=>'密码不能为空'));
        if (empty(I('post.code')))$this->ajaxReturn(array('status'=>-1,'msg'=>'验证码不能为空'));
        $map['status'] = 1;
        $map['busier_account']=I('post.account');
        $find=M('Busier','tab_')->field('id,password')->where($map)->find();
        if(null==$find){
             $this->ajaxReturn(array('status'=>-1,'msg'=>'账号不存在或被禁用！'));
        }else{
             $User = new UserApi;
            if($find['password'] ==$this->think_ucenter_md5(I('post.pwd'), UC_AUTH_KEY)){
                if(!check_verify(I('post.code'))){
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'验证码输入错误！'));
                }
                $this->save_login($find['id'],I('post.account'));
                M('Busier','tab_')->where($find)->setField(array('login_time'=>NOW_TIME));
                $this->ajaxReturn(array('status'=>1,'msg'=>'登录成功'));
            }else{
                $this->ajaxReturn(array('status'=>-1,'msg'=>'密码错误'));
            }
        }
    }
    /* 退出登录 */
    public function logout(){
            unset($_SESSION['user_auth_commerce']);
            $this->ajaxReturn(array('status'=>3,'msg'=>'退出成功！'));
    }
    public function verify(){
        $config = array(
            'seKey'     => 'ThinkPHP.CN',   //验证码加密密钥
            'fontSize'  => 22,              // 验证码字体大小(px)
            'imageH'    => 50,               // 验证码图片高度
            'imageW'    => 180,               // 验证码图片宽度
            'length'    => 4,               // 验证码位数
            'fontttf'   => '4.ttf',              // 验证码字体，不设置随机获取
        );
        ob_clean();
        $verify = new \Think\Verify($config);
        $verify->codeSet = '0123456789';
        $verify->entry(1);
    }
    //保存登录信息
    public function save_login($uid,$account){
        /* 记录登录SESSION和COOKIES */
        if(empty($uid) || empty($account)){
                $this->error('session缺少参数');
        }
        $auth = array(
            'uid'             => $uid,
            'account'        => $account,
        );
        session('user_auth_commerce', $auth);
    }

    /**
     * 系统非常规MD5加密方法
     * @param  string $str 要加密的字符串
     * @return string 
     */
    function think_ucenter_md5($str, $key = 'ThinkUCenter'){
        return '' === $str ? '' : md5(sha1($str) . $key);
    }
}