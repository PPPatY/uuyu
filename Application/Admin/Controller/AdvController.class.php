<?php

namespace Admin\Controller;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdvController extends ThinkController {
    
    /**
    *媒体页面广告位
    */
    public function media_adv_pos_lists(){
        $model = M('Model')->getByName("AdvPos");
        $id = $model['id'];
        if(isset($_REQUEST['title'])){
            $map['title']=array('like','%'.$_REQUEST['title'].'%');
            unset($_REQUEST['title']);
        }
        $map['status']=1;
        $extend = array(
            'id'=>$id,
            'title'=>'媒体广告位管理',
            'map'=>$map,
            'tem_lists' => "media_adv_pos_lists",
        );
        $BaseAdv = A("AdvPos","Event");
        $this->meta_title = '媒体广告管理';
        $BaseAdv->BaseAdv("media",$extend);
    }

    public function media_adv_lists(){
        $pos_id = M('adv_pos','tab_')->field('id')->where(['module'=>'media'])->select();
        $pos_id = array_column($pos_id,'id');
        $map['pos_id'] = ['in',$pos_id];
        $this->adv_lists($map);
    }

    /**
    *APP广告位
    */
    public function app_adv_pos_lists(){
        $model = M('Model')->getByName("AdvPos");
        $id = $model['id'];
        if(isset($_REQUEST['title'])){
            $map['title']=array('like','%'.$_REQUEST['title'].'%');
            unset($_REQUEST['title']);
        }
        $map['status'] = 1;
        $extend = array(
            'id'=>$id,
            'title'=>'APP广告位管理',
            'map'=>$map,
            'tem_lists' => "app_adv_pos_lists",
        );
        $BaseAdv = A("AdvPos","Event");
        $this->meta_title = 'APP广告管理';
        $BaseAdv->BaseAdv("app",$extend);
    }

    public function app_adv_lists(){
        $pos_id = M('adv_pos','tab_')->field('id')->where(['module'=>'app'])->select();
        $pos_id = array_column($pos_id,'id');
        $map['pos_id'] = ['in',$pos_id];
        $this->adv_lists($map);
    }

    /**
     *渠道广告位
     */
    public function promote_adv_pos_lists(){
        $model = M('Model')->getByName("AdvPos");
        $id = $model['id'];
        if(isset($_REQUEST['title'])){
            $map['title']=array('like','%'.$_REQUEST['title'].'%');
            unset($_REQUEST['title']);
        }
        $map['status'] = 1;
        $extend = array(
            'id'=>$id,
            'title'=>'渠道广告位管理',
            'map'=>$map,
            'tem_lists' => "promote_adv_pos_lists",
        );
        $BaseAdv = A("AdvPos","Event");
        $this->meta_title = '渠道广告管理';
        $BaseAdv->BaseAdv("promote",$extend);
    }

    public function promote_adv_lists(){
        $pos_id = M('adv_pos','tab_')->field('id')->where(['module'=>'promote'])->select();
        $pos_id = array_column($pos_id,'id');
        $map['pos_id'] = ['in',$pos_id];
        $this->adv_lists($map);
    }

    public function wap_adv_pos_lists(){
        $model = M('Model')->getByName("AdvPos");
        $id = $model['id'];
        
        if(isset($_REQUEST['title'])){
            $map['title']=array('like','%'.$_REQUEST['title'].'%');
            unset($_REQUEST['title']);
        }
        $extend = array(
            'id'=>$id,
            'title'=>'WAP广告位管理',
            'map'=>$map,
            'tem_lists' => "wap_adv_pos_lists",
        );
        $BaseAdv = A("AdvPos","Event");
        $this->meta_title = 'WAP广告管理';
        $BaseAdv->BaseAdv("wap",$extend);
    }

    public function wap_adv_lists(){
        $pos_id = M('adv_pos','tab_')->field('id')->where(['module'=>'wap'])->select();
        $pos_id = array_column($pos_id,'id');
        $map['pos_id'] = ['in',$pos_id];
        $this->adv_lists($map);
    }

