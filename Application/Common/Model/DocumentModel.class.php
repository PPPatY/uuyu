<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/30
 * Time: 13:56
 */

namespace Common\Model;

use Think\Model;

class DocumentModel extends Model {

    /**
     * app首页活动按照 进行中、即将开始、已结束  1  -1  0 给app返回
     */
    public function getArticleListsByCategory2($game_id='',$name,$p=1,$row=5,$type=1){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        if(is_array($name)){
            $category = M("category")->where(array('name'=>array('in',$name)))->select();
            $map['category_id'] = array('in',array_column($category,'id'));
        }else{
            $category = M("category")->where(['name'=>$name])->find();
            $map['category_id'] = $category['id'];
        }
        $map['d.display'] = 1;
        $map['d.status'] = 1;
        if($game_id){
            $map['belong_game'] = $game_id;
        }
        $time = NOW_TIME;
        $data = $this->alias('d')->field("d.id,c.name as type,d.category_id,d.title,d.description,d.belong_game,d.update_time as line_time,d.create_time as start_time,d.deadline as end_time,d.cover_id as cover")
            ->where($map)
            ->join('sys_category as c on d.category_id = c.id')
            ->order("d.level desc,d.id desc")
//            ->page($page,$row)
            ->select();
        foreach ($data as $key => $val) {
            if(!$game_id){
                $data[$key]['cover'] = icon_url($val['cover']);
            }else{
                unset($data[$key]['cover']);
            }
            $data[$key]['start_time'] = set_show_time($data[$key]['start_time'],'time','forever');
            $data[$key]['url'] = $this->generate_url($val['id'],$type);
            $data[$key]['belong_game'] = get_game_name($data[$key]['belong_game']);
            if($val['end_time']==0){
                $data[$key]['end_time'] == null;
                $data[$key]['article_status'] = $val['start_time'] > time()?-1:1;
            }else{
                $data[$key]['article_status'] = $val['end_time'] <time()?0:($val['start_time'] >time()?-1:1);
            }
            $data[$key]['end_time'] = set_show_time($data[$key]['end_time'],'data','forever');
        }
        foreach ($data as $key => $value){
            if ($data[$key]['article_status'] == 1){
                $ar1[] = $data[$key];
            }elseif($data[$key]['article_status'] == -1){
                $ar2[] = $data[$key];
            }else{
                $ar3[] = $data[$key];
            }
        }
        if (!empty($ar1) && !empty($ar2) && !empty($ar3)){
            $return = array_merge($ar1,$ar2,$ar3);
        }elseif (!empty($ar1) && !empty($ar2)){
            $return = array_merge($ar1,$ar2);
        }elseif (!empty($ar2) && !empty($ar3)){
            $return = array_merge($ar2,$ar3);
        }elseif (!empty($ar1) && !empty($ar3)){
            $return = array_merge($ar1,$ar3);
        }else{
            $return = !empty($ar1)?$ar1:(!empty($ar2)?$ar2:$ar3);
        }

        $new_data = array_slice($return, ($page-1)*$row, $row);

        if(empty($new_data)){
            return false;
        }else{
            return $new_data;
        }
    }

