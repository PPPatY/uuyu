<?php
namespace Admin\Event;
use Think\Controller;
/**
 * 后台事件控制器
 * @author yyh 
 */
class PtbsendEvent extends Controller {
    /*
    *自选
    */
    public function add1()
    {
        $account=trim($_POST['account']);
        $amount= $_POST['amount'];
        if($account==""){$this->error("请输入玩家账号");}
        $isset=get_user_entity($account,1);
        if($isset){
          if($amount==''){$this->error("请输入发放数量！");}     
          if(!is_numeric($amount)||$amount<=0){
            $this->error("发放数量不正确！");
            return false;
          }
          $add['user_id']=$isset['id'];
          $add['user_nickname']=$account;
          $add['order_number']="PT_".build_order_no();
          $add['pay_order_number']=sp_random_string(4).build_order_no();
          $add['user_account']=$account;
          $add['amount']=$amount;
          $add['status']=0;
          $add['op_id']=UID;
          $add['coin_type']=0;//平台币
          $add['op_account']=session("user_auth.username");
          $add['create_time']=NOW_TIME;
          $prov=M("provide","tab_")->add($add);
          $this->success("提交成功",U("ptbsenduser"));
        }else{
          $this->error("玩家不存在");
        }    
    }

    /**
     * 内充管理---导入Excel
     * @author 顽皮蛋 <shf_l@163.com>
     */
    public function add2(){
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
        $upload->rootPath  =     './Uploads/'; // 设置附件上传目录
        $upload->savePath  =      'excel/'; // 设置附件上传目录
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['excelData']);
        $filename = './Uploads/'.$info['savepath'].$info['savename'];
        $exts = $info['ext'];
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
          }else{// 上传成功
            $this->charge_import($filename, $exts);
        }
    }


    public function add3(){
      $account=$_POST['pay_names'];
      $amount=$_POST['amount'];
      if($amount==''){$this->error("请输入发放数量！");}     
      if(empty($account)){$this->error("请输入玩家账号");}
      if($amount<=0){$this->error("发放数量不正确！");}            
      $namearr = explode("\n",$account);
      static $a=0;
      for($i=0;$i<count($namearr);$i++){
          $user=get_user_entity(str_replace(array("\r\n", "\r", "\n"), "", $namearr[$i]),1);
          if(null!=$user){
              $add['user_nickname']=$user['nickname'];
              $add['pay_order_number']=sp_random_string(4).build_order_no();
              $add['order_number']="PT_".build_order_no();
              $add['user_account']=$namearr[$i];
              $add['user_id']=$user['id'];
              $add['amount']=$amount;
              $add['status']=0;
              $add['coin_type']=0;//平台币
              $add['op_id']=UID;
              $add['op_account']=session("user_auth.username");
              $add['create_time']=NOW_TIME;
              $prov=M("provide","tab_")->add($add);
              if($prov){
                  $a++;
              }
          }
      }
      $b=count($namearr)-$a;
      $this->success("成功{$a}个,失败{$b}个",U("ptbsenduser"));
    }



    //导入数据方法
    protected function charge_import($filename, $exts='xls'){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        //import("Org.Util.PHPExcel");
        vendor("PHPExcel.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if($exts == 'xls'){
            //import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
            //import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader=new \PHPExcel_Reader_Excel2007();
        }
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=1;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            }

        }
        $this->save_import($data);
    }

    //保存导入数据并返回错误信息
    public function save_import($data){ 
        unset($data[1]);
        $errorNum = 0;
        $succNum = 0;
        $errorList = array();//存储错误数据;
        foreach ($data as $k=>$v){
          $errorList[$errorNum]['A'] = $v['A'];
          $errorList[$errorNum]['B'] = $v['B'];
          $u = get_user_entity($v['A'],1);
          if(empty($u)){//用户名不存在
            $errorList[$errorNum]['D'] = '用户名不存在';
            $errorNum++;
            continue;
          }
          if($v['B']<=0){//金额有问题
            $errorList[$errorNum]['D'] = '金额有问题';
            $errorNum++;
            continue;
          }
          $succNum++;
          $arr['user_account'] = $u['account'];
          $arr['op_id']=UID;
          $arr['op_account']=session("user_auth.username");
          $arr['user_nickname']=$u['nickname'];
          $arr['user_id']=$u['id'];
          $arr['pay_order_number']=sp_random_string(4).build_order_no();
          $arr['order_number']="PT_".build_order_no();
          $arr['amount'] = (double)$v['B'];
          $arr['status']=0;
          $arr['coin_type']=0;//平台币
          $arr['create_time'] = NOW_TIME;
          D('Provide')->add($arr);
        }
        $a = json_encode($errorList);
        $json = urlencode(json_encode($errorList));          
        $this->assign ( 'errorNum', $errorNum );
        $this->assign ( 'succNum', $succNum );
        $this->assign ( 'status', 1 );
        $this->assign ( 'json', $json);
        $this->success('成功：'.$succNum.'；失败：'.$errorNum,U('lists',array('group'=>2)));
    }
}