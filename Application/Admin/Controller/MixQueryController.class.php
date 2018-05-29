<?php



namespace Admin\Controller;



use User\Api\UserApi as UserApi;



class MixQueryController extends ThinkController

{

    public function bill($p = 0)
    {
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = 10;
        $model = array(
            'title' => '混服对账',
            'template_list' => 'bill',
        );
        $group = I('group',1);
        $this->assign('group',$group);
        if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])&$_REQUEST['group']==1) {

            $starttime = strtotime($_REQUEST['timestart']);

            $endtime = strtotime($_REQUEST['timeend']) + 24 * 60 * 60 - 1;
            $this->assign('start', $starttime);
            $this->assign('end', $endtime);
            $map['tab_mix_pay.settlement_status'] = 0;
            $map['tab_mix_pay.pay_time'] = array('BETWEEN', array($starttime, $endtime));
            $map['tab_mix_pay.order_status'] = 1;

            empty(I('partner_id')) || $map['tab_mix_pay.partner_id'] = I('partner_id');

            $data = M("mix_pay", 'tab_')

                ->field('tab_mix_apply.*,sum(pay_amount) AS total_amount,DATE_FORMAT( FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS period')

                ->join('left join tab_mix_apply ON tab_mix_apply.partner_id = tab_mix_pay.partner_id AND tab_mix_apply.game_id = tab_mix_pay.gid')

                // 查询条件

                ->where($map)

                /* 默认通过id逆序排列 */

                ->order("tab_mix_pay.id")

                //根据字段分组

                ->group("tab_mix_apply.partner_id,tab_mix_apply.game_id")

                /* 数据分页 */

                ->page($page, $row)

                /* 执行查询 */

                ->select();

//                $data = M("mix_pay",'tab_')->getLastSql();

            /* 查询记录总数 */

            $count = M("mix_pay", 'tab_')

                ->field('tab_mix_apply.*,sum(pay_amount) AS total_amount,DATE_FORMAT( FROM_UNIXTIME(pay_time),"%Y-%m-%d") AS period')

                ->join('left join tab_mix_apply ON tab_mix_apply.partner_id = tab_mix_pay.partner_id AND tab_mix_apply.game_id = tab_mix_pay.gid')

                // 查询条件

                ->where($map)

                /* 默认通过id逆序排列 */

                ->order("tab_mix_pay.id")

                //根据字段分组

                ->group("tab_mix_apply.partner_id,tab_mix_apply.game_id")

                ->count();
            //分页

            if ($count > $row) {

                $page = new \Think\Page($count, $row);

                $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

                $this->assign('_page', $page->show());

            }

            $this->meta_title = $model['title'] . '列表';

            $this->assign('list_data', $data);

            $this->display();

        } elseif($_REQUEST['group']==2){

            if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
            $starttime = strtotime($_REQUEST['timestart']);
            $endtime = strtotime($_REQUEST['timeend']) + 24 * 60 * 60 - 1;
            $map['create_time'] = array('BETWEEN', array($starttime, $endtime));
            }
             $data = M("mix_bill", 'tab_')
            // 查询条件
            ->where($map)
            /* 默认通过id逆序排列 */
            ->order("id desc")
            /* 数据分页 */
            ->page($page, $row)
            /* 执行查询 */
            ->select();





            /* 查询记录总数 */
            $count = M("mix_bill", 'tab_')
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order("id desc")
                ->count();
            $total=M("mix_bill", 'tab_')->where($map)->sum('total_amount');
            $this->assign('total',1?0:$total);
            $this->assign('list_data', $data);
             $this->assign('model', $model);

            $this->display($model['template_list']);
            

        }else{
            $this->assign('model', $model);

            $this->display($model['template_list']);

        }

    }

    public function bu($p)
    {
        
         
    }


    // 生成对账单



    public function generateBill()

    {

        $ids = I('request.ids');

        if (empty($ids)) {

            $this->error('请选择要操作的数据');

        }



        if (is_numeric($ids)) {

            $start = I('get.start');

            $end = I('get.end');

            $partner_id = I('get.partner_id');

            $data = array(

                'bill_number' => 'hfdz_' . date('YmdHis', time()) . rand(100, 999),

                'bill_time' => date('Y年m月d日', $start) . '---' . date('Y年m月d日', $end),

                'partner_id' => $partner_id,

                'partner_account' => get_mix_partner_name(I('get.partner_id')),

                'game_id' => I('get.game_id'),

                'game_name' => get_game_name(I('get.game_id')),

                'total_amount' => I('get.total_amount'),

                'ratio' => I('get.ratio'),

                'bill_start_time' => $start,

                'bill_end_time' => $end,

                'create_time' => time(),

                'settlement_amount' => $_REQUEST['total_amount'] * ($_REQUEST['ratio']/100),

            );

            $pay_map['partner_id'] = $partner_id;

            $pay_map['gid'] = I('get.game_id');

            $pay_map['pay_time'] = array('BETWEEN', array($start, $end));

            $res = M("mix_pay", "tab_")->where($pay_map)->setField(array("settlement_status" => 1, 'settlement_time' => time()));



            if (M("mix_bill", "tab_")->add($data)) {


                $this->success('生成对账单成功！', U('bill', array('group' =>1)));

            } else {

                $this->success('生成对账单傻逼！', U('bill', array('group' =>2)));

            }



        } elseif (is_array($ids)) {



            foreach ($ids as $k => $v) {

                $query = explode(',', $v);

                $start = $query[0];

                $end = $query[1];

                $partner_id = $query[2];

                $game_id = $query[3];

                $total_amount = $query[4];

                $ration = $query[5];

                $tempdata = array(

                    'bill_number' => 'hfdz_' . date('YmdHis', time()) . rand(100, 999),

                    'bill_time' => date('Y年m月d日', $start) . '---' . date('Y年m月d日', $end),

                    'partner_id' => $partner_id,

                    'partner_account' => get_mix_partner_name($partner_id),

                    'game_id' => $game_id,

                    'game_name' => get_game_name($game_id),

                    'total_amount' => $total_amount,

                    'ratio' => $ration,

                    'bill_start_time' => $start,

                    'bill_end_time' => $end,

                    'create_time' => time(),

                    'settlement_amount' => number_format($total_amount * $ration * 0.01, 2),

                );

                $data[]=$tempdata;

                $pay_map['partner_id'] = $partner_id;

                $pay_map['gid'] = $game_id;

                $pay_map['pay_time'] = array('BETWEEN', array($start, $end));

                $res = M("mix_pay", "tab_")->where($pay_map)->setField(array("settlement_status" => 1, 'settlement_time' => time()));

            }

            $add = M("mix_bill", "tab_")->addAll($data);

            if ($add > 0) {

                $this->success('生成对账单成功！', U('bill', array('timestart' => date('Y-m-d', $start), 'timeend' => date('Y-m-d', $end))));

            } else {

                $this->error('生成对账单失败！！!', U('bill', array('timestart' => date('Y-m-d', $start), 'timeend' => date('Y-m-d', $end))));

            }

        }

    }



    public function withdraw($p=0)

    {

        $get = I('get');

        foreach($get as $key => $value){

            if($value == "") continue;

            $map[$key] = $value;

        }

        $page = intval($p);

        $page = $page ? $page : 1; //默认显示第一页数据

        $row = 10;

        $data = M('mix_bill','tab_')->where($map)->page($page,$row)->select();

        $count = M('mix_bill','tab_')->where($map)->count();

        if ($count > $row) {

            $page = new \Think\Page($count, $row);

            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

            $this->assign('_page', $page->show());

        }

        $sum = M('mix_bill','tab_')->where(array('settlement_status'=>2))->sum('settlement_amount');

        $this->assign('sum',$sum);

        $this->assign("list_data",$data);

        $this->display();



    }





    public function settlement($p = 0)

    {

        $group = I('group', 1);

        $this->assign('group', $group);

        if ($group == 1) {



            $model = array(

                'm_name' => 'Bill',

                'order' => 'id ',

                'title' => '渠道结算',

                'template_list' => 'settlement',

                'group' => 'game_id',

            );



            $user = A('Bill', 'Event');

            $user->show_bill($model, $p, $map);

        }

        if ($group == 2) {



            $model = array(

                'm_name' => 'settlement',

                'order' => 'id ',

                'title' => '结算账单',

                'template_list' => 'settlement',

            );



            $user = A('Bill', 'Event');

            $user->money_list($model, $p, $map);

        }

    }



    public function cpsettlement($p = 0)

    {

        $group = I('group', 1);

        $this->assign('group', $group);

        if (isset($_REQUEST['timestart']) && $_REQUEST['timestart'] != '' && $_REQUEST['group'] == 1) {

            $starttime = strtotime($_REQUEST['timestart'] . '-01');

            if ($starttime >= strtotime(date('Y-m-01'))) {

                $this->error('时间选择不正确', '', 1);

                exit;

            }

            $endtime = strtotime($_REQUEST['timestart'] . "+1 month -1 day") + 24 * 3600 - 1;

            if (isset($_REQUEST['game_name']) && $_REQUEST['game_name'] != '全部') {

                $map['g.game_name'] = $_REQUEST['game_name'];

            }

            if (isset($_REQUEST['selle_status'])) {

                if ($_REQUEST['selle_status'] == "未结算") {

                    $map['s.selle_status'] = 0;

                } else if ($_REQUEST['selle_status'] == "已结算") {

                    $map['s.selle_status'] = 1;

                }

            }

            $map['s.pay_status'] = 1;

            $map['pay_time'] = array('BETWEEN', array($starttime, $endtime));

            $model = array(

                'm_name' => 'Spend as s',

                'order' => 's.id',

                'title' => '渠道结算',

                'group' => 'g.developers,g.id',

                'fields' => 'sum(s.pay_amount) as total,s.selle_ratio,s.id,g.developers,s.selle_status,g.id as gid,g.game_name,s.pay_status,s.pay_amount',

                'template_list' => 'cpsettlement',

            );



            $user = A('Spend', 'Event');

            $user->cpsettl_list($model, $p, $map);

        } else if ($_REQUEST['group'] == 2) {

            if (isset($_REQUEST['timestart']) && $_REQUEST['timestart'] != '') {

                $starttime = strtotime($_REQUEST['timestart'] . '-01');

                if ($starttime >= strtotime(date('Y-m-01'))) {

                    $this->error('时间选择不正确', '', 1);

                    exit;

                }

                $starttime = strtotime($_REQUEST['timestart'] . '-01');

                $endtime = strtotime($_REQUEST['timestart'] . "+1 month -1 day") + 24 * 3600 - 1;

                $map['pay_time'] = array('BETWEEN', array($starttime, $endtime));

            }

            $map['s.pay_status'] = 1;

            $map['s.selle_status'] = 1;//已结算

            $model = array(

                'm_name' => 'Spend as s',

                'order' => 's.id',

                'title' => '渠道结算',

                'group' => 'g.developers,g.id',

                'fields' => 'sum(s.pay_amount) as total, s.id,s.selle_ratio,g.developers,s.selle_status,s.selle_time,g.id as gid,g.game_name,s.pay_status,s.pay_amount',

                'template_list' => 'cpsettlement',

            );



            $user = A('Spend', 'Event');

            $user->cpsettl_list($model, $p, $map);

        } else {

            $this->display();

        }

    }



    public function generatesettlement()

    {

        $request = I('request.ids');

        if (empty($request)) {

            $this->error('请选择要操作的数据');

        }

        if (is_array($request)) {

            foreach ($request as $v) {

                $query = explode(',', $v);

                $ids[] = $query[0];

                $_REQUEST[$query[0]]['ids'] = $query[0];

                $_REQUEST[$query[0]]['cooperation'] = $query[1];

                $_REQUEST[$query[0]]['cps_ratio'] = $query[2];

                $_REQUEST[$query[0]]['cpa_price'] = $query[3];

            }

            unset($_REQUEST['ids']);

        } elseif (is_numeric($request)) {

            $id = $ids[] = $request;

            $_REQUEST[$id]['ids'] = $id;

            $_REQUEST[$id]['cooperation'] = $_REQUEST['cooperation'];

            $_REQUEST[$id]['cps_ratio'] = $_REQUEST['cps_ratio'];

            $_REQUEST[$id]['cpa_price'] = $_REQUEST['cpa_price'];

        } else {

            $this->error('参数有误！！！');

        }



        sort(array_unique($ids));



        $map['b.id'] = array('in', $ids);



        $bill = D("Bill");



        $data = $bill

            ->field("replace(b.bill_number,'dz','js') as settlement_number,b.id,b.total_money,b.total_number,"



                . "b.game_id,b.game_name,b.promote_id,b.promote_account")

            ->table("__BILL__ as b ")

            ->where($map)

            ->order("b.id asc")

            ->select();



        if (empty($data) || !is_array($data)) {

            $this->error('没有结果！！！');

        }

        foreach ($data as $k => $v) {

            $data[$k]['create_time'] = time();

            $id = $v['id'];

            $data[$k]['pattern'] = $_REQUEST[$id]['cooperation'] == 'CPA' ? 1 : 0;

            $data[$k]['ratio'] = $_REQUEST[$id]['cps_ratio'];

            $data[$k]['money'] = $_REQUEST[$id]['cpa_price'];

            if ($data[$k]['pattern'] == '0') {

                $data[$k]['sum_money'] = ($_REQUEST[$id]['cps_ratio'] / 100) * $v['total_money'];

            } elseif ($data[$k]['pattern'] == '1') {

                $data[$k]['sum_money'] = $_REQUEST[$id]['cpa_price'] * $v['total_number'];

            }

            unset($data[$k]['id']);

        }

        // var_dump($data);exit;            

        $bill->startTrans();

        $settlementstatus = array('settlement_status' => 1);

        $flag = $bill->table("__BILL__ as b ")->where($map)->save($settlementstatus);



        if (!$flag) {

            $bill->rollback();

            $this->error('结算失败！');

        }



        if (D('settlement')->addAll($data)) {

            $bill->commit();

            $this->success('结算成功！', U('settlement', array('promote_account' => I('request.promote_account'), 'game_name' => I('request.game_name'), 'bill_number' => I('request.bill_number'))));

        } else {

            $bill->rollback();

            $this->error('结算失败！！!', U('settlement', array('promote_account' => I('request.promote_account'), 'game_name' => I('request.game_name'), 'bill_number' => I('request.bill_number'))));

        }

    }

    public function generatecpsettlement()

    {//cp结算

        $game_id = I('request.ids');

        if (empty($game_id)) {

            $this->error('请选择要操作的数据');

        }

        $starttime = strtotime($_REQUEST['timestart'] . '-01');

        $endtime = strtotime($_REQUEST['timestart'] . "+1 month -1 day") + 24 * 3600 - 1;

        $map['s.pay_status'] = 1;

        $map['s.selle_status'] = 0;

        if (is_array($game_id)) {

            $map['s.game_id'] = array('in', $game_id);

        } else {

            $map['s.game_id'] = $game_id;

        }

        $map['pay_time'] = array('BETWEEN', array($starttime, $endtime));

        $spe = M('spend as s', 'tab_');

        $smap = array('s.selle_time' => $_REQUEST['timestart'], 's.selle_status' => 1);

        $data = $spe

            ->field('s.id,s.selle_status,s.selle_time')

            ->join('tab_game as g on g.id=s.game_id', 'LEFT')

            ->where($map)

            ->setField($smap);

        if ($data) {

            $this->success('结算成功');

        } else {

            $this->error('结算失败');

        }



    }



    public function changeratio()

    {

        $gid = I('request.game_id');

        if (empty($gid)) {

            $this->ajaxReturn(0, "请选择要操作的数据", 0);

            exit;

        }

        $starttime = strtotime($_REQUEST['timestart'] . '-01');

        $endtime = strtotime($_REQUEST['timestart'] . "+1 month -1 day") + 24 * 3600 - 1;

        $map['s.pay_status'] = 1;

        $map['s.selle_status'] = 0;

        $map['s.game_id'] = $_REQUEST['game_id'];

        $map['pay_time'] = array('BETWEEN', array($starttime, $endtime));

        $spe = M('spend as s', 'tab_');

        $data = $spe

            ->field('s.id,s.selle_status,s.selle_ratio')

            ->join('tab_game as g on g.id=s.game_id', 'LEFT')

            ->where($map)

            ->setField('s.selle_ratio', $_POST['ratio']);

        if ($data) {

            $this->ajaxReturn($data);

        } else {

            $this->ajaxReturn(-1);

        }

    }





    public function set_withdraw_status($settlement_status,$ids)

    {

        $bill = M('mix_bill','tab_');

        $map['id'] = array('in',$ids);

        $res = $bill->where($map)->setField(array('end_time'=>time(),'settlement_status'=>$settlement_status));

        if($res > 0){

            $this->success("成功");

        }else{

            $this->success("失败");

        }



    }





    protected function upPromote($promote_id)

    {

        $model = D('Promote');

        $data['id'] = $promote_id;

        $data['money'] = 0;

        return $model->save($data);

    }

}