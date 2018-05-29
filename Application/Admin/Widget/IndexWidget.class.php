<?php
namespace Admin\Widget;
use Think\Controller;

class IndexWidget extends Controller{
	
	public function navigation($value='',$compare='')
	{
		$mainID   = M('Menu')->where("pid !=0 AND url like '%".$value."%'")->getField('id');
		$MainMenu = M('Menu')->field("id,pid,title,url")->where("pid = ".$mainID)->order("sort asc")->select(); 
		$this->assign("data",$MainMenu);
		$compare = empty($compare)?CONTROLLER_NAME."/".ACTION_NAME:$compare;
		$this->assign('currentUrl',$compare);
		$this->display('Widget:navigation');
	}

	public function CateGroupTree($cate_id=0){
		$map['sys_category.id'] = $cate_id;
		$map['tab.status'] = 1;
		$list = D('category')
				->field("tab.id,tab.title,tab.pid,tab.sort")
				->join("sys_category AS tab ON sys_category.pid = tab.pid")
				->where($map)
				->order('tab.sort asc')
				->select();
		foreach ($list as $key => $value) {
			if($cate_id == $value['id']){
				$title = $value['title'];
				define('TABTITLE',$value['title']);
				break;
			}
		}

		$this->assign('cateID',$_REQUEST['cate_id']);
		$this->assign('title',$title);
        $this->assign('tree', $list);
        $this->display('Widget:CateGroupTree');
	}
}