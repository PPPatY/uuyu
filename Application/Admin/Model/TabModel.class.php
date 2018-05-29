<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 15:41
 */

namespace Admin\Model;

use Think\Model;

class TabModel extends Model {

	public function __construct($name = '', $tablePrefix = '', $connection = '') {
		/* 设置默认的表前缀 */
		$this->tablePrefix ='tab_';
		/* 执行构造方法 */
		parent::__construct($name, $tablePrefix, $connection);
	}
}