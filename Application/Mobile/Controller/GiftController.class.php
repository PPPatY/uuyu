<?php
namespace Mobile\Controller;
use Think\Controller;
use Common\Model\GiftbagModel;
use User\Api\MemberApi;
class GiftController extends BaseController {
	
	public function index($rec_status=1) {
		$users = $this->is_login();
   		$giftbgmodel = new GiftbagModel();
   		$user = is_login();
   		if(is_cache()&&S('gift_data'.$rec_status)){
   			$gamegift=S('gift_data'.$rec_status);
   		}else{
   			$gamegift = $giftbgmodel->getGameGiftLists($rec_status,$user);
   			if(is_cache()){
   				S('gift_data'.$rec_status,$gamegift);
   			}
   		}
   		$allgamegift = $giftbgmodel->getGameGiftLists(false,$user);
   		if(!IS_AJAX){
			$this->assign("data",$gamegift);
			$this->assign("alldata",$allgamegift);
			$this->display();
   		}else{
   			$this->ajaxReturn($gamegift);
   		}
	}
	public function giftdetail($gift_id) {
		$users = $this->is_login();
   		$giftbgmodel = new GiftbagModel();
   		$user = is_login();
		$data = $giftbgmodel->getDetail($gift_id,$user);
		if(!$data){
			$this->ajaxReturn(array('code'=>0,'msg'=>'礼包不存在'));
		}
		$this->assign("data",$data);
		$this->display();
	}
	public function getgift($gameid,$giftid) {
        $users = $this->is_login();	
        if($users) {
       		$giftbgmodel = new GiftbagModel();
       		$gamegift = $giftbgmodel->getGift($giftid,$users);
       		$this->ajaxReturn($gamegift);
        }else{
            $this->ajaxReturn(array('code'=>'0','msg'=>'您还未登陆，请登陆后领取'));
        }
	}
}