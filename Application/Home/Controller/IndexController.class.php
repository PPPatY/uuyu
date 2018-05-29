<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;
use User\Api\PromoteApi;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

    //系统首页
    public function index(){
        $map['game_status'] = 1;
        $map['recommend_status'] = 1;
        $rec_data = M('Game',"tab_")->where($map)->order('sort desc,id DESC')->select();
        $this->assign("rec_data",$rec_data);
        $category_id=M('category')
            ->where(array('name'=>array('IN','tui_gg'),'status'=>1))//,tui_zx
            ->select();
        if(empty($category_id)){
            $category_id =array();
        }
        $category_id = implode(',',array_column($category_id, 'id'));
        $doc=M('Document')->where(array('category_id'=>array('in',$category_id),'status'=>1,'create_time'=>array("elt",time()),'deadline'=>array("not between",array(1,time()))))->order("update_time desc")->select();
        
        $this->assign("doc1",$doc);
        $zcategory_id=M('category')
            ->where(array('name'=>array('IN','tui_zx'),'status'=>1))
            ->select();
        if(empty($zcategory_id)){
            $zcategory_id =array();
        }
        $zcategory_id = implode(',',array_column($zcategory_id, 'id'));
        $zdoc=M('Document')->where(array('category_id'=>array('in',$zcategory_id),'status'=>1,'create_time'=>array("elt",time()),'deadline'=>array("not between",array(1,time()))))->order("update_time desc")->select();
        $this->assign("zdoc",$zdoc);
        $ggadv = D('adv')->adv_lists('index_gg');
        $zxadv = D('adv')->adv_lists('index_zx');
        $this->assign("ga",$ggadv);
        $this->assign("za",$zxadv);
        $this->display();
    }

    public function login(){
        $verify = new \Think\Verify();
         if(!$verify->check(I('yzm'))){
            $this->ajaxReturn(array("status"=>0,"msg"=>"验证码错误",'code'=>0));
        }
        if($_POST['remm']=='on'){
            setcookie('home_account',$_POST['account'],time()+3600*10000,$_SERVER["HTTP_HOST"]);
            setcookie('home_pas',$_POST['password'],time()+3600*10000,$_SERVER["HTTP_HOST"]);
        }else{
            setcookie('home_account',$_POST['account'],time()-1,$_SERVER["HTTP_HOST"]);
            setcookie('home_pas',$_POST['password'],time()-1,$_SERVER["HTTP_HOST"]);
        }
        $account  = $_POST['account'];
        $password = $_POST['password'];
        $union=0;
        $promote = new PromoteApi();
        $result = $promote->login($account,$password,$union);
        if ($result >0) {
            $map['account']=$account;            
            $data['last_login_time']=time();
            M("promote","tab_")->where($map)->save($data);
            $this->ajaxReturn(array("status"=>1,"msg"=>"登录成功",'url'=>U('Promote/index')));
        }
        else{
            $msg = "";
            switch ($result) {
                case -1:
                    $msg = "账号不存在";
                    break;
                case -2:
                    $msg = "密码错误";
                    break;
                case -3:
                    $msg = "账号被禁用,请联系管理员";
                    break;
                case -4:
                    $msg = "审核中,请联系管理员";
                    break;
                case -5:
                    $msg = "您不是推广站成员,请注册";
                    break;
                default:
                    $msg = "未知错误！请联系管理员";
                    break;
            }
            $this->ajaxReturn(array("status"=>0,"msg"=>$msg));
        }
    }
    public function about(){
        $map1['name']='tui_about';
        $map['status']=1;
        $map['create_time']=array("elt",time());
        $map['deadline']=array("not between",array(1,time()));
        $cid = M('category')->field('id')->where($map1)->find();
        $map['category_id']=$cid['id'];
        $dataid = M('Document')->field('id')->where($map)->order("update_time desc")->find();
        $data = M('document_article')->where(array('id'=>$dataid['id']))->find();
        $this->assign('data',$data);
        $this->display();
    }
    public function register(){
        if(IS_POST){
           if(empty($_POST['account'])){
               $this->ajaxReturn(array('status'=>0,'msg'=>'名称不能为空!'));
           }
            unset($_POST['remember']);
            $Promote = new PromoteApi();
            $data = $_POST;
            C('PROMOTE_AUTO_AUDIT')==1?$data['status'] = 1:$data['status'] = 0;
            $pid = $Promote->register($data);
            if($pid > 0){
                $this->ajaxReturn(array('status'=>1,'info'=>$pid,'url'=>U('index'),'check_status'=>$data['status']));
            }
            else{
                $this->ajaxReturn(array('status'=>0,'info'=>$pid));
            }
        }
        else{
            $this->display();
        }
        
    }
    public function forget(){
        $this->display();
    }
    public function rule() {
        $category=D('category')->where(array('name'=>'rule'))->find();
        $docu=D('document')->where(array('category_id'=>$category['id'],'status'=>1))->find();
        $data_article['title'] = $docu['title'];
        $document_article=D('document_article')->where(array('id'=>$docu['id']))->find();
        $data_article['content'] = $document_article['content'];
        $this->assign('article',$data_article);
        $this->display();
    }
    /**
    *检测账号是否存在
    */
    public function checkAccount($account){
        $Promote = new PromoteApi();
        $res = $Promote->checkAccount($account);
        if($res){
            $this->ajaxReturn(true);
        }
        else{
            $this->ajaxReturn(false);
        }
        
    }
    public function checkAccountt($account){
        $Promote = new PromoteApi();
        $res = $Promote->checkAccount($account);
        if($res){
            echo "true";
        }
        else{
            echo "false";
        }
        
    }
    //验证码
    public function verify($vid=''){
        $config = array(
            'seKey'     => 'ThinkPHP.CN',   //验证码加密密钥
            'fontSize'  => 16,              // 验证码字体大小(px)
            'imageH'    => 42,               // 验证码图片高度
            'imageW'    => 107,               // 验证码图片宽度
            'length'    => 4,               // 验证码位数
            'fontttf'   => '4.ttf',              // 验证码字体，不设置随机获取
        );
        ob_clean();
        $verify = new \Think\Verify($config);
        $verify->codeSet = '0123456789';
        $verify->entry($vid);
    }

}