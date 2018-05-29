<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ServerController extends ThinkController {
	const model_name = 'Server';

    public function lists(){
        if(isset($_REQUEST['server_name'])){
            $extend['server_name']=array('like','%'.$_REQUEST['server_name'].'%');
            unset($_REQUEST['server_name']);
        }

        if(isset($_REQUEST['start']) && isset($_REQUEST['end'])){
            $extend['start_time']  =  array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$extend['start_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$extend['start_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$extend['start_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $extend['game_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        $extend['order']="start_time desc";
    	parent::lists(self::model_name,$_GET["p"],$extend);
    }

    public function add(){
    	$model = M('Model')->getByName(self::model_name);
    	parent::add($model["id"]);
    }

    public function edit($id=0){
		$id || $this->error('请选择要编辑的用户！');
		$model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
		parent::edit($model['id'],$id);
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }
     //批量新增
    public function batch(){
        if(IS_POST){
            $server_str = str_replace(array("\r\n", "\r", "\n"), "", I('server'));
            $server_ar1 = explode(';',$server_str);
            array_pop($server_ar1);
            $num = count($server_ar1);
            if($num > 100 ){
                $this->error('区服数量过多，最多只允许添加100个！');
            }
            $verify = ['game_id','server_name','time'];
            foreach ($server_ar1 as $key=>$value) {
                $arr = explode(',',$value);
                foreach ($arr as $k=>$v) {
                    $att = explode('=',$v);
                    if(in_array($att[0],$verify)){
                        switch ($att[0]){
                            case 'time' :
                                $time = $server[$key]['start_time'] = strtotime($att[1]);
                                break;
                            case 'game_id':
                                $game = M('Game','tab_')->find($att[1]);
                                if(empty($game)){
                                    $this->error('游戏ID不存在');
                                }
                                $server[$key]['game_id'] = $att[1];
                                break;
                            default:
                                $server[$key][$att[0]] = $att[1];
                        }
                    }
                }
                $server[$key]['game_name'] = get_game_name($server[$key]['game_id']);
                $server[$key]['server_num'] = 0;
                $server[$key]['recommend_status'] = 1;
                $server[$key]['show_status'] = 1;
                $server[$key]['stop_status'] = 0;
                $server[$key]['server_status'] = 0;
                $server[$key]['parent_id'] = 0;
                $server[$key]['create_time'] = time();
            }
            $res = M('server','tab_')->addAll($server);
            if($res !== false){
                $this->success('添加成功！',U('Server/lists'));
            }else{
                $this->error('添加失败！'.M()->getError());
            }
        }else{
            $this->meta_title = '新增区服管理';
            $this->display();
        }
    }
}
