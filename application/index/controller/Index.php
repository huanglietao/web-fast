<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Curl;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        //获取查看者ip存进数据库
        $this->saveIp();
        return $this->view->fetch();
    }




    public function getClientIp()
    {

        $ip= '';
        if (isset($_SERVER)){

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

                $ip= $_SERVER["HTTP_X_FORWARDED_FOR"];

            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

                $ip= $_SERVER["HTTP_CLIENT_IP"];

            } else {

                $ip= $_SERVER["REMOTE_ADDR"];

            }

        } else {

            if (getenv("HTTP_X_FORWARDED_FOR")){

                $ip= getenv("HTTP_X_FORWARDED_FOR");

            } else if (getenv("HTTP_CLIENT_IP")) {

                $ip= getenv("HTTP_CLIENT_IP");

            } else {

                $ip= getenv("REMOTE_ADDR");

            }

        }
        return $ip;

    }

    //存储ip地址
    public function saveIp()
    {

        $ip = $this->getClientIp();
        //若该ip地址已在ip库则访问次数加1
        $exist = db('ip_repository')->where(['ip' => $ip])->find();
        if (!empty($exist))
        {
            //更新数据，次数加1
            $data['time'] = ++$exist['time'];
            $data['updatetime'] = time();
            db('ip_repository')->where(['id' => $exist['id']])->update($data);
        }else{
            //添加数据，次数加1
            $data['ip'] = $ip;
            $data['time'] = 1;
            $data['createtime'] = time();
            $data['updatetime'] = time();
            db('ip_repository')->insert($data);
        }
        return true;
    }

    //留言板
    public function remark()
    {
        return $this->view->fetch();
    }
    //保存留言内容
    public function remarkSave()
    {
       $post = $this->request->post();
       $sqlRes = $this->checksqlData($post);
       if (!$sqlRes){
           $data = [
               'code' => 0,
               'msg'  => "别搞我数据库,谢谢"
           ];
           return $data;
       }
       $insertData = [
           'ip'          => $this->getClientIp(),
           'username'    => $post['name'],
           'email'       => $post['email'],
           'remark'      => $post['message'],
           'createtime'  => time(),
           'updatetime'  => time(),
       ];
       db('stranger_note')->insert($insertData);
       return [
           'code' => 1,
           'msg'  => 'ok',
       ];
    }

    //检查参数，初步防止注入
    public function checksqlData($data)
    {
        $msg = '';
        foreach ($data as $k => $v){
            if(strpos($v,'select') !== false || strpos($v,'insert') !== false || strpos($v,'update') !== false || strpos($v,'delete') !== false ){
                $msg = "别搞我数据库谢谢";
            }
        }
        if ($msg!=""){
            return false;
        }else{
            return true;
        }

    }


}