	public function getArticleListsByCategory($game_id='',$name,$p=1,$row=5,$type=1){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		if(is_array($name)){
			$category = M("category")->where(array('name'=>array('in',$name)))->select();
			$map['category_id'] = array('in',array_column($category,'id'));
		}else{
			$category = M("category")->where(['name'=>$name])->find();
			$map['category_id'] = $category['id'];
		}
		$map['d.display'] = 1;
		$map['d.status'] = 1;
		if($game_id){
			$map['d.belong_game'] = $game_id;
		}
		$time = NOW_TIME;
		$data = $this->alias('d')->field("d.id,c.name as type,d.category_id,d.title,d.description,d.belong_game,d.update_time as line_time,d.create_time as start_time,d.deadline as end_time,d.cover_id as cover")
			->where($map)
			->join('sys_category as c on d.category_id = c.id')
			->order("d.level desc,d.id desc")
			->page($page,$row)->select();
		// if(strtolower(CONTROLLER_NAME)!='index'){
			foreach ($data as $key => $val) {
				$data[$key]['cover'] = icon_url($val['cover']);
				$data[$key]['start_time'] = set_show_time($data[$key]['start_time'],'time','forever');
				$data[$key]['url'] = $this->generate_url($val['id'],$type);
				$data[$key]['belong_game'] = get_game_name($data[$key]['belong_game']);
				if($val['end_time']==0){
					$data[$key]['end_time'] == null;
					$data[$key]['article_status'] = $val['start_time'] > time()?-1:1;
				}else{
					$data[$key]['article_status'] = $val['end_time'] <time()?0:($val['start_time'] >time()?-1:1);
				}
				$data[$key]['end_time'] = set_show_time($data[$key]['end_time'],'data','forever');
			}
		// }
		if(empty($data)){
			return false;
		}else{
			return $data;
		}
	}
	public function hdmarkrec($user_id,$hidden='hidden_hd'){
		$data = M('User','tab_')
				->field('hidden_option')
				->find($user_id);
		$arr = json_decode($data['hidden_option'],true);
		$arr[$hidden]['status'] = 1;
		$arr[$hidden]['time'] = time();
		$json['hidden_option'] = json_encode($arr);
		$res = M('User','tab_')
				->where(array('id'=>$user_id))
				->save($json);
		return $res;
	}
	/**
	 * 生成文章链接
	 * @param $id
	 * @return string
	 * author: xmy 280564871@qq.com
	 */
	public function generate_url($id,$type=1){
	    if ($type == 1){
            return "http://".$_SERVER['HTTP_HOST'].U("Article/detail",['id'=>$id]);
        }else{
            return "http://".$_SERVER['HTTP_HOST']."/mobile.php/Article/detail/id/".$id;
        }
	}
	public function articleDetail($id){
		$map['sys_document.id'] = $id;
		$map['display'] = 1;
		$map['status'] = 1;
		$time = NOW_TIME;
		$data = $this
			->join('sys_document_article on sys_document.id=sys_document_article.id')
			->where($map)
			->find();
		if(empty($data)){
			return false;
		}else{
			return $data;
		}
	}
	public function searchArticle($name,$map,$limit = 4,$model=''){
		if(is_array($name)){
			$category = M("category")->field('id,name')->where(array('name'=>$name))->select();
			$map['d.category_id'] = array('in',array_column($category,'id'));
		}else{
			$category = M("category")->field('id,name')->where(['name'=>$name])->find();
			$map['d.category_id'] = $category['id'];
		}
		$map['d.display'] = 1;
		$map['d.status'] = 1;
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$map['d.create_time']=array('lt',time());
		$map['_string'] = "d.deadline < ".time()." or d.deadline = 0";
		$data = $this->table("sys_document as d")
			->field("d.id,d.title,a.content,d.cover_id,d.description,d.belong_game,d.category_id as hdtype")
			->join("sys_document_article a on a.id = d.id")
			->join('tab_game as g on d.belong_game = g.id')
			->where($map)
			->order("d.level desc,d.id desc,d.belong_game")
			->limit($limit)
			->select();
		foreach ($category as $key => $value) {
			foreach ($data as $k => $v) {
				if($value['id'] ==$v['hdtype'] ){
					if($value['name']=='wap_gg'||$value['name']=='app_gg'){
						$data[$k]['article_detail_url']=U('Article/detail',array('id'=>$v['id']));
						$data[$k]['belong_game'] = get_game_name($data[$k]['belong_game']);
						$data[$k]['hdtype'] = '公告';
					}elseif($value['name']=='wap_huodong'||$value['name']=='app_huodong'){
						$data[$k]['article_detail_url']=U('Article/detail',array('id'=>$v['id']));

						$data[$k]['belong_game'] = get_game_name($data[$k]['belong_game']);
						$data[$k]['hdtype'] = '活动';
					}else{
						unset($data[$k]);
					}
				}
				if ($model == 'app'){
                    $data[$k]['article_detail_url'] = "http://".$_SERVER['HTTP_HOST']."/mobile.php/Article/detail/id/".$data[$k]['id'];
                }
			}
		}

		foreach ($data as $a => $b){
		    $data[$a]['cover_id'] = icon_url($data[$a]['cover_id']);
        }

		return $data;

	}

}