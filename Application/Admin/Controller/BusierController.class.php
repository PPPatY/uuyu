<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class BusierController extends ThinkController {

    const model_name = 'Busier';

    public function lists(){
        parent::lists(self::model_name,$_GET["p"],$extend);

    }

    public function record(){
    	if(isset($_REQUEST['id'])){
    		$map['id'] = $_REQUEST['id'];
    	}
    	if(isset($_REQUEST['status'])){
    		$map['status'] = $_REQUEST['status'];
    	}
    	parent::lists($model,$_GET['p'],$map);
    }

    public function add($model=""){
    	parent::add($model);
    }

    public function edit($id=0){
    	$_REQUEST['id'] || $this->error('请选择要编辑的用户！');
    	$map['id'] = $_REQUEST['id'];
    	if(IS_POST){
    		if($_POST['password']){
    			$_POST['password'] = think_ucenter_md5($_POST['password'],UC_AUTH_KEY);
    		}
    		$res = M('Busier','tab_')->where($map)->save($_POST);
    		$res ? $this->success('修改成功',U('Busier/lists')) : $this->error('数据没被修改',U('Busier/lists'));
    	}else{
    		$data = M('Busier','tab_')->where($map)->find();
    		$this->assign('data',$data);
    		$this->assign('meta_title','编辑');
    		$this->display();
    	}
    }
    public function check_status($id="",$status=""){
    	$_REQUEST['id'] || $this->error('请选择要编辑的用户！');
    	$map['id'] = $id;
    	$res = M('Busier','tab_')->where($map)->setField(['status'=>$status]);
    	if($res){
    		$this->success('操作成功！');
    	}else{
    		$this->error('操作失败！');
    	}
    }
    
    public function del($model = null, $ids=null){
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids);
    }

    public function get_busier_user(){
    	$map['id'] = $_REQUEST['id'];
    	$data =  M('Busier','tab_')->where($map)->find();
    	$this->assign('data',$data);
    	$this->display();
    }
    
    public function search_busier_user(){
    	if($_REQUEST['account']){
    		$map['account'] = array('like','%'.$_REQUEST['account'].'%');
		}
		if($_REQUEST['busier_id']){
			$map['busier_id'] = $_REQUEST['busier_id'];
		}
    	$data = M('Promote','tab_')->field('id,account')->where($map)->select();
    	$this->ajaxReturn(array('status'=>1,'msg'=>"",'data'=>$data));
    }
    
    public function del_busier(){
    	$map['id'] = $_REQUEST['id'];
		if($_REQUEST['type'] == "all"){
			M('Promote','tab_')->where(array('busier_id'=>$_REQUEST['id']))->setField(['busier_id'=>0]);
			$res = M('Busier','tab_')->where($map)->setField(['promote_user'=>'']);
		}else{
			$data =  M('Busier','tab_')->where($map)->find();
			$promote = explode(',', $data['promote_user']);
			foreach ($promote as $key=>$va){
				if($va == $_REQUEST['type']){
					unset($promote[$key]);
				}
			}
			$string = join(',', $promote);
			M('Promote','tab_')->where(array('id'=>$_REQUEST['type']))->setField(['busier_id'=>0]);
            del_busier_id($_REQUEST['type']);
			$res = M('Busier','tab_')->where($map)->setField(['promote_user'=>$string]);
		}
		$res ? $this->ajaxReturn(array('status'=>1,'url'=>U('Busier/lists'))) : $this->ajaxReturn(array('status'=>0));
    }
    public function get_ajax_area_list(){
        $area = D('Server');
        $map['game_id'] = I('post.game_id',1);
        $list = $area->where($map)->select();
        $this->ajaxReturn($list); 
    }
    public function data_lists(){
    	if(isset($_REQUEST['time-start'])&&isset($_REQUEST['timeend'])){
    		$map2['pay_time'] = $map['register_time'] =array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
    		
    	}
    	if(isset($_REQUEST['busier_id'])){
    		$map1['id'] = $_REQUEST['busier_id'];
    		unset($_REQUEST['busier_id']);
    	}
    	$data = M('Busier','tab_')->where($map1)->select();
    	foreach ($data as $key=>$value){
    		if($value['promote_user']){
                $pidarr = M('Promote','tab_')->field('id')->where(['busier_id'=>$value['id']])->select();
                $pids = implode(',',array_column($pidarr, 'id'));
    			$map2['promote_id'] = $map['promote_id'] = array('in',$pids);
    			$map2['pay_status'] = 1;
    			//总注册数量
                $map['busier_id'] = $value['id'];
    			$data[$key]['register_num'] = M('User','tab_')->field('id')->where($map)->count();
    			$data[$key]['spend_num'] = M('Spend','tab_')->where($map2)->sum('pay_amount');
    		}else{
    			$data[$key]['register_num'] = 0;
    			$data[$key]['spend_num'] = 0;
    		}
    	}
    	$this->assign('list_data',$data);
			$this->meta_title = '汇总查询';
    	$this->display();
    }
}
