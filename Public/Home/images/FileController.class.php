<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use OSS\OssClient;
use Think\ShardUpload;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class FileController extends AdminController {

    /* 文件上传 */
    public function upload(){
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
		/* 调用文件上传组件上传文件 */
		$File = D('File');
		$file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
		$info = $File->upload(
			$_FILES,
			C('DOWNLOAD_UPLOAD'),
			C('DOWNLOAD_UPLOAD_DRIVER'),
			C("UPLOAD_{$file_driver}_CONFIG")
		);

        /* 记录附件信息 */
        if($info){
            $return['data'] = think_encrypt(json_encode($info['download']));
            $return['info'] = $info['download']['name'];
        } else {
            $return['status'] = 0;
            $return['info']   = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    /* 文件分片上传 */
    public function shard_upload(){
        //关闭缓存
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $uploader = new ShardUpload();
        if(I('get.typee') == 1){//指定上传目录
            $uploader->set('path','./Uploads/Ios/description');
        }elseif(I('get.type') == "1"){

	        $uploader->set('path','./Uploads/Material');
        }
        //var_dump($_POST);
        //用于断点续传，验证指定分块是否已经存在，避免重复上传
        if(isset($_POST['status'])){
            if($_POST['status'] == 'chunkCheck'){
                $target = C('DOWNLOAD_UPLOAD.rootPath')."/".$_POST['name'].'/'.$_POST['chunkIndex'];
                if(file_exists($target) && filesize($target) == $_POST['size']){
                    die('{"ifExist":1}');
                }
                die('{"ifExist":0}');

            }elseif($_POST['status'] == 'md5Check'){
                
            }elseif($_POST['status'] == 'chunksMerge'){
                if($result = $uploader->chunksMerge($_POST['name'], $_POST['chunks'], $_POST['ext'])){
                    //todo 把md5签名存入持久层，供未来的秒传验证
                    echo $result;
                    exit();
                }
            }
        }
        if(($path = $uploader->upload('file', $_POST)) !== false){
	        if(I('get.type') == "1"){
	        	$file_info = json_decode($path,true);
		        $this->upload_oss("Material/",$file_info['name'],$file_info['path'].'/'.$file_info['name']);
		        $file_info['path'] = "http://".C('CND_ADDRESS')."/Material";
		        $path = json_encode($file_info);
	        }
            die($path);
        }
        
    }
    /* 苹果渠道包文件分片上传 */
    public function ios_shard_upload(){
        //关闭缓存
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $uploader = new ShardUpload();
        //用于断点续传，验证指定分块是否已经存在，避免重复上传
        if(isset($_POST['status'])){
            if($_POST['status'] == 'chunkCheck'){
                $target = C('DOWNLOAD_UPLOAD.rootPath')."/".$_POST['name'].'/'.$_POST['chunkIndex'];
                if(file_exists($target) && filesize($target) == $_POST['size']){
                    die('{"ifExist":1}');
                }
                die('{"ifExist":0}');

            }elseif($_POST['status'] == 'md5Check'){
                
            }elseif($_POST['status'] == 'chunksMerge'){
                if($result = $uploader->chunksMerge($_POST['name'], $_POST['chunks'], $_POST['ext'])){
                    //todo 把md5签名存入持久层，供未来的秒传验证
                    echo $result;
                    exit();
                }
            }
        }
        if(($path = $uploader->iosUpload('file', $_POST)) !== false){
            die($path);
        }
        
    }
    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            $this->error('参数错误！');
        }

        $logic = D('Download', 'Logic');
        if(!$logic->download($id)){
            $this->error($logic->getError());
        }

    }

    /**
     * 上传图片
     * @author huajie <banhuajie@163.com>
     */
    public function uploadPicture(){
        //TODO: 用户登录检测

        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        ); //TODO:上传到远程服务器

        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return = array_merge($info['download'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
         ob_clean();
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }


	/**
	 *上传到OSS
	 */
	public function upload_oss($oss_path,$file_name,$file_path)
	{
		/**
		 * 根据Config配置，得到一个OssClient实例
		 */
		try {
			Vendor('OSS.autoload');
			$ossClient = new \OSS\OssClient(C("oss_storage.accesskeyid"), C("oss_storage.accesskeysecr"), C("oss_storage.domain"));
		} catch (OssException $e) {
			$this->error($e->getMessage());
		}

		$oss_file_path = $oss_path . $file_name;
		$avatar = $file_path;
		try {

			$this->multiuploadFile($ossClient, C("oss_storage.bucket"), $oss_file_path, $avatar);
			return true;
		} catch (OssException $e) {
			/* 返回JSON数据 */
			$this->error($e->getMessage());
		}
	}

	public function multiuploadFile($ossClient, $bucket, $url, $file)
	{
		//$file = __FILE__;
		$options = array();
		try {
			#初始化分片上传文件
			$uploadId = $ossClient->initiateMultipartUpload($bucket, $url);
			//$ossClient->multiuploadFile($bucket, $url, $file, $options);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		/*
		 * step 2. 上传分片
		 */
		$partSize = 5 * 1000 * 1024;
		$uploadFile = $file;
		$uploadFileSize = filesize($uploadFile);
		$pieces = $ossClient->generateMultiuploadParts($uploadFileSize, $partSize);
		$responseUploadPart = array();
		$uploadPosition = 0;
		$isCheckMd5 = true;
		foreach ($pieces as $i => $piece) {
			$fromPos = $uploadPosition + (integer)$piece[$ossClient::OSS_SEEK_TO];
			$toPos = (integer)$piece[$ossClient::OSS_LENGTH] + $fromPos - 1;
			$upOptions = array(
				$ossClient::OSS_FILE_UPLOAD => $uploadFile,
				$ossClient::OSS_PART_NUM => ($i + 1),
				$ossClient::OSS_SEEK_TO => $fromPos,
				$ossClient::OSS_LENGTH => $toPos - $fromPos + 1,
				$ossClient::OSS_CHECK_MD5 => $isCheckMd5,
			);
			if ($isCheckMd5) {
				$contentMd5 = \OSS\Core\OssUtil::getMd5SumForFile($uploadFile, $fromPos, $toPos);
				$upOptions[$ossClient::OSS_CONTENT_MD5] = $contentMd5;
			}
			//2. 将每一分片上传到OSS
			try {
				$responseUploadPart[] = $ossClient->uploadPart($bucket, $url, $uploadId, $upOptions);
			} catch (OssException $e) {
				printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
				printf($e->getMessage() . "\n");
				return;
			}
			//printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
		}
		$uploadParts = array();
		foreach ($responseUploadPart as $i => $eTag) {
			$uploadParts[] = array(
				'PartNumber' => ($i + 1),
				'ETag' => $eTag,
			);
		}
		/**
		 * step 3. 完成上传
		 */
		try {
			$a = $ossClient->completeMultipartUpload($bucket, $url, $uploadId, $uploadParts);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
	}
}
