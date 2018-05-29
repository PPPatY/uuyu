<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/6
 * Time: 9:22
 */
namespace Home\Event;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Think\Controller;

class QiNiuEvent extends Controller {

    /**
     * 删除七牛空间文件
     * @param $key
     * @return mixed
     */

    public function dleteQiNiuFile($key){
        Vendor('Qiniu.autoload');
        $config = C('qiniu_storage');
        $accessKey = $config['AccessKey'];
        $secretKey = $config['SecretKey'];
        $auth  = new Auth($accessKey,$secretKey);
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        $bucket = C('qiniu_storage.bucket');
        $res = $bucketMgr->delete($bucket, $key);
        return $res;
    }

    /**
     * 七牛上传
     * @param $newName  上传到七牛的文件名称
     * @param $filePath 文件路径
     */
    public function upQiNiuFile($newName,$filePath){
        Vendor('Qiniu.autoload');
        //读取七牛配置
        $config = C('qiniu_storage');
        $accessKey = $config['AccessKey'];
        $secretKey = $config['SecretKey'];
        //实例化鉴权对象
        $auth  = new Auth($accessKey,$secretKey);
        $bucket = $config['bucket'];
        //生成token
        $token = $auth->uploadToken($bucket);
        //实例化上传类
        $uploadMgr = new UploadManager();
        //上传附件
        list($ret,$err) = $uploadMgr->putFile($token,$newName,$filePath);
        if($ret){
            return $url = $config['domain'].'/'.$newName;
        }else{
            return '';
        }
    }
}