<?php
/**
 * Created by Sublime.
 * User: yyh 1010707499@qq.com
 * Date: 2017/10/31
 * Time: 9:55
 */

namespace Common\Model;

class AdvModel extends BaseModel{

    /**
	 * 获取对应广告位的广告
	 * author: yyh 1010707499@qq.com
	 */
    public function getAdv($pos_name,$limit){
        $map['p.name'] = $pos_name;
        $map['a.status'] = 1;
        $now = NOW_TIME;
        $map['a.start_time'] = ['lt',$now];
        $data = $this->table("tab_adv as a")
            ->field("a.title,a.data,a.url,target")
            ->join("left join tab_adv_pos p on p.id=a.pos_id")
            ->where($map)
            ->where("a.end_time > $now or a.end_time = 0")
            ->order("a.sort desc")
            ->limit($limit)
            ->select();
        foreach ($data as $key => $val) {
            $data[$key]['data'] = icon_url($val['data']);
        }
        return $data;
    }
}