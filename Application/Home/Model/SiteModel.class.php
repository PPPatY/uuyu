<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * 分类模型
 */
class SiteModel extends Model{

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
     * 添加/保存数据
     * @return bool
     */
    public function saveData($data=""){
        $data = empty($data) ? I('post.') : $data;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        if(!empty($data['id'])){
            $map['id'] = $data['id'];
            $map['promote_id'] = PID;
            $res = $this->where($map)->save($data);
        }else{
            $res = $this->add($data);
        }
        if ($res !== false){
            return true;
        }else{
            return false;
        }
    }
}
