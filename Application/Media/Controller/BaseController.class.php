<?php
namespace Media\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Admin\Model\PointTypeModel;
use Common\Model\ServerModel;
/**
* 首页
*/
class BaseController extends Controller {
	public function __construct() {
		$_SESSION['media']="media.php";
		$serverhost=$_SERVER['HTTP_HOST'];
		$headerurl = 'http://'.$serverhost.'/media.php';
		if(is_mobile_request()){
			$http = 'http';
	        if(is_https()){
	            $http = 'https';
	        }
	        if($_SERVER['REQUEST_URI']!='/'){
				$url = str_replace('media.php','mobile.php',$_SERVER['REQUEST_URI']);
				$headerurl = $http.'://'.$_SERVER["HTTP_HOST"].$url;
	        }
			Header("Location: $headerurl"); exit;
		}
		$config = api('Config/lists');
        C($config); //添加配置
		$serverhostarr = array('in',$serverhost.",http://".$serverhost.",https://".$serverhost);
		$host=M('apply_union','tab_')->field('union_id,union_account,status,apply_domain_type,domain_url,union_set')->where(array('domain_url'=>$serverhostarr))->find();
		if($host==''&&$serverhost!=C(PC_SET_DOMAIM)){
			die('<h1>404 not found</h1>The requested URL /media.php was not found on this server.');
		}else{
			if($serverhost!=C(PC_SET_DOMAIM)){
				session('for_third',C(PC_SET_DOMAIM));
			}
		}
		if($host['status']==1){
			session('union_host',$host);
		}else{
			if($serverhost!=C(PC_SET_DOMAIM)){
				echo "<script>alert('The site is not audited')</script>";exit;
			}
		}
		if(strtolower(ACTION_NAME)!='user'){
			$this->wechat_login();
		}
		parent::__construct();
		if(session('union_host')){
			$union_set=json_decode(session('union_host')['union_set'],true);
			$this->assign('union_set',$union_set);
		}
		$logindiv = $this->fetch('Public:loginsdk');//登录sdk

		//实名认证
		$name_auth = M('Tips','tab_')->where(['obj'=>1,'status'=>1])->find();
		$zhuceauth = C('REAL_NAME_REGISTER');
		$this->assign('open_name_auth',$zhuceauth);
		$this->assign('open_auth_tip',empty($name_auth)?0:1);
		$this->assign('name_auth_tip',$name_auth['tip']);
        $this->assign('logdiv',$logindiv);
        $userdata = M('User','tab_')->field('lock_status,real_name,idcard')->find(is_login());
    	if($userdata['lock_status']!=1){
    		session('user_auth', null);
            session('user_auth_sign', null);
            session("wechat_token", null);
    	}
        if(strtolower(ACTION_NAME)=='open_game'&&!empty($name_auth)){
        	if($userdata['real_name']==''&&$real_name['idcard']==''&&$name_auth['end_time']>time()){
        		session('stop_play',0);
        		$this->assign('user_no_auth',1);
        	}elseif($userdata['real_name']==''&&$real_name['idcard']==''&&$name_auth['end_time']<time()){
        		session('stop_play',1);
        		$this->assign('user_no_auth',1);
        		$this->assign('stop_play',1);
        	}else{
        		session('stop_play',0);
        	}
        }


        //用户消息
    	$pointtype = new PointTypeModel();
        if(is_login()>0){
        	$servermodel = new ServerModel();
        	$send_notice = $servermodel->send_server_notice(is_login());
        	$msg = M('msg','tab_')->where(['user_id'=>is_login(),'status'=>2])->find();
        	if(!empty($msg)){
        		$this->assign('newmsg',1);
        	}

        	//是否签到
        		$lgmap['pt.key'] = 'sign_in';
		        if(is_login()){
		            $lgjoin .= ' and pr.user_id = '.is_login();
		        }
		        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin,'ctime desc',1,1);
		        if(empty($loginpont[0]['user_id'])){
		            $issignin = 1;//是否显示商城红点
		        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
		            $issignin = 0;
		        }else{
		            $issignin = 1;
		        }
        		$this->assign('mallissignin',$issignin);
        }else{
        	$this->assign('mallissignin',1);
        }
    	$list = $pointtype->where(['key'=>'bind_phone'])->find();
    	$bindphoneadd = $list['point']>0?$list['point']:0;
        $this->assign('bindphoneadd',$bindphoneadd);
        //登录按钮
        $thirloginstr = "qq_login,wx_login,wb_login,bd_login";
        $logbutmap['name'] = array('in',$thirloginstr);
        $tool = M('tool',"tab_")->field('name,status')->where($logbutmap)->select();
        foreach ($tool as $key => $val) {
            $this->assign($tool[$key]['name'],$val['status']);
        }
        
        
        $app_map['name'] = ['like','%H5联运%'];
        $app_map['app_version'] = 1;
        $android = M('app', 'tab_')->where($app_map)->find();
        $this->assign('android',$android['file_url']);
        
