<?php
/**
 * 微信后台操作控制器IndexController.class.php
*/
namespace Admin\Controller;
use  Wx\WechatAuth;

class WxoperateController extends AdminController{
    
    /* 测试号 */
   /* private $appId = 'wx9c854ea9bf0fbe4d'; //AppID(应用ID)
	private $appSecret	= "6b1670c3bcf2246849aea0890c93d7e3";
	private $token = 'zhukao985'; //微信后台填写的TOKEN*/

    private $appId = 'wx295ed8e5b6a937c6'; //AppID(应用ID)
    private $appSecret  = "438fe07a6c976ee29c78ffc83a4565b3";
    private $token = 'uuyucom123'; //微信后台填写的TOKEN
    
	
    /*上传图文消息内的图片获取url*/
    public function upImgUrl(){

        if(IS_POST){
            $filename=$_POST['url'];
            $data['description']=$_POST['description'];

            $auth=new WechatAuth($this->appId,$this->appSecret);
            $token=$auth->getAccessToken();
           
            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->getImgUrl($filename);
         
            if($res['url']){
                $data['url']=$res;
                $con=M('content_imgurl', 'wx_')->data($data)->add();
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
        } else {
            $this->display();
        }
    }
    
    /*上传图文消息内的图片获取url  --- 列表页*/
    public function urlLists(){
        $count=M('content_imgurl','wx_')->count();
        $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('content_imgurl','wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $this->display(); 
    }
    
    /*新增永久图片素材 (图文消息使用)（type:image，thumb）,图片素材返回media_id ,url*/
    public function upMeterial(){
        if(IS_POST){
            $data['type']=$_POST['type'];
            $filename=$_POST['url'];
            $data['mark']=$_POST['mark'];
            $data['create_time']=time();
            
            $auth=new WechatAuth($this->appId,$this->appSecret);
            $token=$auth->getAccessToken();

            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->materialAddMaterial($filename,  $data['type']);//返回media_id

            if($res['media_id']){
                $data['url']=$res['url'];
                $data['media_id']=$res['media_id'];
                
                $con=M('media_id', 'wx_')->data($data)->add();
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
        }else{
            $this->display();
        }
    }
    
    /*永久图片素材列表*/
    public function metelist(){
        $count=M('media_id', 'wx_')->count();
            $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show  = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = M('media_id','wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list',$list);// 赋值数据集delMeterial
            $this->assign('page',$show);// 赋值分页输出
            $this->display();
    }
    
    /*添加永久图文素材到数据库--不影响微信端*/
    public  function    addNewsMeterial(){
        if(IS_POST){
            $data['title']=$_POST['title'];
            $data['thumb_media_id']=$_POST['thumb_media_id'];
            $data['author']=$_POST['author'];
            $data['digest']=$_POST['digest'];
            $data['show_cover_pic']=$_POST['show_cover_pic'];
            $data['content']=$_POST['content'];
            $data['content_source_url']=$_POST['content_source_url'];
            
            $addtw=M('sucai', 'wx_')->data($data)->add();
            if($addtw){
                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>-1,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            }
        } else {
            $this->display();
        }
    }

     /*永久图文素材列表---不影响微信端*/
    public  function  upnewslList(){
        $count=M('sucai', 'wx_')->count();
        $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('sucai', 'wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*编辑永久图文素材列表---不影响微信端*/
    public  function  editnewsMeterial(){
       if(IS_POST){
            $id=$_POST['id'];
            $data['title']=$_POST['title'];
            $data['thumb_media_id']=$_POST['thumb_media_id'];
            $data['author']=$_POST['author'];
            $data['digest']=$_POST['digest'];
            $data['show_cover_pic']=$_POST['show_cover_pic'];
            $data['content']=$_POST['content'];
            $data['content_source_url']=$_POST['content_source_url'];
            $res=M('sucai', 'wx_')->where(array('id'=>$id))->save($data);
            if($res===false){
                $msg=array('code'=>-1,'msg'=>'修改失败！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>1,'msg'=>'修改成功！');
                $this->ajaxReturn($msg);
            }
        }else{
            $id=$_GET['id'];
            $con=M('sucai', 'wx_')->where(array('id'=>$id))->find();
            $this->assign('con',$con);
            $this->display();
        }
    }

    /*新增永久多图文素材*/
    public  function upAddNews(){
        $ids=$_POST['ids'];
        header('Content-Type:text/html;charset=utf-8');
        $data['type']='news';
        $data['create_time']=time();
        $data['mark']=$_POST['mark'];
        $arr=explode(',', $ids);
        $map['id']=array('in',$arr);
        $lists= M('sucai', 'wx_')->field('title,thumb_media_id,author,digest,show_cover_pic,content,content_source_url')->where($map)->select();
        
        $auth=new WechatAuth($this->appId,$this->appSecret);
        $token=$auth->getAccessToken();

        $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
        $res=$oauth->uploadNews($lists);
        
        if($res['media_id']){
                $data['media_id']=$res['media_id'];
                $con=M('media_id', 'wx_')->data($data)->add();
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

    /*删除永久图文素材列表---不影响微信端*/
    public  function delnewsMeterial(){
        if(IS_POST){
            $id=$_POST['id'];
            $res=M('sucai', 'wx_')->where(array('id'=>$id))->delete();
            if($res){
                $msg=array('code'=>1,'msg'=>'成功删除！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'删除失败！');
                $this->ajaxReturn($msg);
            }
        }else{
            $msg=array('code'=>-1,'msg'=>'删除不合法！');
            $this->ajaxReturn($msg);
        }
    }
    
    /*删除微信端永久media_id素材*/
    public function delMeterial(){
        $id=$_POST['id'];
        $mid=M('media_id', 'wx_')->where(array('id'=>$id))->field('media_id')->find();
        
        if($mid){
            $auth=new WechatAuth($this->appId,$this->appSecret);
            $token=$auth->getAccessToken();

            $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
            $res=$oauth->delMeterial($mid['media_id']);
            if($res['errcode']==0){
                //删除数据表里的文件
                $del=M('media_id', 'wx_')->where(array('id'=>$Id))->delete();
                $msg=array('code'=>1,'msg'=>'素材删除成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>$res['errmsg']);
                $this->ajaxReturn($msg);
            }
        }else{
            $msg=array('code'=>-1,'msg'=>'素材不存在！');
            $this->ajaxReturn($msg);
        }
    }

    /*群发图文消息给关注者，最近有发消息给公众号的用户*/
    public function sendMsgToAll(){
        if(IS_POST){

                $auth=new WechatAuth($this->appId,$this->appSecret);
                $token=$auth->getAccessToken();

                $oauth=new WechatAuth($this->appId,$this->appSecret,$token);
                /*获取关注用户列表*/
                $users=$oauth->userGet();
                $ulists=$users['data']['openid'];
                $tousers=implode(',', $ulists);

                $type=$_POST[type];
                $con=$_POST['content'];

                if($type=='text'){
                    $content=array(
                        "touser"=>$tousers,
                        "msgtype"=>"text",
                        "text"=>array("content"=>$con),

                    );
                }elseif($type=='mpnews'){
                    $content=array(
                        "touser"=>$tousers,
                        "mpnews"=>array("media_id"=>$con),
                        "msgtype"=>"mpnews",
                    );
                }

               $res=$oauth->msgSendAll($content);

               if($res['errcode']==0){
                   $msg=array('code'=>1,'msg'=>'群发成功');
                   $this->ajaxReturn($msg);
               }else{
                   $msg=array('code'=>0,'msg'=>'群发失败');
                   $this->ajaxReturn($msg);
               }

        }else{
            $this->display();
        }
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
                $firstArr=M('menue', 'wx_')->field('id,name')->where(array('pid'=>0))->select();
                $tab=array();
                for($i=0;$i<count($firstArr);$i++){
                    if(!empty($firstArr[$i]['name'])){
                        $subArr=M('menue', 'wx_')->where(array('pid'=>$firstArr[$i]['id']))->field('name,type,key,url')->select();
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
                $auth=new WechatAuth($this->appId,$this->appSecret);
                $token=$auth->getAccessToken();

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
        $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
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
            $data['picurl']=$_SERVER['HTTP_HOST'].$_POST['picurl'];
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

    /*菜单事件消息列表*/
    public  function  eventList(){
        $count=M('replay_item','wx_')->count();
        $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('replay_item','wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*新增菜单事件消息*/
    public  function addEvent(){
        if(IS_POST){
            $data['title']=$_POST['title'];
            $data['description']=$_POST['description'];
            $data['picurl']=$_SERVER['HTTP_HOST'].$_POST['picurl'];
            $data['url']=$_POST['url'];
            $data['type']=$_POST['type'];
            $res=M('replay_item','wx_')->data($data)->add();
            if($res){
                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            }
        }else{
            $this->display();
        }
    }

    /*删除事件消息*/
    public  function delEvent(){
            if(IS_POST){
                $id=$_POST['id'];

                $con=M('replay_item','wx_')->where(array('id'=>$id))->delete();
                if($con===false){
                    $msg=array('code'=>-1,'msg'=>'删除失败！');
                    $this->ajaxReturn($msg);
                }else{
                    $msg=array('code'=>1,'msg'=>'删除成功！');
                    $this->ajaxReturn($msg);
                }
            }else{
                $msg=array('code'=>0,'msg'=>'操作不合法！');
                $this->ajaxReturn($msg);
            }
    }

    /*编辑菜单事件*/
    public  function editEvent(){
        if(IS_POST){
            $data['id']=$_POST['id'];
            $data['title']=$_POST['title'];
            $data['description']=$_POST['description'];
            $data['picurl']=$_POST['picurl'];
            $data['url']=$_POST['url'];
            $data['type']=$_POST['type'];
            $res=M('replay_item','wx_')->save($data);
            if($res){
                $msg=array('code'=>1,'msg'=>'修改成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'修改失败！');
                $this->ajaxReturn($msg);
            }
        }else{
            $id=$_GET['id'];
            $info=M('replay_item','wx_')->where(array('id'=>$id))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }


    /*菜单导航事件*/
    public function addNavEvent(){
        if(IS_POST){
            $data['eventkey']=$_POST['eventkey'];
            $data['msgtype']=$_POST['msgtype'];
            $data['msgcontent']=$_POST['msgcontent'];
            $res=M('event','wx_')->data($data)->add();
            if($res){
                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>0,'msg'=>'添加失败！');
                $this->ajaxReturn($msg);
            }
        }else{

            $this->display();

        }

    }

    /*菜单导航事件--列表*/
    public function navEventList(){
        $count=M('event','wx_')->count();
        $Page  = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('event','wx_')->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*菜单导航事件--修改*/
    public function editNavEvent(){
        if(IS_POST){
            $data['id']=$_POST['id'];
            $data['eventkey']=$_POST['eventkey'];
            $data['msgtype']=$_POST['msgtype'];
            $data['msgcontent']=$_POST['msgcontent'];
            $con=M('event','wx_')->save($data);
            if($con===false){
                $msg=array('code'=>0,'msg'=>'修改失败！');
                $this->ajaxReturn($msg);
            }else{

                $msg=array('code'=>1,'msg'=>'添加成功！');
                $this->ajaxReturn($msg);
            }

        }else{

            $id=$_GET['id'];
            $res=M('event','wx_')->where(array('id' =>$id))->find();
            $this->assign('info',$res);
            $this->display();
        }

    }

    /*菜单导航事件---删除*/
    public  function delNavEvent(){
        if(IS_POST){
            $id=$_POST['id'];
            $con=M('event','wx_')->where(array('id' => $id))->delete();
            if($con===false){
                $msg=array('code'=>-1,'msg'=>'删除失败！');
                $this->ajaxReturn($msg);
            }else{
                $msg=array('code'=>1,'msg'=>'成功删除！');
                $this->ajaxReturn($msg);
            }
        }else{
            $msg=array('code'=>0,'msg'=>'缺少参数！');
            $this->ajaxReturn($msg);
        }
    }

}



