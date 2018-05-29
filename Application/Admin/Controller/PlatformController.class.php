<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author yyh
 */
class PlatformController extends ThinkController {
	function game_statistics($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = 10;
		$user=M('User','tab_');
		$map['fgame_id']=array('gt',0);
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['game_name'])&&$_REQUEST['game_name']!=''){
            $map['fgame_name'] =$_REQUEST['game_name'];
            unset($_REQUEST['fgame_name']);
        }
		$data=$user
			->field('fgame_name,fgame_id,date_format(FROM_UNIXTIME( register_time),"%Y-%m-%d") AS time, count(id) as count')
			->where($map)
			->group('fgame_id')
			->order('count desc')
			->select();
        $count=count($data);
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('User',array('fgame_id'=>$value['fgame_id']));
			$data[$key]['today']=$adata['today'];
			$data[$key]['week']=$adata['week'];
			$data[$key]['mounth']=$adata['mounth'];
		}
		$total=$this->data_total($data);
		$this->assign('total',$total);
		if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
		$size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
        $this->display();
    }





    function gamepay_statistics($p=0){
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['id'] = array('gt',0);
        }
		$page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = 10;
		$spend=M('Spend','tab_');
        $deposit = M('Deposit',"tab_");
		$map['game_id']=array('gt',0);
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        // var_dump($_REQUEST);exit;
        if(isset($_REQUEST['game_name'])&&$_REQUEST['game_name']!=''){
            $map['game_name'] =$_REQUEST['game_name'];
            unset($_REQUEST['game_name']);
        }
        $map['pay_status']=1;
		$data=$spend
			->field('game_name,game_id,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
			->where($map)
            ->where($isbdpw)
			->group('game_id')
			->order('count desc')
			->select();
        $count=count($data);
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('Spend',array('game_id'=>$value['game_id'],'pay_status'=>1));
			$data[$key]['today']=$adata['today']==''?0:$adata['today'];
			$data[$key]['week']=$adata['week']==''?0:$adata['week'];
			$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
		}
		$total=$this->data_total($data);
		$this->assign('total',$total);
		if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
        $this->display();
    }







    function resway_statistics($p=0){
    	$page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = 10;
		$user=M('User','tab_');
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['register_way'])&&$_REQUEST['register_way']!=''){
            $map['register_way'] =$_REQUEST['register_way'];
            unset($_REQUEST['register_way']);
        }
		$data=$user
			->field('register_way,date_format(FROM_UNIXTIME(register_time),"%Y-%m-%d") AS time, count(id) as count')
			->where($map)
			->group('register_way')
			->order('count desc')
			->select();
        $count=count($data);
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('User',array('register_way'=>$value['register_way']));
			$data[$key]['today']=$adata['today']==''?0:$adata['today'];
			$data[$key]['week']=$adata['week']==''?0:$adata['week'];
			$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
		}
		$total=$this->data_total($data);
		if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
		$this->assign('total',$total);
        $this->display();
    }





    function payway_statistics($p=0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = 10;
        $deposit = M('Deposit',"tab_");
        $user=M('User','tab_');
		$spend=M('Spend','tab_');
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['pay_way'])&&$_REQUEST['pay_way']!=''){
            $map['pay_way'] =$_REQUEST['pay_way'];
            unset($_REQUEST['pay_way']);
        }
        $map['pay_status']=1;
		$data=$spend
			->field('pay_way,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
			->where($map)
			->group('pay_way')
			->order('count desc')
			->select();
        $count=count($data);
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('Spend',array('pay_way'=>$value['pay_way'],'pay_status'=>1));
			$data[$key]['today']=$adata['today']==''?0:$adata['today'];
			$data[$key]['week']=$adata['week']==''?0:$adata['week'];
			$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
		}
		$total=$this->data_total($data);
		$this->assign('total',$total);
        if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
		if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
        $this->display();
    }





    function promote_statistics($p=0){
    	$page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = intval(C('LIST_ROWS')) ? :10;
		$user=M('User','tab_');
		$map['promote_id']=array('egt',1);
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['promote_id'])){
            $map['promote_id'] =$_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
        }
		$data=$user
			->field('promote_account,promote_id,date_format(FROM_UNIXTIME(register_time),"%Y-%m-%d") AS time, count(tab_user.id) as count')
			->where($map)
            ->join('tab_promote on tab_user.promote_id = tab_promote.id')
			->group('promote_id')
			->order('count desc')
			->select();
        $count=count($data);
        // echo "<pre>";
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('User',array('promote_id'=>$value['promote_id']));
			$data[$key]['today']=$adata['today']==''?0:$adata['today'];
			$data[$key]['week']=$adata['week']==''?0:$adata['week'];
			$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
			if($data[$key]['promote_id']==0){
                unset($data[$key]);
		}
        }
		$total=$this->data_total($data);
		if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
		$this->assign('total',$total);
        $this->display();
    }

    /*
     * 渠道注册详情
     */
    public function pro_regs_detail($p = 0){
    	$page = intval($p);
    	$page = $page ? $page : 1; //默认显示第一页数据
    	$arraypage=$page;
    	$row = 5;
    	$user=M('User','tab_');
    	$map['fgame_id']=array('gt',0);
    	$map['promote_id']=$_REQUEST['promote_id'];
    	
    	$data=$user
    	->field('fgame_name,fgame_id,date_format(FROM_UNIXTIME( register_time),"%Y-%m-%d") AS time, count(id) as count')
    	->where($map)
    	->group('fgame_id')
    	->order('count desc')
    	->select();
    	$count=count($data);
    	foreach ($data as $key => $value) {
    		static $i=0;
    		$i++;
    		$data[$key]['rand']=$i;
    		$adata=$this->day_data('User',array('fgame_id'=>$value['fgame_id']));
    		$data[$key]['today']=$adata['today'];
    		$data[$key]['week']=$adata['week'];
    		$data[$key]['mounth']=$adata['mounth'];
    	}
    	$total=$this->data_total($data);
    	$this->assign('total',$total);
    	if($_REQUEST['data_order']!=''){
    		$data_order=reset(explode(',',$_REQUEST['data_order']));
    		$data_order_type=end(explode(',',$_REQUEST['data_order']));
    		$this->assign('userarpu_order',$data_order);
    		$this->assign('userarpu_order_type',$data_order_type);
    	}
    	if($count > $row){
    		$page = new \Think\Page($count, $row);
    		$page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
    		$this->assign('_page', $page->show());
    	}
    	$data=my_sort($data,$data_order_type,(int)$data_order);
    	$size=$row;//每页显示的记录数
    	$pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
    	//用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
    	$data = array_slice($data, ($arraypage-1)*$size, $size);
    	$this->assign('list_data',$data);
    	$this->display();
    }
    
    function promotepay_statistics($p=0){
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['tab_spend.id'] = array('gt',0);
        }
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $row = intval(C('LIST_ROWS')) ? :10;
        $map['promote_id']=array('gt',0);
		$spend=M('Spend','tab_');
		if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
            unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['promote_id'])){
            $map['promote_id'] =$_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
        }
        $map['pay_status']=1;
		$data=$spend
			->field('promote_account,promote_id,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
            ->join('tab_promote on tab_spend.promote_id = tab_promote.id')
			->where($map)
            ->where($isbdpw)
			->group('promote_id')
			->order('count desc')
			->select();
        $count=count($data);
		foreach ($data as $key => $value) {
			static $i=0;
			$i++;
			$data[$key]['rand']=$i;
			$adata=$this->day_data('Spend',array('promote_id'=>$value['promote_id'],'pay_status'=>'1'));
			$data[$key]['today']=$adata['today']==''?0:$adata['today'];
			$data[$key]['week']=$adata['week']==''?0:$adata['week'];
			$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
            if($data[$key]['promote_id']=='0'){
                unset($data[$key]);
            }
		}
		$total=$this->data_total($data);
		$this->assign('total',$total);
		if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
		$this->assign('list_data',$data);
        $this->display();
    }

    /*
     * 渠道游戏充值详情
     */
    public function pro_pay_detail($p = 0){
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['tab_spend.id'] = array('gt',0);
        }
    	$page = intval($p);
    	$page = $page ? $page : 1; //默认显示第一页数据
    	$arraypage=$page;
    	$row = 5;
    	$map['promote_id']=array('gt',0);
    	$spend=M('Spend','tab_');
    	if(isset($_REQUEST['promote_id'])){
    		$map['promote_id'] =$_REQUEST['promote_id'];
    		unset($_REQUEST['promote_id']);
    	}
    	$map['pay_status']=1;
    	$data=$spend
    	->field('game_id,game_name,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
    	->join('tab_promote on tab_spend.promote_id = tab_promote.id')
    	->where($map)
    	->where($isbdpw)
    	->group('game_id')
    	->order('count desc')
    	->select();
    	$count=count($data);
    	foreach ($data as $key => $value) {
    		static $i=0;
    		$i++;
    		$data[$key]['rand']=$i;
    		$adata=$this->day_data('Spend',array('promote_id'=>$value['promote_id'],'pay_status'=>'1'));
    		$data[$key]['today']=$adata['today']==''?0:$adata['today'];
    		$data[$key]['week']=$adata['week']==''?0:$adata['week'];
    		$data[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
    		if($data[$key]['promote_id']=='0'){
    			unset($data[$key]);
    		}
    	}
    	$total=$this->data_total($data);
    	$this->assign('total',$total);
    	if($_REQUEST['data_order']!=''){
    		$data_order=reset(explode(',',$_REQUEST['data_order']));
    		$data_order_type=end(explode(',',$_REQUEST['data_order']));
    		$this->assign('userarpu_order',$data_order);
    		$this->assign('userarpu_order_type',$data_order_type);
    	}
    	if($count > $row){
    		$page = new \Think\Page($count, $row);
    		$page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
    		$this->assign('_page', $page->show());
    	}
    	$data=my_sort($data,$data_order_type,(int)$data_order);
    	$size=$row;//每页显示的记录数
    	$pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
    	//用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
    	$data = array_slice($data, ($arraypage-1)*$size, $size);
    	
    	$this->assign('list_data',$data);
    	$this->display();
    	
    	
    }
    private function data_total($data){
    	$total['sum_count']=array_sum(array_column($data,'count'));
		$total['sum_today']=array_sum(array_column($data,'today'));
		$total['sum_week']=array_sum(array_column($data,'week'));
		$total['sum_mounth']=array_sum(array_column($data,'mounth'));
		return $total;
    }
  
    function day_data($model='User',$column1=array(),$column2='count'){
    	//今日本周本月不跟随选择的实现变动 只以当前日期为基准
    	$table=M($model,'tab_');
    	$today=total(1);
    	$week=total(2);
    	$mounth=total(3);
    	if($model=='User'){
    		$data['today']=$table->field('count(id) as count')->where($column1)->where('register_time'.$today)->select();
			$data['week']=$table->field('count(id) as count')->where($column1)->where('register_time'.$week)->select();
			$data['mounth']=$table->field('count(id) as count')->where($column1)->where('register_time'.$mounth)->select();
    	}elseif($model=='Spend'){
            if(I('isbd')==1){
                $isbdpw['pay_way'] = array('neq',-1);
            }else{
                $isbdpw['tab_spend.id'] = array('gt',0);
            }
    		$data['today']=$table->field('sum(pay_amount) as count')->where($column1)->where('pay_time'.$today)->where($isbdpw)->select();
			$data['week']=$table->field('sum(pay_amount) as count')->where($column1)->where('pay_time'.$week)->where($isbdpw)->select();
			$data['mounth']=$table->field('sum(pay_amount) as count')->where($column1)->where('pay_time'.$mounth)->where($isbdpw)->select();
    	}
    	foreach ($data as $key => $value) {
    		$v=reset($value);
    		$data[$key]=$v['count'];
    	}
    	return $data;
    }
}
