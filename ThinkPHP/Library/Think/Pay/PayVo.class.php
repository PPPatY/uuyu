<?php

/**
 * 订单数据模型
 */

namespace Think\Pay;

class PayVo {

    protected $_orderNo;
    protected $_fee;
    protected $_title;
    protected $_body;
    protected $_signtype;
    protected $_callback;
    protected $_url;
    protected $_param;
    protected $_payWay;
    protected $_gameid;
    protected $_gameName;
    protected $_gameAppid;
    protected $_serverid;
    protected $_serverName;
    protected $_userid;
    protected $_account;
    protected $_userNickName;
    protected $_promoteid;
    protected $_promoteName;
    protected $_extend;
    protected $_table;
    protected $_bank;
    protected $_money;
    protected $_coin;
    protected $_service;
    protected $_notifyurl;
    protected $_payMethod;
    protected $_sdkVersion;
    protected $_openid;
    protected $_uc;
    protected $_batchno;
    protected $_detaildata;
    //判断是否为uc用户
    public function setUc($uc) {
        $this->_uc = $uc;
        return $this;
    }
   //退款批次号
    public function setBatchNo($batchno) {
        $this->_batchno = $batchno;
        return $this;
    }

   //单笔数据集
    public function setDetailData($detaildata) {
        $this->_detaildata = $detaildata;
        return $this;
    }

    /**
     * 设置订单号
     * @param type $order_no
     * @return \Think\Pay\PayVo
     */
    public function setOrderNo($order_no) {
        $this->_orderNo = $order_no;
        return $this;
    }
    //openid
    public function setOpenid($_openid) {
        $this->_openid = $_openid;
        return $this;
    }
    /**
     * 设置商品价格
     * @param type $fee
     * @return \Think\Pay\PayVo
     */
    public function setFee($fee) {
        $this->_fee = $fee;
        return $this;
    }

    /**
     * 设置商品名称
     * @param type $title
     * @return \Think\Pay\PayVo
     */
    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    /**
     * 设置商品描述
     * @param type $body
     * @return \Think\Pay\PayVo
     */
    public function setBody($body) {
        $this->_body = $body;
        return $this;
    }

    /**
    *签名方式
    *@param  signtype
    *@return \Think\Pay\PayVo
    */
    public function setSignType($signtype){
        $this->_signtype = $signtype;
        return $this;
    }

    /**
     * 设置支付完成后的后续操作接口
     * @param type $callback
     * @return \Think\Pay\PayVo
     */
    public function setCallback($callback) {
        $this->_callback = $callback;
        return $this;
    }

    /**
     * 设置支付完成后的跳转地址
     * @param type $url
     * @return \Think\Pay\PayVo
     */
    public function setUrl($url) {
        $this->_url = $url;
        return $this;
    }

    /**
     * 设置订单的额外参数
     * @param type $param
     * @return \Think\Pay\PayVo
     */
    public function setParam($param) {
        $this->_param = $param;
        return $this;
    }
    /**
     * 设置游戏充值方式
     * @param type $payway
     * @return \Think\Pay\PayVo
     */
    public function setPayWay($payway) {
        $this->_payWay = $payway;
        return $this;
    }

    /**
     * 设置游戏gameid
     * @param type $gameid
     * @return \Think\Pay\PayVo
     */
    public function setGameId($gameid) {
        $this->_gameid = $gameid;
        return $this;
    }

    /**
     * 设置游戏名称gamename
     * @param type $gamename
     * @return \Think\Pay\PayVo
     */
    public function setGameName($gamename) {
        $this->_gameName = $gamename;
        return $this;
    }

    /**
    *游戏APPID
    *@param  type $gameappid
    *@return \Think\Pay\PayVo
    */
    public function setGameAppid($gameappid){
        $this->_gameAppid = $gameappid;
        return $this;
    }

    /**
     * 设置游戏区服serverid
     * @param type $serverid
     * @return \Think\Pay\PayVo
     */
    public function setServerId($serverid) {
        $this->_serverid = $serverid;
        return $this;
    }

    /**
     * 设置游戏区服名称servername
     * @param type $servername
     * @return \Think\Pay\PayVo
     */
    public function setServerName($servername) {
        $this->_serverName = $servername;
        return $this;
    }
    /**
     * 设置游戏区服serverid
     * @param type $serverid
     * @return \Think\Pay\PayVo
     */
    public function setRoleId($roleid) {
        $this->_roleid = $roleid;
        return $this;
    }

    /**
     * 设置游戏区服名称servername
     * @param type $servername
     * @return \Think\Pay\PayVo
     */
    public function setRoleName($rolename) {
        $this->_roleName = $rolename;
        return $this;
    }

    /**
     * 设置用户账号uid
     * @param type $userid
     * @return \Think\Pay\PayVo
     */
    public function setUserId($userid) {
        $this->_userid = $userid;
        return $this;
    }

    /**
     * 设置用户账号
     * @param type $url
     * @return \Think\Pay\PayVo
     */
    public function setAccount($account) {
        $this->_account = $account;
        return $this;
    }

    /**
     * 设置用户账号昵称
     * @param type $url
     * @return \Think\Pay\PayVo
     */
    public function setUserNickName($usernickname) {
        $this->_userNickName = $usernickname;
        return $this;
    }

    /**
    *设置推广员id
    *@param promoteid
    *@return \Think\Pay\PayVo
    */
    public function setPromoteId($promoteid){
        $this->_promoteid = $promoteid;
        return $this;
    }

