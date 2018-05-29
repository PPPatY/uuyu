<?php
namespace App\Controller;

use Admin\Model\AdvModel;
use Common\Model\PointShopModel;
use Common\Model\PointShopRecordModel;
use Common\Model\UserModel;
use Common\Model\PointTypeModel;
use Common\Model\PointRecordModel;

class PointShopController extends BaseController{

    /**
     * 商品列表和其它
     */
    public function mall($type=0,$short=0,$p=1) {
        $shopmodel = new PointShopModel();

        if($type!=0){
            $map['good_type'] =$type;
        }
        if($short){
            for ($i=0; $i <strlen($short) ; $i++) {
                if($i){
                    $shortarr.=" or short_name like '".mb_substr($short,$i,1,'utf-8')."%'";
                }else{
                    $shortarr=" short_name like '".mb_substr($short,$i,1,'utf-8')."%'";
                }
                $map['_string'] = $shortarr;
            }
        }
        $map['status'] = 1;
        $map['number'] = array('gt',0);
        $data = $shopmodel->getLists($map,"create_time desc",$p);
        if (empty($data['data'])){
            $data['data'] = [];
        }

        $this->set_message(1,'success',$data['data']);
    }

    /**
     * 商品详情和其它
     */
    public function mall_detail($gift_id,$token=''){
        if (!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
        }

        $model = new PointShopModel();
        $data = $model->goodsDetail($gift_id,$user_id);
        if (!empty($token)){
            $a = json_decode($data['shop_address'],true);
            $data['consignee'] = !empty($a['consignee'])?$a['consignee']:"";
            $data['consignee_phone'] = !empty($a['consignee_phone'])?$a['consignee_phone']:"";
            $data['consignee_address'] = !empty($a['consignee_address'])?$a['consignee_address']:"";
        }
        $data['PC_SET_SERVER_QQ'] = C('APP_QQ');
        if(!empty($data)){
            $this->set_message(200,'success',$data);
        }else{
            $this->set_message(1033,'暂无数据');
        }
    }

    /**
     * 去除换行符
     */
    function ttt($str){
        $order = array("\r\n", "\n", "\r");
        $replace = '';
        $str = str_replace($order, $replace, $str);
        return $str;
    }

    /**
     * 签到
     */
    public function sign($token){
        $this->auth($token);

        $usermodel = new UserModel();
        $pointtype = new PointTypeModel();

        $userpoint = $usermodel->getUserOneParam(USER_ID,'shop_point');
        $lgmap['pt.key'] = 'sign_in';

        if(USER_ID){
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }else{
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }

        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin);

