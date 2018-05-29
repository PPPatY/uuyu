<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
use OSS\OssClient;
use OSS\Core\OSsException;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ApplyUnionController extends ThinkController {
    const model_name = 'applyunion';

    public function lists(){
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_id']=get_game_id($_REQUEST['game_name']);
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['promote_id'])){
            if($_REQUEST['promote_id']=='全部'){
                unset($_REQUEST['promote_id']);
            }else if($_REQUEST['promote_id']=='自然注册'){
                $map['union_id']=array("elt",0);
                unset($_REQUEST['promote_id']);
            }else{
                $map['union_id']=$_REQUEST['promote_id'];
                unset($_REQUEST['promote_id']);
            }
        }
        if(isset($_REQUEST['apply_domain_type'])){
            if($_REQUEST['apply_domain_type']>0){
                $map['apply_domain_type']=array('gt',0);
            }else{
                $map['apply_domain_type']=0;
            }
            unset($_REQUEST['apply_domain_type']);
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

    public function set_status($model='apply_union'){
        parent::set_status($model);
    }

    public function del($model = null, $ids=null){
        $source = D('apply_union');
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

}
