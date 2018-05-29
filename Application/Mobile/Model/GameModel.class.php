<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Mobile\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class GameModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('game_name',  'require', '游戏名称不能为空',         self::MUST_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('game_name',  '1,30',    '游戏名称不能超过30个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        array('game_appid', 'require', '游戏APPID不能为空',        self::MUST_VALIDATE,  'regex',  self::MODEL_BOTH),

        array('discount',         '/^(?!0+(?:\.0+)?$)\d+(?:\.\d{1,2})?$/',           '游戏折扣输入不正确',             self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('game_coin_ration', '/^[0-9]*$/',             '游戏比例必须是数字',             self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('sort',             '/^[0-9]*$/',             '排序必须是数字',                 self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('game_score',       '/^(\d(\.\d)?|10)$/',     '游戏评分输入格式不正确',         self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('recommend_level',  '/^(\d(\.\d)?|10)$/',     '推荐指数输入格式不正确',         self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
         array('discount','0,4', '折扣输入错误',  self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        array('discount',         '/^[1-9](\.\d+)?$/',           '代充折扣错误',             self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),

    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time',       'getCreateTime',         self::MODEL_INSERT,  'callback'),
        array('recommend_level',   0,                       self::MODEL_INSERT),
        array('game_score',        0,                       self::MODEL_INSERT),
        array('discount',          0,                       self::MODEL_INSERT),
    );

    /**
     * 构造函数
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        /* 设置默认的表前缀 */
        $this->tablePrefix ='tab_';
        /* 执行构造方法 */
        parent::__construct($name, $tablePrefix, $connection);
    }

    /**
     * 获取详情页数据
     * @param  integer $id 文档ID
     * @return array       详细数据
     */
    public function detail($id){
        /* 获取基础数据 */
        $info = $this->field(true)->find($id);
        if(!(is_array($info) || 1 !== $info['status'])){
            $this->error = '游戏被禁用或已删除！';
            return false;
        }
        return $info;
    }

    /**
     * 新增或更新一个游戏
     * @param array  $data 手动传入的数据
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author 王贺 
     */
    public function update($data = null){
        /* 获取数据对象 */
        $data = $this->token(false)->create($data);
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add(); //添加基础内容
            if(!$id){
                $this->error = '新增基础内容出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新基础内容出错！';
                return false;
            }
        }
         // 添加或新增扩展内容
        $logic = $this->logic('Set');
        $logic->checkModelAttr(5);
        if(!$logic->update($id)){
            if(isset($id)){ //新增失败，删除基础数据
                $this->delete($id);
            }
            $this->error = $logic->getError();
            return false;
        }
        return $data;
    }
    

    /**
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }

    /**
     * 获取扩展模型对象
     * @param  integer $model 模型编号
     * @return object         模型对象
     */
    private function logic($model){
        $name  = $model;//parse_name(get_document_model($model, 'name'), 1);
        $class = is_file(MODULE_PATH . 'Logic/' . $name . 'Logic' . EXT) ? $name : 'Base';
        $class = MODULE_NAME . '\\Logic\\' . $class . 'Logic';
        return new $class($name);
    }

    /**
     * 生成不重复的name标识
     * @author huajie <banhuajie@163.com>
     */
    private function generateName(){
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';	//源字符串
        $min = 10;
        $max = 39;
        $name = false;
        while (true){
            $length = rand($min, $max);	//生成的标识长度
            $name = substr(str_shuffle(substr($str,0,26)), 0, 1);	//第一个字母
            $name .= substr(str_shuffle($str), 0, $length);
            //检查是否已存在
            $res = $this->getFieldByName($name, 'id');
            if(!$res){
                break;
            }
        }
        return $name;
    }

    private function game_list($category, $order = '`id` DESC', $status = 1, $field = true){

    }

    /**
    *游戏列表
    */
    public function game_list_limt($category, $order = '`id` DESC', $status = 1, $field = true,$limit=10){
        $map = $this->listMap($category, $status);
        return $this->field($field)->where($map)->order($order)->limit($limit)->select();
    }

    /**
    *推荐游戏信息
    */
    public function game_recommend_limt($recommend_status, $order = '`id` DESC', $status = 1, $field = true,$limit=10){
        $map['tab_game.game_status'] = $status;
        $map['tab_game.recommend_status'] = $recommend_status;
        $return=$this
            ->field('tab_game.*,tab_giftbag.game_id')
            ->where($map)
            ->order($order)
            ->limit($limit)
            ->join('tab_giftbag on tab_game.id = tab_giftbag.game_id','left')
            ->group('tab_game.id')
            ->select();
        return $return;
    }

    /**
     * 设置where查询条件
     * @param  number  $category 游戏分类ID
     * @param  number  $pos      推荐位
     * @param  integer $status   状态
     * @return array             查询条件
     */
    private function listMap($category, $status = 1){
        /* 设置状态 */
        $map = array('game_status' => $status);

        /* 设置分类 */
        if(!is_null($category)){
            if(is_numeric($category)){
                $map['game_type_id'] = $category;
            } else {
                $map['game_type_id'] = array('in', str2arr($category));
            }
        }
        return $map;
    }

    /**
     * 分类统计游戏数量
     * @author 卜昭鹤
     */
    public function game_type_count(){
        $data=$this->table('tab_game_type a')
                   ->field('a.icon,a.id,a.type_name,if(count(b.id),count(b.id),0) as game_count')
                   ->join('join tab_game b ON a.id = b.game_type_id')
                   ->where(array('b.game_status'=>1,'a.status'=>1,'a.status_show'=>1))
                   ->group('b.game_type_id')
                   ->select();
        return $data;
    }

    public function game_count()
    {
        return D('game')->where(array('game_status'=>1))->count();
    }
    public function game_select_list($field='true',$map=array(),$order='sort desc',$limit='')
    {
        $data = D('game')
                ->field($field)
                ->where($map)
                ->order($order)
                ->limit($limit)
                ->select();
        return $data;
    }
}