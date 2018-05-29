<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class GameController extends ThinkController {
    const model_name = 'game';

    /**
    *游戏信息列表
    */
    public function lists(){
        if(isset($_REQUEST['game_type_name'])){
            if($_REQUEST['game_type_name']=='全部'){
                unset($_REQUEST['game_type_name']);
            }else{
                $extend['game_type_name'] = $_REQUEST['game_type_name'];
                unset($_REQUEST['game_type_name']);
            }
        }
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $extend['game_name'] = $_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['recommend_status'])){
            if($_REQUEST['recommend_status']==''){
                unset($_REQUEST['recommend_status']);
            }else{
                $extend['recommend_status'] = array('like','%'.$_REQUEST['recommend_status'].'%');
                unset($_REQUEST['recommend_status']);
            }
        }
        if(isset($_REQUEST['game_type_name'])){
            if($_REQUEST['game_type_name']=='全部'){
                unset($_REQUEST['game_type_name']);
            }else{
                $extend['game_type_name'] = $_REQUEST['game_type_name'];
                unset($_REQUEST['game_type_name']);
            }
        }
        
        if(isset($_REQUEST['game_appid'])){
            $extend['game_appid'] = array('like','%'.$_REQUEST['game_appid'].'%');
            unset($_REQUEST['game_appid']);
        }
        $extend['order']="sort desc,id desc ";
        // $extend['type'] = 1;
        $extend['for_show_pic_list']='icon';//列表显示图片
        parent::lists(self::model_name,$_GET["p"],$extend);
    }

    /**
     *  第三方游戏添加
     */
    public function third_add(){

        if(IS_POST){
            // $_POST['recommend_status']=implode(',',$_POST['recommend_status']);
            $game   =   D(self::model_name);//M('$this->$model_name','tab_');
            $_POST['discount'] ==''?$_POST['discount'] = 10:$_POST['discount'];
            $_POST['developers']       = trim($_POST['developers']);
           // $_POST['login_notify_url'] = trim($_POST['login_notify_url']);
           // $_POST['pay_notify_url']   = trim($_POST['pay_notify_url']);
          //  $_POST['game_key']         = trim($_POST['game_key']);
           // $_POST['game_pay_appid']   = trim($_POST['game_pay_appid']);
            if($_POST['game_type_id']==''){
                $_POST['game_type_name']='';
            }
            $pinyin = new \Think\Pinyin();
            $num=mb_strlen($_POST['game_name'],'UTF8');
            $short = '';
            for ($i=0; $i <$num ; $i++) {
                $str=mb_substr( $_POST['game_name'], $i, $i+1, 'UTF8');
                $short.=$pinyin->getFirstChar($str);
            }
            $_POST['short']=$short;
            $res = $game->update($_POST);
            if(!$res){
                $this->error($game->getError());
            }else{
                $this->success($res['id']?'更新成功':'新增成功',U('third_list'));
            }
        }
        else{
            $this->display();
        }

    }
    /**
     *   第三方游戏编辑
     */
    public function third_edit($id=null){
        if(IS_POST){
            $game   =   D(self::model_name);//M('$this->$model_name','tab_');
            $_POST['developers']       = trim($_POST['developers']);
            $_POST['discount'] ==''?$_POST['discount'] = 10:$_POST['discount'];
            if($_POST['game_type_id']==''){
                $_POST['game_type_name']='';
            }
            $pinyin = new \Think\Pinyin();
            $num=mb_strlen($_POST['game_name'],'UTF8');
            $short = '';
            for ($i=0; $i <$num ; $i++) {
                $str=mb_substr( $_POST['game_name'], $i, $i+1, 'UTF8');
                $short.=$pinyin->getFirstChar($str);
            }
            $_POST['short']=$short;
            $game_id['game_id'] = $_POST['id'];
            $game_name['game_name'] = $_POST['game_name'];
            $reslut = M('rebate','tab_')
                ->where($game_id)
                ->data($game_name)
                ->save();
            $reslut = M('spend','tab_')
                ->where($game_id)
                ->data($game_name)
                ->save();

            $res = $game->update();
            if(!$res){
                $this->error($game->getError());
            }else{
                $this->success($res['id']?'更新成功':'新增成功',U('third_list'));
            }
        }
        else{
            $id || $this->error('id不能为空');
            $data = D(self::model_name)->detail($id);
            $data || $this->error('数据不存在！');
            $this->assign('data', $data);
            $this->meta_title = '编辑游戏';
            $this->display();
        }
    }

    /**
     *第三方游戏信息列表
     */
