<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        //获取查看者ip
        $ip = $this->getClientIp();
        $data = [
            'ip' => $ip,
            'createtime' => time(),
            'updatetime' => time(),
        ];
        //存进数据库
        db('ip_repository')->insert($data);
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

    //留言板
    public function remark()
    {
        if ($this->request->isAjax()) {
            var_dump($this->request->post());
        }
        return $this->view->fetch();

    }
}
