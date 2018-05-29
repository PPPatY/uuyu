<?php
namespace Commerce\Controller;
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
            $Commissioner = D('Commissioner');
            $game_data= $Commissioner->show_game($_SESSION['user_auth_commerce']['account']);
            $account_data= $Commissioner->show_promote($_SESSION['user_auth_commerce']['account']);
            if(empty(  $Commissioner->show_promote_id($_SESSION['user_auth_commerce']['account'])) || empty($game_data) || empty($account_data)){
                    $this->error('您没有数据');die;
            }
         switch ($id) {
            case 10:
                 $xlsName = "注册充值";
                 $xlsCell = array(
                     array('id', 'ID'),
                     array('user_account', '用户名'),
                     array('game_name', '注册游戏'),
                     array('play_time', '注册日期'),
                     array('promote_account', '推广员'),
                 );
                 //限制条件 当前商务专员下  注册的游戏名
                 $str =  $Commissioner->arr_to_str( $game_data,'game_name');
                 $map['game_name'] = array('in',$str);
                    $str =  $Commissioner->arr_to_str( $account_data,'account');
                 $map['promote_account'] = array('in',$str);
                 $xlsData = M('User_play','tab_')->field("id,user_account,game_name,promote_account,play_time")->where($map)->order('id desc')->select();
                 foreach ($xlsData as $k => $v) {
                     $xlsData[$k]['play_time'] = date("Y-m-d H:i:s",$v['play_time']);
                }
                 break;
            case 11://buydetail
                 $xlsName = "充值明细";
                 $xlsCell = array(
                     array('id', 'ID'),
                     array('user_account', '用户帐号'),
                     array('order_number', '订单号'),
                     array('game_name', '游戏名称'),
                     array('cost', '应付金额'),
                     array('pay_amount', '实付金额'),
                     array('pay_way', '支付方式'),
                     array('create_time', '充值时间'),
                     array('pay_status', '充值状态'),
                     array('promote_account', '推广员'),
                 );
                 //限制条件 当前商务专员下 所有的推广渠道账号 和 注册的游戏名
                 $str =  $Commissioner->arr_to_str( $game_data,'game_name');
                 $map['game_name'] = array('in',$str);
                  $str =  $Commissioner->arr_to_str( $account_data,'account');
                 $map['promote_account'] = array('in',$str);
                 $xlsData = M('spend','tab_')->field("id,user_account,order_number,game_name,cost,pay_amount,FROM_UNIXTIME(pay_time,'%Y-%m-%d %H:%i:%s') as create_time,pay_status,pay_way,promote_account")->where($map)->order('id desc')->select();
                 foreach ($xlsData as $k => $data) {
                    if($xlsData[$k]['pay_way'] == 0)$xlsData[$k]['pay_way'] = '平台币';
                    if($xlsData[$k]['pay_way'] == 1){$xlsData[$k]['pay_way'] = '支付宝';}
                    if($xlsData[$k]['pay_way'] == 2)$xlsData[$k]['pay_way'] = '微信';
                    if($xlsData[$k]['pay_way'] == 3)$xlsData[$k]['pay_way'] = '微信app';
                    if($xlsData[$k]['pay_way'] == 4)$xlsData[$k]['pay_way'] = '富通';
                    if($xlsData[$k]['pay_way'] == 5)$xlsData[$k]['pay_way'] = '聚宝云';
                    if($xlsData[$k]['pay_way'] == 6)$xlsData[$k]['pay_way'] = '竣付通';
                    if($xlsData[$k]['pay_status'] == 1)$xlsData[$k]['pay_status'] = '已充值';
                    if($xlsData[$k]['pay_status'] == 0)$xlsData[$k]['pay_status'] = '未充值';
                 }
                 break;
            case 12:
                 $xlsName = "数据汇总";
                 $xlsCell = array(
                     array('play_time','时间'),
                     array('promote_account', '推广员帐号'),
                     array('game_name', '游戏名称'),
                     array('total_number', '总注册（个）'),
                     array('cost', '充值人次（个）'),
                     array('total_money', '总充值'),
                 );
                 $str =  $Commissioner->arr_to_str( $game_data,'game_name');
                 $map['game_name'] = array('in',$str);
                  $str =  $Commissioner->arr_to_str( $account_data,'account');
                 $map['promote_account'] = array('in',$str);
                 $xlsData = M('User_play','tab_')->field("id,game_name,promote_account,play_time")->where($map)->order('id desc')->select();
                foreach ($xlsData as $key => $value) {
                    $xlsData[$key]['play_time'] = date('Y-m-d H:i:s',$value['play_time']);
                    $xlsData[$key]['total_number'] = get_play_count($value['game_name'],$value['promote_account']);
                    $xlsData[$key]['cost'] = get_spend_count($value['game_name'],$value['promote_account']);
                    $xlsData[$key]['total_money'] = get_spend_num($value['game_name'],$value['promote_account']);
                    ;
                }
                 break;
     	}
         $this->exportExcel($xlsName, $xlsCell, $xlsData);
     }
	
}