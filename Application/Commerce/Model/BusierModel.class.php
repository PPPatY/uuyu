<?php

namespace Commerce\Model;
use Think\Model;


class BusierModel extends Model{
	/* 自动验证规则 */
    protected $_validate = array(
        array('account', '6,16', '昵称长度为6-16个字符', self::EXISTS_VALIDATE, 'length'),
        array('account','','昵称被占用',0,'unique',1),
        
        array('sw_name', '1,16', '姓名长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
        /* 验证密码 */
        array('password','6,30', "密码长度不合法", self::EXISTS_VALIDATE, 'length'), //密码长度不合法
        array('phone','/^1[3|4|5|8][0-9]\d{4,8}$/','手机号码错误！','0','regex',1),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('create_time', 'getCreateTime', self::MODEL_INSERT,'callback'),
        array('inferiors', 0, self::MODEL_INSERT),
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

    public function sw_add($add_data=array()){
        $data = array(
            'account'     => $add_data['account'],
            'password'    => $add_data['password'],
            'sw_name'     => $add_data['sw_name'],
            'phone'    	  => $add_data['phone'],
            'qq'      	  => $add_data['qq'],
            'inferiors'   => $add_data['inferiors'],
            'status'      => $add_data['status'],
            'create_time' => NOW_TIME,
        );
        /* 添加用户 */
        $result = $this->create($data);
        if($result){
            $uid = $this->add();
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
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
}