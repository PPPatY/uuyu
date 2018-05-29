<?php
namespace Channel\Controller;
use Think\Controller;
use User\Api\PromoteApi;
use User\Api\UserApi;

class UserController extends BaseController {

    /**
     *APP用户登录
     */
    public function user_login() {
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        #判断数据是否为空
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "登录数据不能为空")));
            exit;
        }
        $promote = new PromoteApi();
        $result = $promote->login($user['account'], $user['password']);
        if ($result > 0) {
            $map['account'] = $user['account'];
            $data['last_login_time'] = time();
            $entity = get_promote_entity($result);
            M("Promote", "tab_")->where($map)->save($data);
            $data['id'] = $result;
            $data['nickname'] = $entity['nickname'];
            $data['last_login_time'] = empty($entity['last_login_time']) ? date('Y-m-d H:i:s', time()) :date('Y-m-d H:i:s', $entity['last_login_time']);
            $data['real_name'] = $entity['real_name'];
            $data['phone'] = $entity['mobile_phone'];
            $data['email'] = $entity['email'];
            echo base64_encode(json_encode(array("status" => 1, "msg" => "登录成功", "data" => $data)));
        } else {
            $msg = "";
            switch ($result) {
                case -1:
                    $msg = "用户不存在";
                    break;
                case -2:
                    $msg = "密码错误";
                    break;
                case -3:
                    $msg = "用户被禁用,请联系管理员";
                    break;
                case -4:
                    $msg = "审核中,请联系管理员";
                    break;
                default:
                    $msg = "未知错误！请联系管理员";
                    break;
            }
            echo base64_encode(json_encode(array("status" => -1, "msg" => $msg)));
        }

    }

    //注册明细
    public function reg_details() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }

        $map['promote_id'] = $user['promote_id'];
        if (substr($user['promote_id'],-3) == 'all'){
            if (is_array(get_child_ids($map['promote_id']))) {
                foreach (get_child_ids($map['promote_id']) as $key => $value) {
                    $asd[] = implode(",", $value);
                }
                $map['promote_id'] = array('in', implode(',', $asd));
            } else {
                $map['promote_id'] = $user['promote_id'];
            }
        }else{
            $map['promote_id'] = $user['promote_id'];
        }

        if (isset($user['user_account']) && $user['user_account'] != "") {
            $map['account'] = array('like', '%' . $user['user_account'] . '%');
        }
        if (isset($user['fgame_name']) && $user['fgame_name'] != "") {
            $map['fgame_name'] = array('like', '%' . $user['fgame_name'] . '%');
        }
        if (isset($user['time_start']) && isset($user['time_end']) && $user['time_start'] != "" && $user['time_end'] != "") {
            $map['register_time'] = array('BETWEEN', array(strtotime($user['time_start']), strtotime($user['time_end']) + 24 * 60 * 60 - 1));
        } elseif (isset($user['time_start']) && $user['time_start'] != "") {
            $map['register_time'] = array('gt', strtotime($user['time_start']));
        } elseif (isset($user['time_end']) && $user['time_end'] != "") {
            $map['register_time'] = array('lt', strtotime($user['time_end']) + 24 * 60 * 60 - 1);
        }
        if (isset($user['limit'])) {
            $limit = $user['limit'];
        } else {
            $limit = 1;
        }

        $model = array('m_name' => 'user', 'field' => 'id,account,register_time,fgame_name,promote_account', 'map' => $map, 'list_row' => 10, 'order' => 'register_time desc',);
        $user1 = A('User', 'Event');
        $res = $user1->user_join($model, $limit);
        $res_data['count'] = M('user', 'tab_')->where($map)->count();
        $time = total(1);
        $res_data['today_register'] = M('user', 'tab_')->where($map)->where("register_time" . $time)->count();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res, "res_data" => $res_data)));
    }

    //流水信息
    public function get_liushui() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $today_amount = $this->get_amount("today", $user['id']) ? $this->get_amount("today", $user['id']) : '0';
        $yes_amount = $this->get_amount('yes', $user['id']) ? $this->get_amount('yes', $user['id']) : '0';
        $this_mon_amount = $this->get_amount('thismonth', $user['id']) ? $this->get_amount('thismonth', $user['id']) : '0';
        $total_amount = $this->get_amount('total', $user['id']) ? $this->get_amount('total', $user['id']) : '0';
        $amount['today'] = $today_amount;
        $amount['yes'] = $yes_amount;
        $amount['thismonth'] = $this_mon_amount;
        $amount['total'] = $total_amount;
        echo base64_encode(json_encode(array("status" => 1, "data" => $amount)));
    }

    //获取可以代充的游戏
    public function get_promote_game() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['promote_id'];
        $map['status'] = 1;
        $list = M('apply', 'tab_')->field('tab_game.id,tab_game.game_name')->join('tab_game on tab_game.id=tab_apply.game_id')->where($map)->order('apply_time desc')->select();
        if (!$list) {
            echo base64_encode(json_encode(array("status" => -1, "data" => "", "msg" => "失败")));
        } else {
            echo base64_encode(json_encode(array("status" => 1, "data" => $list, "msg" => "成功")));
        }
    }

    //流水信息
    public function get_amount($time, $id) {
        switch ($time) {
            case 'today':
                $time1 = total(1);
                break;
            case 'yes':
                $time1 = total(5);
                break;
            case 'thismonth':
                $time1 = total(3);
                break;
            case 'total':
                $ids = M('Promote', 'tab_')->where('parent_id=' . $id)->getfield("id", true);
                if (empty($ids)) {
                    $ids = $id;
                } else {
                    array_unshift($ids, $id);
                }
                if (count($ids) > 1) {
                    $map1['promote_id'] = array('in', $ids);
                } else {
                    $map1['promote_id'] = $ids;
                }
                $map1['pay_status'] = 1;
                $map1['is_check'] = array('neq', 2);
                $amount = M('spend', 'tab_')->where($map1)->sum('pay_amount');
                return $amount;
        }
        $ids = M('Promote', 'tab_')->where(array('parent_id'=>$id))->getfield("id", true);
        if (empty($ids)) {
            $ids = $id;
        } else {
            array_unshift($ids, $id);
        }
        if (count($ids) > 1) {
            $map1['promote_id'] = array('in', $ids);
        } else {
            $map1['promote_id'] = $ids;
        }
        $map1['pay_status'] = 1;
        $map1['is_check'] = array('neq', 2);
        $amount = M('spend', 'tab_')->where($map1)->where(array('pay_time' . $time1))->sum('pay_amount');
        return $amount;
    }


    //申请游戏
    public function apply() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $game = M('Game', 'tab_');
        $gdata = $game->where(array('id' => $user['game_id']))->find();
        $ratio = $gdata['ratio'];
        $money = $gdata['money'];
        $data['game_id'] = $user['game_id'];
        $data['game_name'] = get_game_name($user['game_id']);
        $data['promote_id'] = $user["pid"];
        $data['promote_account'] = get_promote_name($user["pid"]);
        $data['apply_time'] = time();
        $data['status'] = 0;
        $data['enable_status'] = 1;
        $data['pattern'] = $pattern;
        $data['sdk_version'] = $user['sdk_version'];
        $data['ratio'] = $ratio;
        $data['money'] = $money;
        $data['register_url']="/mobile.php/"."?s="."Game/open_game/pid/".$user['pid']."/game_id/".$data['game_id'].".html";
        $res = M('apply', 'tab_')->add($data);
        if ($res) {
            echo base64_encode(json_encode(array("status" => 1, "msg" => "申请成功")));
        } else {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "申请失败")));
            exit;
        }
    }

    //充值明细
    public function pay_details() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map2['promote_id'] = $user['promote_id'];
        if (substr($user['promote_id'],-3) == 'all'){
            if (is_array(get_child_ids($user['promote_id']))) {
                foreach (get_child_ids($user['promote_id']) as $key => $value) {
                    $asd[] = implode(",", $value);
                }
                $map2['promote_id'] = array('in', implode(',', $asd));
            } else {
                $map2['promote_id'] = $user['promote_id'];
            }
        }else{
            $map2['promote_id'] = $user['promote_id'];
        }

        if (isset($user['time_start']) && isset($user['time_end']) && $user['time_start'] != "" && $user['time_end'] != "") {
            $map2['pay_time'] = array('BETWEEN', array(strtotime($user['time_start']), strtotime($user['time_end']) + 24 * 60 * 60 - 1));
        } elseif ($user['time_start'] && $user['time_start'] != "") {
            $map2['pay_time'] = array('gt', strtotime($user['time_start']));
        } elseif ($user['time_end'] && $user['time_end'] != "") {
            $map2['pay_time'] = array('lt', strtotime($user['time_end']) + 24 * 60 * 60 - 1);
        }
        if (isset($user['user_account']) && $user['user_account'] != "") {
            $map2['user_account'] = array('like','%'.$user['user_account']."%");
        }
        if (isset($user['game_name']) && $user['game_name'] != "") {
            $map2['game_name'] = array('like', '%' . $user['game_name'] . '%');
        }
        $map2['is_check'] = array('neq', 2);
        $map2['pay_status'] = 1;
        if (isset($user['limit'])) {
            $limit = $user['limit'];
        } else {
            $limit = 1;
        }
        $model = array('m_name' => 'spend', 'field' => 'id,user_account,game_name,pay_amount,cost,pay_time,pay_way', 'map' => $map2, 'list_row' => 10, 'order' => 'pay_time desc',);
        $user1 = A('User', 'Event');
        $list = $user1->user_join($model, $limit);
        foreach ($list as &$value) {
            $value['pay_way'] = get_pay_way($value['pay_way']);
        }

        $list_data['all_sum'] = M('spend', 'tab_')->where($map2)->sum('pay_amount') ? M('spend', 'tab_')->where($map2)->sum('pay_amount') : 0;
        $time1 = total(1);
        $list_data['count'] = M('spend', 'tab_')->where($map2)->where("pay_time" . $time1)->sum('pay_amount') ? M('spend', 'tab_')->where($map2)->where("pay_time" . $time1)->sum('pay_amount') : 0;
        echo base64_encode(json_encode(array("status" => 1, "data" => $list, "list_data" => $list_data)));
    }

    //充值详情
    public function pay_piece() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $arr = M('spend', 'tab_')->where($map)->field('user_account,pay_order_number,game_name,pay_amount,pay_way,pay_time,cost,promote_account,pay_status
')->find();
        $arr['pay_way'] = get_pay_way($arr['pay_way']);
        $arr['pay_time'] = date('Y-m-d H:i:s', $arr['pay_time']);
        echo base64_encode(json_encode(array("status" => 1, "data" => $arr)));
    }

    //获取二级推广账号
    public function get_child_id() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map1['parent_id'] = $user['promote_id'];
        $map1['id'] = $user['promote_id'];
        $map1['_logic'] = 'OR';
        $arr1 = M('promote', 'tab_')->where($map1)->field('id,account')->order('id asc')->select();
        echo base64_encode(json_encode(array("status" => 1, "data" => $arr1)));

    }

    //获取二级推广账号 (不包含本身渠道)
    public function get_child_promote() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map1['parent_id'] = $user['promote_id'];
