<?php
/**
 * 微信后台操作控制器IndexController.class.php
*/
namespace Admin\Controller;
use  Wx\WechatAuth;

class WxoperateController extends IndexController{
    
    /* 测试号 */
    private $appId = 'wx9c854ea9bf0fbe4d'; //AppID(应用ID)
	private $appSecret	= "6b1670c3bcf2246849aea0890c93d7e3";
	private $token = 'zhukao985'; //微信后台填写的TOKEN
	
    /*上传图文消息内的图片获取url*/
    public function upImgUrl(){
            $filename=$_SERVER['DOCUMENT_ROOT'].$_POST['url'];
            $data['description']=$_POST['description'];
            $token=$this->getToken();
           
            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->getImgUrl($filename);
         
            if($res['url']){
                $data['url']=$res;
                $con=M('content_imgurl')->data($data)->add();
                if($con){
                    $msg=array('code'=>1,'msg'=>'获取图片地址成功！');
                    $this->ajaxReturn($msg);
                }else{
                    $msg=array('code'=>0,'msg'=>'更新数据失败！');
                    $this->ajaxReturn($msg);
                }
            }else{
                $msg=array('code'=>-1,'msg'=>'获取图片url失败！');
                $this->ajaxReturn($msg);
            }
    }
    
    /*上传图文消息内的图片获取url  --- 列表页*/
    public function urlLists(){
        $count=M('content_imgurl')->count();
        $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('content_imgurl')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
       
        $this->display(); 
    }
    
    /*新增永久图片素材 (图文消息使用)（type:image，thumb）,图片素材返回media_id ,url*/
    public function upMeterial(){
        $data['type']=$_POST['type'];
        $filename=$_SERVER['DOCUMENT_ROOT'].$_POST['url'];
        $data['mark']=$_POST['mark'];
        $token=$this->getToken();
        $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
        
        $res=$oauth->materialAddMaterial($filename,  $data['type']);//返回media_id
       
        if($res){
                $data['url']=$res['url'];
                $data['media_id']=$res['media_id'];
                $data['create_time']=time();
                $con=M('media_id')->data($data)->add();
                if($con){
                    $msg=array('code'=>1,'msg'=>'添加成功');
                    $this->ajaxReturn($msg);
                }else{
                    $msg=array('code'=>0,'msg'=>'添加数据失败');
                    $this->ajaxReturn($msg);
                } 
        }else{
                $msg=array('code'=>-1,'msg'=>'上传永久素材失败');
                $this->ajaxReturn($msg);
        }
    }
    
    /*永久图片素材列表*/
    public function metelist(){
            $lists=M('media_id')->order('id  asc')->select();
            var_dump($lists);
            $this->assign('list',$lists);
            $this->display();
    }
    
    /*添加永久图文素材到数据库--不影响微信端*/
    public  function    addMeterial(){
            $data['title']=$_POST['title'];
            $data['thumb_media_id']=$_POST['thumb_media_id'];
            $data['author']=$_POST['author'];
            $data['digest']=$_POST['digest'];
            $data['show_cover_pic']=$_POST['show_cover_pic'];
            $data['content']=$_POST['content'];
            $data['content_source_url']=$_POST['content_source_url'];
            
            $addtw=M('sucai')->data($data)->add();
            if($addtw){
                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>-1,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            }
    }
    
    /*新增永久多图文素材*/
    public  function upAddNews($ids='1,2'){
            header('Content-Type:text/html;charset=utf-8');
            $data['type']='news';
            $data['create_time']=time();
            $data['mark']=$_POST['mark'];
            $arr=explode(',', $ids);
            $map['id']=array('in',$arr);
        
            $lists= M('sucai')->field('title,thumb_media_id,author,digest,show_cover_pic,content,content_source_url')->where($map)->select();
            
            $token=$this->getToken();
            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->uploadNews($lists);
            
            var_dump($res);
            if($res['media_id']){
                    $data['media_id']=$res['media_id'];
                    $con=M('media_id')->data($data)->add();
                    if($con){
                        $msg=array('code'=>1,'msg'=>'添加成功！');
                        $this->ajaxReturn($msg);
                    }else{
                        $msg=array('code'=>0,'msg'=>'数据更新失败！');
                        $this->ajaxReturn($msg);
                    }
            }else{
                $msg=array('code'=>-1,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            } 
    }
    
    
    /*删除永久media_id素材*/
    public function delMeterial($mediaId){
        $mid=M('media_id')->where(array('id'=>$mediaId))->field('media_id')->find();
        if( $mid){
            $token=$this->getToken();
            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->delMeterial($mid['media_id']);
            if($res['errcode']==0){
                //删除数据表里的文件
                $del=M('media_id')->where(array('id'=>$mediaId))->delete();
                $msg=array('code'=>1,'msg'=>'素材删除成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'素材删除失败，稍后再试！');
                $this->ajaxReturn($msg);
            }
        }else{
            $msg=array('code'=>-1,'msg'=>'素材不存在！');
            $this->ajaxReturn($msg);
        }
    }

    /*群发图文消息给关注者，最近有发消息给公众号的用户*/
    public function sendMsgToAll(){
        $token=$this->getToken();
        $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
        $content=array(
            "touser"=>"oecBGuHio0NCnBfSFFo6_Wk1YUTQ",
            "mpnews"=>array("media_id"=>"bVm0HskoE5cd5m95bg_p9T7e7iTkuR4tb--cq231cIc"),
            "msgtype"=>"mpnews",
        );
        $oauth->msgSendAll($content);
    }

    /*关注消息设置*/
    public function subscribeTxt(){
        if(IS_POST){
            $data['type']=$_POST['type'];
            $data['content']=$_POST['content'];
            $data['news']=$_POST['news'];
            $con=M('replay_subscrib','wx_')->where(array('id'=>1))->save($data);
            if($con){
                $msg=array('code'=>1,'msg'=>'更新成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>-1,'msg'=>'更新失败！');
                $this->ajaxReturn($msg);
            }
            
        }else{
            $res=M('replay_subscrib','wx_')->field('type,content,news')->find();
            $this->assign('res',$res);
            $this->display();
        }
    }
    
    
}



