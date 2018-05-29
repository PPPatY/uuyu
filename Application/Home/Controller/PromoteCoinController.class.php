<?php

namespace Home\Controller;
use OT\DataDictionary;
use User\Api\PromoteApi;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class PromoteCoinController extends BaseController {

    const MODEL_NAME = 'PromoteCoin';

    public function index($p=0){
        $map['type'] = 1;
        $map['promote_id'] = PID;
        if(I('timeend'))$end_time = strtotime(I('timeend'))+24*60*60-1;
        $start_time = strtotime(I('timestart'));
        if(!empty($end_time) && !empty($start_time)){
            $map['create_time'] = ['between',[$start_time,$end_time]];
        }elseif(!empty($end_time)){
            $map['create_time'] = ['elt',$end_time];
        }elseif(!empty($start_time)){
            $map['create_time'] = ['gt',$start_time];
        }
        $extend['map'] = $map;
        $this->meta_title = '平台币入账记录';
        $this->data_lists($p,self::MODEL_NAME,$extend);
    }
    public function data_lists($p,$model,$extend=[]){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = 10;
        $model = D($model);
        // 条件搜索
        $map = [];
        foreach(I('get.') as $name=>$val){
            $map[$name] =   $val;
        }
        $map = array_merge($map,$extend['map']);
        $lists_data = $model->where($map)->order('create_time desc')->page($page,$row)->select();
        $count = $model->where($map)->count();

        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            //分页跳转的时候保证查询条件
            foreach($_REQUEST as $key=>$val) {
                $page->parameter[$key]   =  $val;
            }
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $this->assign('lists_data',$lists_data);
        $this->assign('count',$count);
        $this->display();
    }

    /**
     * 转移平台币
     */
    public function shift($p=0){
        $data = D('Promote')->find(PID);
        if(IS_POST){
            $child = get_prmoote_chlid_account(PID);
            $child = array_column($child,'id');
            //子渠道过滤非法用户
            if (!in_array(I('promote_id'),$child)){
                $this->error('非法参数');
            }
            $num = I('num');
            $res = D('Promote')->shift_coin(PID,I('promote_id'),$num);
            if($res){
                $this->ajaxReturn(array('status'=>1,'msg'=>'转移成功！'));
            }else{
                $this->ajaxReturn(array('status'=>-1,'msg'=>'转移失败！'));
            }
        }else{
						$child = M('Promote','tab_')->field('account,balance_coin')->where(['parent_id'=>PID])->select();
            $page = $p ? $p : 1; //默认显示第一页数据
            $row = 10;
            $count = count($child);
            $arraypage = $page;
            $size=$row;//每页显示的记录数
            $pnum = ceil($count / $row); //总页数，ceil()函数用于求大于数字的最小整数
            //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
            $childData = array_slice($child, ($arraypage-1)*$row, $row);
            //分页
            if($count > $row){
                $page = new \Think\Page($count, $row);
                $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
                $this->assign('_page', $page->show());
            }
            $this->assign("childData",$childData);
						
						$this->meta_title = "平台币转移";
            $this->assign('data',$data);
            $this->display();
        }
    }

    /**
     * 获取渠道平台币
     * @param $id
     */
    public function get_coin($id){
        $data = D('promote')->find($id);
        $res['coin'] = $data['balance_coin'];
        $this->ajaxReturn($res);
    }

    /**
     * 转移平台币记录
     */
    public function record($p=0){
        $map['source_id'] = empty(I('promote_id')) ? ['neq','0'] : I('promote_id');
        $map['type'] = 2;
        $map['promote_id'] = PID;
        $end_time = strtotime(I('timeend'));
        $start_time = strtotime(I('timestart'));
        if(!empty($end_time) && !empty($start_time)){
            $map['create_time'] = ['between',[$start_time,$end_time+86399]];
        }elseif(!empty($end_time)){
            $map['create_time'] = ['elt',$end_time+86399];
        }elseif(!empty($start_time)){
            $map['create_time'] = ['gt',$start_time];
        }
        $extend['map'] = $map;
        $extend['order'] = 'create_time desc';
				$this->meta_title = "转移记录";
        parent::data_lists($p,self::MODEL_NAME,$extend);
    }
}