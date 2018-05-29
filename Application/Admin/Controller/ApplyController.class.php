<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
use OSS\OssClient;
use OSS\Core\OSsException;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ApplyController extends ThinkController {
    const model_name = 'Apply';

    public function lists(){
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_id']=get_game_id($_REQUEST['game_name']);
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['promote_name'])){
            if($_REQUEST['promote_name']=='全部'){
                unset($_REQUEST['promote_name']);
            }else if($_REQUEST['promote_name']=='自然注册'){
                $map['promote_id']=array("elt",0);
                unset($_REQUEST['promote_name']);
            }else{
                $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
                unset($_REQUEST['promote_name']);
            }
        }
        if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
            $map['apply_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);
        }
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['apply_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        $PROMOTE_URL_AUTO_AUDIT = C('PROMOTE_URL_AUTO_AUDIT');
        $this->assign('PROMOTE_URL_AUTO_AUDIT',$PROMOTE_URL_AUTO_AUDIT);
        parent::lists(self::model_name,$_GET["p"],$map);
    }

    public function edit($id=null){
        $id || $this->error('请选择要编辑的用户！');
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::edit($model['id'],$id);
    }

    public function set_status($model='Apply'){
        parent::set_status($model);
    }

    public function del($model = null, $ids=null){
        $source = D(self::model_name);
        $id = array_unique((array)$ids);
        $map = array('id' => array('in', $id) );
        $list = $source->where($map)->select();
        foreach ($list as $key => $value) {
            $file_url = APP_ROOT.$value['pack_url'];
            unlink($file_url);
        }
        $model = M('Model')->getByName(self::model_name); /*通过Model名称获取Model完整信息*/
        parent::del($model["id"],$ids,"tab_");
    }
    /**
    *修改申请信息
    */
    public function updateinfo($id,$pack_url,$promote){
        $model = M('Apply',"tab_");
        $data['id'] = $id;
        $data['pack_url'] = $pack_url;
        $data['dow_url']  = '/index.php?s=/Home/Down/down_file/game_id/'.$promote['game_id'].'/promote_id/'.$promote['promote_id'];
        $data['dispose_id'] = UID;
        $data['dispose_time'] = NOW_TIME;
        $res = $model->save($data);
        return $res;
    }

    public function game_source($game_id,$type){
        $model = D('Source');
        $map['game_id'] = $game_id;
        $map['type'] = $type;
        $data = $model->where($map)->find();
        return $data;
    }             
    public function change_auto_audit(){
        if($_REQUEST['value']==1){
            $value = 0;
        }else{
            $value = 1;
        }
        $config['value'] = $value;
        $res = M('config')->where(array('name'=>'PROMOTE_URL_AUTO_AUDIT'))->save($config);
        S('DB_CONFIG_DATA',null);
        $this->ajaxReturn(array('status'=>1));
    }
    
    /*
     * APP分包
     *   */
    public function app_lists($p=0)
    {
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $row = intval(C('LIST_ROWS')) ? :10;
        "" == I('status') || $map['status'] = I('status');
        "" == I('app_version') || $map['app_version'] = I('app_version');
        "" == I('promote_id') || $map['promote_id'] = I('promote_id');
        "" == I('enable_status') || $map['enable_status'] = I('enable_status');
    
        $data = M('app_apply','tab_')->where($map)->order('apply_time desc')->page($page,$row)->select();
        $count = M('app_apply','tab_')->where($map)->count();
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('_page', $page->show());
        }
    
        $this->assign('list_data',$data);
        $this->meta_title = 'APP分包';
        $this->display();
    }
    

    //APP分包审核
    public function app_audit($ids){
        $map['id'] = ['in',$ids];
        $data['status'] = 1;
        $data['dispose_id'] = UID;
        $data['dispose_time'] = time();
        $res = M('app_apply','tab_')->where($map)->setField($data);
        if($res !== false) {
            $this->success('审核成功');
        }else{
            $this->error('审核失败');
        }
    }
    
    /*
     * APP分包打包
     *   */
    public function app_package($ids=null)
    {
        if(!is_array($ids)){
            $ids = [$ids];
        }
    
        try{
            $ids || $this->error("打包数据不存在",U('Apply/app_lists'),1);
            static $a=0;//无数据或没审核
            static $b=0;//原包不存在
            static $c=0;//成功
            static $d=0;//操作失败
            static $e=0;//分包失败
            foreach ($ids as $key => $value) {
                $apply_data = M('app_apply','tab_')->find($value);
                //验证数据正确性
                if (empty($apply_data) || $apply_data["status"] != 1) {
                    $a++;
                    continue;
                }
                #获取原包数据
                $source_file = M('app','tab_')->find($apply_data['app_id']);
                //验证原包是否存在
                if (empty($source_file) || !file_exists($source_file['file_url'])) {
                    $b++;
                    continue;
                }
                if($apply_data['app_version']==1){
                    $app_type=".apk";
                    $url_ver="META-INF/mch.properties";
                }else{
                    $app_type=".ipa";
                    $url_ver="Payload/YiNiu.app/_CodeSignature/mch.txt";
                }
                $newname = "app_package" . $apply_data["app_id"] . "-" . $apply_data['promote_id'] . $app_type;
                $to = "./Uploads/GamePack/" . $newname;
                copy($source_file['file_url'], $to);
                #打包新路径
                $zip = new \ZipArchive;
                $res = $zip->open($to, \ZipArchive::CREATE);
                if ($res == TRUE) {
                    #打包数据
                    $pack_data = array(
                    "promote_id" => $apply_data['promote_id'],
                    "promote_account" => get_promote_name($apply_data["promote_id"]),
                    );
                    $zip->addFromString($url_ver, json_encode($pack_data));
                    $zip->close();
                    if (get_tool_status("oss_storage") == 1) {
                        $to = "http://" . C("oss_storage.bucket") . "." . C("oss_storage.domain") . "/GamePack/" . $newname;
                        $to = str_replace('-internal', '', $to);
                        $new_to = "./Uploads/GamePack/" . $newname;
                        $updata['savename'] = $newname;
                        $updata['path'] = $new_to;
    
                        $this->upload_game_pak_oss($updata);
                        @unlink($new_to);
                    }elseif(get_tool_status("qiniu_storage")==1){
                        $this->dleteQiNiuFile($newname);
                        $url = $this->upQiNiuFile($newname,$to);
                        @unlink ($to);
                        $to = "http://".$url;
                    }elseif(get_tool_status("cos_storage")==1){
                        $cos=A('Cos');
                        $cos->cosupload("","/App/".$newname,2);
                        $cos_res=$cos->cosupload($to,"/App/".$newname);
                        if(strlen($cos_res)>10){
                            @unlink ($to);
                            $to=$cos_res;
                             
                        }else{
                            $this->error("Cos参数错误");
                        }
                    }
    
                    if($apply_data['app_version']==0){
                        $plist_url = A('Plist')->create_plist_app('1',$apply_data['promote_id'],'app',$to);
                        $apply_data['plist_url'] = $plist_url;
                    }
                    $apply_data['dow_url'] = $to;
                    $apply_data['enable_status'] = 1;
                    $res = M('app_apply','tab_')->save($apply_data);
                    if ($res !== false) {
                        $c++;
                    } else {
                        $d++;
                    }
                } else {
                    $e++;
                }
            }
            $f=$a+$b+$d+$e;
    
            $this->success('成功'.$c.',失败'.$f,U('app_lists'));
    
        }
        catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }
    /*
     * 删除APP分包申请
     *   */
    public function app_del($ids=null){
        $map['id'] = ['in',$ids];
        $res = M('app_apply','tab_')->where($map)->delete();
        if($res !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
