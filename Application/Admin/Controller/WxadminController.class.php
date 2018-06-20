<?php
/**
 * 微信控制器IndexController.class.php
*/
namespace dmin\Controller;
use  Think\Controller;

use  Wx\Wechat;
use  Wx\WechatAuth;



class WxadminController extends Controller {
	
	/* 测试号 */
    private $appId = 'wx9c854ea9bf0fbe4d'; //AppID(应用ID)
	private $appSecret	= "6b1670c3bcf2246849aea0890c93d7e3";
	private $token = 'zhukao985'; //微信后台填写的TOKEN
	
	/* private $appId = 'wx295ed8e5b6a937c6'; //AppID(应用ID)
	private $appSecret	= "438fe07a6c976ee29c78ffc83a4565b3";
	private $token = 'uuyucom123'; //微信后台填写的TOKEN */
	
	private $cryptstr = 'eLTVuT7gK3UwDBpKEroR3ivvrXbUKDPiE3XGCn9k9L6'; //消息加密KEY（EncodingAESKey）
	
    public function index(){
		
        /* 加载微信SDK */
        $wechat = new  Wechat($this->token,$this->appId, '');
        
        /* 获取请求信息 */
        $data = $wechat->request();
		
        if($data && is_array($data)){
          
            $type  = $data['MsgType'];
            
            if ($type == 'event'){
				
                $keyword = $data['Event'];
				
				if($keyword=='subscribe'){
					
					$subcon=$this->getSubscrib();
					
					if($subcon['type']==0){
						
						array_shift($subcon);
						
						$wechat->replyText($subcon);
						
					}elseif($subcon['type']==1){
						
						array_shift($subcon);
						
						$wechat->replyNewsOnce($subcon);
					}
					
				}elseif($keyword=='CLICK'){
					
					$menuevent=$this->getMenuevent($data['EventKey'],$data['FromUserName']);
					
					if($menuevent){
						if($menuevent['type']==1){
							array_shift($menuevent);
							$wechat->replyNewsOnce($menuevent);
						}else{
							/* 文本和连接方法相同 */
							$wechat->replyText($menuevent);
							return;
						}
					}else{
						$content=array("Content"=>"导航菜单设置错误！");
						$wechat->replyText($content);
						return;
					}
					
				}elseif($keyword=='VIEW'){
					
				}
				
            }else if($type == 'text'){
				
                $keyword = $data['Content'];
				$keyReplay=$this->getKeyWord($keyword);
				
				if($keyReplay){
					$wechat->replyNewsOnce($keyReplay);
					return;
				}else{
					$content=array("Content"=>"暂未开发此功能，敬请后期关注！");
					$wechat->replyText($content);
					return;
				}
            }else{
				$content=array("Content"=>"暂未开发此功能，敬请后期关注！");
				$wechat->replyText($content);
				return;
			}
        }
        
    }
	
	//获取access_token
	public function getToken(){
		
		if($_SESSION['access_token'] && $_SESSION['expire_time'] > time()){
            return $_SESSION['access_token'];
		}else{ 
			
			$auth=new WechatAuth($this->appId,$this->appSecret);
			$res=$auth->getAccessToken('client');
			$_SESSION['access_token']=$res['access_token'];
            $_SESSION['expire_time']=time()+7000; 
			
			return $res['access_token'];
		}
		
	}
	