//        $map1['id'] = $user['promote_id'];
//        $map1['_logic'] = 'OR';
        $arr1 = M('promote', 'tab_')->where($map1)->field('id,account')->order('id asc')->select();
        echo base64_encode(json_encode(array("status" => 1, "data" => $arr1)));

    }

    //我的结算
    public function settlement() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['promote_id'];

        if (isset($user['game_name']) && $user['game_name'] != "") {
            $map['game_name'] = array('like', '%' . $user['game_name'] . '%');
        }
        if (isset($user['limit']) && $user['limit'] != "") {
            $limit = $user['limit'];
        }
        if (isset($user['ti_status']) && $user['ti_status'] != "" && $user['ti_status'] !=-2) {
            $map['ti_status'] = $user['ti_status'];
        }
        $res = M('Settlement', 'tab_')->where($map)->field('id,game_name,promote_account,sum_money,ti_status')->page($limit, 10)->select();
        unset($map['ti_status']);
        $res_data['all_sum'] = M('Settlement', 'tab_')->where($map)->sum('sum_money') ? M('Settlement', 'tab_')->where($map)->sum('sum_money') : 0;
        $res_data['all_success_sum'] = M('Settlement', 'tab_')->where($map)->where('ti_status=1')->sum('sum_money') ? M('Settlement', 'tab_')->where($map)->where('ti_status=1')->sum('sum_money') : 0;
        $res_data['all_error_sum'] = M('Settlement', 'tab_')->where($map)->where('ti_status!=1')->sum('sum_money') ? M('Settlement', 'tab_')->where($map)->where('ti_status!=1')->sum('sum_money') : 0;
        echo base64_encode(json_encode(array("status" => 1, "data" => $res, "res_data" => $res_data)));
    }

    public function settle_status() {
        $res_data[0] = array("id" => "-2", "status" => "请选择提现状态");
        $res_data[1] = array("id" => "-1", "status" => "未申请");
        $res_data[2] = array("id" => "0", "status" => "申请中");
        $res_data[3] = array("id" => "1", "status" => "已通过");
        $res_data[4] = array("id" => "2", "status" => "未通过");
        echo base64_encode(json_encode(array("sta" => 1, "res_data" => $res_data)));
    }

    //结算单详情
    public function settle_details() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('settlement', 'tab_')->where($map)->field('settlement_number,game_name,pattern,total_money,total_number,ratio,money,sum_money,status,promote_account')->find();
        $res['pattern'] = get_pattern($res['pattern']);
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //申请提现
    public function ap_withdraw() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $with = M("withdraw", "tab_");
        $seet = M("settlement", "tab_")->where($map)->find();
        $with_map['settlement_number'] = $seet['settlement_number'];
        $fid = $with->where($with_map)->find();
        if ($fid == null) {
            $add['settlement_number'] = $seet['settlement_number'];
            $add['sum_money'] = $seet['sum_money'];
            $add['promote_id'] = $seet['promote_id'];
            $add['promote_account'] = $seet['promote_account'];
            $add['create_time'] = time();
            $add['status'] = 0;
            $with->add($add);
            M("settlement", "tab_")->where($map)->save(array('ti_status' => 0));
            echo base64_encode(json_encode(array("status" => 1, "msg" => "申请成功")));
        } else {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "申请失败")));
            exit;
        }
    }

    //代充记录
    public function agent_pay_record() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }

        $map['promote_id'] = $user['promote_id'];
        if (substr($user['promote_id'],-3) == 'all'){
            if (is_array(get_child_ids($map['promote_id']))) {
                foreach (get_child_ids($map['promote_id']) as $key => $value) {
                    $asd[] = implode(",", $value);
                }
                $map['promote_id'] = array('in', implode(',', $asd));
            } else {
                $map['promote_id'] = $user['promote_id'];
            }
        }else{
            $map['promote_id'] = $user['promote_id'];
        }

        if (isset($user['game_name']) && $user['game_name'] != "") {
            $map['game_name'] = array('like', '%' . $user['game_name'] . '%');
        }
        if (isset($user['user_account']) && $user['user_account'] != "") {
            $map['user_account'] = array('like',"%".$user['user_account']."%");
        }
        if (isset($user['starttime']) && isset($user['endtime']) && $user['starttime'] != "" && $user['endtime'] != "") {
            $map['create_time'] = array('BETWEEN', array(strtotime($user['starttime']), strtotime($user['endtime'])+ 24 * 60 * 60 - 1));
        } elseif (isset($user['starttime']) && $user['starttime'] != "") {
            $map['create_time'] = array('gt', strtotime($user['starttime']));
        } elseif (isset($user['endtime']) && $user['endtime'] != "") {
            $map['create_time'] = array('lt', strtotime($user['endtime'])+ 24 * 60 * 60 - 1);
        }
        if (isset($user['limit'])) {
            $limit = $user['limit'];
        } else {
            $limit = 1;
        }
        $model = array('m_name' => 'agent', 'field' => 'id,game_name,amount,real_amount,user_account,create_time', 'map' => $map, 'list_row' => 10, 'order' => 'create_time desc',);
        $user1 = A('User', 'Event');
        $res = $user1->user_join($model, $limit);
        foreach ($res as $key => $value) {
            $res[$key]['user_account'] = $res[$key]['user_account'] ? $res[$key]['user_account'] : "";
        }
        $res_data['all_amount'] = M('agent', 'tab_')->where($map)->sum('amount') ? M('agent', 'tab_')->where($map)->sum('amount') : 0;
        $res_data['all_real_amount'] = M('agent', 'tab_')->where($map)->sum('real_amount') ? M('agent', 'tab_')->where($map)->sum('real_amount') : 0;
        $res_data['data_amount'] = M('agent', 'tab_')->where($map)->count() ? M('agent', 'tab_')->where($map)->count() : 0;
        echo base64_encode(json_encode(array("status" => 1, "data" => $res, "res_data" => $res_data)));
    }

    //代充详情
    public function agent_pay_details() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('agent', 'tab_')->where($map)->field('pay_order_number,game_name,user_account,amount,real_amount,pay_way,create_time,pay_status')->find();
        $res['create_time'] = date('Y-m-d H:i:s', $res['create_time']);
        $res['pay_status'] = $res['pay_status'] == 1 ? "成功" : "失败";
        $res['pay_way'] = get_pay_way($res['pay_way']);
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //我的资料
    public function my_profile() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('promote', 'tab_')->where($map)->field('id,account,real_name,mobile_phone,email')->find();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //修改资料(1:资料，2：密码，3：二级密码)
    public function modify_profile() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "data" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        switch ($user['type']) {
            case '1':
                $con['id'] = $user['id'];
                /*$result = M('user', 'tab_')->where($con)->field('account')->find();
                $map['account'] = $result['account'];*/
                $map['real_name'] = $user['real_name'];
                $map['mobile_phone'] = $user['phone'];
                $map['email'] = $user['email'];
                $res = M('promote', 'tab_')->save($map);
                if ($res) {
                    echo base64_encode(json_encode(array("status" => 1, "data" => "修改成功")));
                } else {
                    echo base64_encode(json_encode(array("status" => -1, "data" => "修改失败")));
                    exit;
                }
                break;
            case '2':
                $use = new UserApi();
                $find = M('promote', 'tab_')->where($map)->field('password')->find();
                if (think_ucenter_md5($user['password'], UC_AUTH_KEY) != $find['password']) {
                    echo base64_encode(json_encode(array("status" => -1, "data" => "旧密码错误")));
                    exit;
                } else if ($user['new_pass'] != $user['confirm_pass']) {
                    echo base64_encode(json_encode(array("status" => -2, "data" => "确认密码与新密码不同")));
                    exit;
                } else {
                    $data['password'] = think_ucenter_md5($user['new_pass'], UC_AUTH_KEY);
                    $res = M('promote', 'tab_')->where($map)->save($data);
                    if ($res) {
                        echo base64_encode(json_encode(array("status" => 1, "data" => "修改成功")));
                    } else {
                        echo base64_encode(json_encode(array("status" => -1, "data" => "修改失败")));
                        exit;
                    }
                }
                break;
            case '3':
                $use = new UserApi();
                $find = M('promote', 'tab_')->where($map)->field('second_pwd')->find();
                if (think_ucenter_md5($user['second_pwd'], UC_AUTH_KEY) != $find['second_pwd']) {
                    echo base64_encode(json_encode(array("status" => -1, "data" => "旧密码错误")));
                    exit;
                } else if ($user['new_second_pass'] != $user['confirm_second_pass']) {
                    echo base64_encode(json_encode(array("status" => -2, "data" => "确认密码与新密码不同")));
                    exit;
                } else {
                    $data['second_pwd'] = think_ucenter_md5($user['new_second_pass'], UC_AUTH_KEY);

                    $res = M('promote', 'tab_')->where($map)->save($data);
                    if ($res) {
                        echo base64_encode(json_encode(array("status" => 1, "data" => "修改成功")));
                    } else {
                        echo base64_encode(json_encode(array("status" => -1, "data" => "修改失败")));
                        exit;
                    }
                }
                break;
        }
    }

    //获取下级玩家id
    public function get_son_players() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "data" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['promote_id'];
        $res = M('user', 'tab_')->where($map)->field('id,account')->select();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //获取玩家平台币
    public function get_players_balance() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "data" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('user', 'tab_')->where($map)->field('balance')->find();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //获取会长余额
    public function get_promoter_balance() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "data" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('promote', 'tab_')->where($map)->field('balance_coin')->find();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }


    private function pay($param = array()) {
        $out_trade_no = "AG_" . date('Ymd') . date('His') . sp_random_string(4);
        $user = get_user_entity($param['account'], true);
        switch ($param['apitype']) {
            case 'swiftpass':
                $pay = new \Think\Pay($param['apitype'], $param['config']);
                break;

            default:
                $pay = new \Think\Pay($param['apitype'], C($param['config']));
                break;
        }

        $vo = new \Think\Pay\PayVo();
        $vo->setFee($param['price'])//支付金额
        ->setTitle("代充")->setBody("代充")->setOrderNo($out_trade_no)->setService($param['server'])->setSignType($param['signtype'])->setPayMethod("mobile")->setTable("agent")->setPayWay($param['payway'])->setGameId($param['game_id'])->setGameName($param['game_name'])->setGameAppid($param['game_appid'])->setUserId($param['user_id'])->setAccount($user['account'])->setUserNickName($user['nickname'])->setPromoteId($user['promote_id'])->setPromoteName($user['promote_account'])->setExtend($param['extend'])->setSdkVersion($param['sdk_version'])->setParam($param['zhekou'])->setMoney($param['amount']);
        return $pay->buildRequestForm($vo);
    }


    //会长代充，平台币方式
    public function agent_pay() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $promote_id = $user['promote_id'];
        $game_id = $user['game_id'];
        $account = $user['account'];
        $map5['account'] = $account;
        $user_id = M('user', 'tab_')->where($map5)->field('id')->find();
        if ($user_id == null) {
            echo base64_encode(json_encode(array("status" => -5, "msg" => "不存在该玩家")));
            exit;
        }
        $user_id = $user_id['id'];
        $user_nickname = M('user', 'tab_')->where($map5)->field('nickname')->find();
        $user_nickname = $user_nickname['nickname'];
        $amount = $user['pay_amount'];
        $real_amount = $user['real_pay_amount'];
        $type = $user['pay_type'];
        $map1['id'] = $game_id;
        $game_appid = M('game', 'tab_')->where($map1)->field('game_appid')->find();
        $game_appid = $game_appid['game_appid'];
        $promote_account = get_promote_account($promote_id);
        $map2['id'] = $game_id;
        $game_name = M('game', 'tab_')->where($map2)->field('game_name')->find();
        $game_name = $game_name['game_name'];
        $map3['promote_id'] = $promote_id;
        $map3['game_id'] = $game_id;
        $zhekou = $this->get_in_discount($map3);
        $map4['game_id'] = $game_id;
        $map4['user_account'] = $account;
        $res_player_game = M('user_play', 'tab_')->where($map4)->find();
        if ($res_player_game == null) {
            echo base64_encode(json_encode(array("status" => -4, "msg" => "该玩家没有玩过该游戏")));
            exit;
        }
        switch ($type) {
            case 'ptb'://平台币
                $map10['id'] = $promote_id;
                $own_amount = M('promote', 'tab_')->where($map10)->field('balance_coin')->find();
                $own_amount = $own_amount['balance_coin'];
                if ($real_amount > $own_amount) {
                    echo base64_encode(json_encode(array("status" => -3, "msg" => "充值金额大于拥有平台币")));
                    exit;
                }
                $map4['id'] = $promote_id;
                $res4 = M("Promote", "tab_")->field('second_pwd')->where($map4)->find();
                if ($this->think_ucenter_md5($user['code'], UC_AUTH_KEY) != $res4['second_pwd']) {

                    echo base64_encode(json_encode(array("status" => -6, "msg" => "二级密码验证失败")));
                    exit;
                }
                $own_amount = $own_amount - $real_amount;
                $data['balance_coin'] = $own_amount;
                $res1 = M('promote', 'tab_')->where($map10)->save($data);
                if ($res1) {
                    $map5['account'] = $account;
                    $before_amount = M('user', 'tab_')->where($map5)->field('balance')->find();
                    $before_amount = $before_amount['balance'];
                    $before_amount = $before_amount + $amount;
                    $data2['balance'] = $before_amount;
                    $res2 = M('user', 'tab_')->where($map5)->save($data2);
                    if ($res2) {
                        $data3['order_no'] = "AG_" . date('Ymd') . date('His') . sp_random_string(4);
                        $data3['game_id'] = $game_id;
                        $data3['game_appid'] = $game_appid;
                        $data3['game_name'] = $game_name;
                        $data3['promote_id'] = $promote_id;
                        $data3['promote_account'] = $promote_account;
                        $data3['user_id'] = $user_id;
                        $data3['user_account'] = $account;
                        $data3['user_nickname'] = $user_nickname;
                        $data3['amount'] = $amount;
                        $data3['real_amount'] = $real_amount;
                        $data3['pay_way'] = 4;//平台币
                        $data3['zhekou'] = $zhekou;
                        $this->add_agent($data3);
                        echo base64_encode(json_encode(array("status" => 1, "msg" => "代充成功")));
                    } else {
                        echo base64_encode(json_encode(array("status" => -2, "msg" => "代充失败")));
                        exit;
                    }
                } else {
                    echo base64_encode(json_encode(array("status" => -1, "msg" => "代充失败")));
                    exit;
                }
                break;
            case 'zfb':
                $map4['id'] = $promote_id;
                $res4 = M("Promote", "tab_")->field('second_pwd')->where($map4)->find();
                if ($this->think_ucenter_md5($user['code'], UC_AUTH_KEY) !== $res4['second_pwd']) {
                    echo base64_encode(json_encode(array("status" => -6, "msg" => "二级密码验证失败")));
                    exit;
                }
                $request['promote_id'] = $promote_id;
                $request['promote_account'] = $promote_account;
                $request['account'] = $account;
                $request['game_id'] = $game_id;
                $request['game_name'] = $game_name;
                $request['price'] = $real_amount;
                $request['amount'] = $amount;
                $request['pay_way'] = 1;
                $request['user_id'] = $user_id;
                $request['zhekou'] = $zhekou;
                $request['game_appid'] = $game_appid;
                $request['apitype'] = "alipay";
                $request['config'] = "alipay";
                $request['signtype'] = "MD5";
                $request['server'] = "mobile.securitypay.pay";
                $request['payway'] = 1;
                $data22 = $this->pay($request);

                $md5_sign = $this->encrypt_md5(base64_encode($data22['arg']), "mengchuang");
                $data_pay = array("status" => 1, "orderInfo" => base64_encode($data22['arg']), "out_trade_no" => $data22['out_trade_no'], "order_sign" => $data22['sign'], "md5_sign" => $md5_sign);
                echo base64_encode(json_encode($data_pay));
                break;

        }


    }

    public function add_agent($data) {
        $agent = M("agent", "tab_");
        $ordercheck = $agent->where(array('pay_order_number' => $data["order_no"]))->find();
        if ($ordercheck) $this->error("订单已经存在，请刷新充值页面重新下单！");
        $agnet_data['order_number'] = "";
        $agnet_data['pay_order_number'] = $data["order_no"];
        $agnet_data['game_id'] = $data["game_id"];
        $agnet_data['game_appid'] = $data["game_appid"];
        $agnet_data['game_name'] = $data["game_name"];
        $agnet_data['promote_id'] = $data["promote_id"];
        $agnet_data['promote_account'] = $data["promote_account"];
        $agnet_data['user_id'] = $data["user_id"];
        $agnet_data['user_account'] = $data["user_account"];
        $agnet_data['user_nickname'] = $data["user_nickname"];
        $agnet_data['pay_type'] = 0;//代充 转移
        $agnet_data['amount'] = $data["amount"];
        $agnet_data['real_amount'] = $data["real_amount"];
        $agnet_data['pay_status'] = 1;
        $agnet_data['pay_way'] = $data['pay_way'];
        $agnet_data['create_time'] = time();
        $agnet_data['zhekou'] = $data['zhekou'];
        $agent->create($agnet_data);
        $resutl = $agent->add();
    }

    //获取折扣后的真实价格
    public function get_real_amount() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['promote_id'];
        $map['game_id'] = $user['game_id'];
        $res = M('promote_welfare', 'tab_')->where($map)->find();
        if (null !== $res) {
            $discount_ = $res['promote_discount'];
        } else {
            $map2['id'] = $user['game_id'];
            $discount = M('game', 'tab_')->where($map2)->field('discount')->find();
            $discount_ = $discount['discount'];
        }
        $real_amount = $user['amount'] * $discount_ / 10;
        echo base64_encode(json_encode(array("status" => 1, "data" => $real_amount, "data2" => $user['amount'])));
    }

    //获取折扣信息
    public function get_discount() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['game_id'] = $user['game_id'];
        $map['promote_id'] = $user['promote_id'];
        $data = M('promote_welfare', 'tab_')->where($map)->find();
        if (empty($data)) {
            $map1['id'] = $user['game_id'];
            $game = M('game', 'tab_')->where($map1)->find();
            $discount_ = $game['discount'];
        } else {
            $discount = discount_data($data);
            $discount_ = $discount['promote_discount'];
        }
        echo base64_encode(json_encode(array("status" => 1, "data" => $discount_)));
    }

    //内部用的
    public function get_in_discount($user) {
        $map['promote_id'] = $user['promote_id'];
        $map['game_id'] = $user['game_id'];
        $res = M('promote_welfare', 'tab_')->where($map)->find();
        if (null !== $res) {
            $discount_ = $res['promote_discount'];
        } else {
            $map2['id'] = $user['game_id'];
            $discount = M('game', 'tab_')->where($map2)->field('discount')->find();
            $discount_ = $discount['discount'];
        }
        return $discount_;
    }

    //获取子渠道的id和账号
    public function get_son_promoter() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $parent_id = M('Promote','tab_')->where(array('id'=>$user['id']))->getField('parent_id');
        $map2['parent_id'] = $user['id'];
        if (empty($parent_id)){
            $map2['id'] = $user['id'];
            $map2['_logic'] = 'OR';
        }
        $res = M('promote', 'tab_')->where($map2)->field('id,account')->select();
        $count = count($res);
        if ($count == 1){
            $res = null;
        }
        if (!empty($res)){
            $all = array(['id'=>$user['id'].",all",'account'=>'全部渠道']);
            $res = array_merge($all,$res);
        }
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //会长转移
    public function balance_transfer() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['main_id'];
        $map2['id'] = $user['sub_id'];
        $map3['id'] = $user['sub_id'];
        $con = M('promote', 'tab_')->where($map3)->find();
        if (!$con) {
            echo base64_encode(json_encode(array("status" => -5, "msg" => "转移账号不存在")));
            exit;
        }
        $money_t = $user['amount'];
        $money = M('promote', 'tab_')->where($map)->field('balance_coin')->find();
        $money = $money['balance_coin'];
        $money2 = M('promote', 'tab_')->where($map2)->field('balance_coin')->find();
        $money2 = $money2['balance_coin'];
        if ($money_t > $money) {
            echo base64_encode(json_encode(array("status" => -2, "msg" => "您的余额不足")));
            exit;
        }
        $data['balance_coin'] = $money - $money_t;
        $res = M('promote', 'tab_')->where($map)->save($data);//减少主渠道平添比
        if ($res) {
            $data2['balance_coin'] = $money2 + $money_t;
            $res2 = M('promote', 'tab_')->where($map2)->save($data2);//增加子渠道平台币
            if ($res2) {
                $data3['main_balance_coin'] = number_format($data['balance_coin'], 2, '.', '');
                $data3['sub_balance_coin'] = number_format($data2['balance_coin'], 2, '.', '');
                echo base64_encode(json_encode(array("status" => 1, "msg" => "转移成功", "data1" => $data3)));
                $data['promote_id'] = $user['main_id'];
                $data['promote_type'] = 1;
                $data['num'] = $money_t;
                $data['create_time'] = time();
                $data['op_id'] = 0;
                $data['type'] = 2;
                $data['source_id'] = $user['sub_id'];
                M('promote_coin', 'tab_')->add($data);

                $data2['promote_id'] = $user['sub_id'];
                $data2['promote_type'] = 2;
                $data2['num'] = $money_t;
                $data2['create_time'] = time();
                $data2['op_id'] = 0;
                $data2['type'] = 1;
                $data2['source_id'] = $user['main_id'];
                M('Promote_coin','tab_')->add($data2);
            } else {
                echo base64_encode(json_encode(array("status" => -2, "msg" => "转移失败")));
                exit;
            }
        } else {
            echo base64_encode(json_encode(array("status" => -2, "msg" => "转移失败")));
            exit;
        }

    }

    //获取会长联系人电话邮箱
    public function promote_info() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['id'];
        $res = M('promote', 'tab_')->field('real_name,mobile_phone,email')->where($map)->find();
        if ($res['real_name'] == null) {
            $res['real_name'] = '';
        }
        if ($res['mobile_phone'] == null) {
            $res['mobile_phone'] = '';
        }
        if ($res['email'] == null) {
            $res['email'] = '';
        }
        echo base64_encode(json_encode(array("status" => 1, "data" => $res)));
    }

    //渠道转移记录
    public function promote_transfe() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['promote_id'];
        $map['source_id'] = array('gt',0);
        if (isset($user['child_promote_id']) && substr($user['child_promote_ud'],-3)=='all') {
            if ($map['promote_id'] == $user['child_promote_id']) {
                unset($user['child_promote_id']);
            } else {
                $map['source_id'] = $user['child_promote_id'];
            }
        }elseif(isset($user['child_promote_id'])){
            $map['source_id'] = $user['child_promote_id'];
        }
        if (isset($user['start_time']) && isset($user['end_time']) && $user['start_time'] != "" && $user['end_time'] != "") {
            $map['create_time'] = array('BETWEEN', array(strtotime($user['start_time']), strtotime($user['end_time']) + 24 * 60 * 60 - 1));
        } elseif (isset($user['start_time']) && $user['start_time'] != "") {
            $map['create_time'] = array('gt', strtotime($user['start_time']));
        } elseif (isset($user['end_time']) && $user['end_time'] != "") {
            $map['create_time'] = array('lt', strtotime($user['end_time'])+ 24 * 60 * 60 - 1);
        }

        $page = $user['limit'] ? $user['limit'] : 1;
        $res = M('PromoteCoin', 'tab_')->field('id,source_id,num,create_time')->where($map)->order('create_time desc')->page($page, 8)->select();
        foreach ($res as &$value) {
            $value['promote_id'] = get_promote_account($value['source_id']);
        }
        $all_list = M('PromoteCoin', 'tab_')->where($map)->count();
        $time = date("Y-m-d", time());
        $time = strtotime($time);
        $map['create_time'] = array('gt', $time);
        $today_list = M('PromoteCoin', 'tab_')->where($map)->count();
        echo base64_encode(json_encode(array("status" => 1, "data" => $res, "all_list" => $all_list, "today_list" => $today_list)));
    }

    function think_ucenter_md5($str, $key = 'ThinkUCenter') {
        return '' === $str ? '' : md5(sha1($str) . $key);
    }

    //验证会长二级密码
    public function promote_code() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        $use = new UserApi();
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['promote_id'];
        $res = M('Promote', 'tab_')->field('second_pwd')->where($map)->find();
        if ($this->think_ucenter_md5($user['second_pwd'], UC_AUTH_KEY) == $res['second_pwd']) {
            echo base64_encode(json_encode(array("status" => 1, "msg" => "验证成功")));
        } else {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "验证失败")));
        }
    }

    //设置会长二级密码
    public function set_promote_code() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['promote_id'];
        $res1 = M('Promote', "tab_")->field('second_pwd')->where($map)->find();
        if ($res1['second_pwd'] != null) {
            echo base64_encode(json_encode(array("status" => -2, "msg" => "该渠道已经设置二级密码了")));
            exit;
        }
        $use = new UserApi();
        $data = array("second_pwd" => $this->think_ucenter_md5($user['second_pwd'], UC_AUTH_KEY),);
        $res = M('Promote', "tab_")->where($map)->save($data);
        if ($res) {
            echo base64_encode(json_encode(array("status" => 1, "msg" => "二级密码设置成功")));
            exit;
        } else {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "二级密码设置失败")));
            exit;
        }
    }

    /**
     * [判断该渠道是否设置了二级密码]
     * @author 幽灵[syt]
     */
    public function get_promote_code_status(){
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit;
        }
        $map['id'] = $user['promote_id'];
        $res1 = M('Promote', "tab_")->field('second_pwd')->where($map)->find();
        if ($res1['second_pwd'] != null) {
            echo base64_encode(json_encode(array("status" => 1, "msg" => "该渠道已经设置二级密码")));
            exit;
        }else{
            echo base64_encode(json_encode(array("status" => 0, "msg" => "该渠道没有设置二级密码")));
            exit;
        }
    }
}
 