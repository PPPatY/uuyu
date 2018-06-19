<?php
namespace Home\Controller;
use Think\Controller;
class ExportController extends Controller
{
	public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称  
//        $fileName = session('promote_auth.account').date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $fileName=$expTitle;
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
        ob_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }

	//导出Excel
     function expUser($id)
     {
         switch ($id) {
             case 1:
                 $xlsName = "代充汇总";
                 $xlsCell = array(
                     array('user_account', '账号'),
                     array('game_name', '游戏名称'),
                     array('pay_order_number', '流水号'),
                     array('amount', '充值金额'),
                     array('real_amount', '实扣金额'),
                     array('pay_status', '支付状态'),
                     array('pay_way', '支付方式'),
                     array('create_time', '充值时间'),
                 );
                 $map['promote_id'] = session('promote_auth.pid');
                 if (isset($_REQUEST['user_account'])) {
                     $map['user_account'] = array('like', '%' . $_REQUEST['user_account'] . '%');
                 }
                 if (isset($_REQUEST['game_id'])) {
                     if ($_REQUEST['game_id'] == '0') {
                         unset($_REQUEST['game_id']);
                     } else {
                         $map['game_id'] = $_REQUEST['game_id'];
                         unset($_REQUEST['game_id']);
                     }
                 }
                 if (!empty($_REQUEST['time-start']) && !empty($_REQUEST['time-end'])) {
                     $map['create_time'] = array('BETWEEN', array(strtotime($_REQUEST['time-start']), strtotime($_REQUEST['time-end']) + 24 * 60 * 60 - 1));
                     unset($_REQUEST['time-start']);
                     unset($_REQUEST['time-end']);
                 }
                 if (!empty($_REQUEST['start']) && !empty($_REQUEST['end'])) {
                     $map['create_time'] = array('BETWEEN', array(strtotime($_REQUEST['start']), strtotime($_REQUEST['end']) + 24 * 60 * 60 - 1));
                     unset($_REQUEST['start']);
                     unset($_REQUEST['end']);
                 }elseif(!empty($_REQUEST['start'])){
                     $map['create_time']  =  array('egt',strtotime($_REQUEST['start']));
                     unset($_REQUEST['time-start']);
                 }elseif(!empty($_REQUEST['end'])){
                     $map['create_time']  =  array('lt',strtotime($_REQUEST['end'])+24*60*60);
                     unset($_REQUEST['end']);
                 }
                 $xlsData = M('agent', 'tab_')
                     ->field("user_account,pay_order_number,game_name,amount,real_amount,pay_status,pay_way,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time")
                     ->where($map)
                     ->order("id desc")
                     ->select();
                 foreach ($xlsData as &$val){
                     $val['pay_status'] = get_info_status($val['pay_status'],7);
                     $val['pay_way'] = get_pay_way1($val['pay_way']);
                 }
                 break;
             case 2:
                 $xlsName = "代充记录";
                 $xlsCell = array(
                     array('promote_account', '代理账号'),
                     array('parent_account', '父级账号'),
                     array('order_number', '流水号'),
                     array('amount', '充值金额'),
                     array('create_time', '充值时间'),
                 );
                 if (isset($_REQUEST['promote_account']) && $_REQUEST['promote_account'] !== "") {
                     $map['promote_account'] = array("like", "%" . $_REQUEST['promote_account'] . "%");
                     unset($_REQUEST['promote_account']);
                 }
                 $map['parent_id'] = session('promote_auth.pid');
                 $xlsData = M('PayAgents', 'tab_')
                     ->field("promote_account,parent_account,order_number,amount,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time")
                     ->where($map)
                     ->order("create_time")
                     ->select();
                 break;
             case 3:
                 $xlsName = "购买记录";
                 $xlsCell = array(
                     array('pay_order_number', '订单号'),
                     array('promote_account', '渠道账号'),
                     array('amount', '充值金额'),
                     array('pay_status', '状态(0 未充值 1 已充值)'),
                     array('create_time', '充值时间'),
                 );
                 if (isset($_REQUEST['promote_account']) && $_REQUEST['promote_account'] !== "") {
                     $map['promote_account'] = array("like", "%" . $_REQUEST['promote_account'] . "%");
                     unset($_REQUEST['promote_account']);
                 }
                 $map['promote_id'] = session('promote_auth.pid');
                 $map['pay_status'] = 1;
                 $xlsData = M('ProSpend', 'tab_')
                     ->field("pay_order_number,promote_account,promote_account,amount,pay_status,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time")
                     ->where($map)
                     ->select();
                 break;
             case 4:
                 $xlsName = "充值明细";
                 $xlsCell = array(
                     array('user_account', '用户帐户'),
                     array('pay_order_number', '订单号'),
                     array('game_name', '游戏名称'),
                     array('pay_amount', '实付金额'),
                     array('pay_time','充值时间'),
                     array('pay_status', '状态(0 未充值 1 已充值)'),
                     array('pay_way', '支付方式(-1绑币 0平台币 1支付宝 2微信)'),
                     array('promote_account','渠道'),
                 );
                 $pro_id = get_prmoote_chlid_account(session('promote_auth.pid'));
                 foreach ($pro_id as $key => $value) {
                     $pro_id1[] = $value['id'];
                 }
                 if (!empty($pro_id1)) {
                     $pro_id2 = array_merge($pro_id1, array(get_pid()));
                 } else {
                     $pro_id2 = array(get_pid());
                 }
                 $map['promote_id'] = array('in', $pro_id2);
                 if (isset($_REQUEST['user_account']) && trim($_REQUEST['user_account'])) {
                     $map['user_account'] = array('like', '%' . $_REQUEST['user_account'] . '%');
                     unset($_REQUEST['user_account']);
                 }
                 if (!empty($_REQUEST['game_appid']) && $_REQUEST['game_appid'] != '') {
                     $map['game_appid'] = $_REQUEST['game_appid'];
                 }
                 if ($_REQUEST['promote_id'] > 0) {
                     $map['promote_id'] = $_REQUEST['promote_id'];
                 }
                 if (!empty($_REQUEST['time-start']) && !empty($_REQUEST['time-end'])) {
                     $map['pay_time'] = array('BETWEEN', array(strtotime($_REQUEST['time-start']), strtotime($_REQUEST['time-end']) + 24 * 60 * 60 - 1));
                     unset($_REQUEST['time-start']);
                     unset($_REQUEST['time-end']);
                 }
                 if (!empty($_REQUEST['start']) && !empty($_REQUEST['end'])) {
                     $map['pay_time'] = array('BETWEEN', array(strtotime($_REQUEST['start']), strtotime($_REQUEST['end']) + 24 * 60 * 60 - 1));
                     unset($_REQUEST['start']);
                     unset($_REQUEST['end']);
                 }elseif (!empty($_REQUEST['start'])) {
                     $map['pay_time'] = array('egt', strtotime($_REQUEST['start']));
                     unset($_REQUEST['start']);
                 }elseif (!empty($_REQUEST['end'])) {
                     $map['pay_time'] = array('lt', strtotime($_REQUEST['end']) + 24 * 60 * 60);
                     unset($_REQUEST['end']);
                 }
                 

                 if (empty($_REQUEST['isbd'])) {
                     $map['pay_way'] = array('egt',0);
                 }else{
                     $map['pay_way'] = array('lt',0);
                 }
                 
                 $map['pay_status'] = 1;
                 $map['is_check']=array('neq',2);
                 
                 $xlsData = M('Spend', 'tab_')
                     ->field("tab_spend.*,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as pay_time")
                     ->where($map)
                     ->order('pay_time desc')
                     ->select();
                 break;
             case 5:
                 $xlsName = "我的游戏";
                 $xlsCell = array(
                     array('promote_account', '申请账号'),
                     array('game_name', '游戏名称'),
                     array('game_type_name', '类型'),
                     array('money', '注册单价'),
                     array('ratio', '分成比例'),
                     array('status', '状态(0待审核 1已审核)'),
                 );
                 if (isset($_REQUEST['game_name']) && $_REQUEST['game_name'] != null) {
                     $map['tab_game.game_name'] = array('like', '%' . trim($_REQUEST['game_name']) . '%');
                 }
                 $map['promote_id'] = session("promote_auth.pid");
                 if ($_REQUEST['type'] == -1 || !isset($_REQUEST['type'])) {
                     unset($map['status']);
                 } else {
                     $map['status'] = $_REQUEST['type'];
                 }
                 $xlsData = M("game", "tab_")
                     ->field("tab_game.*,tab_apply.promote_id,tab_apply.promote_account,tab_apply.status")
                     ->join("tab_apply ON tab_game.id = tab_apply.game_id and tab_apply.promote_id = " . session('promote_auth.pid'))
                     // 查询条件
                     ->order("apply_time desc")
                     ->where($map)
                     ->select();
                 break;
             case 6:
                 $xlsName = "注册明细";
                 $xlsCell = array(
                     array('id','ID'),
                     array('account', '玩家账号'),
                     array('fgame_name','注册游戏'),
                     array('register_time', '注册时间'),
                     array('register_ip', '注册IP'),
                     array('promote_account', '推广人员'),
                 );
                 $pro_id = get_prmoote_chlid_account(session('promote_auth.pid'));
                 foreach ($pro_id as $key => $value) {
                     $pro_id1[] = $value['id'];
                 }
                 if (!empty($pro_id1)) {
                     $pro_id2 = array_merge($pro_id1, array(get_pid()));
                 } else {
                     $pro_id2 = array(get_pid());
                 }
                 $map['promote_id'] = array('in', $pro_id2);
                 if (isset($_REQUEST['account']) && trim($_REQUEST['account'])) {
                     $map['account'] = array('like', '%' . $_REQUEST['account'] . '%');
                     unset($_REQUEST['user_account']);
                 }
                 if (isset($_REQUEST['game_id']) && $_REQUEST['game_id'] != 0) {
                     $map['fgame_id'] = $_REQUEST['game_id'];
                 }
                 if ($_REQUEST['promote_id'] > 0) {
                     $map['promote_id'] = $_REQUEST['promote_id'];
                 }
                 if (!empty($_REQUEST['time-start']) && !empty($_REQUEST['time-end'])) {
                     $map['register_time'] = array('BETWEEN', array(strtotime($_REQUEST['time-start']), strtotime($_REQUEST['time-end']) + 24 * 60 * 60 - 1));
                 }
                 if (!empty($_REQUEST['start']) && !empty($_REQUEST['end'])) {
                     $map['register_time'] = array('BETWEEN', array(strtotime($_REQUEST['start']), strtotime($_REQUEST['end']) + 24 * 60 * 60 - 1));
                 }elseif (!empty($_REQUEST['start'])) {
                     $map['register_time'] = array('egt', strtotime($_REQUEST['start']));
                 }elseif (!empty($_REQUEST['end'])) {
                     $map['register_time'] = array('lt', strtotime($_REQUEST['end']) + 24 * 60 * 60);
                 }
                 $map['is_check'] = array('neq', 2);
                 $xlsData = M("User", "tab_")
                     ->field("tab_user.*,FROM_UNIXTIME(register_time,'%Y-%m-%d %H:%i:%s') as register_time")
                     // 查询条件
                     ->order('register_time desc')
                     ->where($map)
                     ->select();
                 break;
             case 7:
                 $xlsName = "我的对账单";
                 $xlsCell = array(
                     array('bill_number', '对账单号'),
                     array('bill_time', '对账单时间'),
                     array('promote_account', '所属渠道'),
                     array('game_name', '游戏名称'),
                     array('total_money', '充值总额'),
                     array('total_number', '注册人数'),
                     array('status', '状态(0未对账;1已对账)'),
                 );
                 $map['promote_id'] = get_pid();
                 if (isset($_REQUEST['bill_number']) && !empty($_REQUEST['bill_number'])) {
                     $map['bill_number'] = $_REQUEST['bill_number'];
                 }
                 if (isset($_REQUEST['game_id']) && !empty($_REQUEST['game_id'])) {
                     $map['game_id'] = $_REQUEST['game_id'];
                 }
                 if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
                     $map['bill_start_time'] = array('egt', strtotime($_REQUEST['timestart']));
                     $map['bill_end_time'] = array('elt', strtotime($_REQUEST['timeend']) + 24 * 3600 - 1);
                 }
                 $xlsData = M("Bill", "tab_")
                     ->field("tab_bill.*")
                     // 查询条件
                     ->where($map)
                     ->select();
                 break;
             case 8:
                 $xlsName = "平台入账记录";
                 $xlsCell = array(
                     array('id', 'ID'),
                     array('create_time', '发放时间'),
                     array('num', '平台币数量'),
                     array('source_id', '平台币来源'),
                 );
                 $map['type'] = 1;
                 $map['promote_id'] = session('promote_auth.pid');
                 if($_REQUEST['timeend'])$end_time = strtotime($_REQUEST['timeend'])+24*60*60-1;
                 $start_time = strtotime($_REQUEST['timestart']);
                 if (!empty($end_time) && !empty($start_time)) {
                     $map['create_time'] = ['between', [$start_time, $end_time]];
                 }elseif(!empty($end_time)){
                     $map['create_time'] = ['elt',$end_time];
                 }elseif(!empty($start_time)){
                     $map['create_time'] = ['gt',$start_time];
                 }
                 $xlsData = D('PromoteCoin')->where($map)->order('create_time desc')->select();
                 foreach ($xlsData as $k => $v) {
                     $xlsData[$k]['create_time'] = time_format($v['create_time']);
                     $xlsData[$k]['source_id'] = $v['source_id']>0?"上级渠道":get_status_text(4,$v['source_id']);
                 }
                 break;
             case 9:
                 $xlsName = "平台转移记录";
                 $xlsCell = array(
                     array('id', 'ID'),
                     array('create_time', '发放时间'),
                     array('num', '平台币数量'),
                     array('source_id', '转移帐号'),
                 );
                 $map['source_id'] = ['neq', '0'];
                 $map['type'] = 2;
                 $map['promote_id'] = session('promote_auth.pid');
                 $end_time = strtotime(I('timeend'));
                 $start_time = strtotime(I('timestart'));
                 if (!empty($end_time) && !empty($start_time)) {
                     $map['create_time'] = ['between', [$start_time, $end_time+86399]];
                 }elseif(!empty($end_time)){
                     $map['create_time'] = ['elt',$end_time+86399];
                 }elseif(!empty($start_time)){
                     $map['create_time'] = ['gt',$start_time];
                 }
                 $xlsData = D('PromoteCoin')->where($map)->order('create_time desc')->select();
                 foreach ($xlsData as $k => $v) {
                     $xlsData[$k]['create_time'] = time_format($v['create_time']);
                     $xlsData[$k]['source_id'] = get_promote_account($v['source_id']);
                }
                 break;
             case 10:
                 $xlsName = "子渠道游戏";
                 $xlsCell = array(
                     array('promote_account', '子渠道账号'),
                     array('game_name', '游戏名称'),
                     array('dispose_time', '审核时间'),
                     array('promote_money', '注册单价'),
                     array('promote_ratio', '分成比例'),
                 );

                 if (isset($_REQUEST['game_id']) && $_REQUEST['game_id'] != null) {
                     $map['tab_game.id'] = trim($_REQUEST['game_id']);
                 }

                 $start_time = strtotime(I('time_start'));
                 $end_time   = strtotime(I('time_end'));
                 if(!empty($start_time)&&!empty($end_time)){
                     $map['tab_apply.dispose_time']  = ['BETWEEN',[$start_time,$end_time+24*60*60-1]];
                     unset($_REQUEST['time_start']);unset($_REQUEST['time_end']);
                 }else if(!empty($start_time)){
                     $map['tab_apply.dispose_time'] = array('gt',$start_time);
                 }else if(!empty($end_time)){

                     $map['tab_apply.dispose_time'] = array('lt',$end_time+24*60*60-1);
                 }

                 $sid = M('Promote','tab_')->field('id')->where(array('parent_id'=>is_login_promote(),'status'=>1))->select();

                 if ($sid){
                     $map['tab_apply.promote_id']=array('in',array_column($sid,'id'));
                 }else{
                     $map['tab_apply.promote_id']=-1;
                 }

                 if ($_REQUEST['type'] == -1 || !isset($_REQUEST['type'])) {
                     unset($map['status']);
                 } else {
                     $map['status'] = $_REQUEST['type'];
                 }

                 $xlsData = M("game", "tab_")
                     ->field("tab_game.*,tab_apply.promote_id,tab_apply.promote_account,tab_apply.status,tab_apply.promote_money,tab_apply.promote_ratio,tab_apply.dispose_time")
                     ->join("tab_apply ON tab_game.id = tab_apply.game_id")
                     // 查询条件
                     ->order("apply_time desc")
                     ->where($map)
                     ->select();

                 //格式化时间戳
                 foreach($xlsData as &$v){
                     $v['dispose_time'] = date("Y-m-d H:i:s",$v['dispose_time']);
                 }
                 unset($v);

                 break;

     	}
         $this->exportExcel($xlsName, $xlsCell, $xlsData);
     }
	
}