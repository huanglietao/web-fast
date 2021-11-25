<?php

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\Db;

class TestForSh extends Command
{

    // 配置定时器的信息
     protected function configure()
     {
         $this->setName('testForSh')
             ->setDescription('Command TestForSh');
     }
     protected function execute(Input $input, Output $output)
     {
         file_put_contents(APP_PATH."/testHlt.log",date("Y-m-d H:i:s").":  开始测试;"."\r\n",FILE_APPEND);
         $data = [
             'remark' => "现在是".date("Y-m-d H:i:s"),
         ];
         $newId = db('test')->insertGetId($data);
         file_put_contents(APP_PATH."/testHlt.log",date("Y-m-d H:i:s").": 插入id为：".$newId." 结束测试;"."\r\n",FILE_APPEND);
     }
}
