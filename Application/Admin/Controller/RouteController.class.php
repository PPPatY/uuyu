<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/26
 * Time: 13:42
 */
namespace Admin\Controller;

class RouteController extends ThinkController{


    public function lists(){
		$this->meta_title = "路由设置";
        $data = M('Route','tab_')->select();
        $this->assign('list_data',$data);
        $this->display();
    }

    public function add(){
		$this->meta_title = "路由设置";
        if(IS_POST){
            $route = M('route','tab_');
            if($route->create() && $route->add() !==  false){
                $config = $route->where(array('status'=>1))->select();
                $this->set_config('full_name',$config);
                $this->success('添加成功',U('lists'));
            }else{
                $this->error('添加失败:'.$route->getError());
            }
        }else{
            $this->display();
        }
    }

    public function edit($id=0){
		$this->meta_title = "路由设置";
        if(IS_POST){
            $route = M('route','tab_');
            $data = I('post.');
            if($route->save($data) !==  false){
                $config = $route->where(array('status'=>1))->select();
                $this->set_config('full_name',$config);
                $this->success('编辑成功',U('lists'));
            }else{
                $this->error('编辑失败:'.$route->getError());
            }
        }else{
            $data = M('route','tab_')->find($id);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function del($ids=0){
        !empty($ids) || $this->error('删除失败');
        $route = M('route','tab_');
        $map = array('id' => array('in', $ids) );
        if($route->where($map)->delete()){
            $config = $route->where(array('status'=>1))->select();
            $this->set_config('full_name',$config);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    private function set_config($name = "", $data = "")
    {
        $config_file = "./Application/Common/Conf/route.php";
        foreach($data as $k=>$v){
            $config[$v['url']] = $v['full_url'];
        }
        $result = file_put_contents($config_file, "<?php\treturn " . var_export($config, true) . ";");
    }
}