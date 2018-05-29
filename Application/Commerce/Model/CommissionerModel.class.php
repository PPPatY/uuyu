<?php
namespace Commerce\Model;
use Think\Model;

class CommissionerModel extends Model
{
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
     * [show_promote 查看该专员下所有的推广员]
     * @param  [string] $account [商务专员账号]
     * @return [array]          [所有的推广员]
     */
    public function show_promote($account){
        $promote_id = $this->show_promote_id($account);
        $promote_id = $this->guolv($promote_id);
        $promote_id = M('Promote','tab_')->field('distinct account')->where("id in (".$promote_id.") ")->select();
        return $promote_id;
    }
    /**
     * [show_game 查询所有的游戏]
     * @param  [str] $promote_id   [商务专员账号]
     * @return [array]             [游戏名称]
     */
    public function show_game($account){
        $promote_id = $this->show_promote_id($account);
        $promote_id = $this->guolv($promote_id);
        $game_name = M('Apply','tab_')->field('distinct game_name')->where("promote_id in (".$promote_id.") ")->select();
        return $game_name;
    }
    /**
     * [show_promote 查询所有的推广账号ID]
     * @param  [str] $account [商务专员账号]
     * @return [str]          [推广账号ID]
     */
    public function show_promote_id($account){
        if(!empty($account)){
            $map['account']=$account;
        }
        $promote_id = M('Commissioner','tab_')->field('promote_id')->where($map)->find();  
        $promote_id = $promote_id['promote_id'];
        if(empty($promote_id)){
                $promote_id= '0';
        }
        return $promote_id;
    }
    /**
     * [arr_to_str description]
     * @param  [arr] $arr [要转换的二维数组] 
     * @param  [str] $arr [数组的键名]
     * @return [type]      [返回以逗号分隔的字符串]
     */
    public function arr_to_str($arr,$field){
        foreach ($arr as $k => $v) {
            $str[$k] =$v[$field] ;
        }
        return implode(',',$str);
    }
    public function guolv($promote_id){
        if(substr(strrev($promote_id),0,1) == ','){
                $promote_id = strrev(substr(strrev($promote_id),1));
                }
                return $promote_id;
    }
    
}
