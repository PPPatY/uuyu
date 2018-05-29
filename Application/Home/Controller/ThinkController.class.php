<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
/**
 * 模型数据管理控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ThinkController extends Controller {
    //数组排序
    public function array_order_page($count,$arraypage,$data,$row='10'){
        $this->showPage($count,$row);
        if(isset($extend_map['for_show_pic_list'])){
            if($extend_map['for_show_pic_list']=='icon'){
                foreach ($data as $key => $value) {
                    $data[$key]['pic_path']=get_cover($value['icon'],'path');
                }
            }
            if($extend_map['for_show_pic_list']=='novice'){
                foreach ($data as $key => $value) {
                    $data[$key]['novice']=arr_count($value['novice']);
                }
            }
        }
        if($_REQUEST['data_order']!=''){
            $data_order=reset(explode(',',$_REQUEST['data_order']));
            $data_order_type=end(explode(',',$_REQUEST['data_order']));
            $this->assign('userarpu_order',$data_order);
            $this->assign('userarpu_order_type',$data_order_type);
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        $this->assign('list_data', $data);
    }
    /**
     * 页码显示
     * @param $count
     * @param int $row
     * author: xmy 280564871@qq.com
     */
    public function showPage($count,$row=10){
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
    }
}