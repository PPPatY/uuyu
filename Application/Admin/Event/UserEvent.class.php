<?php
namespace Admin\Event;
use Think\Controller;
/**
 * 后台事件控制器
 * @author 王贺 
 */
class UserEvent extends BaseEvent {
    
    public function lists($model=null,$p=1,$extends_map=array()){
        parent::custom_list($model,$p,$extends_map);
    }
    public function user_join($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $arraypage = $page; //默认显示第一页数据
        //解析列表规则
        $fields = $model['fields'];
        // 关键字搜索
        $map    =  $model['map']; //array();
        foreach ($key as $key => $value) {
            if(isset($_REQUEST[$value])){
                $map[$value]  =   array('like','%'.$_GET[$value].'%');
                unset($_REQUEST[$value]);
            }
        }
        // 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($name,$fields)){
                $map[$name] =   $val;
            }
        }

        $row    = intval(C('LIST_ROWS')) ? :(empty($model['list_row']) ? 10 : 3);

        //读取模型数据列表
        $name = $model['m_name'];
        $data = M($name,"tab_")
            ->field($model['fields'])
            ->join($model['join'])
            ->join($model['joins'])
            ->join($model['joinss'])
            // 查询条件
            ->where($model['map'])
            /* 默认通过id逆序排列 */
            ->order($model['order'])
            ->group($model['group'])
            /* 执行查询 */
            ->select();
            $count=count($data);
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
    /**
	*获取用户实体
	*@param int $id
	*@return array
	*@author 小纯洁 
    */
    public function user_entity($id=0){
    	$user = M("user","tab_");
    	$map['id'] = $id;
    	$data = $user->where($map)->find();
    	return $data;
    }


     public function user_join_($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        //解析列表规则
        $fields = $model['fields'];
        // 关键字搜索

        $row    = empty($model['list_row']) ? 20 : $model['list_row'];
        // $row = 1;
        //读取模型数据列表
        $name = $model['m_name'];
        $data = M($name,"tab_")
            ->field($model['fields'])
            ->join($model['join'])
            ->join($model['joins'])
            ->join($model['joinss'])
            // 查询条件
            ->where($model['map'])
            /* 默认通过id逆序排列 */
            ->order($model['order'])
            ->group($model['group'])
            /* 数据分页 */
            ->page($page, $row)
            /* 执行查询 */
            ->select();
        /* 查询记录总数 */
        $count = M($name,"tab_")
            ->field($model['fields'])
            ->join($model['join'])
            ->join($model['joins'])
            ->join($model['joinss'])
            // 查询条件
            ->where($model['map'])
            /* 默认通过id逆序排列 */
            ->order($model['order'])
            ->group($model['group'])
            /* 数据分页 */
            /* 执行查询 */
            ->count();
            if($model['ranking']==1){
            $i=($page-1)*$row;
            foreach ($data as $key => $value) {
               $ss[++$i]=$value;
            }
                 $this->assign('list_data', $ss);
            }else{
                 $this->assign('list_data', $data);
            }

          //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('model', $model);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']);
    }
    
}
