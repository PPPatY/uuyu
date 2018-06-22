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

    /*后台菜单设置*/
    public function menueSet(){
        if(IS_POST){
            if($_POST['id']){
                $id=$_POST['id'];
                $name=$_POST['name'];
                $type=$_POST['type'];
                $key=$_POST['key'];
                $url=$_POST['url'];
                if(!empty($name)){
                    $data['name']=$name;
                }elseif(!empty($type)){
                    $data['type']=$type;
                }elseif(!empty($key)){
                    $data['key']=$key;
                }elseif(!empty($url)){
                    $data['url']=$url;
                }
                $con=M('menue','wx_')->where(array('id'=>$id))->save($data);
                if($con===false){
                    $msg=array('code'=>-1,'msg'=>'修改失败！');
                    $this->ajaxReturn($msg);
                }else{
                    $msg=array('code'=>1,'msg'=>'修改成功！');
                    $this->ajaxReturn($msg);
                }
            }elseif($_POST['submit']){
                $firstArr=M('menue')->field('id,name')->where(array('pid'=>0))->select();
                $tab=array();
                for($i=0;$i<count($firstArr);$i++){
                    if(!empty($firstArr[$i]['name'])){
                        $subArr=M('menue')->where(array('pid'=>$firstArr[$i]['id']))->field('name,type,key,url')->select();
                        for($j=0; $j <= count($subArr)+1; $j++){
                            if(empty($subArr[$j]['name'])){
                                unset($subArr[$j]);
                            }
                        }
                        for($n=0; $n <= count($subArr); $n++){
                            if($subArr[$n]['type']=='view'){
                                unset($subArr[$n]['key']);
                            }elseif($subArr[$n]['type']=='click'){
                                unset($subArr[$n]['url']);
                            }
                        }
                        $tab[$i]=array('name'=>$firstArr[$i]['name'],'sub_button'=>$subArr);
                    }
                }
                $token=$this->getToken();
                $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
                $oauth->menuCreate($tab);
            }
        }else{
            $result=M('menue','wx_')->where(array('pid'=>0))->field('id,name')->select();
            $child_arr=array();
            for($i=0; $i<count($result);  $i++){
                $child=M('menue','wx_')->where(array('pid'=>$result[$i]['id']))->field('id,name,type,key,url')->select();
                for($j=0;$j<count($child);$j++){
                    if(empty($child[$j]['key']) ){
                        unset($child[$j]['key']);
                    }elseif(empty($child[$j]['url']) ){
                        unset($child[$j]['url']);
                    }
                    elseif(empty($child['key']) ){
                        unset($child['key']);
                    
                    }elseif (empty($child['url'])){
                        unset($child['url']);
                    }
                }
                array_shift($result[$i]);
                $result[$i]['sub_button']=$child;
            }
            $this->assign('data',$result);
            $this->assign('sub0',$result[0]['sub_button']);
            $this->assign('sub1',$result[1]['sub_button']);
            $this->assign('sub2',$result[2]['sub_button']);
            // var_dump($result[0]['sub_button']);
            $this->display();
        }
    }

    /*关键字列表*/
    public  function keyWords(){
        $count=M('keyword_replay','wx_')->count();
        $Page  = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('keyword_replay','wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        
        $this->display(); 
    }
       
    /*添加关键字*/
    public  function  addkeywords(){
        if(IS_POST){
            $data['keyword']=$_POST['keyword'];
            $data['title']=$_POST['title'];
            $data['description']=$_POST['description'];
            $data['picurl']=$_POST['picurl'];
            $data['url']=$_POST['url'];
            $con=M('keyword_replay','wx_')->data($data)->add();
            if($con){
                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            }
        }else{
            // $msg=array('code'=>-1,'msg'=>'请求不合法！');
            // $this->ajaxReturn($msg);
            $this->display(); 
        }
    }

     /*修改关键字*/
     public function editkeywords(){
        if(IS_POST){
            $data['id']=$_POST['id'];
            $data['keyword']=$_POST['keyword'];
            $data['title']=$_POST['title'];
            $data['description']=$_POST['description'];
            $data['picurl']=$_POST['picurl'];
            $data['url']=$_POST['url'];
            $con=M('keyword_replay','wx_')->save($data);
            if($con){
                $msg=array('code'=>1,'msg'=>'修改成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'修改失败！');
                $this->ajaxReturn($msg);
            }
        }else{
            $id=$_GET['id'];
            $res=M('keyword_replay','wx_')->where(array('id'=>$id))->find();
            $this->assign('res',$res);
            $this->display();
        }
    }

    /*删除关键字*/
    public  function delkeywords(){
        if(IS_POST){
            $id=$_POST['id'];
            $res=M('keyword_replay','wx_')->where(array('id'=>$id))->delete();
            if($res===false){
                $msg=array('code'=>-1,'msg'=>'删除失败！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>1,'msg'=>'成功删除！');
                $this->ajaxReturn($msg);
            }
        }else{
            $msg=array('code'=>0,'msg'=>'操作不合法！');
            $this->ajaxReturn($msg);
        }
    }


}