    /*创建菜单：view,click两种类型*/
    public function createMenu(){
        $token=$this->getToken();
		$oauth=new WechatAuth($this->appId,$this->appSecret,$token);
        $postArr=array(
			array(
				"name"=>"热门游戏",
				"sub_button"=>array(
					array(
						"name"=>"全民主公",
						"type"=>"view",
						"url"=>"http://uuyu.com/mobile.php/?s=Game/open_game/game_id/54",
					),
					array(
						"name"=>"传奇荣耀",
						"type"=>"view",
						"url"=>"http://uuyu.com/mobile.php/?s=Game/open_game/game_id/25",
					),
					array(
						"name"=>"心动女友",
						"type"=>"view",
						"url"=>"http://uuyu.com/mobile.php/?s=Game/open_game/game_id/2",
					),
					array(
						"name"=>"侠客行",
						"type"=>"view",
						"url"=>"http://uuyu.com/mobile.php/?s=Game/open_game/game_id/10",
					),
					array(
						"name"=>"花儿消消乐",
						"type"=>"view",
						"url"=>"http://uuyu.com/mobile.php/?s=Game/open_game/game_id/17",
					),
				)
			),
			array(
				"name"=>"最新福利",
				"sub_button"=>array(
					array(
						"name"=>"游戏礼包",
						"type"=>"click",
						"key"=>"GAME_LIBAO",
					),
					array(
						"name"=>"新户红包",
						"type"=>"click",
						"key"=>"GAME_HONGBAO",
					),
				)
			),
			array(
				"name"=>"客服",
				"sub_button"=>array(
					array(
						"name"=>"联系我们",
						"type"=>"click",
						"key"=>"GAME_ADD",
					),
					array(
						"name"=>"合作招商",
						"type"=>"click",
						"key"=>"GAME_COMMERCE",
					),
				)
			)
        );
		
		$oauth->menuCreate($postArr);
       
    }

	
	/* 关注自动回复查询 */
	protected function getSubscrib(){
		$sub=M('replay_subscrib')->field('type,content,news')->find();
		if($sub['type']==='0'){
			$arr['type']=0;
			$arr['Content']=$sub['content'];
			return $arr;
		}elseif($sub['type']==='1'){
			$idarr=explode(',',$sub['news']);
			$content=array(
				'type'=>1,
				'ArticleCount'=>count($idarr),
				'Articles'=>array(
				),
			);
			
			if(count($idarr) <= 8 && count($idarr) >= 1){
				
				for($i=0;$i < count($idarr);$i++){
					$item=M('replay_item')->field('title,description,picurl,url')->where(array('id'=>$idarr[$i]))->find();
					
					$items['item']['Title']=$item['title'];
					$items['item']['Description']=$item['description'];
					$items['item']['PicUrl']=$item['picurl'];
					$items['item']['Url']=$item['url'];
					array_splice($content['Articles'],0,0,$items);
				}
				return $content;
				
			}else{
				throw new \Exception('图文消息不能查过8条！');
			}
			
		}
		
	}
	
	
	/*关键字回复查询*/
	protected function getKeyWord($key){
		
		$map['keyword']=array('like','%'.$key.'%');
		
		$res=M('keyword_replay')->where($map)->field('title,description,picurl,url')->find();
		
		if($res){
			$arr['ArticleCount']=1;
			$arr['Articles']['item']['Title']=$res['title'];
			$arr['Articles']['item']['Description']=$res['description'];
			$arr['Articles']['item']['PicUrl']=$res['picurl'];
			$arr['Articles']['item']['Url']=$res['url'];
			
			return $arr;
		}else{
			
			return ;
		}
		
	}
	
	
	/* 菜单点击事件 */
	protected  function getMenuevent($eventKey,$openid){
		
		/* 判断是否是红包请求 */
		if($eventKey=='GAME_HONGBAO'){
			
			$ckuser=$this->checkUser($openid);
			$content=array('Content'=>$ckuser);
			return	$content;
			
		}else{
			$res=M('event')->where(array('eventkey'=>$eventKey))->field('msgtype,msgcontent')->find();
			if($res){
				/*文本0图文1链接3 */
				if($res['msgtype']==='1'){
					$idarr=explode(',',$res['msgcontent']);
					$content=array(
						'type'=>1,
						'ArticleCount'=>count($idarr),
						'Articles'=>array(
						),
					);
					if(count($idarr) <= 9 && count($idarr) >= 1){
						for($j=0;$j<count($idarr);$j++){
							$item=M('replay_item')->field('title,description,picurl,url')->where(array('id'=>$idarr[$j]))->find();
							$items['item']['Title']=$item['title'];
							$items['item']['Description']=$item['description'];
							$items['item']['PicUrl']=$item['picurl'];
							$items['item']['Url']=$item['url'];
							array_splice($content['Articles'],0,0,$items);
						}
						
						return $content;
					}else{
						throw new \Exception('图文消息不能查过9条！');
					}
					
				}else{
					/* 图文和连接方法相同 */
					$content['Content']=$res['msgcontent'];
					return $content;
				}
				
			}else{
				return ;
			}
		}
	}
	
	/*检查用户是否领取过红包*/
	protected  function checkUser($openid){
		/* 1查表用户存在否，不存在则入库，存在则查看是否领过红包 */
		$cktab=M('hongbao')->where(array('openid'=>$openid))->find();
		
		/* 设定红包金额 */
		$hbarr=array(1.01,1.02,1.03,1.04,1.05,1.07,1.08,1.09,1.10,1.11,1.12,1.13,1.14,1.15,1.16,1.17,1.18,1.19,2);
		$rand_keys = array_rand ($hbarr, 1);
		$setNum=$hbarr[$rand_keys]; 
		$num=$setNum*100;
		$data['money']=$setNum;
		$data['status']=1;
		
		if(!empty($cktab)){
			/* 如果未领红包，则可以领取 */
			if($cktab['money']=='0' && $cktab['status']=='0'){
				require_once APP_PATH."/wxtool/WxHongbao.php";
				$hongbao = new \wxtool\WxHongbao();
				$params=array("nick_name"=>'友友互娱',
						"send_name"=>'友友互娱(武汉)信息技术有限公司',
						"re_openid"=>$openid,
						"wishing"=>'感谢您的关注，友友游戏中心为您提供丰富好玩的游戏！',
						"act_name"=>'新用户关注领红包',
						"remark"=>'领取您的红包',
						"total_amount"=>$num,
				);
				$res =  $hongbao->send($params);
				$con=json_decode( json_encode($res),true);
				if($con['result_code']=='SUCCESS'){
					
					//更新数据库
					$uphb=M('hongbao')->where(array('openid'=>$openid))->save($data);
					if($uphb){
						$echo='红包已发送，请点击拆开红包！';
						return $echo;
					}
				}else{
					
					$echo='系统繁忙，请稍后再试1！';
					return $echo;
				}
			}else{
				
				$echo='您已经领取过红包啦！';
				return $echo;
			}
			
		}else{
			
			/* 新用户发红包将用户入库 */
			require_once APP_PATH."/wxtool/WxHongbao.php";
			$hongbao = new \wxtool\WxHongbao();
			$params=array("nick_name"=>'友友互娱',
					"send_name"=>'友友互娱游戏中心',
					"re_openid"=>$openid,
					"wishing"=>'为您提供丰富好玩的游戏！',
					"act_name"=>'新用户关注领红包',
					"remark"=>'领取您的红包',
					"total_amount"=>$num,
			);
			$res =  $hongbao->send($params);
			$con=json_decode( json_encode($res),true);
			
			if($con['result_code']=='SUCCESS'){
				//更新数据库
				$data['openid']=$openid;
				$addUser=M('hongbao')->data($data)->add();
				
				if($addUser){
					$echo='红包已发送，请点击拆开红包！';
					return $echo;
				}
				
			}else{
				$echo='系统繁忙，请稍后再试2';
				return $echo;
			}
		}
		
	}
    
	
	
	
	
}





