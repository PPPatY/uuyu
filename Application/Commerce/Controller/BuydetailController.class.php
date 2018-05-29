<?php
namespace Commerce\Controller;
use Think\Controller;
class BuydetailController extends MainController{
    public function index(){
        $this->display();
    }
    protected function _initialize(){
       //判断是否已经登录
        //$this->check_login();
    }

    /**
    *注册查询页面
    *@author 小纯洁
    */
    public function registSearch($p = 0){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 8;

        if(isset($_REQUEST['username'])){
            $map['account'] =  array('like','%'.$_REQUEST['username'].'%');
            unset($_REQUEST['username']);
        }

        if(isset($_REQUEST['game_id'])){
            $map['fgame_id'] = $_REQUEST['game_id'];
            unset($_REQUEST['game_id']);
        }
				
				
				if (!empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
					$map['register_time'] = array('BETWEEN',array(strtotime(I('begtime')),(strtotime(I('endtime')) + 24 * 60 * 60 - 1)));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				} elseif (!empty($_REQUEST['begtime']) && empty($_REQUEST['endtime'])) {
					$map['register_time']= array('egt',strtotime(I('begtime')));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				} elseif (empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
					$map['register_time']= array('elt',(strtotime(I('endtime')) + 24 * 60 * 60 - 1));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				}
				

				if(isset($_REQUEST['promote_id'])){
            $map['promote_id'] = $_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
        } else{
        $model = M("Busier","tab_")->field('promote_user')->find($_SESSION['user_auth_commerce']['uid']);
        if($model['promote_user']){
            $pm='';
            $promote = M('Promote','tab_')->field('id')->where(array('parent_id'=>array('in',$model['promote_user'])))->select();
            if (is_array($promote)) {
              $pm = implode(array_column($promote,'id'),',');
            }
            $map['promote_id'] = array('in',$pm?($model['promote_user'].','.$pm):$model['promote_user']);             
            
        }else{
            $map['promote_id'] = -1;
        }
					
				}
        
        $data  = M('User','tab_')->field('id,account,fgame_id,fgame_name,register_time,promote_id,promote_account')
                 ->where($map)
                 ->select();
        $count = count($data);
        if($count > $size){
            $page = new \Think\Page($count, $size);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data_order = 0;
        $data_order_type = "id";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        $data = array_slice($data, ($arraypage-1)*$size, $size);

        $this->meta_title = "注册查询";
        $this->assign("list_data",$data);
        $this->map_game_list();
        $this->display();
    }


    /**
    *充值记录页面
    *@author 小纯洁
    */
    public function buydetail($isbd=0,$p = 0){

        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 8;
        
        $url = $_REQUEST;
        if($isbd==0){
        	$map['pay_way'] = array('egt',0);
        }else{
        	$map['pay_way'] = array('lt',0);
        }
        if(isset($_REQUEST['username'])){
            $map['user_account'] =  array('like','%'.$_REQUEST['username'].'%');
            unset($_REQUEST['username']);
        }

        if(isset($_REQUEST['game_id'])){
            $map['tab_spend.game_id'] = $_REQUEST['game_id'];
            unset($_REQUEST['game_id']);
        }

        if(isset($_REQUEST['promote_id'])){
            $map['tab_spend.promote_id'] = $_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
        }

				
				
				if (!empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
					$map['tab_spend.pay_time'] = array('BETWEEN',array(strtotime(I('begtime')),(strtotime(I('endtime')) + 24 * 60 * 60 - 1)));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				} elseif (!empty($_REQUEST['begtime']) && empty($_REQUEST['endtime'])) {
					$map['tab_spend.pay_time']= array('egt',strtotime(I('begtime')));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				} elseif (empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
					$map['tab_spend.pay_time']= array('elt',(strtotime(I('endtime')) + 24 * 60 * 60 - 1));
					unset($_REQUEST['begtime']);
          unset($_REQUEST['endtime']);
				}
				
				
				
        $model = M("Busier","tab_")->field('promote_user')->find($_SESSION['user_auth_commerce']['uid']);
        if($model['promote_user']){
            $pm='';
            $promote = M('Promote','tab_')->field('id')->where(array('parent_id'=>array('in',$model['promote_user'])))->select();
            if (is_array($promote)) {
              $pm = implode(array_column($promote,'id'),',');
            }
            $map['promote_id'] = array('in',$pm?($model['promote_user'].','.$pm):$model['promote_user']);             
            
            
            
        }else{
            $map['promote_id'] = -1;
        }
        $map['pay_status'] = 1;
        $data  = M('Spend','tab_')->field('user_id,user_account,game_id,game_name,pay_order_number,order_number,pay_status,cost,pay_amount,pay_time,pay_way,promote_id,promote_account')
                 ->where($map)
                 ->select();
        $count = count($data);
        if($count > $size){
            $page = new \Think\Page($count, $size);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $data_order = 0;
        $data_order_type = "pay_time";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        $data = array_slice($data, ($arraypage-1)*$size, $size);

        unset($url['isbd']);
        $this->assign("url",U('buydetail',$url));
        $url['isbd'] = 1;
        $this->assign("url1",U('buydetail',$url));
        $this->assign("idbd",$isbd);
        $this->assign("list_data",$data);
        $this->assign("uid",$_SESSION['user_auth_commerce']['uid']);
        $map['pay_status'] = 1;
        $paydata = array_sum(array_column($data, 'pay_amount'));
        $this->assign('pay_amount_num',$paydata>0?$paydata:'0.00');
        $this->assign('count',$count);
        $this->meta_title = "充值明细";
        $this->map_game_list();
        $this->display();
        
    }

    public function summary($p=1){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 10;

        $model = new \Think\Model();
        $sql = "SELECT 
                    tab1.register_time,tab1.promote_id,tab1.promote_account,tab1.game_id,tab1.game_name,tab1.user_id,tab1.user_account,
                    SUM(tab1.regUser) AS regUser,SUM(tab1.spendUser) AS spendUser,SUM(tab1.pay_amount) AS pay_amount 
                FROM(
                    SELECT tab_user.id as user_id,tab_user.account as user_account,tab_user.fgame_id as game_id,tab_user.fgame_name as game_name,tab_user.promote_id,
                           tab_user.promote_account,
                           DATE_FORMAT(FROM_UNIXTIME(tab_user.register_time),'%Y-%m-%d') AS register_time,1 AS 'regUser', 0 AS 'spendUser', 0 AS pay_amount 
                    FROM tab_promote INNER JOIN tab_user ON tab_promote.id = tab_user.promote_id AND tab_promote.busier_id = ".$_SESSION['user_auth_commerce']['uid']." 
                    UNION ALL 
                    SELECT tab_spend.user_id,tab_spend.user_account,tab_spend.game_id,tab_spend.game_name,tab_spend.promote_id,tab_spend.promote_account,
                           DATE_FORMAT(FROM_UNIXTIME(tab_spend.pay_time),'%Y-%m-%d') AS pay_time, 0 AS 'regUser',1 AS 'spendUser' ,tab_spend.pay_amount 
                    FROM tab_promote INNER JOIN tab_spend ON tab_promote.id = tab_spend.promote_id 
                         AND tab_promote.busier_id = ".$_SESSION['user_auth_commerce']['uid']." AND tab_promote.account = tab_spend.promote_account AND tab_spend.pay_status = 1
                    ) AS tab1 ";

        if (isset($_REQUEST['begtime']) || isset($_REQUEST['endtime'])) {
						
           
						if (!empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
							$sql = $sql. "  WHERE register_time BETWEEN '".$_REQUEST['begtime']."' AND '".$_REQUEST['endtime']."' ";
							unset($_REQUEST['begtime']);
							unset($_REQUEST['endtime']);
						} elseif (!empty($_REQUEST['begtime']) && empty($_REQUEST['endtime'])) {
							$sql = $sql. "  WHERE register_time >= ".($_REQUEST['begtime'])."";
						} elseif (empty($_REQUEST['begtime']) && !empty($_REQUEST['endtime'])) {
							$sql = $sql. "  WHERE register_time <=  ".date('Y-m-d',(strtotime($_REQUEST['endtime'])+86400))." ";
						}
						
						

            if(isset($_REQUEST['promote_id'])){
              
                $pm='';
                $promote = M('Promote','tab_')->field('id')->where(array('parent_id'=>$_REQUEST['promote_id']))->select();
                if (is_array($promote)) {
                  $pm = implode(array_column($promote,'id'),',');
                }
        
                $sql = $sql." AND promote_id in(".($pm?($_REQUEST['promote_id'].','.$pm):$_REQUEST['promote_id']).') ';
                unset($_REQUEST['promote_id']);
            }
            $sql = $sql." AND game_id >0 ";
            if(isset($_REQUEST['game_id'])){
                $sql = $sql." AND game_id = ".$_REQUEST['game_id'];
                unset($_REQUEST['game_id']);
            }

            $sql = $sql." GROUP BY tab1.register_time,tab1.promote_id,tab1.game_id";
            $data = $model->query($sql);
        }
        
        $count = count($data);
        if($count > $size){
            $page = new \Think\Page($count, $size);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data_order = 0;
        $data_order_type = "register_time";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        $this->assign("list_data",$data);
        $this->map_game_list();
        $this->assign("uid",$_SESSION['user_auth_commerce']['uid']);
        $this->meta_title = "数据汇总";
        $this->display();
    }


    /**
    *导出注册充值信息
    *@author 小纯洁
    */
    public function exportReg($p=1){

        $xlsName = "注册查询";
        $xlsCell = array(
            array('id', 'ID'),
            array('account', '用户名'),
            array('fgame_name', '注册游戏'),
            array('register_time', '注册日期'),
            array('promote_account', '推广员'),
        );
        
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 8;

        if(isset($_REQUEST['username'])){
            $map['account'] =  array('like','%'.$_REQUEST['username'].'%');
            unset($_REQUEST['username']);
        }

        if(isset($_REQUEST['game_id'])){
            $map['fgame_id'] = $_REQUEST['game_id'];
            unset($_REQUEST['game_id']);
        }

        if (isset($_REQUEST['begtime']) && isset($_REQUEST['endtime'])) {
            $map['register_time'] = array('BETWEEN',array(strtotime(I('begtime')),(strtotime(I('endtime')) + 24 * 60 * 60 - 1)));
            unset($_REQUEST['begtime']);
            unset($_REQUEST['endtime']);
        }

        
        $model = M("Busier","tab_")->field('promote_user')->find($_SESSION['user_auth_commerce']['uid']);
        if($model['promote_user']){
            $map['promote_id'] = array('in',$model['promote_user']);
        }else{
            $map['promote_id'] = -1;
        }
        $data  = M('User','tab_')->field('id,account,fgame_id,fgame_name,register_time,promote_id,promote_account')
                 ->where($map)
                 ->select();
        $count = count($data);
        if($count > $size){
            $page = new \Think\Page($count, $size);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $data_order = 0;
        $data_order_type = "id";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        $data = array_slice($data, ($arraypage-1)*$size, $size);

        //限制条件 当前商务专员下  注册的游戏名
        $xlsData = $data;
        foreach ($xlsData as $k => $v) {
            $xlsData[$k]['register_time'] = date("Y-m-d H:i:s",$v['register_time']);
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

    /**
    *导出充值明细信息
    *@author 小纯洁
    */
    public function exportSpendDetail($p=1){
        $xlsName = "充值明细";
        $xlsCell = array(
                     array('user_account', '用户帐号'),
                     array('order_number', '订单号'),
                     array('game_name', '游戏名称'),
                     array('cost', '应付金额'),
                     array('pay_amount', '实付金额'),
                     array('pay_way', '支付方式'),
                     array('pay_time', '充值时间'),
                     array('pay_status', '充值状态'),
                     array('promote_account', '推广员'),
                 );
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 8;

        if(isset($_REQUEST['username'])){
            $map['user_account'] =  array('like','%'.$_REQUEST['username'].'%');
            unset($_REQUEST['username']);
        }

        if(isset($_REQUEST['game_id'])){
            $map['tab_spend.game_id'] = $_REQUEST['game_id'];
            unset($_REQUEST['game_id']);
        }

        if(isset($_REQUEST['promote_id'])){
            $map['tab_spend.promote_id'] = $_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
        }

        if (isset($_REQUEST['begtime']) && isset($_REQUEST['endtime'])) {
            $map['tab_spend.pay_time'] = array('BETWEEN',array(strtotime(I('begtime')),(strtotime(I('endtime')) + 24 * 60 * 60 - 1)));
            unset($_REQUEST['begtime']);
            unset($_REQUEST['endtime']);
        }
        $model = M("Busier","tab_")->field('promote_user')->find($_SESSION['user_auth_commerce']['uid']);
        if($model['promote_user']){
            $map['promote_id'] = array('in',$model['promote_user']);
        }else{
            $map['promote_id'] = -1;
        }
        $data  = M('Spend','tab_')->field('user_id,user_account,game_id,game_name,order_number,pay_status,cost,pay_amount,pay_time,promote_id,promote_account')
                 ->where($map)
                 ->select();
        $count = count($data);
        if($count > $size){
            $page = new \Think\Page($count, $size);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $data_order = 0;
        $data_order_type = "pay_time";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        //限制条件 当前商务专员下  注册的游戏名
        $xlsData = $data;
        
        foreach ($xlsData as $k => $v) {
            $xlsData[$k]['order_number'] = chunk_split($xlsData[$k]['order_number'],9," ");
            if($xlsData[$k]['pay_way'] == 0)$xlsData[$k]['pay_way'] = '平台币';
            if($xlsData[$k]['pay_way'] == 1)$xlsData[$k]['pay_way'] = '支付宝';
            if($xlsData[$k]['pay_way'] == 2)$xlsData[$k]['pay_way'] = '微信';
            if($xlsData[$k]['pay_way'] == 3)$xlsData[$k]['pay_way'] = '微信app';
            if($xlsData[$k]['pay_way'] == 4)$xlsData[$k]['pay_way'] = '富通';
            if($xlsData[$k]['pay_way'] == 5)$xlsData[$k]['pay_way'] = '聚宝云';
            if($xlsData[$k]['pay_way'] == 6)$xlsData[$k]['pay_way'] = '竣付通';
            if($xlsData[$k]['pay_status'] == true){$xlsData[$k]['pay_status'] = '已充值';}
            if($xlsData[$k]['pay_status'] == false){$xlsData[$k]['pay_status'] = '未充值';}
            $xlsData[$k]['pay_time'] = date("Y-m-d H:i:s",$v['pay_time']);
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

    public function exportSummary($p=1){
        $xlsName = "数据汇总";
        $xlsCell = array(
                    array('register_time', '查询时间'),
                    array('promote_account', '推广员帐号'),
                    array('game_name', '游戏名称'),
                    array('reguser', '总注册（个）'),
                    array('spenduser', '充值人次（个）'),
                    array('pay_amount', '总充值（元）'),
                );
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $size = 20;
        $model = new \Think\Model();
        $sql = "SELECT 
                    tab1.register_time,tab1.promote_id,tab1.promote_account,tab1.game_id,tab1.game_name,tab1.user_id,tab1.user_account,
                    SUM(tab1.regUser) AS regUser,SUM(tab1.spendUser) AS spendUser,SUM(tab1.pay_amount) AS pay_amount 
                FROM(
                    SELECT tab_user.id as user_id,tab_user.account as user_account,tab_user.fgame_id as game_id,tab_user.fgame_name as game_name,tab_user.promote_id,
                           tab_user.promote_account,
                           DATE_FORMAT(FROM_UNIXTIME(tab_user.register_time),'%Y-%m-%d') AS register_time,1 AS 'regUser', 0 AS 'spendUser', 0 AS pay_amount 
                    FROM tab_promote INNER JOIN tab_user ON tab_promote.id = tab_user.promote_id AND tab_promote.busier_id = ".$_SESSION['user_auth_commerce']['uid']." 
                    UNION ALL 
                    SELECT tab_spend.user_id,tab_spend.user_account,tab_spend.game_id,tab_spend.game_name,tab_spend.promote_id,tab_spend.promote_account,
                           DATE_FORMAT(FROM_UNIXTIME(tab_spend.pay_time),'%Y-%m-%d') AS pay_time, 0 AS 'regUser',1 AS 'spendUser' ,tab_spend.pay_amount 
                    FROM tab_promote INNER JOIN tab_spend ON tab_promote.id = tab_spend.promote_id 
                         AND tab_promote.busier_id = ".$_SESSION['user_auth_commerce']['uid']." AND tab_promote.account = tab_spend.promote_account AND tab_spend.pay_status = 1
                    ) AS tab1 ";
        $xlsData = array();
        if (isset($_REQUEST['begtime']) && isset($_REQUEST['endtime'])) {
            $sql = $sql. "  WHERE register_time BETWEEN '".$_REQUEST['begtime']."' AND '".$_REQUEST['endtime']."' ";
            unset($_REQUEST['begtime']);
            unset($_REQUEST['endtime']);

            if(isset($_REQUEST['promote_id'])){
                $sql = $sql." AND promote_id = ".$_REQUEST['promote_id'];
                unset($_REQUEST['promote_id']);
            }
            $sql = $sql." AND game_id >0 ";
            if(isset($_REQUEST['game_id'])){
                $sql = $sql." AND game_id = ".$_REQUEST['game_id'];
                unset($_REQUEST['game_id']);
            }

            $sql = $sql." GROUP BY tab1.register_time,tab1.promote_id,tab1.game_id";
            $data = $model->query($sql);
        }
        $data_order = 0;
        $data_order_type = "register_time";
        $data=my_sort($data,$data_order_type,(int)$data_order,SORT_STRING);
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        $xlsData = $data;
        foreach ($xlsData as $key => $value) {
            $xlsData[$key]['register_time'] = date("m月d日",strtotime($value['register_time']));
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }
    

      
    //判断是否登录
    public function check_login(){
          if(empty($_SESSION['user_auth_commerce']['uid'])){
            $this->redirect('Index/index');
          }
          $uid = M('Commissioner','tab_')->where(array('id'=>$_SESSION['user_auth_commerce']['uid']))->find();
          if(empty($uid)){
            $this->redirect('Index/index');
          }

    }

    private function map_game_list(){
        $data = M('Apply',"tab_")
                ->field('game_id,game_name,tab_apply.status')
                ->join('tab_promote ON tab_apply.promote_id = tab_promote.id AND tab_promote.busier_id = '.$_SESSION['user_auth_commerce']['uid'])
                ->where('tab_apply.status = 1')->group('game_id')->select();
        $this->assign("game_list",$data);
    }

    public function get_promote_account($id=0){
        $data = M('promote','tab_')->field('account')->find($id);
        return $data['account'];
    }

    public function get_game_name($id){
        $data = M('game','tab_')->field('game_name')->find($id);
        return $data['game_name'];
    }
    
}