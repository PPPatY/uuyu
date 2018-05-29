<?php
namespace Channel\Controller;
use User\Api\MemberApi;
use User\Api\UserApi;

class ServerController extends BaseController {

    protected function _initialize() {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
    }


    //全部游戏 通过推广id查询apply表 join tab_game表 status 为1 的游戏
    public function get_all_game() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => 1, "msg" => "数据不能为空")));
            exit;
        }

        $data = M('game', 'tab_')->field('id,game_name')->where($map)->select();
        echo base64_encode(json_encode(array("status" => 1, "data" => $data)));
        exit;
    }


    //我的游戏
    public function my_game() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "数据不能为空")));
            exit;
        }
        $map['promote_id'] = $user['id'];
        if (isset($user['type'])) {
            $map['status'] = $user['type'];
        }
        if (isset($user['limit']) && $user['limit'] != "") {
            $limit = $user['limit'];
        } else {
            $limit = 1;
        }
        if (isset($user['game_name']) && $user['game_name'] != "") {
            $map['tab_apply.game_name'] = array('like', '%' . $user['game_name'] . '%');
        }
        $model = array('m_name' => 'apply',
                       'field' => 'tab_apply.game_id,tab_apply.game_name,tab_apply.pack_url,tab_apply.dow_url,tab_apply.register_url,tab_game.icon,tab_game.game_type_name,tab_game.game_size,tab_apply.status,tab_game_set.apk_pck_name',
                       'join' => 'tab_game on tab_game.id=tab_apply.game_id',
                       'joins' => 'tab_game_set on tab_game.id=tab_game_set.game_id',
                       'map' => $map,
                       'list_row' => 10,
                       'order' => 'apply_time desc',
            );
        $user1 = A('User', 'Event');
        $list = $user1->user_join($model, $limit);

        foreach ($list as $key => $value) {
            $list[$key]['game_name'] = preg_replace('/(\(.+)/i', '', $value['game_name']);
            $list[$key]['icon'] = icon_url($value['icon']);
            $list[$key]['dow_url'] = 'http://' . $_SERVER['HTTP_HOST'] . $value['dow_url'];
            $list[$key]['apk_pck_name'] = $list[$key]['apk_pck_name'] ? $list[$key]['apk_pck_name'] : "";
        }
        echo base64_encode(json_encode(array("status" => 1, "data" => $list)));

    }

    //申请游戏的列表
    public function apply_game_list() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "数据不能为空")));
            exit;
        }
        if (isset($user['game_name']) && $user['game_name'] != "") {
            $map['game_name'] = array('like', '%' . $user['game_name'] . '%');
        }
        if (isset($user['limit'])) {
            $limit = $user['limit'];
        } else {
            $limit = 1;
        }

        $map['game_status'] = 1;
        $promote_id = $user['promote_id'];
        $model = array('m_name' => 'game', 'field' => 'id,game_name,icon,game_type_name,game_size,money,ratio', 'map' => $map, 'list_row' => 10, 'order' => 'id',);
        $user1 = A('User', 'Event');
        $list = $user1->user_join($model, $limit);
        
        foreach ($list as $key => $value) {
            $list[$key]['game_name'] = preg_replace('/(\(.+)/i', '', $value['game_name']);
            $list[$key]['icon'] = icon_url($value['icon']);
            $list[$key]['status'] = $this->apply_g($value['id'], $promote_id);
        }
        echo base64_encode(json_encode(array("status" => 1, "data" => $list)));
    }

    //游戏详情
    public function game_details() {
        $user = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($user)) {
            echo base64_encode(json_encode(array("status" => 0, "msg" => "数据不能为空")));
            exit;
        }
        if (isset($user['game_id']) && $user['game_id'] != "") {
            $map['id'] = $user['game_id'];
        }
        $promote_id = $user['promote_id'];
        $list = M('game', 'tab_')->where($map)->field('id,game_name,icon,game_type_name,game_size,money,ratio')->find();
        $list['game_name'] = preg_replace('/(\(.+)/i', '', $list['game_name']);
        $list['icon'] = icon_url($list['icon']);
        $list['status'] = $this->apply_g($list['id'], $promote_id);
        echo base64_encode(json_encode(array("status" => 1, "msg" => "成功", "data" => $list)));
    }

    public function apply_g($game_id, $promote_id = 1) {
        $map['game_id'] = $game_id;
        $map['promote_id'] = $promote_id;
        $data = M('apply', 'tab_')->where($map)->find();
        if (null == $data) {
            return "-1";
        } else if ($data['status'] == 0) {
            return "0";
        } else if ($data['status'] == 1) {
            return "1";
        }
    }

    //IOS安装地址
    public function ios_game_pack_down() {
        $request = json_decode(base64_decode(file_get_contents("php://input")), true);
        if (empty($request)) {
            echo base64_encode(json_encode(array("status" => -1, "msg" => "数据不能为空")));
            exit();
        }
        $map['game_id'] = $request['game_id'];
        $map['file_type'] = 2;
        $map['game_id'] = $request['game_id'];
        $gamescoue = M("GameSource", "tab_")->where($map)->find();
        if (null == $gamescoue) {
            $game_map['id'] = $request['game_id'];
            $game = M('game', 'tab_')->where($game_map)->find();
            if (!empty($game['ios_game_address'])) {
                echo base64_encode(json_encode(array("status" => 1, "msg" => "数据返回成功", "url" => $game['ios_game_address'])));
                exit();
            } else {
                echo base64_encode(json_encode(array("status" => -1, "msg" => "原包未上传")));
                exit;
            }
        } else {
            echo base64_encode(json_encode(array("status" => 1, "url" => "https://" . $_SERVER["HTTP_HOST"] . ltrim($gamescoue['file_url'], "."))));
        }
    }

}