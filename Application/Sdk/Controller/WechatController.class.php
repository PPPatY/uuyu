<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Sdk\Controller;

use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WechatController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //调试
        try{
            
            $appid = C('wechat.appid'); //AppID(应用ID)
            $token = C('wechat.token'); //微信后台填写的TOKEN
            $crypt = C('wechat.key'); //消息加密KEY（EncodingAESKey）
            $secret= C('wechat.appsecret');
            /* 加载微信SDK */
            $wechat = new Wechat($token, $appid, $crypt);
            /* 获取请求信息 */
            $data = $wechat->request();
            if($data && is_array($data)){
                //执行Demo
                $this->demo($wechat, $data);
                //记录微信推送过来的数据
                file_put_contents('./data.json', $wechatauth->accessToken);
                //$this->wechat_menu();
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
        
    }

    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        $wechatname=C('wechat.wechatname');
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                $msg = C('wechat.message');
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        //关注公众号,修改用户状态为已关注
                        $uid = str_replace('qrscene_','',$data['EventKey']);
                        $wx_openid = $data['FromUserName'];
                        //判断openid是否已经绑定其他账号
                        $is_sub = M('user','tab_')->where(['wx_openid'=>$wx_openid])->getField('id');
                        if(empty($is_sub)){//未绑定
                            M('user','tab_')->where(['id'=>$uid])->save(['is_wechat_subscribe'=>1,'wx_openid'=>$wx_openid]);
                        }else{
                            M('user','tab_')->where(['id'=>$is_sub])->save(['is_wechat_subscribe'=>1]);
                        }
                        //回复文字
                        $wechat->replyText($msg['first_msg']);
                        break;
                    case Wechat::MSG_EVENT_SCAN:
                        //关注公众号,修改用户状态为已关注
                        $uid = str_replace('qrscene_','',$data['EventKey']);
                        $wx_openid = $data['FromUserName'];
                        //判断openid是否已经绑定其他账号
                        $is_sub = M('user','tab_')->where(['wx_openid'=>$wx_openid])->getField('id');
                        if(empty($is_sub)){//未绑定
                            M('user','tab_')->where(['id'=>$uid])->save(['is_wechat_subscribe'=>1,'wx_openid'=>$wx_openid]);
                        }else{
                            M('user','tab_')->where(['id'=>$is_sub])->save(['is_wechat_subscribe'=>1]);
                        }
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        $wx_openid = $data['FromUserName'];
                        M('user','tab_')->where(['wx_openid'=>$wx_openid])->save(['is_wechat_subscribe'=>0]);
                        break;
                    case Wechat::MSG_EVENT_CLICK:
                        //click事件
                        if($data['EventKey']=='10000'){
                            $wechat->replyText('小朱-QQ:<a href="http://wpa.qq.com/msgrd?v=3&uin=3002989121&site=qq&menu=yes">3002989121</a>

电话：18905647853

合作方式：
联运：
   1. 渠道可以是拥有一定粉丝量的微信公众号或者其他APP。
   2. 对接平台SDK，或者走CPS，获得渠道链接以后，只需将生成的链接放到微信用户可以点击的地方（比如公众号底部菜单、粉丝群、微社区、图文消息等），就可以生效推广了。
发行：
   1.代发H5游戏，数百家合作渠道，秒接SDK，有H5游戏产品都可以推荐过来撩！
期待合作~');
                        }
                        break;
                    default:
                        $wechat->replyText($msg['default_msg']);
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {

                    case '图文':
                        $wechat->replyNewsOnce(
                            "全民创业蒙的就是你，来一盆冷水吧！",
                            "全民创业已经如火如荼，然而创业是一个非常自我的过程，它是一种生活方式的选择。从外部的推动有助于提高创业的存活率，但是未必能够提高创新的成功率。第一次创业的人，至少90%以上都会以失败而告终。创业成功者大部分年龄在30岁到38岁之间，而且创业成功最高的概率是第三次创业。", 
                            "http://www.topthink.com/topic/11991.html",
                            "http://yun.topthink.com/Uploads/Editor/2015-07-30/55b991cad4c48.jpg"
                        ); //回复单条图文消息
                        break;

                    case '多图文':
                        $news = array(
                            "全民创业蒙的就是你，来一盆冷水吧！",
                            "全民创业已经如火如荼，然而创业是一个非常自我的过程，它是一种生活方式的选择。从外部的推动有助于提高创业的存活率，但是未必能够提高创新的成功率。第一次创业的人，至少90%以上都会以失败而告终。创业成功者大部分年龄在30岁到38岁之间，而且创业成功最高的概率是第三次创业。", 
                            "http://www.topthink.com/topic/11991.html",
                            "http://yun.topthink.com/Uploads/Editor/2015-07-30/55b991cad4c48.jpg"
                        ); //回复单条图文消息
                        $wechat->replyNews($news, $news, $news, $news, $news);
                        break;
                    default:
                        $map['game_name']=array("like","%".$data['Content']."%");
                        $game = M("game","tab_")
                                ->field("tab_game.game_name,tab_game.introduction,tab_game.icon,tab_game_set.login_notify_url")
                                ->join("tab_game_set  on tab_game.id=tab_game_set.id")
                                ->where($map)
                                ->find();
                        if(!empty($game)){
                            $picture = M('Picture')->where(array('status'=>1))->getById($game['icon']);
                            $picture['path'] = $_SERVER['HTTP_HOST'].$picture['path'];
                            $_key = 'jCTVfkOz2nQPLBwYM2f1as4MtFTe9wm9';//游戏放提供
                            $data['uid']   = $uid;//uid同步平台uid唯一值,
                            $data['email'] = "xx";//同步平台账号
                            $data['t']     = time();
                            $data['sign']  = md5($data['uid']."&".$data['email']."&".$data['t']."&".$_key);     
                            $wechat->replyNewsOnce(
                                $game['game_name'],
                                $game['introduction'], 
                                $game['login_notify_url']."?".http_build_query($data),
                                "http://yun.topthink.com/Uploads/Editor/2015-07-30/55b991cad4c48.jpg"
                            ); //回复单条图文消息
                        }
                        $wechat->replyText("欢迎访问".$wechatname."公众平台！您输入的内容是：{$data['Content']}");
                        break;
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
                $filename = './Public/image.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'voice':
                $filename = './Public/voice.mp3';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'video':
                $filename    = './Public/video.mp4';
                $discription = array('title' => '视频标题', 'introduction' => '视频描述');
                $media       = $auth->materialAddMaterial($filename, $type, $discription);
                break;

            case 'thumb':
                $filename = './Public/music.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;
            default:
                return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }
        return $media['media_id'];
    }

    /**
    *微信 菜单设置
    */
    private function wechat_menu(){
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        $newmenu =  array(         
            array('type'=>'click','name'=>'测试菜单','key'=>'MENU_KEY_NEWS'),
            array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
            array('name'=>'菜单',"sub_button"=>array(
                array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
                array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
            )),                  
        );
        $data = $auth->menuCreate($newmenu);
        file_put_contents(dirname(__FILE__).'/data.json', json_encode($data));
    }
}
