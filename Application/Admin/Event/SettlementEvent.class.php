<?php
namespace Admin\Event;
use Think\Controller;
/**
 * 后台事件控制器
 * @author 鹿文学 
 */
class SettlementEvent extends ThinkEvent {
    public function settlement($model = null,$p,$extend=array()) {
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage=$page;
        $umap=$extend['umap'];
        $smap=$extend['smap'];
        //解析列表规则
        $fields = $model['fields'];
        $row    = intval(C('LIST_ROWS')) ? :(empty($model['list_row']) ? 10 : $model['list_row']);
        // 关键字搜索
        $udata=M('User','tab_')
            ->field('fgame_id as game_id,fgame_name as game_name,if(parent_id = 0,promote_id,parent_id) as pid,count(*) as unum,ratio,money')
            ->where($umap)
            ->join('tab_game on tab_user.fgame_id = tab_game.id')
            ->group('fgame_id,pid')
            // ->page($page, $row)
            ->order('tab_user.id desc')
            ->select();
            $ucount=count($udata);
        $sdata=M('Spend','tab_')
            ->field('game_id,tab_spend.game_name,if(tab_promote.parent_id = 0,tab_spend.promote_id,parent_id) as pid,sum(pay_amount) as spay_amount ,ratio,tab_game.money')
            ->join('tab_promote on tab_spend.promote_id = tab_promote.id')
            ->join('tab_game on tab_spend.game_id= tab_game.id')
            ->where($smap)
            ->group('game_id,pid')
            ->select();
            $scount=count($sdata);
        if(!empty($udata) && !empty($sdata)){
            foreach ($udata as $key => $value) {
                foreach ($sdata as $k => $v) {
                    if (($value['pid'] == $v['pid']) && ($value['game_id'] == $v['game_id'])) {
                        $data[] = array_merge($value,$v);unset($udata[$key]);unset($sdata[$k]);
                    }
                }
            }
            $data = array_merge($data,$udata,$sdata);
        }elseif (!empty($udata)) {
            $data = $udata;
        }elseif (!empty($sdata)) {
            $data = $sdata;
        }
        $count=$ucount>=$scount?$ucount:$scount;
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        $this->assign('model', $model);
        $this->assign('list_data', $data);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']); 
    }
    
    public function money_list($model = null,$p,$extend=array()) {
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        //解析列表规则
        $fields = $model['fields'];
        // 关键字搜索
        $map    =   empty($extend)?array():$extend;
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];
        //读取模型数据列表 
        $name = $model['m_name'];
        $new_model = D($name);
        $data = D($name)                   
            ->where($map)                  
            ->order($model['order'])      
            ->page($page, $row)     
            ->select();
        $count = D($name)->where($map)->count();
        $to_map=$map;
        $to_map['status']=1;
        $total =  D($name)                   
            ->where($to_map)                  
            ->order($model['order'])      
            ->page($page, $row)
            ->sum("sum_money"); 
        //分页
         
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('model', $model);
        $this->assign('list_data', $data);
        $this->assign('total',$total==null?0:$total);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']);
    }
       
}