<?php
namespace Admin\Controller;
use User\Api\PromoteApi;
use User\Api\UserApi;
use Org\XiguSDK\Xigu;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PromoteController extends ThinkController {

    const model_name = 'Promote';

    public function lists(){
        if(isset($_REQUEST['account'])){
            $map['account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }
        if(isset($_REQUEST['busier_id'])){
        	$map['busier_id']= $_REQUEST['busier_id'];
        	unset($_REQUEST['busier_id']);
        }
        if(isset($_REQUEST['status'])){
        	$map['status']= $_REQUEST['status'];
        	unset($_REQUEST['status']);
        }
        if(isset($_REQUEST['admin_id'])){
            if ($_REQUEST['admin_id']=="全部") {
                unset($_REQUEST['admin_id']);
            }else{
                $map['admin_id']=get_admin_id($_REQUEST['admin_id']);
                unset($_REQUEST['admin_id']);
            }  
        }
        if(isset($_REQUEST['is_union'])){
            if($_REQUEST['is_union']>0){
                $map['is_union']=array('gt',0);
            }else{
                $map['is_union']=0;
            }
            unset($_REQUEST['is_union']);
        }
        if (isset($_REQUEST['parent_id'])) {
            if ($_REQUEST['parent_id']=='全部') {
                unset($_REQUEST['parent_id']);
            }
            $zid=get_zi_promote_id($_REQUEST['parent_id']);
            if($zid){
                $zid=$zid.','.$_REQUEST['parent_id'];
            }else{
                $zid=$_REQUEST['parent_id'];
            }
            $map['id']=array('in',$zid);
            unset($_REQUEST['parent_id']);
        }
        if(I('promote_level') == 1){
            $map['parent_id'] = 0;
        }elseif(I('promote_level') == 2){
            $map['parent_id'] = ['neq',0];
        }
        $PROMOTE_AUTO_AUDIT = C('PROMOTE_AUTO_AUDIT');
        $this->assign('PROMOTE_AUTO_AUDIT',$PROMOTE_AUTO_AUDIT);
        parent::order_lists(self::model_name,$_GET["p"],$map);
    }

    public function add($account=null,$password=null,$second_pwd=null,$real_name=null,$email=null,$mobile_phone=null,$bank_name=null,$bank_card=null,$busier_id=null,$status=null){
        if(IS_POST){
        	$data=array('account'=>$account,'password'=>$password,'second_pwd'=>$second_pwd,'real_name'=>$real_name,'email'=>$email,'mobile_phone'=>$mobile_phone,'bank_name'=>$bank_name,'bank_card'=>$bank_card,'busier_id'=>$busier_id,'status'=>$status);
            $user = new PromoteApi();
            $res = $user->promote_add($data);
            if($data['busier_id'] > 0){
            	$busier = M('Busier','tab_')->where(array('id'=>$busier_id))->find();
            	if($busier['promote_user']){
            		$busier['promote_user'] = $busier['promote_user'].','.$res;
            	}else{
            		$busier['promote_user'] = $res;
            	}
            	M('Busier','tab_')->save($busier);
            }
            if($res>0){
                $this->success("添加成功",U('lists'));
            }
            else{
                $this->error($res);
            }
        }
        else{
        	$this->meta_title = "新增";
            $this->display();
        }
    }
    public function del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); 
        /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }
    //代充删除
    public function agent_del($model = null, $ids=null){
        $model = M('Model')->getByName('Agent'); 
        /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }
    public function edit($id=0){
		$id || $this->error('请选择要查看的用户！');
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        $data = array();
        if(IS_POST){
            $data = array(
                "id"         => $_POST['id'],
                "password"   => $_POST['password'],
                 "second_pwd"   => $_POST['second_pwd'],
                "status"     => $_POST['status'],
                "mark1"     => $_POST['mark1'],
                "mark2"     => $_POST['mark2'],
                "busier_id" => $_POST['busier_id']
            );
            $promote = M('Promote','tab_')->where(array('id'=>$data['id']))->find();
            $shiwu = M();
            $shiwu->startTrans();
            //商务专员修改
            if($data['busier_id']&&$promote['busier_id'] != $data['busier_id']){
            	$save_busier = $this->save_busier($data['busier_id'],$promote);
            }
            if($save_busier===false){
                $shiwu->rollback();
                $this->error('修改失败');
            }
            //一级推广员
            if(!$promote['parent_id']){
                $set_busier_id = set_busier_id($promote['id'],$data['busier_id']);
            }
            if($set_busier_id===false){
                $shiwu->rollback();
                $this->error('修改失败');
            }
            $pwd = trim($_POST['password']);
            $second_pwd = trim($_POST['second_pwd']);
            $use=new UserApi();
            $data['password']=think_ucenter_md5($pwd,UC_AUTH_KEY);
            $data['second_pwd']=think_ucenter_md5($second_pwd,UC_AUTH_KEY);
            if(empty($pwd)){unset($data['password']);}
            if(empty($second_pwd)){unset($data['second_pwd']);}
            $res=M("promote","tab_")->where(array("id"=>$_POST['id']))->save($data);
            $res2=M("promote","tab_")->where(array("parent_id"=>$_POST['id']))->find();
            
            if($res !== false){
                if (isset($res1)) {
                    if ($res1 !== false) {
                        $shiwu->commit();
                        $this->success('修改成功',U('lists'));
                    }else{
                        $this->error('修改失败');
                        $this->error('修改失败');
                    }
                }else{
                    $shiwu->commit();
                    $this->success('修改成功',U('lists'));
                }
                
            }
            else{
                $this->error('修改失败');
            }
        }
        else{
            $model = D('Promote');
            $data = $model->find($id);
            $data['bank_area']=explode(',',$data['bank_area']);
            $this->meta_title = "编辑";
            $this->assign('data',$data);
            $this->display();
        }
    }
    //修改推广员商务专员
    function save_busier($new,$promote){
        $busier = M('Busier','tab_')->where(array('id'=>$promote['busier_id']))->find();
        $oldbus = $busier['promote_user'];
        // $zi_promote_arr = explode(',',get_zi_promote_id($promote['id']));
        $zi_promote_arr[] = $promote['id'];
        $zi_promote_arr = array_unique($zi_promote_arr);
        foreach ($zi_promote_arr as $ke => $valu) {
            if($valu==0){
                unset($zi_promote_arr[$ke]);
            }
        }
        $buspidarr = array();
        if($busier['promote_user']){
            $buspidarr = explode(',', $busier['promote_user']);
        }
        foreach ($buspidarr as $key => $value) {
            foreach ($zi_promote_arr as $k => $v) {
                if($value==$v){
                    unset($buspidarr[$key]);
                }
            }
        }
        if($busier['id']>0){
            $newbus['id'] = $busier['id'];
            $newbus['promote_user'] = implode(',', $buspidarr);
        	$res = M('Busier','tab_')->save($newbus);
        }

    	$busierdata = M('Busier','tab_')->where(array('id'=>$new))->find();
    	//保存修改数据
    	if(empty($busierdata['promote_user'])){
    		$busierdata['promote_user'] = implode(',', $zi_promote_arr);
    	}else{
            $buspidarr = explode(',', $busierdata['promote_user']);
            foreach ($buspidarr as $key1 => $value1) {
                foreach ($zi_promote_arr as $k1 => $v1) {
                    if($value1!=$v1&&$v1>0){
                        $buspidarr[] = $v1;
                    }
                }
            }
    		$busierdata['promote_user'] = implode(',',array_unique($buspidarr));
    	}
    	return M('Busier','tab_')->save($busierdata);
    }
  //设置状态
    public function set_status($model='Promote'){
        if(isset($_REQUEST['model'])){
            $model=$_REQUEST['model'];
            unset($_REQUEST['model']);
        }
        $a=0;
        $map['id']=array('in',$_REQUEST['ids']);
        $set=M('promote','tab_')->where($map)->setField('status',$_REQUEST['status']);
        if($set!==false){
            if($_REQUEST['status']==1){
                $select=M('promote','tab_')->where($map)->select();
                foreach ($select as $key => $value) {
                    // $count=$this->send_sms($value['mobile_phone']);
                    if($count=="000000"){
                        $a++;
                    }
                }
                $this->success('操作成功,已通知'.$a.'人');
            }else{
                $this->success('操作成功');
            }
            
        }else{
            $this->success('操作失败');
        }
    }

     /**
    *短信发送
    */
    public function send_sms($phone)
    {
        /// 产生手机安全码并发送到手机且存到session
        $rand = rand(100000,999999);
        $xigu = new Xigu(C('sms_set.smtp'));
        $param = $rand.",".'1';
        $result = json_decode($xigu->sendSM(C('sms_set.smtp_account'),$phone,C('sms_set.smtp_notice_port'),$param),true); 
        $result['create_time'] = time();
        #TODO 短信验证数据 
        
        return$result['send_status']; // '000000'
    }

    //设置对账状态yyh
    public function set_check_status($model='Promote'){
        if(isset($_REQUEST['model'])){
            $model=$_REQUEST['model'];
            unset($_REQUEST['model']);
        }
        parent::set_status($model);
    }
    /**
    *渠道注册列表
    */
    public function ch_reg_list(){
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['fgame_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        $map['tab_user.promote_id'] = array("neq",0);
        if(isset($_REQUEST['promote_name'])){
            if($_REQUEST['promote_name']=='全部'){
                unset($_REQUEST['promote_name']);
            }else if($_REQUEST['promote_name']=='自然注册'){
                $map['tab_user.promote_id']=array("elt",0);
                unset($_REQUEST['promote_name']);
            }else{
                $map['tab_user.promote_id']=array('eq',get_promote_id($_REQUEST['promote_name']));
                unset($_REQUEST['promote_name']);
            }
        }
        if(isset($_REQUEST['parent_id'])){
            $allid=get_subordinate_promote(get_promote_id($_REQUEST['parent_id']));
            $allid[]=$_REQUEST['parent_id'];
            $map['tab_user.promote_account']=array('in',implode(',',$allid));
            unset($_REQUEST['parent_id']);
        }
         if(isset($_REQUEST['is_union'])){
            $map['tab_user.is_union']=$_REQUEST['is_union'];
            unset($_REQUEST['is_union']);
        }
        if(isset($_REQUEST['busier_id'])){
            $all_promote=array_column(get_admin_promotes($_REQUEST['busier_id'],'busier_id'),'id');
            if($all_promote==''){
                $all_promote[]=-1;
            }
            $map['tab_user.promote_id']=array($map['tab_user.promote_id'],array('in',implode(',',$all_promote)),'and');
        }
        if(isset($_REQUEST['is_check'])&&$_REQUEST['is_check']!="全部"){
            $map['is_check']=$_REQUEST['is_check'];
            unset($_REQUEST['is_check']);
        }
        if(isset($_REQUEST['account'])){
            $map['tab_user.account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }

        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['register_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['register_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['register_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['register_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
        $model = array(
            'm_name' => 'User',
            'fields' => array('id','tab_user.account','tab_user.fgame_id as game_id','nickname','email','phone','promote_id','parent_id','register_time','register_way','register_ip','promote_account','parent_name','is_check','is_union'),
            'key'    => array('tab_user.account','tab_game.fgame_name'),
            'map'    => $map,
            'order'  => 'id desc',
            'title'  => '渠道注册',
            'template_list' =>'ch_reg_list',
        );
        $map1=$map;
        $total=D($model['m_name'])->where($map1)->count();
        $ttotal=D($model['m_name'])->where('register_time'.total(1))->where(array('tab_user.promote_id'=>array('neq',0)))->where($map)->count();
        $ytotal=D($model['m_name'])->where('register_time'.total(5))->where(array('tab_user.promote_id'=>array('neq',0)))->where($map)->count();
        // var_dump(D($model['m_name'])->getlastsql());exit;  
        // $this->assign('dtotal',$dtotal);
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        $user = A('User','Event');
        $user->user_join($model,$_GET['p']);
    }

    /**
    *渠道充值
    */
    public function spend_list(){
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        if(!empty($_REQUEST['server_id'])){
            $map['server_id']=$_REQUEST['server_id'];
            unset($_REQUEST['server_id']);
        }
        if(isset($_REQUEST['admin_id'])){
        	if ($_REQUEST['admin_id']=="全部") {
        		unset($_REQUEST['admin_id']);
        	}else{
        		$map['admin_id']=get_admin_id($_REQUEST['admin_id']);
        		unset($_REQUEST['admin_id']);
        	}
        }
        if(!empty($_REQUEST['pay_order_number'])){
            $map['pay_order_number']=array('like','%'.$_REQUEST['pay_order_number'].'%');
            unset($_REQUEST['pay_order_number']);
        }
        if(isset($_REQUEST['promote_id'])){
            $map['promote_id']=$_REQUEST['promote_id'];
            unset($_REQUEST['user_account']);
        }else{
            $map['promote_id']=array("gt",0);
        }
        
        if(isset($_REQUEST['pay_way'])){
            $map['pay_way']=$_REQUEST['pay_way'];
            unset($_REQUEST['pay_way']);
        }
        if(isset($_REQUEST['account'])){
            $map['user_account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['user_account']);
        }
        if(isset($_REQUEST['spend_ip'])){
            $map['spend_ip']=array('like','%'.$_REQUEST['spend_ip'].'%');
            unset($_REQUEST['spend_ip']);
        }

        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['pay_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['pay_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
        if(!empty(I('parent_id'))){
        	$map['p.parent_id'] = I('parent_id');
        }
        if($_REQUEST['busier_id']){
        	$map['p.busier_id']=$_REQUEST['busier_id'];
        	unset($_REQUEST['busier_id']);
        }
        if($_REQUEST['parent_id']==$_REQUEST['promote_id']&&isset($_REQUEST['parent_id'])){
            $map['p.parent_id'] = 0;
        }elseif(isset($_REQUEST['parent_id'])&&!isset($_REQUEST['promote_id'])){
            unset($map['p.parent_id']);
            $zi = get_zi_promote_id($_REQUEST['parent_id']);
            $ziarr = explode(',',$zi);
            $ziarr[] = $_REQUEST['parent_id'];
            $zi = implode(',',$ziarr);
            $map['promote_id'] = array('in',$zi);
        }
        $model = array(
            'm_name' => 'Spend',
            'map'    => $map,
            'order'  => 'tab_spend.id desc',
            'join'   => 'left join tab_promote p on p.id = tab_spend.promote_id',
            'title'  => '渠道充值',
            'template_list' =>'spend_list',
        );
        $map1=$map;
        $map1['pay_status']=1;
        $total=null_to_0(D('Spend')->where($map1)->join($model['join'])->sum('pay_amount'));
        $ttotal=null_to_0(D('Spend')->where('pay_time'.total(1))->join($model['join'])->where(array('pay_status'=>1))->where($map)->sum('pay_amount'));
        $ytotal=null_to_0(D('Spend')->where('pay_time'.total(5))->join($model['join'])->where(array('pay_status'=>1))->where($map)->sum('pay_amount'));
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        $user = A('Spend','Event');
        $user->spend_list($model,$_GET['p']);
    }

    /**
    *代充记录
    */
    public function agent_list(){
        $map['promote_id'] = array("neq",0);
        if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.$_REQUEST['user_account'].'%');
            unset($_REQUEST['user_account']);
        }
        if(isset($_REQUEST['pay_status'])){
            $map['pay_status']=$_REQUEST['pay_status'];
            unset($_REQUEST['pay_status']);
        }
        if(isset($_REQUEST['promote_name'])){
            if($_REQUEST['promote_name']=='全部'){
                unset($_REQUEST['promote_name']);
            }else if($_REQUEST['promote_name']=='自然注册'){
                $map['promote_id']=array("elt",0);
                unset($_REQUEST['promote_name']);
            }else{
                $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
                unset($_REQUEST['promote_name']);
                unset($_REQUEST['promote_id']);
            }
        }

        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['create_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['is_union'])){
            $ids=get_union_member($_REQUEST['is_union']);
            if($ids){
                $ids=implode(',',$ids);
                $map['user_id']=array('in',$ids);
            }else{
                $map['user_id']=-1;
            }
            unset($_REQUEST['is_union']);
        }
        empty(I('promote_id')) || $map['promote_id'] = I('promote_id');
        $map1=$map;
        $map1['pay_status']=1;
        $total=D('Agent')->field('sum(amount) amount,sum(real_amount) real_amount')->where($map1)->find();
        $ttotal=D('Agent')->field('sum(amount) amount,sum(real_amount) real_amount')->where('create_time'.total(1))->where(array('pay_status'=>1))->where($map)->find();
        $ytotal=D('Agent')->field('sum(amount) amount,sum(real_amount) real_amount')->where('create_time'.total(5))->where(array('pay_status'=>1))->where($map)->find();
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        parent::order_lists('Agent',$_GET["p"],$map);
    }
    /**
    *代充额度
    */
    public function pay_limit(){
        if(isset($_REQUEST['account'])){
            $map['account']=array('like','%'.$_REQUEST['account'].'%');
            unset($_REQUEST['account']);
        }
        if(isset($_REQUEST['promote_name'])){
            if($_REQUEST['promote_name']=='全部'){
                unset($_REQUEST['promote_name']);
            }else if($_REQUEST['promote_name']=='自然注册'){
                $map['id']=array("elt",0);
                unset($_REQUEST['promote_name']);
            }else{
                $map['id']=get_promote_id($_REQUEST['promote_name']);
                unset($_REQUEST['promote_name']);
            }
        }
        if(isset($_REQUEST['is_union'])){
            $map['is_union']=$_REQUEST['is_union'];
            unset($_REQUEST['is_union']);
        }
        $row=10;
        $map['pay_limit']=array('gt','0');
        $page = intval($_GET['p']);
        $page = $page ? $page : 1; //默认显示第一页数据
        $model=D('Promote');
        $data=$model
        ->field('id,account,pay_limit,set_pay_time')
        ->where($map)
        ->page($page, 10)
        ->select();
        $count=$model
        ->field('id,account,pay_limit')
        ->where($map)
        ->count();
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->meta_title = '代充额度';
        $this->assign('list_data', $data);
        $this->display();
    }
    public function pay_limits_add()
    {
        $limit=D("Promote");
        if(IS_POST){
            if(trim($_REQUEST['promote_id'])==''){
            $this->error("请选择管理员推广员");
            }
            if(trim($_REQUEST['limits'])==''){
            $this->error("请输入代充额度");
            }
            if(trim($_REQUEST['limits'])==0){
            $this->error("代充额度不能低于0");
            }
            $data['id']=$_REQUEST['promote_id'];
            $data['pay_limit']=$_REQUEST['limits'];
            $find=$limit->where(array("id"=>$data['id']))->find();
            if($find['pay_limit']!=0&&$find['set_pay_time']!=null){
            $this->error("已经设置过该推广员",U('pay_limit'));
            }else{
             $limit->where(array("id"=>$data['id']))->setField('pay_limit',trim($_REQUEST['limits']));
             $limit->where(array("id"=>$data['id']))->setField('set_pay_time',time());
             $this->success("添加成功！",U('pay_limit'));
            }
        }else{
            $this->display();
        }
    }
    public function pay_limit_del()
    {
        $limit=D("Promote");
        if(empty($_REQUEST['ids'])){
            $this->error('请选择要操作的数据');
        }
        if(isset($_REQUEST['ids'])){
            $id=$_REQUEST['ids'];
        }
         $limit
         ->where(array("id"=>$id))
         ->setField('pay_limit','0');
         $this->success("删除成功！",U('pay_limit'));
    }
    public function pay_limit_edit()
    {
        $limit=D("Promote");
        if(IS_POST){
            if(trim($_REQUEST['promote_id'])==''){
            $this->error("请选择管理员推广员");
            }
            if(trim($_REQUEST['limits'])==''){
            $this->error("请输入代充额度");
            }
            $data['id']=$_REQUEST['promote_id'];
             $edit=$limit->where(array("id"=>$data['id']))->setField('pay_limit',trim($_REQUEST['limits']));
             $limit->where(array("id"=>$data['id']))->setField('set_pay_time',time());
             if($edit==0){
                $this->error('数据未更改');
             }else{
                $this->success("编辑成功！",U('pay_limit'));
            }
        }else{
            $edit_data=$limit
            ->where(array('id'=>$_REQUEST['ids']))
            ->find();
            $this->assign('edit_data',$edit_data);
            $this->display();
        }
    }

    public function change_auto_audit(){
        if($_REQUEST['value']==1){
            $value = 0;
        }else{
            $value = 1;
        }
        $config['value'] = $value;
        $res = M('config')->where(array('name'=>'PROMOTE_AUTO_AUDIT'))->save($config);
        S('DB_CONFIG_DATA',null);
        $this->ajaxReturn(array('status'=>1));
    }
    
}