    /**
    *设置推广员账号
    *@param promotename
    *@return \Think\Pay\PayVo
    */
    public function setPromoteName($promotename){
        $this->_promoteName = $promotename;
        return $this;
    }

    /**
    *CP方扩展透传信息
    *@param extend
    *@return \Think\Pay\PayVo
    */
    public function setExtend($extend){
        $this->_extend = $extend;
        return $this;
    }

    /**
    *要插入的表
    */
    public function setTable($table){
        $this->_table = $table;
        return $this;
    }
    
    /**
     * 设置充值银行
     * @param type $param
     * @return \Think\Pay\PayVo
     */
    public function setBank($bank) {
        $this->_bank = $bank;
        return $this;
    }
    /**
     * 设置充值实际金额（除去手续费）
     * @param type $param
     * @return \Think\Pay\PayVo
     */
    public function setMoney($money) {
        $this->_money = $money;
        return $this;
    }
    /**
     * 设置充值游戏币数量
     * @param type $param
     * @return \Think\Pay\PayVo
     */
    public function setCoin($coin) {
        $this->_coin = $coin;
        return $this;
    }

    /**
     * 设置支付服务类型
     * @param type $param
     * @return \Think\Pay\PayVo
     */
    public function setService($service){
        $this->_service = $service;
        return $this;
    }

    /**
    *支付异步通知地址
    */
    public function setNotifyUrl($notifyurl){
        $this->_notifyurl = $notifyurl;
        return $this;
    }

    /**
    *支付方法(第三方支付选择例如1：支付宝 2：微信)
    */
    public function setPayMethod($payMethod){
        $this->_payMethod = $payMethod;
        return $this;
    }
    /**
    *sdk版本安卓 苹果
    */
    public function setSdkVersion($SdkVersion){
        $this->_sdkVersion = $SdkVersion;
        return $this;
    }

    /**
     * 获取游戏充值方式
     * @return type
     */
    public function getPayWay() {
        return $this->_payWay;
    }

   //获取退款批次号
    public function getBatchNo() {
        return $this->_batchno;
    }

   //单笔数据集
    public function getDetailData() {
        
        return $this->_detaildata;
    }
    /**
     * 获取游戏gid
     * @return type
     */
    public function getGameId() {
        return $this->_gameid;
    }

    /**
     * 获取游戏名称
     * @return type
     */
    public function getGameName() {
        return $this->_gameName;
    }

    /**
    * 获取游戏appid
    */
    public function getGameAppid(){
        return $this->_gameAppid;
    }

    /**
     * 获取游戏区服id
     * @return type
     */
    public function getServerId() {
        return $this->_serverid;
    }

    /**
     * 获取游戏区服名称
     * @return type
     */
    public function getServerName() {
        return $this->_serverName;
    }

    /**
     * 获取游戏区服id
     * @return type
     */
    public function getRoleId() {
        return $this->_roleid;
    }

    /**
     * 获取游戏区服名称
     * @return type
     */
    public function getRoleName() {
        return $this->_roleName;
    }

    /**
     * 获取账号uid
     * @return type
     */
    public function getUserId() {
        return $this->_userid;
    }
    public function getOpenid() {
        return $this->_openid;
    }

    /**
     * 获取用户账号
     * @return type
     */
    public function getAccount() {
        return $this->_account;
    }

    /**
     * 获取用户昵称
     * @return type
     */
    public function getUserNickName() {
        return $this->_userNickName;
    }

    /**
    * 获取推广员id
    */
    public function getPromoteId(){
        return $this->_promoteid;
    }

    /**
    * 获取推广员名称
    */
    public function getPromoteName(){
        return $this->_promoteName;
    }

    /**
    * 获取CP方扩展透传信息
    */
    public function getExtend(){
        return $this->_extend;
    }

    /*
    * 获取要插入的表
    */
    public function getTable(){
        return $this->_table;
    }
    
    /**
     * 获取充值银行
     * @return type
     */
    public function getBank() {
        return $this->_bank;
    }
    /**
     * 获取充值实际金额（除去手续费）
     * @return type
     */
    public function getMoney() {
        return $this->_money;
    }
    /**
     * 获取充值游戏币数量
     * @return type
     */
    public function getCoin() {
        return $this->_coin;
    }
        /**
     * 获取uc用户
     * @return type
     */
    public function getUc() {
        return $this->_uc;
    }
    /**
     * 获取订单号
     * @return type
     */
    public function getOrderNo() {
        return $this->_orderNo;
    }

    /**
     * 获取商品价格
     * @return type
     */
    public function getFee() {
        return $this->_fee;
    }

    /**
     * 获取商品名称
     * @return type
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
    * 获取验签方式
    */
    public function getSignType(){
        return $this->_signtype;
    }

    /**
     * 获取支付完成后的后续操作接口
     * @return type
     */
    public function getCallback() {
        return $this->_callback;
    }

    /**
     * 获取支付完成后的跳转地址
     * @return type
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * 获取商品描述
     * @return type
     */
    public function getBody() {
        return $this->_body;
    }

    /**
     * 获取订单的额外参数
     * @return type
     */
    public function getParam() {
        return $this->_param;
    }

    /**
    *支付服务类型
    *@return type
    */
    public function getService(){
        return $this->_service;
    }

    /**
    *支付异步通知地址
    */
    public function getNotifyUrl(){
        return $this->_notifyurl;
    }

    /**
    *支付方法
    */
    public function getPayMethod(){
        return $this->_payMethod;
    }
    /**
    *SDK版本苹果安卓
    */
    public function getSdkVersion(){
        return $this->_sdkVersion;
    }

}