    /**
    *编辑广告位
    */
    protected function baes_edit($model="",$id=0,$page_url=""){
        //获取模型信息
        $model = D('Model')->find($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
//            $Model  = $this->checkAttr($Model,$model['id']);
            if($Model->create() && ($Model->save()!==false)){
                $this->success('保存'.$model['title'].'成功！', U($page_url));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $id=$_REQUEST['id'];
            $fields     = get_model_attribute($model['id']);
            //获取数据
            $data       = D(get_table_name($model['id']))->find($id);
            $data || $this->error('数据不存在！');
            if ($data['pos_id']) {      
                $sqlmap['status'] = 1;          
                $sqlmap['id'] = $data['pos_id'];          
                $advpos = M("AdvPos","tab_")->field(true)
                        ->where($sqlmap)
                        ->find();
                $this->assign('advpos',$advpos);
            }
            $this->assign('page_url',$page_url);
            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->meta_title = '编辑'.$model['title'];
            $this->display();
        }
    }
    
    // 添加广告位
    protected function baes_add($model="",$page_url=""){
        //获取模型信息
        $model = D('Model')->find($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
            $Model  = $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->add()){
                $this->success('保存'.$model['title'].'成功！', U($page_url));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $this->assign('model', $model);
            $this->meta_title = '新增'.$model['title'];
            $this->display($model['template_edit']?$model['template_edit']:'');
        }
    }

    /**
    *删除广告
    */
    public function base_del($model = null, $ids=null){
        $model = M('Model')->find($model);
        $model || $this->error('模型不存在！');

        $ids = array_unique((array)I('ids',0));

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }

        $Model = D(get_table_name($model['id']));
        $map = array('id' => array('in', $ids) );
        if($Model->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
    *添加广告
    */
    protected function base_add_adv($model = null,$page_url=""){
        $model = "";
        //获取模型信息
        if(IS_POST){
            $posdata=M('adv_pos','tab_')
                    ->field('id,type')
                    ->where(array('id'=>$_REQUEST['pos_id']))
                    ->find();
            $Model = D('Adv');
            $slidata=$Model->where(array('pos_id'=>$_REQUEST['pos_id']))->find();
            if($posdata['type']==1&&$slidata){
                $this->error('单图类型只允许加一次');
            }
            // 获取模型的字段信息
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->add()){
                $this->success('添加'.$model['title'].'成功！', U("$page_url",array('model'=>$model['name'])));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $sqlmap['status'] = 1;
            $sqlmap['id'] = $_REQUEST['pos_id'];
            $advpos = M("AdvPos","tab_")->field(true)
                    ->where($sqlmap)->find();
            $this->assign('advpos',$advpos);
            $fields = get_model_attribute($model['id']);
            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->meta_title = '新增广告';
            $this->display();
        }
    }

    /**
     *编辑媒体广告位
     */
    public function edit_media_adv_pos($model='',$id=0){
        $this->baes_edit($model,$id,"media_adv_pos_lists");
    }

    // 添加媒体广告位
    public function add_media_adv_pos($model=''){
        $this->baes_add($model,"media_adv_pos_lists");
    }

    /**
     *编辑媒体广告位
     */
    public function edit_promote_adv_pos($model='',$id=0){
        $this->baes_edit($model,$id,"promote_adv_pos_lists");
    }

    // 添加媒体广告位
    public function add_promote_adv_pos($model=''){
        $this->baes_add($model,"promote_adv_pos_lists");
    }

    /**
    *编辑APP广告位
    */
    public function edit_app_adv_pos($model='',$id=0){
        $this->baes_edit($model,$id,"app_adv_pos_lists");
    }
    
    // 添加APP广告位
    public function add_app_adv_pos($model=''){
        $this->baes_add($model,"app_adv_pos_lists");
    }

    
    // 编辑WAP广告位
    
    public function edit_wap_adv_pos($model='',$id=0){
        $this->baes_edit($model,$id,"wap_adv_pos_lists");
    }
    
    // 添加WAP广告位
    
    public function add_wap_adv_pos($model=''){
        $this->baes_add($model,"wap_adv_pos_lists");
    }
    
    
    /**
    *广告列表
    */
    public function adv_lists($map=""){
        parent::lists("adv",$_GET["p"],$map);
    }

    /**
    *删除媒体广告
    */
    public function del_adv($model="",$ids=0){
        $model = M('Model')->getByName("adv");
        $this->base_del($model['id'],$ids);
    }

    /**
    *编辑广告
    */
    public function media_edit_adv($model="",$id=0){
        $this->base_edit_adv($model,$id,"media_adv_lists");
    }

    /**
    *编辑广告
    */
    public function app_edit_adv($model="",$id=0){
        $this->base_edit_adv($model,$id,"app_adv_lists");
    }

    /**
    *编辑广告
    */
    public function wap_edit_adv($model="",$id=0){
        $this->base_edit_adv($model,$id,"wap_adv_lists");
    }

    /**
     *编辑广告
     */
    public function promote_edit_adv($model="",$id=0){
        $this->base_edit_adv($model,$id,"promote_adv_lists");
    }

    /**
    *添加媒体广告
    */
    public function add_media_adv($model=""){
        $this->base_add_adv($model,"media_adv_lists");
    }

    /**
     *添加渠道广告
     */
    public function add_promote_adv($model=""){
        $this->base_add_adv($model,"promote_adv_lists");
    }

    /**
    *添加APP广告
    */
    public function add_app_adv($model=""){
        $this->base_add_adv($model,"app_adv_lists");
    }
    
    /**
    *添加WAP广告
    */
    public function add_wap_adv(){
        $this->base_add_adv(15,"wap_adv_lists");
    }

    /**
     *编辑广告
     */
    protected function base_edit_adv($model="",$id=0,$page_url=""){
        //获取模型信息
        $model = D('Model')->find($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
//            $Model  = $this->checkAttr($Model,$model['id']);
             if($Model->create()){
                $res = $Model->create();
                $res['start_time'] = strtotime($res['start_time']);
                $res['end_time'] = strtotime($res['end_time']);
                $res['create_time'] = time();
                $Model->save($res);
                $this->success('保存'.$model['title'].'成功！', U($page_url));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $id=$_REQUEST['id'];
            $fields     = get_model_attribute($model['id']);
            //获取数据
            $data       = D(get_table_name($model['id']))->find($id);
            $data || $this->error('数据不存在！');
            $adv_pos = M('adv_pos','tab_')->find($data['pos_id']);
            $this->assign('advpos',$adv_pos);
            $this->assign('page_url',$page_url);
            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->meta_title = '编辑'.$model['title'];
            $this->display('edit_adv');
        }
    }
}
