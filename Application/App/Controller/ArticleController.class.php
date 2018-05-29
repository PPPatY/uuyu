<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/30
 * Time: 13:53
 */
namespace App\Controller;

use Common\Model\DocumentModel;
use Common\Model\UserModel;

class ArticleController extends BaseController{

	public function show_red_point($token=''){
        $hdmark = 0;
        $user_id = 0;
        $usermodel = new UserModel();
        $Docmodel = new DocumentModel();
		if($token!=''){
			$this->auth($token);
			$user_id = USER_ID;
		}
		if($user_id){
            $hdmark = 1;
            $oneparam = $usermodel->getUserOneParam($user_id,'hidden_option');
            $article = $Docmodel->getArticleListsByCategory('',array('app_huodong'),1,1000);
            $oneparamarr = json_decode($oneparam['hidden_option'],true);
            $hdmarkarr = $oneparamarr['hidden_app_hd'];
            $hdmarkstatus = $hdmarkarr['status'];//1隐藏红点  0显示
            $hdmarktime = $hdmarkarr['time']==NULL?0:$hdmarkarr['time'];
            foreach ($article as $key => $value) {
                if($value['line_time']>$hdmarktime){
                    $hdmark = 0;//今日新增或更新  且满足显示条件
                    break;
                }
            }
        }else{
        	$hdmark = 1;
            $article = $Docmodel->getArticleListsByCategory('',array('app_huodong'),1,1000);
            foreach ($article as $key => $value) {
                if($value['line_time']>mktime(0,0,0,date('m'),date('d'),date('Y'))){
                    $hdmark = 0;//今日新增或更新  且满足显示条件
                    break;
                }
            }
        }
        $data['hdmark'] = $hdmark;
        $this->set_message(200,'success',$data);

	}

	public function set_red_point($token=''){
		$this->auth($token);
		$user_id = USER_ID;
		$model = new DocumentModel();
        if($user_id){
            $hdmark = $model->hdmarkrec($user_id,'hidden_app_hd');
        }else{
            $hdmark = false;
        }
        $this->set_message(200,'success',array());
	}

	/**
	 * 获取文章列表
	 * @param int $p
	 * @param $category
	 * author: xmy 280564871@qq.com
	 */
	public function get_article_lists($p=1,$category=2,$token=''){
		switch ($category) {
			case 1://资讯
				$category_name = "app_zx";
				$row = 10;
				break;
			case 2://公告
				$category_name = "app_gg";
				$row = 10;
				break;
			case 3://活动
				$category_name = "app_huodong";
				$row = 10;
				break;
		}
		$model = new DocumentModel();
		if(!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
            $hdmark = $model->hdmarkrec(USER_ID);
        }else{
            $hdmark = false;
        }
        if ($category == 3){
            $data = $model->getArticleListsByCategory2('',$category_name,$p,$row,$type=2);
        }else{
            $data = $model->getArticleListsByCategory('',$category_name,$p,$row,$type=2);
        }
		if(empty($data)){
			$msg = array(
				"code" => 200,
				"msg"  => 'success',
				"data" => []
			);
			$msg['hdmark'] = 1;
			echo json_encode($msg);
			exit;
		}else{
			$msg = array(
				"code" => 200,
				"msg"  => 'success',
				"data" => $data
			);
			$msg['hdmark'] = $hdmark===false?0:1;
			echo json_encode($msg);
			exit;
		}
	}

	/**
	 * 文章显示
	 * @param string $id
	 * author: xmy 280564871@qq.com
	 */
	public function show($id){
	    $model = new DocumentModel();
		$data = $model->articleDetail($id);
		$this->assign("data",$data);
		$this->display("index");
	}


	/**
	 * 用户协议
	 * author: xmy 280564871@qq.com
	 */
	public function agreement()
	{
		$data = D("Document")->getArticleListsByCategory("agreement");
		$data = D("Document")->getArticle($data[0]['id']);
		$this->assign("data",$data);
		$this->display();
	}

	/**
	 * 获取分享信息
	 * @param $game_id
	 * author: xmy 280564871@qq.com
	 */
	public function get_share_info($article_id){
		$model = new DocumentModel();
		$article = $model->getArticle($article_id);
		if(empty($article)){
			$this->set_message(-1,"文章不存在");
		}
		$result['title'] = $article['title'];
		$result['icon'] = $article['cover_id'];
		$result['content'] = $article['description'];
		$result['url'] = U('Article/show',['id'=>$article_id],true,true);
		$this->set_message(1,1,$result);
	}

}