<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Admin\Controller\CosController;
use Admin\Controller\OssController;
use Admin\Controller\BosController;
use Admin\Event\QiNiuEvent;
use Think\Model;
use Think\Upload;

/**
 * 图片模型
 * 负责图片的上传
 */

class PictureModel extends Model{
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local', $config = null)
    {
        /* 上传文件 */
        $setting['callback'] = array($this, 'isFile');
        $setting['removeTrash'] = array($this, 'removeTrash');
        $Upload = new Upload($setting, $driver, $config);
        $info = $Upload->upload($files);
        if ($info) {
            //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 记录文件信息 */
                /* 已经存在文件记录 */
                if (!isset($value['id'])) {
                    $value['path'] = substr($setting['rootPath'], 1) . $value['savepath'] . $value['savename']; //在模板里的url路径
                    if ($this->create($value) && ($id = $this->add())) {
                        $value['id'] = $id;
                    } else {
                        //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                        unset($info[$key]);
                    }
                }
                if (get_tool_status("oss_storage") == 1) {
                    $path = explode("/", $value['path']);
                    $to = "http://" . C("oss_storage.bucket") . "." . C("oss_storage.domain") . "/icon/" . $path[4];
                    $to = str_replace('-internal', '', $to);
                    // $to="http://down.vlcms.com/icon/".$path[4];
                    $updata['savename'] = $path[4];
                    $updata['path']     = "." . $value['path'];
                    $oss = new OssController();
                    $oss->upload_game_pak_oss($updata);
                    $data_url['oss_url'] = $to;
                    $coo = $this->where(array('id' => $value['id']))->save($data_url);
                } elseif (get_tool_status("qiniu_storage") == 1) {//七牛上传
                    $path    = explode("/", $value['path']);
                    $newName = $path[4];
                    $QiNiu = new QiNiuEvent();
                    $value['url'] = $data_url['url'] = "http://" . $QiNiu->upQiNiuFile($newName, "." . $value['path']);
                    $coo = $this->where(array('id' => $value['id']))->save($data_url);
                }
                // elseif(get_tool_status("cos_storage") == 1){
                //     $path = explode("/", $value['path']);
                //     $newName = $path[4];
                //     $cos=new Coscontroller();
                //     $cos_res=$cos->cosupload("." . $value['path'],"/Icon/".$newName);
                //     if(strlen($cos_res)>10){
                //     $data_url['url'] = $cos_res;
                //     $coo = $this->where(array('id' => $value['id']))->save($data_url);
                //     }else{
                //     return false;
                //     }
                // }elseif(get_tool_status("bos_storage") == 1){ //上传到百度云
                //     $path = explode("/", $value['path']);
                //     $to = "http://" . C("bos_storage.bucket") . "." . C("bos_storage.domain") . "/icon/" . $path[4];
                //     $to = str_replace('-internal', '', $to);
                //     $updata['savename'] = $path[4];
                //     $updata['path'] = "." . $value['path'];
                //     $oss = new BosController();
                //     $oss->upload_bos($updata);
                //     $data_url['bos_url'] = $to;
                //     $coo = $this->where(array('id' => $value['id']))->save($data_url);
                // }
                // elseif(get_tool_status("ks3_storage") == 1){ //上传到金山云
                //     $path = explode("/", $value['path']);
                //     $to = "http://" . C("ks3_storage.bucket") . "." . C("ks3_storage.domain") . "/icon/" . $path[4];
                //     $to = str_replace('-internal', '', $to);
                //     $updata['savename'] = $path[4];
                //     $updata['path'] = "." . $value['path'];
                //     $oss = new Ks3Controller();
                //     $oss->upload_ks3($updata);
                //     $data_url['bos_url'] = $to;
                //     $coo = $this->where(array('id' => $value['id']))->save($data_url);
                // }



            }
//                 wite_text(json_encode($info),dirname(__FILE__).'/info.txt');
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }


    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public function download($root, $id, $callback = null, $args = null){
        /* 获取下载文件信息 */
        $file = $this->find($id);
        if(!$file){
            $this->error = '不存在该文件！';
            return false;
        }

        /* 下载文件 */
        switch ($file['location']) {
            case 0: //下载本地文件
                $file['rootpath'] = $root;
                return $this->downLocalFile($file, $callback, $args);
            case 1: //TODO: 下载远程FTP文件
                break;
            default:
                $this->error = '不支持的文件存储类型！';
                return false;

        }

    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
        $map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null){
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            /* 执行下载 */ //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            $this->error = '文件已被删除！';
            return false;
        }
    }

    /**
     * 清除数据库存在但本地不存在的数据
     * @param $data
     */
    public function removeTrash($data){
        $this->where(array('id'=>$data['id'],))->delete();
    }

}
