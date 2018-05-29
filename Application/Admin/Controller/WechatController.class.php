<?php

namespace Admin\Controller;
use Com\Wechat;
use Com\WechatAuth;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class WechatController extends ThinkController {
    private $template;
    public function index($value='')
    {
    	$this->BaseConfig("wechat");
        $this->assign("nav_data",$this->template);
    	$this->assign("wechat_url","http://".$_SERVER["HTTP_HOST"]."/sdk.php/Wechat/index");
    	$this->meta_title = "微信公众号设置";
        $this->display();
    }

    /**
    *保存设置
    */
    public function saveTool($value='')
    {
        $name   = $_POST['name'];
        $config = I('config');
        $data   = array('config'=>json_encode($config),'template'=>json_encode($_POST['template']),'status'=>$_POST['status']);
        $flag   = M('Tool','tab_')->where("name='{$name}'")->setField($data);
        if($flag !== false){
            $this->set_config($name,$config);
            $this->get_menu($_POST['template']['nav']);
            $this->success('保存成功');
        }else{
            $this->error('保存失败');
        }

    }

    /**
    *显示扩展设置信息
    */
    protected function BaseConfig($name='')
    {   
        $map['name'] = array('in',$name);
        $tool = M('tool',"tab_")->where($map)->select();
        if(empty($tool)){$this->error('没有此设置');}
        foreach ($tool as $key => $val) {
            $this->assign($tool[$key]['name'],json_decode($tool[$key]['config'],true));
            $this->template = json_decode($tool[$key]['template'],true);
            $this->assign($tool[$key]['name']."_tem",$this->template);
            unset($tool[$key]['config']);
            $this->assign($tool[$key]['name']."_data",$tool[$key]);
        }
        $data = $this->template['nav'];
        foreach ($data[1] as $key => $value) {
            foreach ($value as $k => $val) {
                $data_n[$k][$key] = $val;
            }
        }
        foreach ($data[2] as $key => $value) {
            foreach ($value as $k => $val) {
                $data_n1[$k][$key] = $val;
            }
        }
        foreach ($data_n as $key => $value) {
            foreach ($data_n1 as $k => $val) {
                if($key == $val['pid']){
                    $data_n[$key]['child'][$k]=$val;
                }
            }
        }
        $this->template = $data_n;
    }

    /**
    *设置config
    */
    private function set_config($name="",$config=""){
        $config_file ="./Application/Common/Conf/pay_config.php";
        if(file_exists($config_file)){
            $configs=include $config_file;
        }else {
            $configs=array();
        }
        #定义一个数组
        $data = array();
        #给数组赋值
        $data[$name] = $config;
        $configs=array_merge($configs,$data);
        $result = file_put_contents($config_file, "<?php\treturn " . var_export($configs, true) . ";");
    }

    private function get_menu($data_n=""){
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        $newmenu = $this->set_menu($data_n);
        file_put_contents(dirname(__FILE__).'/menu.json', json_encode($newmenu));
        $data = $auth->menuCreate($newmenu);
    }

    private function set_menu($data){
        foreach ($data[1] as $key => $value) {
            foreach ($value as $k => $val) {
                $data_n[$k][$key] = $val;
            }
        }
        foreach ($data[2] as $key => $value) {
            foreach ($value as $k => $val) {
                $data_n1[$k][$key] = $val;
            }
        }
        foreach ($data_n as $key => $value) {
            foreach ($data_n1 as $k => $val) {
                if($key == $val['pid']){
                    $data_n[$key]['child'][]=$val;
                }
            }
        }
        foreach ($data_n as $key => $value) {
            if(empty($value["child"])){
                $menu[$key]["type"] = "view";
                $menu[$key]["name"] = $value["title"];
                $menu[$key]["url"]  = $value["url"];
            }else{
                $value['child']=$this->my_sort($value['child'],'marak');
                foreach ($value["child"] as $k => $val) {
                    if($key == $val['pid']){
                        $menu[$key]["name"] = $value["title"];
                        $menu_child[$k] = array(
                            "type"=>"view",
                            "name"=>$val['title'],
                            "url" =>$val['url']
                        );
                        $menu[$key]["sub_button"] = $menu_child;
                    }
                }
                $menu_child = array();
            }
        }
        if(count($menu) == 4){//不懂为什么是3
            $new_menu = array(0=>$menu);
        }
        else{
            $new_menu = $menu;
        }
        return $new_menu;
    }
    function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){   
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    }
}
