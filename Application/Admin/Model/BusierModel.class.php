<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class BusierModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('busier_account','require','商务专员账户不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
    	array('busier_account', '5,50', '商务专员账户必须在5~50个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    	array('busier_account', 'Checkaccount', '账户已存在', 1, 'unique', 1), // 新增时候验证是否唯一
        array('password','require','密码不能为空',self::MUST_VALIDATE,'regex',self::MODEL_BOTH),
    	array('password', '6,50', '密码必须在6~50个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        array('real_name', 'require', '姓名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    	array('phone', 'require', '联系电话不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    	array('phone', '8,11', '联系电话必须在8~11个数字', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    	array('phone',             '/^[0-9]*$/',             '联系电话必须是数字',                 self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
    	array('qq',             '/^[0-9]*$/',             'QQ必须是数字',                 self::VALUE_VALIDATE,  'regex',  self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
    		array('create_time', 'getCreateTime', self::MODEL_BOTH,'callback'),
    		array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
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
 * 商务专员账户检测
 */
    protected function Checkaccount(){
    	$busier_account=   I('post.busier_account');
    	$busier = $this->where(array('busier_account'=>$busier_account))->find();
    	if(empty($busier)){
    		return true;
    	}else{
    		return  false;
    	}
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
}