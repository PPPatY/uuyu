<?php
namespace Mobile\Widget;
use Think\Controller;
class SuspensionWidget extends Controller {

	public function index() {
        $config = api('Config/lists');
        C($config); //添加配置
		$uid=session("user_auth.user_id");
        if($uid){
            $user=D('User')->field('account,nickname,phone,login_time')->where(array('id'=>$uid))->find();
        }else{
            $user = '';
        }
        //最近在玩
        $user_play = $this->user_play($uid);

        //热门游戏
        $map['game_status']=1;
        $map['tab_game.recommend_status']=2;
        $model=array(
            'field'=>'tab_game.*',
            'model'=>'Game',
            'order'=>'sort desc',
            );
        $hot=$this->showgame($model,$map);
        //当前游戏礼包
        $gift = $this->game_gift($_REQUEST['game_id']);

        //其他游戏礼包
        $othergift = $this->game_gift($_REQUEST['game_id'],1);
        $co = count($othergift);
        if($co>8){
        	$otherglimit=8;
        }else{
        	$otherglimit=$co;
        }
        $gift_keys=array_rand($othergift,$otherglimit);
		foreach ($gift_keys as $val) {
    		$othergift[]=$othergift[$val];
    	}

        //随机游戏
        $sjmap['game_status']=1;
        $sjmap['tab_game.id']=array('neq',$_REQUEST['game_id']);
        $sjmodel=array(
            'field'=>'tab_game.*',
            'model'=>'Game',
            'order'=>'sort desc',
            );
        $sjgame=$this->showgame($sjmodel,$sjmap);
        $sjco = count($sjgame);
        if($sjco>3){
            $sjlimit=3;
        }else{
            $sjlimit=$sjco;
        }
        $sjgame_keys=array_rand($sjgame,$sjlimit);
        foreach ($sjgame_keys as $val) {
            $sjkgame[]=$sjgame[$val];
        }

        $this->assign("headpic",session("wechat_token.headimgurl"));
        $this->assign('user',$user);
        $this->assign('user_play',$user_play);
        $this->assign('hot',$hot);
        $this->assign('gift',$gift);
        $this->assign('sjgame',$sjkgame);
        $this->assign('othergift',$othergift);
		$this->display('Suspension/index');
	}



	//最近在玩
	public function user_play($uid='',$limit=4){
		switch ($uid) {
			case '':
				return false;
				break;
		}
		$map['user_id'] = $uid;
		$map['game_status'] = 1;
    	$userplay = M('User_play as up','tab_')
    	           ->field('max(up.id),user_id,game_id,up.game_name,icon,short')
    	           ->join('tab_game on up.game_id=tab_game.id')
    	           ->where($map)
    	           ->limit($limit)
    	           ->group('game_id')
    	           ->order('play_time desc')
    	           ->select();
    	return $userplay;
	}
	//显示游戏
	public function showgame($model,$map){
        $data=M($model['model'],'tab_')
            ->field($model['field'])
            ->where($map)
            ->order($model['order'])
            ->join($model['join'],'left')
            ->join($model['join2'],'left')
            ->limit($model['limit'])
            ->group('tab_game.id')
            ->select();
        return $data;
    }
    //游戏礼包
    public function game_gift($game_id='',$type='') {
		$m['name'] = "giftbag";
		$m['map'] = array("status"=>1);
		if($type){
			$m['map'] = array("tab_giftbag.game_id"=>array('neq',$game_id));
		}else{
			$m['map'] = array("tab_giftbag.game_id"=>$game_id);
		}
		$m['map']['end_time']=array(array('gt',time()),array('eq',0),'or');
		$m['map']['start_time']=array('lt',time());
		$data = $this->bind_list($m);
		foreach ($data as $key => $value) {
			$return = gift_recorded($value['game_id'],$value['id']);
			$data[$key]['allcount_novice']=$return['all'];
			$data[$key]['wnovice']=$return['wei'];
			if(!$data[$key]['allcount_novice']){
				unset($data[$key]);
			}
		}
		return $data;
	}
	protected function bind_list($m=null) {
		$model = M($m['name'],'tab_');
		$data  = $model
				->field("tab_giftbag.*,tab_game.icon")
				->join("left join tab_game on tab_giftbag.game_id = tab_game.id")
				->where($m['map'])
				->order('start_time desc')
				->select();
		return $data;
	}
}
