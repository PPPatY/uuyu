<?php
namespace Media\Controller;
use Think\Controller;
use Common\Model\GameModel;
use Common\Model\DocumentModel;
use Common\Model\GiftbagModel;
class SearchController extends BaseController {
	//搜索首页 
	public function index() {
		$hotmap['recommend_status'] = 2;
        $model = new GameModel();
        $hotgame = $model->getGameLists($hotmap,'g.sort desc,g.id desc',1,9);
        $allgame = $model->getGameLists($map,'g.sort desc,g.id desc',1,1000);
        $this->assign('titlegame',$hotgame[0]);
        $this->assign('allgame',$allgame);
        $this->assign('hotgame',$hotgame);
		$this->display();
	}
	
	// 异步返回
	public function search($keyword='') {
		empty($keyword)&&$this->ajaxReturn(array('code'=>-1,'msg'=>'未输入任何字符'));
		$gamemod = new GameModel();
		$gamemap['g.game_name'] = array('like','%'.$keyword.'%');
		$game=$gamemod->searchgame($gamemap);

		if(!empty($game)){
			$docmodel = new DocumentModel();
			$docmap['d.belong_game'] = array('in',array_column($game,'id'));
	        $article = $docmodel->searchArticle(array('in',array('wap_huodong','wap_gg')),$docmap,100);
	        $article = $article?$article:'';

			$giftmodel = new GiftbagModel();
			$giftgame = array('in',implode(',',array_column($game,'id')));
			$gift = $giftmodel->getGiftLists($giftgame,1,100);
	        $gift = $gift?$gift:'';
			$this->ajaxReturn(array('code'=>1,'msg'=>'搜索成功','url'=>U('index',array('kw'=>$keyword)),'data'=>array('game'=>$game,'gift'=>$gift,'article'=>$article)));
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'搜索失败','data'=>''));
		}
	}
	public function search_list(){
		$this->display();
	}
}