/*     public function third_list(){
        if(isset($_REQUEST['game_type_name'])){
            if($_REQUEST['game_type_name']=='全部'){
                unset($_REQUEST['game_type_name']);
            }else{
                $extend['game_type_name'] = $_REQUEST['game_type_name'];
                unset($_REQUEST['game_type_name']);
            }
        }
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $extend['game_name'] = $_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['recommend_status'])){
            if($_REQUEST['recommend_status']==''){
                unset($_REQUEST['recommend_status']);
            }else{
                $extend['recommend_status'] = $_REQUEST['recommend_status'];
                unset($_REQUEST['recommend_status']);
            }
        }
        if(isset($_REQUEST['game_type_name'])){
            if($_REQUEST['game_type_name']=='全部'){
                unset($_REQUEST['game_type_name']);
            }else{
                $extend['game_type_name'] = $_REQUEST['game_type_name'];
                unset($_REQUEST['game_type_name']);
            }
        }

        if(isset($_REQUEST['game_appid'])){
            $extend['game_appid'] = array('like','%'.$_REQUEST['game_appid'].'%');
            unset($_REQUEST['game_appid']);
        }
        $extend['order']="sort desc,id desc ";
        $extend['type'] = 0;
        $extend['for_show_pic_list']='icon';//列表显示图片
        parent::lists(self::model_name,$_GET["p"],$extend);
    } */

    /**
     * 第三方游戏删除
     */
    public function third_del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::remove($model["id"],'Set',$ids);
    }

    /**
    *游戏原包列表
    */
    public function source(){
        $extend = array('field_time'=>'create_time');
        parent::lists('Source',$_GET["p"],$extend);
    }
    /**
    *游戏属性
    */
    public function attribute(){
        $this->display();
    }
    /**
    *游戏更新列表
    */
    public function update(){
        parent::lists('Update',$_GET["p"]);
    }

    /**
    *添加游戏原包
    */
    public function add_source(){
        if(IS_POST){
            if(empty($_POST['game_id']) || empty($_POST['file_type'])){
                $this->error('游戏名称或类型不能为空');
            }
            $map['game_id']=$_POST['game_id'];
            $map['file_type'] = $_POST['file_type'];
            $d = D('Source')->where($map)->find();
            $source = A('Source','Event');
            if(empty($d)){
                $source->add_source();
            }
            else{
                $source->update_source($d['id']);
            }
        }
        else{

            $this->display();
        }
    }

    /**
    *删除原包
    */
    public function del_game_source($model = null, $ids=null){
        $source = D("Source");
        $id = array_unique((array)$ids);
        $map = array('id' => array('in', $id) );
        $list = $source->where($map)->select();
        foreach ($list as $key => $value) {
            $file_url = APP_ROOT.$value['file_url'];
            unlink($file_url);
        }
        $model = M('Model')->getByName("source"); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids,"tab_game_");
    }

    public function add(){

    	if(IS_POST){
            $_POST['recommend_status']=implode(',',$_POST['recommend_status']);
            $_POST['introduction']=str_replace(array("\r\n", "\r", "\n"), "~~", $_POST['introduction']);
    		$game   =   D(self::model_name);//M('$this->$model_name','tab_');
            $_POST['discount'] ==''?$_POST['discount'] = 10:$_POST['discount'];
            $_POST['developers']       = trim($_POST['developers']);
            $_POST['login_notify_url'] = trim($_POST['login_notify_url']);
            $_POST['pay_notify_url']   = trim($_POST['pay_notify_url']);
            $_POST['game_key']         = trim($_POST['game_key']);
            $_POST['game_pay_appid']   = trim($_POST['game_pay_appid']);
            if($_POST['game_type_id']==''){
                $_POST['game_type_name']='';
            }
            $pinyin = new \Think\Pinyin();
            $num=mb_strlen($_POST['game_name'],'UTF8');
            $short = '';
            for ($i=0; $i <$num ; $i++) { 
                $str=mb_substr( $_POST['game_name'], $i, $i+1, 'UTF8');
                $short.=$pinyin->getFirstChar($str);
            }
            $_POST['short']=$short;
	        $res = $game->update($_POST);  
	        if(!$res){
	            $this->error($game->getError());
	        }else{
	            $this->success($res['id']?'更新成功':'新增成功',U('lists'));
	        }
    	}
    	else{
    		$this->display();
    	}
    	
    }

    public function edit($id=null){
        if(IS_POST){
            $_POST['recommend_status']=implode(',',$_POST['recommend_status']);
            $_POST['introduction']=str_replace(array("\r\n", "\r", "\n"), "~~", $_POST['introduction']);
            $game   =   D(self::model_name);//M('$this->$model_name','tab_');
            $_POST['developers']       = trim($_POST['developers']);
            $_POST['discount'] ==''?$_POST['discount'] = 10:$_POST['discount'];
            $_POST['login_notify_url'] = trim($_POST['login_notify_url']);
            $_POST['pay_notify_url']   = trim($_POST['pay_notify_url']);
            $_POST['game_key']         = trim($_POST['game_key']);
            $_POST['game_pay_appid']   = trim($_POST['game_pay_appid']);   
            if($_POST['game_type_id']==''){
                $_POST['game_type_name']='';
            }
            $pinyin = new \Think\Pinyin();
            $num=mb_strlen($_POST['game_name'],'UTF8');
            $short = '';
            for ($i=0; $i <$num ; $i++) { 
                $str=mb_substr( $_POST['game_name'], $i, $i+1, 'UTF8'); 
                $short.=$pinyin->getFirstChar($str);  
            }
            $_POST['short']=$short;
            $game_id['game_id'] = $_POST['id'];
            $game_name['game_name'] = $_POST['game_name'];
            $reslut = M('rebate','tab_')
            ->where($game_id)
            ->data($game_name)
            ->save();
            $reslut = M('spend','tab_')
            ->where($game_id)
            ->data($game_name)
            ->save();
            
            $res = $game->update();  
            if(!$res){
                $this->error($game->getError());
            }else{
                $this->success($res['id']?'更新成功':'新增成功',U('lists'));
            }
        }
        else{
            $id || $this->error('id不能为空');
            $data = D(self::model_name)->detail($id);
            $data || $this->error('数据不存在！');
            $data['recommend_status']=explode(',',$data['recommend_status']);
            $this->assign('data', $data);
            $this->meta_title = '游戏列表';
            $this->display();
        }
    }

    public function set_status($model='Game'){
        parent::set_status($model);
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::remove($model["id"],'Set',$ids);
    }
    //开放类型
    public function openlist(){
        $extend = array(
        );
        parent::lists("opentype",$_GET["p"],$extend);
    }
    //新增开放类型
    public function addopen(){
        if(IS_POST){
            $game=D("opentype");
        if($game->create()&&$game->add()){
            $this->success("添加成功",U('openlist'));
        }else{
            $this->error("添加失败",U('openlist'));
        }
        }else{
            $this->display();
        }
        
    }
    //编辑开放类型
    public function editopen($ids=null){
          $game=D("opentype");
        if(IS_POST){
        if($game->create()&&$game->save()){
             $this->success("修改成功",U('openlist'));
        }else{
           $this->error("修改失败",U('openlist'));
        }
        }else{  
         $map['id']=$ids;
            $date=$game->where($map)->find();
            $this->assign("data",$date);
            $this->display();
        }
    }
    //删除开放类型
    public function delopen($model = null, $ids=null){
       $model = M('Model')->getByName("opentype"); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }
    /**
     * 文档排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        //获取左边菜单$this->getMenus()
       
        if(IS_GET){
            $map['status'] = 1;
            $list = D('Game')->where($map)->field('id,game_name')->order('sort DESC, id DESC')->select();

            $this->assign('list', $list);
            $this->meta_title = '游戏排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = array_reverse(explode(',', $ids));
            foreach ($ids as $key=>$value){
                $res = D('Game')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }

    public function chgculumn(){
        $res = D('Game')->chgculumn();
        if($res==1){
            $data['status'] = 1;
            $data['msg'] = '修改成功';
        }elseif($res==2){
            $data['status'] = 2;
            $data['msg'] = '缺少字段';
        }elseif($res==-1){
            $data['status'] = 0;
            $data['msg'] = '缺少字段';
        }else{
            $data['status'] = 0;
            $data['msg'] = '修改失败';
        }
        $this->ajaxReturn($data);
    }

}