        $ios = U('Index/iosdownload');
        $this->assign('ios',$ios);
	}

	public function is_login(){
		$user = session('user_auth');
	    if (empty($user)) {
	        return 0;
	    } else {
	        return session('user_auth_sign') == data_auth_sign($user) ? $user['user_id'] : 0;
	    }
	}

	/** * 第三方微信公众号登陆 * */
	public function wechat_login($state=0){
		if(empty(session("user_auth.user_id")) && is_weixin()){
			$appid = C('wechat.appid');
			$appsecret = C('wechat.appsecret');
			$auth  = new WechatAuth($appid, $appsecret);
			if(session('for_third')==C(PC_SET_DOMAIM)){
				$state=$_SERVER['HTTP_HOST'];
				$redirect_uri = "http://".session('for_third')."/mobile.php/ThirdLogin/wechat_login/gid/".$_REQUEST['gid']."/pid/".$_REQUEST['pid'];
			}else{
				$redirect_uri = "http://".$_SERVER['HTTP_HOST']."/mobile.php/ThirdLogin/wechat_login/gid/".$_REQUEST['gid']."/pid/".$_REQUEST['pid'];
			}
            redirect($auth->getRequestCodeURL($redirect_uri,$state));
		}
	}

	/** * 第三方微信扫码登陆 * */
	public function wechat_qrcode_login($state=1){
		if(empty(is_login()) && !is_weixin()){
			$appid     = C('wx_login.appid');
            $appsecret = C('wx_login.appsecret');
            $auth = new WechatAuth($appid, $appsecret);
			if(session('for_third')==C(PC_SET_DOMAIM)){
				$state=$_SERVER['HTTP_HOST'];
				$redirect_uri = "http://".session('for_third')."/mobile.php/ThirdLogin/wechat_login/gid/".$_REQUEST['gid']."/pid/".$_REQUEST['pid'];
			}else{
				$redirect_uri = "http://".$_SERVER['HTTP_HOST']."/mobile.php/ThirdLogin/wechat_login/gid/".$_REQUEST['gid']."/pid/".$_REQUEST['pid'];
			}
            redirect($auth->getQrconnectURL($redirect_uri,$state));
		}
	}

	public function pagelists($model,$p){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据	
 
		if (empty($model['where']))			
			$map = " ";
		else $map = $model['where'];
		
		if (empty($model['order']))			
			$mapo = " id DESC ";
		else $mapo = $model['order'];
        //获取模型信息
        $model = M('Model')->getByName($model["model"]);
        $model || $this->error('模型不存在！');
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];
        //读取模型数据列表
        $name = parse_name(get_table_name($model['id']), true);
        $data = M($name)
        /* 查询指定字段，不指定则查询所有字段 */
        ->field(true)
        // 查询条件
        ->where($map)
        /* 默认通过id逆序排列 */
        ->order($mapo)
        /* 数据分页 */
        ->page($page, $row)
        /* 执行查询 */
        ->select();
        /* 查询记录总数 */
        $count = M($name)->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
		
        $this->assign('model', $model);
        $this->assign('plist_data', $data);
        $this->meta_title = $model['title'].'列表';
	}
	
	public function plists($model,$p){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据	
		$map=array();
		$map[] = " 1 ";
		$tablename = C('DB_PREFIX').strtolower($model["model"]);
		$m = M($name)->query("SHOW COLUMNS FROM ".$tablename);
		foreach($m as $n) {
			$fields[]=$tablename.'.'.$n['Field'];
		}
		// 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($tablename.'.'.$name,$fields)){
                $map[$tablename.'.'.$name]	=	$val;
            }
        }

		if (empty($model['order']))			
			$mapo = $tablename.".id DESC ";
		else $mapo = $tablename.'.'.$model['order'];
		if (empty($model['join']))			
			$mapj = null;
		else {
			$mapj = $model['join'];
			$mapd=$model['direction'];
		}
		if (!empty($model['field'])) {
			$f = explode(',',$model['field']);
			foreach($f as $i) {
				$fields[]=$i;
			}
		}
        //获取模型信息
        $model = M('Model')->getByName($model["model"]);
        $model || $this->error('模型不存在！');
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];
        //读取模型数据列表
        $name = parse_name(get_table_name($model['id']), true);
		if (empty($mapj)) {
			$data = M($name)
			/* 查询指定字段，不指定则查询所有字段 */
			->field(true)
			// 查询条件
			->where($map)
			/* 默认通过id逆序排列 */
			->order($mapo)
			/* 数据分页 */
			->page($page, $row)
			/* 执行查询 */
			->select();
		
		} else {
			$data = M($name)
			->field($fields)
			->join($mapj,$mapd)
			->where($map)
			->order($mapo)
			->page($page, $row)
			->select();
		}
			/* 查询记录总数 */
        $count = M($name)->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
		
        $this->assign('model', $model);
        $this->assign('plist_data', $data);
        $this->meta_title = $model['title'].'列表';
	}
	
	public function getlists($model) {
		if (isset($model['join'])) {
			$join = $model['join'];
			$joinnum = isset($model['joinnum'])? $model['joinnum'] : 'INNER';
		} else {
			$join=$joinnum="";
		}
		if (isset($model['join1'])) {
			$join1 = $model['join1'];
			$joinnum1 = isset($model['joinnum1'])? $model['joinnum1'] : 'INNER';
		} else {
			$join1=$joinnum1="";
		}
		$m = substr($model['model'],0,1);
		$field = isset($model['field'])?$model['field']:true;
		$table = isset($model['table'])?$model['table']:"__".strtoupper($model['model'])."__ as ".strtolower($m)." ";
		$order = isset($model['order'])?$model['order']:" ".strtolower($m).".id DESC ";
		$mo = D($model['model']);
		$list = $mo->field($field)->table($table)
		->join($join,$joinnum)
		->join($join1,$joinnum1)
		->where($model['where'])
		->order($order)->limit($model['limit'])
		->page($model['page'])->select();
		$count = $mo->table($table)->join($join,$joinnum)
		->join($join1,$joinnum1)->where($model['where'])->count();
		$totalpage = intval(($count-1)/$model['limit']+1);	
		return array('list'=>$list,'total'=>$totalpage);
	}
	
	public function getlist($model) {
		if (isset($model['join'])) {
			$join = $model['join'];
			$joinnum = isset($model['joinnum'])? $model['joinnum'] : 'INNER';
		} else {
			$join=$joinnum="";
		}
		if (isset($model['join1'])) {
			$join1 = $model['join1'];
			$joinnum1 = isset($model['joinnum1'])? $model['joinnum1'] : 'INNER';
		} else {
			$join1=$joinnum1="";
		}
		$m = substr($model['model'],0,1);
		$field = isset($model['field'])?$model['field']:true;
		$table = isset($model['table'])?$model['table']:"__".strtoupper($model['model'])."__ as ".strtolower($m)." ";
		$order = isset($model['order'])?$model['order']:" ".strtolower($m).".id DESC ";
		$mo = D($model['model']);
		$list = $mo->field($field)->table($table)
		->join($join,$joinnum)
		->join($join1,$joinnum1)
		->where($model['where'])
		->order($order)->limit($model['limit'])
		->page($model['page'])->select();
		$count = $mo->table($table)->join($join,$joinnum)
		->join($join1,$joinnum1)->where($model['where'])->count();
		return array('list'=>$list,'count'=>$count);
	}
    
    
    public function showgame($model,$map){
        $data=M($model['model'],'tab_')
            ->field($model['field'])
            ->where($map)
            ->order($model['order'])
            ->join($model['join'],'left')
            ->join($model['join2'],'left')
            ->limit($model['limit'])
            ->group('tab_game.id')
            ->select();
        return $data;
    }
    public function downQrcode($url,$level=3,$size=4){
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $url = "http://".$_SERVER['HTTP_HOST'].base64_decode(base64_decode($url));
        $object = new \QRcode();
        ob_clean();
        echo $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    }
    
    public function appdownQrcode($url,$level=3,$size=4){
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $url = base64_decode(base64_decode($url));
        $object = new \QRcode();
        ob_clean();
        echo $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    }
    
    
    public function downapp()
    {
        if (get_device_type() == "ios") {
                $iosapp = M("App", "tab_");
                $map['app_version'] = 0;
                $data = $iosapp->where($map)->find();
                if(null==$data){
                    $this->error('暂无苹果app下载~');exit;
                }else{
                    $file = "https://" . $_SERVER['HTTP_HOST'] . "/Uploads/AppPlist/ios-app.plist";
                }
                    $file = "itms-services://?action=download-manifest&url=$file";
        } else {
                $iosapp = M("App", "tab_");
                $map['app_version'] = 1;
                $data = $iosapp->where($map)->find();
                if(null==$data){
                    $this->error('暂无安卓app下载~');exit;
                }else{
                    $file = "https://" . $_SERVER['HTTP_HOST'] . "/Uploads/App/".$data['file_name'];
                }
        }
        Header("Location:$file");//大文件下载
    }

    
    
    
}