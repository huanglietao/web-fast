<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Curl;
use think\Db;
use think\Request;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function __construct(Request $request = null)
    {
        //获取查看者ip存进数据库
        $this->saveIp();
        parent::__construct($request);
    }

    public function index()
    {
        return $this->view->fetch();
    }

    public function xian()
    {
        var_dump("看看看，看什么看，再看打你");die;
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
            //判断登录端
            $agentArr = explode('||||',$exist['user_agent']);
            if (!in_array($_SERVER['HTTP_USER_AGENT'],$agentArr)){
                $agentArr[] = $_SERVER['HTTP_USER_AGENT'];
            }
            if (!empty($agentArr)){
                $data['user_agent'] = implode('||||',$agentArr);
                $data['user_agent'] = ltrim($data['user_agent'],'||||');
            }
            db('ip_repository')->where(['id' => $exist['id']])->update($data);
        }else{
            //添加数据，次数加1
            $data['ip'] = $ip;
            $data['time'] = 1;
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
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


    public function messageBoard()
    {
        return $this->view->fetch();
    }

    //获取留言内容
    public function getRemark()
    {
        $list = Db::name('stranger_note')->field('id,createtime,username,remark')->order('createtime desc')->select();
        foreach ($list as $k => $v)
        {
            //转换时间戳
            $list[$k]['time'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        return $list;
    }

}
