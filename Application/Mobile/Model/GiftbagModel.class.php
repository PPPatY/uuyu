<?php
namespace Mobile\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class GiftbagModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('giftbag_name', 'require', '礼包名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('giftbag_name', '1,30', '礼包名称不能超过30个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        array('giftbag_type', 'require', '请选择礼包类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('start_time', 'require', '开始时间不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('end_time', 'require', '结束时间不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', 'getCreateTime', self::MODEL_BOTH,'callback'),
        array('area_num', 0, self::MODEL_BOTH),
        array('start_time', 'strtotime', self::MODEL_BOTH, 'function'),
        array('end_time', 'strtotime', self::MODEL_BOTH, 'function'),
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
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
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

    /**
    *游戏列表
    */
    public function gift_list_limt($category, $order = '`id` DESC', $status = 1, $field = true,$limit=10){
        $map = $this->listMap($category, $status);
        return $this->field('tab_giftbag.*,tab_game.icon,tab_game.cover')
                    ->join("LEFT JOIN tab_game ON tab_giftbag.game_id = tab_game.id")
                    ->where($map)
                    ->order($order)
                    ->limit($limit)
                    ->select();
    }

    /**
    *推荐游戏信息
    */
    public function gift_recommend_limt($recommend_status, $order = '`id` DESC', $status = 1, $field = true,$limit=10){
        $map['status'] = $status;
        $map['tab_giftbag.recommend_status'] = $recommend_status;
        return $this->field('tab_giftbag.*,tab_game.icon,tab_game.cover')
                    ->join("LEFT JOIN tab_game ON tab_giftbag.game_id = tab_game.id")
                    ->where($map)
                    ->order($order)
                    ->limit($limit)
                    ->select();
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
        $map = array('status' => $status);

        /* 设置分类 */
        if(!is_null($category)){
            if(is_numeric($category)){
                $map['giftbag_type'] = $category;
            } else {
                $map['giftbag_type'] = array('in', str2arr($category));
            }
        }
        return $map;
    }


    /**
     * 开服开测信息
     * @author 卜昭鹤
     */
    public function open_service()
    {
        $tomorrow=mktime(0,0,0,date('m'),date('d')+1,date('Y'));
        $where=array(
            'a.show_status'=>1,
            'b.game_status'=>1,
            'a.start_time'=>array(array('gt',$tomorrow),array('lt',$tomorrow+86400*7)),
            );
        //未来一周开服情况
        $data['week']=$this->table('tab_server a')
                           ->field('a.server_name,FROM_UNIXTIME(a.start_time,"%m月%d日 %H:%i") strat_time,b.icon,b.id as game_id,b.game_name,b.game_type_name')
                           ->where($where)
                           ->join('LEFT JOIN tab_game b ON b.id = a.game_id')
                           ->order('start_time')
                           ->select();  
        //当日开服情况                    
        $where['a.start_time']=array(array('gt',strtotime(date('Y-m-d',time()))),array('lt',strtotime(date('Y-m-d',time()))+86400)); 
        $data['today']=$this->table('tab_server a')
                            ->field('a.server_name,FROM_UNIXTIME(a.start_time,"%m月%d日 %H:%i") strat_time,b.icon,b.id as game_id,b.game_name,b.game_type_name,a.server_status')
                            ->where($where)
                            ->join('tab_game b ON b.id = a.game_id') 
                            ->order('start_time')
                            ->select();
        return $data;           
    }

    public function gift_select_list($field='true',$m=array(),$order='sort desc',$limit='')
    {
        $data=M('Giftbag','tab_')
                ->field("tab_giftbag.*,max(tab_giftbag.create_time) as new_gift,tab_game.icon,tab_game.features,count(tab_giftbag.id) as gift_num")
                ->join("left join tab_game on tab_giftbag.game_id = tab_game.id")
                ->where($m['map'])
                ->where('tab_game.game_status=1')
                ->group($m['group'])
                ->limit($limit)
                ->order($order)
                ->select();
        return $data;
    }
}