        if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||empty($loginpont[0]['day'])){
            $signday = 1;
        }else{
            $signday = $loginpont[0]['day']+1;
        }
        if(empty($loginpont[0]['user_id'])){
            $issignin = 0;//今日是否签到
        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
            $issignin = 1;
        }else{
            $issignin = 0;
        }
        $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];

        if ($issignin == 0){
            $this->set_message(200,'success',$addpoint);
        }else{
            $this->set_message(1118,'您今天已经签到过了，明天再来吧~');
        }
    }

    public function sign_info(){
        $url = "http://".$_SERVER['HTTP_HOST']."/mobile.php/PointShop/mall_sign";
        $this->set_message(200,'success',$url);
    }

    public function sign_in($token){
        $this->auth($token);

        $model = new PointTypeModel();
        $res = $model->userGetPoint(USER_ID,strtolower('sign_in'));
        if($res==1){
            $this->set_message(200,'success','');
            $this->ajaxReturn(array('status'=>200,'msg'=>'签到成功'));
        }elseif($res==-1){
            $this->set_message(1119,'签到功能已关闭');
        }elseif($res==-2){
            $this->set_message(1120,'已签到，不要重复签到');
        }else{
            $this->set_message(1121,'签到失败');
        }
    }


    /**
     * 积分任务
     */
    public function mall_integral($token=''){
        if($token){
            $this->auth($token);
        }else{
            define("USER_ID",0);
        }
        $pointtype = new PointTypeModel();
        $usermodel = new UserModel();
        $lgmap['pt.key'] = 'sign_in';
        if(USER_ID){
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }else{
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }
        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin);
        if(empty($loginpont[0]['user_id'])){
            $issignin = 0;//今日是否签到
        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
            $issignin = 1;
        }else{
            $issignin = 0;
            if($loginpont[0]['ct']!=date("Y-m-d",strtotime("-1 day"))){
                $loginpont[0]['day'] = 0;
            }
        }
        if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||empty($loginpont[0]['day'])){
            $signday = 1;
            $tosignday = $signday+1;
        }else{
            $signday = $loginpont[0]['day']+1;
            $tosignday = $signday;
        }
        if($tosignday>=7){
            $tosignday = 1;
        }else{
            $tosignday = $tosignday;
        }
        $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];
        $topoint = 	$loginpont[0]['point']+($tosignday-1)*$loginpont[0]['time_of_day'];
        $list = $pointtype->where(['status'=>1])->order('id asc')->select();
        $newlist = array_reduce($list,function(&$newlist,$v){
            $newlist[$v['key']] = $v;
            return $newlist;
        });


        $bindphone = $usermodel->getUserOneParam(USER_ID,'phone');
        $newlist['bind_phone']['is_complete'] = $bindphone['phone']?1:0;

        $newlist['sign_in']['is_complete'] = $issignin;
        $newlist['sign_in']['addpoint'] = $addpoint;
        $newlist['sign_in']['topoint'] = $topoint;

        $firstspend = M('Spend','tab_')->where(['pay_time'=>total(1,false),'user_id'=>USER_ID,'game_id'=>['gt',0]])->find();
        $newlist['share_game']['is_complete'] = $firstspend?1:0;

        $newlist['share_article']['is_complete'] = 0;

        $newlist['user_point'] = $usermodel->getUserOneParam(USER_ID,'shop_point');

        $ddd = $newlist['sign_in']['is_complete'] == 1? "明日" : "今日";
        $newlist['sign_in']['remark'] = $ddd.$newlist['sign_in']['remark'];
        $newlist['bind_phone']['point'] = $newlist['bind_phone']['point'];
        $newlist['share_game']['point'] = $newlist['share_game']['point'];

        $this->set_message(200,'success',$newlist);

    }


    /**
     * 积分记录
     */
    public function mall_record($token,$p=1,$row=10,$type=1){
        //type 为1时获取全部 2获取增加  3获取消费
        if ($type == 1){
            $row = round($row/2,0);
        }

        $this->auth($token);

        $model = new PointRecordModel();
        $map['user_id'] = USER_ID;
        $data = $model->getRecordLists2($map,$order="pr.create_time desc",$p,$row);

        $data['pointrecord'] = empty($data['pointrecord'])?"":$data['pointrecord'];
        $data['pointshoprecord'] = empty($data['pointshoprecord'])?"":$data['pointshoprecord'];
        $data['all'] = empty($data['all'])?"":$data['all'];

        switch ($type){
            case 1:
                $return = $data['all'];
                break;
            case 2:
                $return  = $data['pointrecord'];
                break;
            case 3:
                $return =  $data['pointshoprecord'];
                break;
            default:
                $return = $data['all'];
                break;
        }

        if (empty($return)){
            $return = [];
        }

        $this->set_message(200,'',$return);
    }

    /**
     * 积分规则
     */
    public function mall_rule(){
        $data = array();
        $data = M('PointRule','tab_')->field('id,title,content')->where(array('status'=>1))->order('sort desc')->select();
        $this->set_message(1,'success',$data);
    }

    /**
     * 轮播数据
     */
    public function mall_play(){

        $recordmodel = new PointShopRecordModel();
        $record = $recordmodel->getLists('',"sr.create_time desc,sr.id desc",1,50);
        $recorddata = $record['data']==''?[]:$record['data'];

        $this->set_message(200,'success',$recorddata);
    }


    /**
     * 购买商品
     */
    public function mall_buy($token,$good_id,$num=1){
        $this->auth($token);
        $model = new PointShopModel();
        $res = $model->shopBuy(USER_ID,$good_id,$num);

        switch ($res) {

            case 0:
                $this->set_message(1060,'购买失败');
                break;

            case -3:
                $this->set_message(1108,'积分不足');
                break;

            case -4:
                $this->set_message(1109,"库存不足");
                break;

            case 1:
                $this->set_message(200,'success',[]);
                break;

            default:
                $this->set_message(200,'success',$res[0]);//要求虚拟物品兑换一个
                break;
        }
    }



}