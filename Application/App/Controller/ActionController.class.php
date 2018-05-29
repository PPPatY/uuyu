<?php
namespace App\Controller;

use Common\Model\GameModel;
use Common\Model\DocumentModel;
use Common\Model\GiftbagModel;
class ActionController extends BaseController{

    /**
     * 收藏与取消收藏 (单体)
     */
    public function change_collect_status($token,$game_id,$status){
        $this->auth($token,"");
        $gdata = M('Game','tab_')->find($game_id);
        if(empty($gdata)){
            $this->set_message(1041,'没有该游戏',"");
        }
        $map['user_id'] = USER_ID;
        $map['game_id'] = $game_id;
        $map['status'] = ['in','-1,1'];
        $data = M('user_behavior as ub','tab_')
            ->where($map)
            ->find();
        if($data){
            $save['status'] = $status==1?1:-1;
            $save['id'] = $data['id'];
            $save['update_time'] = time();
            $res = M('user_behavior','tab_')->save($save);
            if($res){
                $this->set_message(200,'success',"");
            }else{
                $this->set_message(1106,'收藏失败',"");
            }
        }else{
            $save['user_id'] = USER_ID;
            $save['game_id'] = $game_id;
            $save['status'] = $status==1?1:-1;
            $save['update_time'] = time();
            $save['create_time'] = $save['update_time'];
            $res = M('user_behavior','tab_')->add($save);
            if($res){
                $this->set_message(200,'success',"");
            }else{
                $this->set_message(1107,'取消收藏失败',"");
            }
        }
    }


    /**
     * 取消收藏 （群体）
     */
    public function cancel_collect($token,$game_id){
        $this->auth($token,"");

        $game_id = explode(',',$game_id);

        $where['user_id'] = USER_ID;
        $where['status'] = 1;
        $where['game_id'] = array('in',$game_id);

        $save['update_time'] = time();
        $save['status'] = -1;

        $res = M('user_behavior','tab_')
            ->where($where)
            ->save($save);
        if($res){
            $this->set_message(200,'success',"");
        }else{
            $this->set_message(1107,'取消收藏失败',"");
        }
    }


    /**
     * 搜索
     */
    public function search($keyword,$token=""){
        $gamemod = new GameModel();
        $gamemap['g.game_name'] = array('like','%'.$keyword.'%');
        $game=$gamemod->searchgame($gamemap);

        if(!empty($game)){
            $docmodel = new DocumentModel();
            $docmap['d.belong_game'] = array('in',array_column($game,'id'));
            $article = $docmodel->searchArticle(array('in',array('app_huodong','app_gg')),$docmap,100,$model='app');
            $article = $article?$article:[];

            $giftmodel = new GiftbagModel();
            $giftgame = array('in',implode(',',array_column($game,'id')));
            $data = $giftmodel->getGiftLists($giftgame,1,100);

            if($token){
                $this->auth($token);
                foreach ($data as $key => $value) {
                    $map['game_id'] = $data[$key]['game_id'];
                    $map['gift_id'] = $data[$key]['gift_id'];
                    $map['user_id'] = USER_ID;
                    $isget = M('gift_record','tab_')->where($map)->getField('id');
                    if(!empty($isget)){
                        $data[$key]['received'] = 1;
                    }else{
                        $data[$key]['received'] = 0;
                    }
                }
            }

            $gift = $data?$data:[];
            $return['game'] = $game;
            $return['article'] = $article;
            $return['gift'] = array_values($gift);
            $this->set_message(200,'success',$return);
        }else{
            $data['game'] = [];
            $data['article'] = [];
            $data['gift'] = [];
            $this->set_message(200,'success',$data);
        }
    }

    /**
     * 搜索热词
     */
    public function search_word(){

    }


    /**
     * 签到、文章分享
     * @ram int $group 状态分组
     * @param int $type  状态文字 1签到  2文章
     * @param int $article_id  type=2时  文章id
     * @author yyh
     */
    public function share($type=1,$article_id=''){
        switch ($type) {
            case 1:
                $data['title'] = "每日签到，不限量积分等你来领";
                $data['cover'] = "http://".$_SERVER['HTTP_HOST']."/Public/Mobile/images/invitate_btn_logo.png";
                $data['introduction'] = "每日签到，不限量积分等你来领！     积分当钱花，更有机会兑换神秘礼品！";
                $data['url'] = "http://".$_SERVER['HTTP_HOST']."/mobile.php/PointShop/mall_sign";
                break;
            case 2:
                if(!$article_id){
                    $this->set_message(1090,'参数错误',array());
                }
                $model = new DocumentModel();
                $article_data = $model->articleDetail($article_id);
                if($article_data===false){
                    $this->set_message(1046,'暂无文章',array());
                }
                $data['title'] = $article_data['title'];
                $data['cover'] = "http://".$_SERVER['HTTP_HOST']."/Public/Mobile/images/invitate_btn_logo.png";
                $data['introduction'] = $article_data['description']==''?'号外！号外！大新闻':$article_data['description'];
                $data['url'] = "http://".$_SERVER['HTTP_HOST']."/mobile.php/Article/detail/id/".$article_id;
                break;
        }

        $this->set_message(200,'success',$data);
    }


    /**
     * 删除我的足迹
     */
    public function delete_foot($token,$ids){
        $this->auth($token,'');
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        $model = new GameModel();
        $user = USER_ID;
        $map['id'] = array('in',$ids);
        $data = $model->optionBehavior($user,2,$map);
        if($data!=false){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1051,'删除失败','');
        }
    }
}
