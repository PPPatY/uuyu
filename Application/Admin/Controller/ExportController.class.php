<?php
namespace admin\Controller;
use Think\Controller;
use Admin\Controller\PlatformController;
class ExportController extends Controller
{
	public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称  
        $fileName = session('user_auth.username').date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        Vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);  
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$xlsTitle.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }

	//导出Excel
     function expUser($id){
     	switch ($id) {
          case 1:
            $xlsName  = "代充记录";
            $xlsCell  = array(
                    array('id','编号'),
                    array('user_account','账号'),
                    array('game_name','游戏名称'), 
                    array('amount','充值金额'),
                    array('real_amount','实扣金额'),
                    array('zhekou','折扣比例'),
                    array('pay_status','支付状态'),
                    array('create_time','充值时间'),  
                    array('promote_account','推广员账号'),    
            ); 
            if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.$_REQUEST['user_account'].'%');
            }
            if(isset($_REQUEST['game_name'])){
                if($_REQUEST['game_name']=='全部'){
                    unset($_REQUEST['game_name']);
                }else{
                    $map['game_name']=$_REQUEST['game_name'];
                    unset($_REQUEST['game_name']);
                }
            }    
            if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
                $map['create_time']=array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            }
            if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
                $map['create_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            }      
           $xlsData=M('agent','tab_')
           ->field("id,user_account,game_name,amount,real_amount,zhekou,pay_status,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time,promote_account")
             ->where($map) 
             ->order("create_time")
             ->select(); 
        break; 
             case 2:
                $xlsName  = "渠道充值";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('user_account','账号'),
                    array('game_name','游戏名称'),
                    array('pay_amount','充值金额'),
                    array('pay_way','充值方式'),
                    array('pay_time','充值时间'),  
                    array('spend_ip','充值IP'),  
                    array('promote_account','所属渠道'),
                    array('promote_id','上线渠道'), 
                    array('is_check','是否对账(1参与;2不参与;3参与(已对账);4不参与(已对账))'),   
                );
            if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
            }
            if(isset($_REQUEST['promote_name'])){
                if($_REQUEST['promote_name']=='全部'){
                    unset($_REQUEST['promote_name']);
                }else if($_REQUEST['promote_name']=='自然注册'){
                    $map['promote_id']=array("lte",0);
                    
                    unset($_REQUEST['promote_name']);
                }else{
                    $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
                    unset($_REQUEST['promote_name']);
                }
            }else{
                $map['promote_id']=array("gt",0);
            }
            
            if(isset($_REQUEST['pay_way'])){
                $map['pay_way']=$_REQUEST['pay_way'];
                unset($_REQUEST['pay_way']);
            }
            if(isset($_REQUEST['is_check'])&&$_REQUEST['is_check']!="全部"){
                $map['is_check']=check_status($_REQUEST['is_check']);
                unset($_REQUEST['is_check']);
            }
            if(isset($_REQUEST['user_account'])){
                $map['user_account']=array('like','%'.$_REQUEST['user_account'].'%');
                unset($_REQUEST['user_account']);
            }
            if(isset($_REQUEST['promote_name'])){
                $map['promote_account']=$_REQUEST['promote_name'];
                unset($_REQUEST['user_account']);
            }
            if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
                $map['pay_time']=array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                unset($_REQUEST['time-start']);unset($_REQUEST['time_end']);
            }
            if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
                $map['pay_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
                unset($_REQUEST['start']);unset($_REQUEST['end']);
            }
            $map['tab_spend.pay_status'] = 1;
            $xlsData=M('Spend','tab_')
            ->field('tab_spend.*,DATE_FORMAT( FROM_UNIXTIME(pay_time),"%Y-%m-%d %H:%i:%s") AS pay_time')
            ->where($map) 
            ->select();
            foreach ($xlsData as &$value) {
                $value['pay_way']=get_pay_way($value['pay_way']);
            }
            foreach ($xlsData as &$value) {
                $value['promote_id']=get_top_promote($value['promote_id'],get_parent_id($value['promote_id']));
            }
            break; 
            case 3:
                $xlsName  = "玩家列表";
                $xlsCell  = array(
                    array('id','编号'),
                    array('account','账号'),
                    array('fgame_name','注册游戏'),
                    array('register_ip','注册IP'),
                    array('promote_account','所属渠道'),
                    array('promote_id','上线渠道'),
                    array('register_time','注册时间'),  
                    array('is_check','是否对账(1参与;2不参与;3参与(已对账);4不参与(已对账))'),
                );
                if(isset($_REQUEST['promote_id'])){
                	empty($hav) || $hav .= ' AND ';
                	if($_REQUEST['promote_id'] == "0"){
                		$hav .= "tab_user.promote_id like '%".I('promote_id')."%'";
                	}else{
                		$hav .= "tab_user.promote_id =".I('promote_id');
                	}
                	unset($_REQUEST['promote_id']);
                }
                //         "" == I('promote_id') || $hav .= "tab_user.promote_id =".I('promote_id');
                if(isset($_REQUEST['account'])){
                	//            $map['tab_user.account'] = array('like','%'.$_REQUEST['account'].'%');
                	empty($hav) || $hav .= ' AND ';
                	$hav .= "tab_user.account like '%".I('account')."%'";
                	unset($_REQUEST['account']);
                }
                if(isset($_REQUEST['register_way'])){
                	//            $map['register_way'] = $_REQUEST['register_way'];
                	empty($hav) || $hav .= ' AND ';
                	$hav .= 'tab_user.register_way ='.I('register_way');
                	unset($_REQUEST['register_way']);
                } else {
									if ($_REQUEST['group']==1) {
										empty($hav) || $hav .= ' AND ';
										$hav .= 'tab_user.register_way in (3,4,5,6)';
									} else {
										empty($hav) || $hav .= ' AND ';
										$hav .= 'tab_user.register_way in (0,1,2)';
									}
								}
                if(isset($_REQUEST['time_start']) && isset($_REQUEST['time_end'])){
                	empty($hav) || $hav .= ' AND ';
                	$hav .= 'tab_user.register_time BETWEEN '.strtotime(I('time_start')).' AND '.(strtotime(I('time_end'))+24*60*60-1);
                	unset($_REQUEST['time_start']);unset($_REQUEST['time_end']);
                }elseif(isset($_REQUEST['time_start'])){
                    empty($hav) || $hav .= ' AND ';
                    $hav .= 'tab_user.register_time >= '.strtotime(I('time_start'));
                    unset($_REQUEST['time_start']);
                }elseif(isset($_REQUEST['time_end'])){
                    empty($hav) || $hav .= ' AND ';
                    $hav .= 'tab_user.register_time <= '.(strtotime(I('time_end'))+24*60*60-1);
                    unset($_REQUEST['time_end']);
                }
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                	empty($hav) || $hav .= ' AND ';
                	$hav .= 'tab_user.register_time BETWEEN '.strtotime(I('start')).' AND '.strtotime(I('end'));
                	unset($_REQUEST['start']);unset($_REQUEST['end']);
                }
                if(!empty(I('line_day'))){
                	$day = strtotime(date('Y-m-d')) - intval(I('line_day')) * 86400;
                	empty($hav) || $hav .= ' AND ';
                	$hav .= $day.'> tab_user.login_time';
                }
                if(!empty(I('recharge_total'))){
                	empty($hav) || $hav .= ' AND ';
                	$hav .= 'recharge_total > '.I('recharge_total');
                }
                if(isset($_REQUEST['status'])){
                	empty($hav) || $hav .= ' AND ';
                	$hav .= 'tab_user.lock_status = '.I('status');
                }
								if (isset($_REQUEST['user_id'])) {
									empty($hav) || $hav .= ' AND ';
									$hav .= 'tab_user.id = '.I('user_id');
								}
                //排序
                $order = '';
                if (I('recharge_status') == 1) {
                	$order = 'recharge_total,';
                } elseif (I('recharge_status') == 2) {
                	$order = 'recharge_total desc,';
                }
                if (I('balance_status') == 1) {
                	$order .= 'balance,';
                } elseif (I('balance_status') == 2) {
                	$order .= 'balance desc,';
                }
                $order .= 'tab_user.id desc';
                $xlsData= M('user','tab_')->field('tab_user.*,cumulative as recharge_total')
                ->having($hav)
                ->order($order)
                ->select();
            foreach ($xlsData as &$value) {
                $value['promote_id']=get_top_promote($value['promote_id'],get_parent_id($value['promote_id']));
                $value['register_time'] = date('Y-m-d H:i:s',$value['register_time']);
            }
            break;
             case 4:
                $xlsName  = "渠道对账";
                $xlsCell  = array(
                    array('bill_number','订单编号'),
                    array('bill_time','对账时间'),
                    array('game_name','游戏名称'),
                    array('total_money','充值总额'),
                    array('total_number','注册人数'),
                    array('promote_account','推广员账号'),
                    array('settlement_status','结算状态(0未结算;1已结算)'),
                    // array('pay_time','充值时间'),  
                );
                $map['status'] = 1;
                // 条件搜索
                foreach($_REQUEST as $name=>$val){
                    switch ($name) {
                        case 'id':break;
                        case 'group':break;
                        case 'timestart':
                            $map['bill_start_time'] = array('egt',strtotime($val));
                            break;
                        case 'timeend':
                            $map['bill_end_time'] = array('elt',strtotime($val)+24*60*60-1);
                            break;
                        default :
                            if ($val == '全部') {$map[$name]=array('like','%%');}
                            else
                                $map[$name]=array('like','%'.$val.'%');
                            break;
                    }
                }
                $xlsData=M('bill','tab_')
                ->where($map) 
                ->select(); 
            break;
            case 5:
                $xlsName  = "渠道结算";
                $xlsCell  = array(
                    array('id','编号'),
                    array('game_name','游戏名称'),
                    array('promote_account','推广员账号'),
                    array('total_money','充值总额'),
                    array('total_number','注册人数'),
                    array('pattern','合作模式(0CPS;1CPA)'), 
                    array('ratio','分成比例'),
                    array('money','注册单价'),
                    array('sum_money','结算金额'),
                    array('create_time','结算时间'),
                );
                foreach($_REQUEST as $name=>$val){
                    switch ($name) {
                        case 'group':break;
                        case 'id':break;
                        case 'timestart':
                            $map['create_time'] = array('egt',strtotime($val));
                            break;
                        case 'timeend':
                            $map['create_time'] = array('elt',strtotime($val)+24*60*60-1);
                            break;
                        case 'withdraw_status':
                            $map['withdraw_status'] = $val;break;
                        default :
                            if ($val == '全部') {$map[$name]=array('like','%%');}
                            else
                                $map[$name]=array('like','%'.$val.'%');
                            break;
                    }
                }
                $xlsData=M('Settlement','tab_')
                    ->field('tab_settlement.*,DATE_FORMAT( FROM_UNIXTIME(create_time),"%Y-%m-%d %H:%i:%s") AS create_time')
                    ->where($map)
                    ->select();     
            break;
            case 6:
                $xlsName  = "渠道提现";
                $xlsCell  = array(
                    array('id','编号'),
                    array('promote_account','推广员账号'),
                    array('sum_money','提现金额'),
                    array('settlement_number','提现单号'),
                    array('create_time','提现时间'),   
                    array('status','提现状态(-1未申请;0申请中;1已通过;2已驳回)'),   
                );
                foreach($_REQUEST as $name=>$val){
                    switch ($name) {
                        case 'group':break;
                        case 'id':break;
                        case 'timestart':
                            $map['create_time'] = array('egt',strtotime($val));
                            break;
                        case 'timeend':
                            $map['create_time'] = array('elt',strtotime($val)+24*60*60-1);
                            break;
                        case 'withdraw_status':
                            $map['withdraw_status'] = $val;break;
                        default :
                            if ($val == '全部') {$map[$name]=array('like','%%');}
                            else
                                $map[$name]=array('like','%'.$val.'%');
                            break;
                    }
                }
                $xlsData=M('withdraw','tab_')
                    ->field('tab_withdraw.*,DATE_FORMAT( FROM_UNIXTIME(create_time),"%Y-%m-%d %H:%i:%s") AS create_time')
                    ->where($map)
                    ->select();
            break;
            case 7:
                $xlsName  = "游戏消费记录";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('user_account','用户帐号'),
                    array('game_name','游戏名称'),
                    array('pay_amount','充值金额'),
                    array('pay_time','充值时间'),    
                    array('pay_way','充值方式(0平台币;1支付宝;2微信)'),
                    array('pay_status','充值状态(0未支付;1成功)'),
                );
                if(isset($_REQUEST['user_account'])){
                $map['user_account']=array('like','%'.$_REQUEST['user_account'].'%');
                }
                if(isset($_REQUEST['pay_way'])){
                    $map['pay_way']=$_REQUEST['pay_way'];
                }
                if(isset($_REQUEST['pay_status'])){
                    $map['pay_status']=$_REQUEST['pay_status'];
                }
                if(isset($_REQUEST['spend_ip'])){
                    $map['spend_ip']=$_REQUEST['spend_ip'];
                }
                 if(isset($_REQUEST['pay_game_status'])){
                    $map['pay_game_status']=$_REQUEST['pay_game_status'];
                }
                
                if(isset($_REQUEST['game_name'])){
                    if($_REQUEST['game_name']=='全部'){
                        unset($_REQUEST['game_name']);
                    }else{
                        $map['game_name']=$_REQUEST['game_name'];
                        unset($_REQUEST['game_name']);
                    }
                }
                
								if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
									$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
									
								} elseif (!empty($_REQUEST['timestart']) ) {
									$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
								
								} elseif (!empty($_REQUEST['timeend']) ) {
									$map['pay_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								}
								
								
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                    $map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));;
                }
                $xlsData=M('Spend','tab_')
                ->field("id,pay_order_number,user_account,game_name,pay_amount,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as pay_time,pay_way,pay_status")
                ->where($map) 
                ->order("pay_time")
                ->select(); 
                header("Content-Type:text/html;charset=utf-8");
                foreach ($xlsData as &$value) {
                    $value['pay_way']=get_pay_way($value["pay_way"]);
                }
                foreach ($xlsData as &$value) {
                    $value['pay_status']=get_info_status($value["pay_status"],12);
                }
            break;
            case 8:
                $xlsName  = "平台币充值记录";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('user_nickname','用户昵称'),
                    array('pay_amount','支付金额'),
                    array('promote_account','所属渠道'),
                    array('create_time','充值时间'),    
                    array('pay_way','充值方式(0支付宝;1微信)'),
                    array('pay_status','充值状态(0失败;1成功)'),
                );
                if(isset($_REQUEST['user_nickname'])){
                $map['user_nickname']=array('like','%'.$_REQUEST['user_nickname'].'%');
                }
                if(isset($_REQUEST['pay_way'])){
                    $map['pay_way']=$_REQUEST['pay_way'];
                }
                if(isset($_REQUEST['pay_status'])){
                    $map['pay_status']=$_REQUEST['pay_status'];
                }
                if(!isset($_REQUEST['promote_id'])){

                }else if(isset($_REQUEST['promote_id']) && $_REQUEST['promote_id']==0){
                    $map['promote_id']=array('elt',0);
                }elseif(isset($_REQUEST['promote_name'])&&$_REQUEST['promote_id']==-1){
                    $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
                }else{
                    $map['promote_id']=$_REQUEST['promote_id'];
                }
                 if(isset($_REQUEST['pay_ip'])){
                    $map['pay_ip']=$_REQUEST['pay_ip'];
                }
								
								if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
									
								} elseif (!empty($_REQUEST['timestart']) ) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
									
								} elseif (!empty($_REQUEST['timeend']) ) {
									$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								}
								
								
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                    $map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));;
                }
                $xlsData=M('Deposit','tab_')
                ->field("id,pay_order_number,user_nickname,pay_amount,promote_account,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time,pay_way,pay_status,pay_source")
                ->where($map) 
                ->order("create_time")
                ->select(); 
                foreach ($xlsData as &$value) {
                    $value['pay_way']=get_pay_way($value["pay_way"]);
                }
                foreach ($xlsData as &$value) {
                    $value['pay_status']=get_info_status($value["pay_status"],12);
                }
            break;
            case 9:
                $xlsName  = "平台币发放记录";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('user_nickname','用户昵称'),
                    array('amount','金额'),
                    array('create_time','充值时间'),    
                    array('status','状态(0未充值;1已充值)'),
                    array('op_account','操作人'),
                );
                if(I('group')==2){
                	$map['coin_type'] =0;
                }else{
                	$xlsCell  = array(
                			array('id','编号'),
                			array('pay_order_number','订单号'),
                			array('user_nickname','用户昵称'),
                			array('game_name','游戏名称'),
                			array('amount','金额'),
                			array('create_time','充值时间'),
                			array('status','状态(0未充值;1已充值)'),
                			array('op_account','操作人'),
                	);
                	$map['coin_type'] =-1;
                }
                if(isset($_REQUEST['user_account'])){
                $map['user_account']=array('like','%'.$_REQUEST['user_account'].'%');
                }
                if (isset($_REQUEST['game_name'])) {
                    if ($_REQUEST['game_name'] == '全部') {
                        unset($_REQUEST['game_name']);
                    } else {
                        $map['game_name'] = $_REQUEST['game_name'];
                        unset($_REQUEST['game_name']);
                    }
                }
                
								if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
									
								} elseif (!empty($_REQUEST['timestart']) ) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
									
								} elseif (!empty($_REQUEST['timeend']) ) {
									$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								}
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                    $map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));;
                }
                $xlsData=M('Provide','tab_')
                ->field("id,pay_order_number,user_nickname,game_name,amount,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time,status,op_account")
                ->where($map) 
                ->order("create_time")
                ->select(); 
            break;
            case 10:
                $xlsName  = "平台币使用记录";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('user_nickname','用户昵称'),
                    array('game_name','游戏'),
                    array('pay_amount','金额'),
                    array('props_name','游戏道具'),
                    array('pay_time','充值时间'),    
                    array('pay_status','状态(0下单未支付;1成功)'),
                );
                if(isset($_REQUEST['user_nickname'])){
                $map['user_nickname']=array('like','%'.$_REQUEST['user_nickname'].'%');
                }
                if(isset($_REQUEST['game_name'])){
                    if($_REQUEST['game_name']=='全部'){
                    }else{
                        $map['game_name']=$_REQUEST['game_name'];
                    }
                }
                if(isset($_REQUEST['time-start']) && isset($_REQUEST['time-end'])){
                    $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                }
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                    $map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));;
                }
                if(isset($_REQUEST['spend_ip'])){
                    $map['spend_ip']=$_REQUEST['spend_ip'];
                }
                if(!isset($_REQUEST['promote_id'])){

                }else if(isset($_REQUEST['promote_id']) && $_REQUEST['promote_id']==0){
                    $map['promote_id']=array('elt',0);
                    unset($_REQUEST['promote_id']);
                    unset($_REQUEST['promote_name']);
                }elseif(isset($_REQUEST['promote_name'])&&$_REQUEST['promote_id']==-1){
                    $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
                }else{
                    $map['promote_id']=$_REQUEST['promote_id'];
                    unset($_REQUEST['promote_id']);
                    unset($_REQUEST['promote_name']);
                }
                if(isset($_REQUEST['game_name'])){
                    if($_REQUEST['game_name']=='全部'){
                        unset($_REQUEST['game_name']);
                    }else{
                        $map['game_name']=$_REQUEST['game_name'];
                    }
                    unset($_REQUEST['game_name']);
                }
                $xlsData=M('Bind_spend','tab_')
                ->field("id,pay_order_number,user_nickname,game_name,pay_amount,props_name,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as pay_time,pay_status")
                ->where($map) 
                ->order("pay_time")
                ->select(); 
            break;
            case 11:
                $xlsName  = "礼包领取记录";
                $xlsCell  = array(
                    array('id','编号'),
                    array('game_name','游戏名称'),
                    array('gift_name','礼包名称'),
                    array('user_account','领取用户'),
                    array('novice','激活码'),    
                    array('create_time','领取时间'),
                );
                if(isset($_REQUEST['game_name'])){
                $map['game_name']=array('like','%'.$_REQUEST['game_name'].'%');
                }
                $xlsData=M('gift_record','tab_')
                ->field("id,game_name,gift_name,user_account,novice,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time")
                ->where($map) 
                ->order("create_time")
                ->select(); 
            break;
          case 12:
                $xlsName  = "平台用户";
                $xlsCell  = array(
                    array('id','用户id'),
                    array('account','用户账号'),
                    array('balance','平台币余额'),
                    array('register_way','注册方式'),
                    array('register_time','注册时间'),
                );
                if(isset($_REQUEST['account'])){
                    $map['tab_user.account'] = array('like','%'.$_REQUEST['account'].'%');
                }
                if(isset($_REQUEST['game_id'])){
                    $map['tab_game.id'] = $_REQUEST['game_id'];
                }
                if(isset($_REQUEST['register_way'])){
                    $map['register_way'] = $_REQUEST['register_way'];
                }
                if(isset($_REQUEST['time-start']) && isset($_REQUEST['time-end'])){
                    $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                }
                if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
                    $map['register_time'] = array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));;
                }
                $xlsData=M('User','tab_')
                ->field("id,account,balance,register_time,register_way,FROM_UNIXTIME(register_time,'%Y-%m-%d %H:%i:%s') as register_time")
                ->where($map) 
                ->order("register_time")
                ->select(); 
                foreach ($xlsData as &$value) {
                    $value['register_way']=get_registertype($value["register_way"]);
                }
                foreach ($xlsData as &$value) {
                    $value['register_way']=get_registertype($value["register_way"]);
                }
            break;
            case 13:
                $xlsName  = "代充额度";
                $xlsCell  = array(
                    array('id','编号'),
                    array('account','渠道账号'),
                    array('pay_limit','代充上限'),
                    array('set_pay_time','更新时间'),
                );
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
            $map['pay_limit']=array('gt','0');
                $xlsData=M('Promote','tab_')
                ->field("id,account,pay_limit,FROM_UNIXTIME(set_pay_time,'%Y-%m-%d %H:%i:%s') as set_pay_time")
                ->where($map) 
                ->order("set_pay_time")
                ->select(); 
            break;
             case 14:
                $xlsName  = "游戏返利";
                $xlsCell  = array(
                    array('id','编号'),
                    array('pay_order_number','订单号'),
                    array('account','玩家账号'),
                    array('game_name','游戏名称'),
                    array('pay_amount','充值金额'),
                    array('ratio','返利比例'),
                    array('ratio_amount','返利绑币'),
                    array('promote_name','所属推广员'),
                    array('create_time','返利时间'),
                );
            if(isset($_REQUEST['game_name'])){
                if($_REQUEST['game_name']=='全部'){
                    unset($_REQUEST['game_name']);
                }else if($_REQUEST['game_name']=='自然注册'){
                    $map['id']=array("elt",0);
                    unset($_REQUEST['game_name']);
                }else{
                    $map['id']=get_game_id($_REQUEST['game_name']);
                    unset($_REQUEST['game_name']);
                }
            }
            
                $xlsData=M('RebateList','tab_')
                ->field("tab_rebate_list.id,tab_rebate_list.pay_order_number,tab_user.account,tab_rebate_list.user_name,tab_rebate_list.game_name,tab_rebate_list.pay_amount,tab_rebate_list.ratio,tab_rebate_list.ratio_amount,tab_rebate_list.promote_name,tab_rebate_list.create_time")
                ->join("tab_user on tab_user.id=tab_rebate_list.user_id")
                ->where($map) 
                ->order("create_time")
                ->select(); 
                foreach ($xlsData as &$value) {
                    if($value['ratio']>0){
                        $value['ratio'] = $value['ratio'].'%';
                    }
                    $value['create_time']=date('Y-m-d H:i:s',$value['create_time']);
                }
            break;
            case 15:
                $xlsName  = "混服注册";
                $xlsCell  = array(
                    array('id','用户id'),
                    array('account','用户账号'),
                    array('game_name','游戏名称'),
                    array('create_time','注册时间'),
                    array('register_ip','注册ip'),
                    array('partner_account','所属混服账号'),
                );
                if(isset($_REQUEST['partner_account'])){
                    $map['partner_account'] = array('like','%'.$_REQUEST['partner_account'].'%');
                }
                if(isset($_REQUEST['time-start']) && isset($_REQUEST['time-end'])){
                    $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                }
                $xlsData=M('MixUser','tab_')
                ->field("id,account,game_name,register_ip,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time,partner_account")
                ->where($map) 
                ->select(); 
            break;
             case 16:
                $xlsName  = "混服充值";
                $xlsCell  = array(
                    array('pay_order_number','订单号'),
                    array('user_account','用户账号'),
                    array('gname','游戏名称'),
                    array('pay_amount','充值金额'),
                    array('pay_time','充值时间'),
                    array('pay_ip','充值ip'),
                    array('order_status','订单状态(0 未支付 1 已支付)'),
                    array('game_status','通知状态(0 失败 1成功)'),
                    array('partner_account','所属混服账号'),
                );
                if(isset($_REQUEST['partner_account'])){
                    $map['partner_account'] = array('like','%'.$_REQUEST['partner_account'].'%');
                }
                if(isset($_REQUEST['time-start']) && isset($_REQUEST['time-end'])){
                    $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                }

                $xlsData=M('MixPay','tab_')
                ->field("pay_order_number,user_account,gname,pay_amount,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as pay_time,pay_ip,order_status,game_status,partner_account")
                ->where($map) 
                ->select(); 
            break;
              case 17:
                $xlsName  = "退款记录";
                $xlsCell  = array(
                    array('pay_order_number','支付订单号'),
                    array('order_number','退款订单号'),
                    array('pay_time','充值时间'),
                    array('user_account','玩家账号'),
                    array('promote_account','所属渠道'),
                    array('game_name','游戏名称'),
                    array('pay_amount','充值金额'),
                    array('tui_amount','退款金额'),
                    array('pay_way','支付方式1支付宝;2微信'),
                    array('tui_status','退款状态(0 未退款 1 已退款)'),
                );

                if(isset($_REQUEST['user_account'])){
                    $map['user_account'] = array('like','%'.$_REQUEST['user_account'].'%');
                }

                if(isset($_REQUEST['pay_order_number'])){
                    $map['pay_order_number'] = $_REQUEST['pay_order_number'];
                }

                if(isset($_REQUEST['pay_way'])){
                    $map['pay_way'] = $_REQUEST['pay_way'];
                }

                if(isset($_REQUEST['tui_status'])){
                    $map['tui_status'] = $_REQUEST['tui_status'];
                }

                if(isset($_REQUEST['game_name'])){
                    if($_REQUEST['game_name']=='全部'){
                        unset($_REQUEST['game_name']);
                    }else{
                        $map['game_name']=$_REQUEST['game_name'];
                    }
                    unset($_REQUEST['game_name']);
                }
                
                if(isset($_REQUEST['time-start']) && isset($_REQUEST['time-end'])){
                    $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
                }

                $xlsData=M('refund_record','tab_')
                ->field("pay_order_number,order_number,user_account,promote_account,game_name,tui_amount,pay_amount,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as pay_time,pay_way,tui_status")
                ->where($map) 
                ->select(); 

            break;
              case 18:
              	$xlsName  = "实时注册";
              	$xlsCell  = array(
              			array('account','玩家账号'),
              			array('game_name','注册游戏'),
              			array('promote_account','所属推广员'),
              			array('register_time','注册时间'),
              			array('is_check','是否对账(1参与;2不参与;3参与(已对账);4不参与(已对账))'),
              	);
              	
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
              	if(isset($_REQUEST['admin'])){
              		$admin_id=get_admin_id($_REQUEST['admin']);
              		$all_promote=array_column(get_admin_promotes($admin_id),'id');
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
								
              	$xlsData = M('User','tab_')
              	->field('tab_user.id,tab_user.account,tab_user_play.game_id,tab_user_play.game_name,nickname,email,
              			phone,tab_user.promote_id,parent_id,register_time,register_way,register_ip,
              			tab_user.promote_account,parent_name,is_check,is_union')
              	->join('join tab_user_play on tab_user_play.user_id = tab_user.id')
              	->where($map)
              	->group('tab_user.id')
              	->order('register_time desc')
              	->select();
              	foreach ($xlsData as &$value) {
              		$value['register_time']=date('Y-m-d H:i:s',$value['register_time']);
              	}
              break;
              case 19:
              	$xlsName  = "实时充值";
              	$xlsCell  = array(
              			array('pay_order_number','订单号'),
              			array('pay_time','充值时间'),
              			array('account','玩家账号'),
              			array('promote_account','所属推广员'),
              			array('game_name','充值游戏'),
              			array('pay_amount','充值金额'),
              			array('spend_ip','充值IP'),
              			array('pay_way','支付方式(-1:绑定平台币0:平台币,1:支付宝,2:微信(扫码)3微信app 4 威富通 5聚宝云'),
              			array('is_check','是否对账(1参与;2不参与;3参与(已对账);4不参与(已对账))'),
              	);
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
              	if(isset($_REQUEST['promote_name'])){
              		if($_REQUEST['promote_name']=='全部'){
              			unset($_REQUEST['promote_name']);
              		}else if($_REQUEST['promote_name']=='自然注册'){
              			$map['promote_id']=array("lte",0);
              			unset($_REQUEST['promote_name']);
              		}else{
              			$map['promote_id']=get_promote_id($_REQUEST['promote_name']);
              			unset($_REQUEST['promote_name']);
              		}
              	}else{
              		$map['promote_id']=array("gt",0);
              	}
              	
              	if(isset($_REQUEST['pay_way'])){
              		$map['pay_way']=$_REQUEST['pay_way'];
              		unset($_REQUEST['pay_way']);
              	}
              	// if(isset($_REQUEST['is_check'])&&$_REQUEST['is_check']!="全部"){
              	//     $map['is_check']=check_status($_REQUEST['is_check']);
              	//     unset($_REQUEST['is_check']);
              	// }
              	if(isset($_REQUEST['user_account'])){
              		$map['tab_spend.user_account']=array('like','%'.$_REQUEST['user_account'].'%');
              		unset($_REQUEST['user_account']);
              	}
              	if(isset($_REQUEST['spend_ip'])){
              		$map['spend_ip']=array('like','%'.$_REQUEST['spend_ip'].'%');
              		unset($_REQUEST['spend_ip']);
              	}
              	if(isset($_REQUEST['promote_name'])){
              		$map['promote_account']=$_REQUEST['promote_name'];
              		unset($_REQUEST['user_account']);
              	}
              	
              	if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
              		$map['pay_time']=array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
              		unset($_REQUEST['start']);unset($_REQUEST['end']);
              	}
								
								
              	if(!empty(I('parent_id'))){
              		$pro = M('promote','tab_')->where(['parent_id'=>I('parent_id')])->select();
              		$pro_ids = array_column($pro,'id');
              		$pro_ids[] = I('parent_id');
              		$map['promote_id'] = ['in',$pro_ids];
              	}
								
								if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
									$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
									
								} elseif (!empty($_REQUEST['timestart']) ) {
									$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
									
								} elseif (!empty($_REQUEST['timeend']) ) {
									$map['pay_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								}
								
								if (!empty($_REQUEST['promote_id'])) {
									$map['tab_spend.promote_id']=$_REQUEST['promote_id'];
								} else {
									$map['tab_spend.promote_id']=array('gt',0);
									if(!empty(I('parent_id'))){
										$pro = M('promote','tab_')->where(['parent_id'=>I('parent_id')])->select();
										$pro_ids = array_column($pro,'id');
										$pro_ids[] = I('parent_id');
										$map['tab_spend.promote_id'] = ['in',$pro_ids];
									}
								}
								
              	$map['tab_spend.pay_status'] = 1;
              	$xlsData = M('Spend','tab_')
              			->where($map)
										->join("left join tab_promote p on p.id = tab_spend.promote_id")
              			->order('tab_spend.id desc')
              			->select();
              			foreach ($xlsData as &$value) {
              				$value['pay_time']=date('Y-m-d H:i:s',$value['pay_time']);
              			}
         	break;
              case 20:
              	$xlsName  = "会长代充";
              	$xlsCell  = array(
              			array('promote_account','所属推广员'),
              			array('user_account','玩家账号'),
              			array('game_id','充值游戏'),
              			array('amount','充值金额'),
              			array('zhekou','折扣比例'),
              			array('real_amount','实付金额'),
              			array('pay_status','充值状态（1成功0失败）'),
              			array('create_time','充值时间'),
              	);
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
                if($_REQUEST['data_order']!=''){
                    $data_order=reset(explode(',',$_REQUEST['data_order']))==3?'desc':'asc';
                    $data_order_type=end(explode(',',$_REQUEST['data_order']));
                    $order = $data_order_type.' '.$data_order;
                }else{
                    $order = 'id desc';
                }
              	empty(I('promote_id')) || $map['promote_id'] = I('promote_id');
              	$xlsData = M('Agent','tab_')
              	->where($map)
                ->order($order)
              	->select();
              	foreach ($xlsData as &$value) {
                    $value['create_time']=date('Y-m-d H:i:s',$value['create_time']);
              		$value['game_id']=get_game_name($value['game_id']);
              	}
              	break;
              case 21:
              	$xlsName  = "推广提现";
              	$xlsCell  = array(
              			array('settlement_number','提现单号'),
              			array('sum_money','提现金额'),
              			array('promote_account','推广员账号'),
              			array('create_time','申请时间'),
              			array('status','提现状态（0:审核中;1:已通过;2:已拒绝;）'),
              			array('end_time','审核时间'),
              	);
              	if(isset($_REQUEST['settlement_number'])){
              		$map['settlement_number']=$_REQUEST['settlement_number'];
              	}
              	if(isset($_REQUEST['status'])){
              		$map['status']=$_REQUEST['status'];
              	}
              	if(isset($_REQUEST['promote_account'])){
              		if($_REQUEST['promote_account']=='全部'){
              			unset($_REQUEST['promote_account']);
              		}else{
              			$map['promote_id'] = array('gt',0);
              		}
              	}
              	$xlsData = M('withdraw','tab_')
              	->where($map)
              	->select();
              	foreach ($xlsData as &$value) {
              		$value['create_time']=date('Y-m-d H:i:s',$value['create_time']);
                    if($value['end_time']!=''){
              		    $value['end_time']=date('Y-m-d H:i:s',$value['end_time']);
                    }else{
                        $value['end_time']='未审核';
                    }
              	}
              	break;
              case 22:
              	$xlsName  = "推广结算";
              	$xlsCell  = array(
              			array('settlement_number','结算单号'),
              			array('starttime','结算周期'),
              			array('promote_account','推广员账号'),
              			array('game_name','结算游戏'),
              			array('total_money','总充值'),
              			array('pattern','合作模式'),
              			array('ratio','分成比例'),
              			array('money','注册单价'),
              			array('sum_money','结算金额'),
              			array('create_time','结算时间'),
              	);

								if (!empty($_REQUEST['stimestart']) && !empty($_REQUEST['stimeend'])) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['stimestart']),strtotime($_REQUEST['stimeend'])+24*60*60-1));
									
								} elseif (!empty($_REQUEST['stimestart']) ) {
									$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['stimestart']),time()));
									
								} elseif (!empty($_REQUEST['stimeend']) ) {
									$map['create_time'] = array('elt',strtotime($_REQUEST['stimeend'])+24*60*60-1);
								
								}
								
								if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
									$map['starttime']=array('egt',strtotime($_REQUEST['timestart']));
									$map['endtime']=array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								} elseif (!empty($_REQUEST['timestart']) ) {
									$map['starttime']=array('egt',strtotime($_REQUEST['timestart']));
									
								} elseif (!empty($_REQUEST['timeend']) ) {
									$map['endtime']=array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
									
								}
								
              	
              	if(isset($_REQUEST['game_name'])){
              		if($_REQUEST['game_name']=='全部'){
              			unset($_REQUEST['game_name']);
              		}else{
              			$map['game_name'] = $_REQUEST['game_name'];
              		}
              	}
              	if(isset($_REQUEST['promote_account'])){
              		if($_REQUEST['promote_account']=='全部'){
              			unset($_REQUEST['promote_account']);
              		}else{
              			$map['promote_account'] = $_REQUEST['promote_account'];
              		}
              	}
              	if(!empty($_REQUEST['settlement_number'])){
              		$map['settlement_number'] = $_REQUEST['settlement_number'];
              	}
              	
              	$xlsData = M('settlement','tab_')
              	->where($map)
              	->order('create_time desc')
              	->select();
              	foreach ($xlsData as &$value) {
              		$value['pattern'] = get_pattern($data['pattern']);
              		$value['create_time']=date('Y-m-d H:i:s',$value['create_time']);
              		//结算周期
              		$value['starttime']=date('Y-m-d',$value['starttime']).'至'.date('Y-m-d',$value['endtime']);
              	}
              	break;
              case 23:
              	$xlsName  = "推广员列表";
              	$xlsCell  = array(
              			array('id','编号'),
              			array('account','推广员账号'),
              			array('nickname','昵称'),
              			array('mobile_phone','手机号'),
              			array('email','邮箱'),
              			array('real_name','真实姓名'),
              			array('balance_coin','平台币余额'),
              			//get_qu_promote($data['parent_id'])
              			array('parent_id','推广员等级'),
              			//set_show_time($data['create_time'])
              			array('create_time','注册时间'),
              	);              	
              	if(isset($_REQUEST['account'])){
              		$map['account']=array('like','%'.$_REQUEST['account'].'%');
              		unset($_REQUEST['account']);
              	}
              	if(isset($_REQUEST['busier_id'])){
              		$map['busier_id']= $_REQUEST['busier_id'];
              		unset($_REQUEST['busier_id']);
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
              	if(isset($_REQUEST['status'])){
              		$map['status']= $_REQUEST['status'];
              		unset($_REQUEST['status']);
              	}
              	$xlsData=M('Promote','tab_')
              	->field("id,account,nickname,mobile_phone,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time,email,
							real_name,balance_coin,parent_id")
							->where($map)
							->select();
				foreach ($xlsData as $key=>$va){
					$xlsData[$key]['parent_id'] = get_qu_promote($va['parent_id']);
				}
              	break;
              case 24:
              	$xlsName  = "注册统计";
              	$xlsCell  = array(
              			array('fgame_name','游戏名称'),
              			array('count','总注册'),
              			array('rand','排行'),
              			array('today','今日注册'),
              			array('week','本周注册'),
              			array('mounth','本月注册'),
              	);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}

              	if(isset($_REQUEST['game_name'])&&$_REQUEST['game_name']!=''){
              		$map['fgame_name'] =$_REQUEST['game_name'];
              		unset($_REQUEST['fgame_name']);
              	}
              	$xlsData=M('User','tab_')
              	->field('fgame_name,fgame_id,date_format(FROM_UNIXTIME( register_time),"%Y-%m-%d") AS time, count(id) as count')
                ->where($map)
                ->group('fgame_id')
                ->order('count desc')
                ->select();
              	$register = new PlatformController();
				foreach ($xlsData as $key=>$va){
					static $i=0;
                    if($xlsData[$key]['fgame_id']==0){
                        unset($xlsData[$key]);
                        continue;
                    }
                    $i++;
                    $xlsData[$key]['rand']=$i;
					$adata=$register->day_data('Spend',array('promote_id'=>$value['promote_id'],'pay_status'=>'1'));
					$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
					$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
					$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
				}
                $xlsData = array_values($xlsData);
				break;
              case 25:
              	$xlsName  = "游戏充值统计";
              	$xlsCell  = array(
              			array('game_name','游戏名称'),
              			array('count','总充值'),
              			array('rand','排行'),
              			array('today','今日充值'),
              			array('week','本周充值'),
              			array('mounth','本月充值'),
              	);
              	if(I('isbd')==1){
              		$isbdpw['pay_way'] = array('neq',-1);
              	}else{
              		$isbdpw['id'] = array('gt',0);
              	}
              	$map['game_id']=array('gt',0);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}
              	
              	if(isset($_REQUEST['game_name'])&&$_REQUEST['game_name']!=''){
              		$map['game_name'] =$_REQUEST['game_name'];
              		unset($_REQUEST['game_name']);
              	}
              	$map['pay_status']=1;
              	$xlsData=M('Spend','tab_')
              	->field('game_name,game_id,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
              	->where($map)
              	->where($isbdpw)
              	->group('game_id')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              		static $i=0;
              		$i++;
              		$xlsData[$key]['rand']=$i;
              		$adata=$register->day_data('Spend',array('promote_id'=>$value['promote_id'],'pay_status'=>'1'));
              		$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
              		$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
              		$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
              	}
              	break;
              case 26:
              	$xlsName  = "注册方式统计";
              	$xlsCell  = array(
              			array('register_way','注册方式'),
              			array('count','总注册'),
              			array('rand','排行'),
              			array('today','今日注册'),
              			array('week','本周注册'),
              			array('mounth','本月注册'),
              	);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}
              	// var_dump($_REQUEST);exit;
              	if(isset($_REQUEST['register_way'])&&$_REQUEST['register_way']!=''){
              		$map['register_way'] =$_REQUEST['register_way'];
              		unset($_REQUEST['register_way']);
              	}
              	
              	$xlsData=M('User','tab_')
              	->field('register_way,date_format(FROM_UNIXTIME(register_time),"%Y-%m-%d") AS time, count(id) as count')
              	->where($map)
              	->group('register_way')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              		static $i=0;
              		$i++;
              		$xlsData[$key]['rand']=$i;
              		$adata=$register->day_data('User',array('register_way'=>$value['register_way']));
              		$xlsData[$key]['register_way']=get_register_way($va['register_way']);
              		$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
              		$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
              		$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
              	}
              	break;
              case 27:
              	$xlsName  = "充值方式统计";
              	$xlsCell  = array(
              			array('pay_way','充值方式'),
              			array('count','累计充值'),
              			array('rand','排行'),
              			array('today','今日充值'),
              			array('week','本周充值'),
              			array('mounth','本月充值'),
              	);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}
              	// var_dump($_REQUEST);exit;
              	if(isset($_REQUEST['pay_way'])&&$_REQUEST['pay_way']!=''){
              		$map['pay_way'] =$_REQUEST['pay_way'];
              		unset($_REQUEST['pay_way']);
              	}
              	$map['pay_status']=1;
              	$xlsData=M('Spend','tab_')
              	->field('pay_way,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
              	->where($map)
              	->group('pay_way')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              		static $i=0;
              		$i++;
              		$xlsData[$key]['rand']=$i;
              		$adata=$register->day_data('Spend',array('pay_way'=>$value['pay_way']));
              		$xlsData[$key]['register_way']=get_register_way($va['register_way']);
              		$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
              		$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
              		$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
              	}
              	break;
              case 28:
              	$xlsName  = "推广员注册统计";
              	$xlsCell  = array(
              			array('promote_account','推广员账号'),
              			array('count','累计注册'),
              			array('rand','排行'),
              			array('today','今日注册'),
              			array('week','本周注册'),
              			array('mounth','本月注册'),
              	);
              	$map['promote_id']=array('egt',1);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}
              	// var_dump($_REQUEST);exit;
              	if(isset($_REQUEST['promote_id'])){
              		$map['promote_id'] =$_REQUEST['promote_id'];
              		unset($_REQUEST['promote_id']);
              	}
              	$xlsData=M('User','tab_')
              	->field('promote_account,promote_id,date_format(FROM_UNIXTIME(register_time),"%Y-%m-%d") AS time, count(tab_user.id) as count')
              	->where($map)
              	->join('tab_promote on tab_user.promote_id = tab_promote.id')
              	->group('promote_id')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              		static $i=0;
              		$i++;
              		$xlsData[$key]['rand']=$i;
              		$adata=$register->day_data('User',array('promote_id'=>$va['promote_id']));
              		$xlsData[$key]['register_way']=get_register_way($va['register_way']);
              		$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
              		$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
              		$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
              	}
              	break;
              case 29:
              	$xlsName  = "推广员充值统计";
              	$xlsCell  = array(
              			array('promote_account','推广员账号'),
              			array('count','累计充值'),
              			array('rand','排行'),
              			array('today','今日充值'),
              			array('week','本周充值'),
              			array('mounth','本月充值'),
              	);
              	
              	if(I('isbd')==1){
              		$isbdpw['pay_way'] = array('neq',-1);
              	}else{
              		$isbdpw['tab_spend.id'] = array('gt',0);
              	}
              	
              	$map['pay_status']=1;
              	
              	$map['promote_id']=array('egt',1);
              	if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
              		$map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
              		unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
              	}
              	if(isset($_REQUEST['promote_id'])){
              		$map['promote_id'] =$_REQUEST['promote_id'];
              		unset($_REQUEST['promote_id']);
              	}
              	$xlsData=M('Spend','tab_')
              	->field('promote_account,promote_id,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
              	->join('tab_promote on tab_spend.promote_id = tab_promote.id')
              	->where($map)
              	->where($isbdpw)
              	->group('promote_id')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              		static $i=0;
              		$i++;
              		$xlsData[$key]['rand']=$i;
              		$adata=$register->day_data('Spend',array('promote_id'=>$va['promote_id'],'pay_status'=>'1'));
              		$xlsData[$key]['register_way']=get_register_way($va['register_way']);
              		$xlsData[$key]['today']=$adata['today']==''?0:$adata['today'];
              		$xlsData[$key]['week']=$adata['week']==''?0:$adata['week'];
              		$xlsData[$key]['mounth']=$adata['mounth']==''?0:$adata['mounth'];
              	}
              	break;
              case 30:
              	$xlsName  = "ARPU分析";
              	$xlsCell  = array(
              			array('promote_account','日期'),
              			array('count','新增玩家'),
              			array('rand','活跃玩家'),
              			array('today','1日留存'),
              			array('week','充值'),
              			array('mounth','付费玩家'),
              			array('mounth','新付费玩家'),
              			array('mounth','付费率'),
              			array('mounth','ARPU'),
              			array('mounth','ARPPU'),
              			array('mounth','累计付费玩家'),
              	);
              	$xlsData=M('Spend','tab_')
              	->field('promote_account,promote_id,date_format(FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS time, sum(pay_amount) as count')
              	->join('tab_promote on tab_spend.promote_id = tab_promote.id')
              	->where($map)
              	->where($isbdpw)
              	->group('promote_id')
              	->order('count desc')
              	->select();
              	$register = new PlatformController();
              	foreach ($xlsData as $key=>$va){
              	}
              	break;
                case 31:
                $xlsName  = "汇总查询";
                $xlsCell  = array(
                        array('busier_account','商务专员账号'),
                        array('count','旗下推广员数量'),
                        array('register_num','总注册（个）'),
                        array('spend_num','总充值（元）'),
                );
                if(isset($_REQUEST['timestart'])&&isset($_REQUEST['timeend'])){
                    $map2['pay_time'] = $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
                    unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
                }
                if(isset($_REQUEST['busier_id'])){
                    $map1['id'] = $_REQUEST['busier_id'];
                    unset($_REQUEST['busier_id']);
                }
                $xlsData = M('Busier','tab_')->where($map1)->select();
                foreach ($xlsData as $key=>$value){
                    if($value['promote_user']){
                        $map2['promote_id'] = $map['promote_id'] = array('in',$value['promote_user']);
                        $map2['pay_status'] = 1;
                        //总注册数量
                        $xlsData[$key]['register_num'] = M('User','tab_')->field('id')->where($map)->count();
                        $xlsData[$key]['spend_num'] = M('Spend','tab_')->where($map2)->sum('pay_amount');
                        $xlsData[$key]['spend_num'] = $xlsData[$key]['spend_num']>0?$xlsData[$key]['spend_num']:0;
                        $xlsData[$key]['count'] = get_busier_num($value['id']);
                    }else{
                        $xlsData[$key]['register_num'] = 0;
                        $xlsData[$key]['spend_num'] = 0;
                        $xlsData[$key]['count'] = 0;
                    }
                }
                break;
     	}
     	   $this->exportExcel($xlsName,$xlsCell,$xlsData);

     }
	
}