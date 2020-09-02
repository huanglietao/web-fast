<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\wnmp\nginx\html\web-fast\public/../application/index\view\index\remark.html";i:1599036095;}*/ ?>
<style>
    body{
        margin: 0;
    }
    .elegant-aero {
        background: #D2E9FF;
        font: 12px Arial, Helvetica, sans-serif;
        color: #666;
        width: 100%;
        height: 100vh;
        margin-bottom: 0;
    }
    .elegant-aero h1 {
        font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
        padding: 10px 10px 10px 20px;
        display: block;
        /*background: #C0E1FF;
        border-bottom: 1px solid #B8DDFF;*/
    }
    .elegant-aero h1>span {
        display: block;
        font-size: 11px;
    }

    .elegant-aero label>span {
        float: left;
        margin-top: 10px;
        color: #5E5E5E;
    }
    .elegant-aero label {
        display: block;
        margin: 0px 0px 5px;
    }
    .elegant-aero label>span {
        float: left;
        width: 20%;
        text-align: right;
        padding-right: 15px;
        margin-top: 10px;
        font-weight: bold;
    }
    .elegant-aero input[type="text"], .elegant-aero input[type="email"], .elegant-aero textarea, .elegant-aero select {
        color: black;
        width: 70%;
        padding: 0px 0px 0px 5px;
        border: 1px solid #C5E2FF;
        background: #FBFBFB;
        outline: 0;
        -webkit-box-shadow:inset 0px 1px 6px #ECF3F5;
        box-shadow: inset 0px 1px 6px #ECF3F5;
        font: 200 12px/25px Arial, Helvetica, sans-serif;
        height: 30px;
        line-height:15px;
        margin: 2px 6px 16px 0px;
    }
    .elegant-aero textarea{
        height:100px;
        padding: 5px 0px 0px 5px;
        width: 70%;
    }
    .elegant-aero select {
        background: #fbfbfb url('down-arrow.png') no-repeat right;
        background: #fbfbfb url('down-arrow.png') no-repeat right;
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance: none;
        text-indent: 0.01px;
        text-overflow: '';
        width: 70%;
    }
    .elegant-aero .button{
        padding: 10px 30px 10px 30px;
        background: #66C1E4;
        border: none;
        color: #FFF;
        box-shadow: 1px 1px 1px #4C6E91;
        -webkit-box-shadow: 1px 1px 1px #4C6E91;
        -moz-box-shadow: 1px 1px 1px #4C6E91;
        text-shadow: 1px 1px 1px #5079A3;

    }
    .elegant-aero .button:hover{
        background: #3EB1DD;
    }

</style>
<form action="" method="post" class="elegant-aero">
    <h1>
        <span></span>
    </h1>
    <label>
        <span>姓名:</span>
        <input id="name" type="text" name="name" placeholder="可填可不填,你喜欢" />
    </label>
    <label>
        <span>联系方式:</span>
        <input id="email" type="email" name="email" placeholder="可填可不填,你喜欢" />
    </label>

    <label>
        <span>留言:</span>
        <textarea id="message" name="message" placeholder="想说啥说啥"></textarea>
    </label>
    <label>
        <span>&nbsp;</span>
        <input type="button" class="button remark-save" value="提交" style="cursor: pointer" />
    </label>
</form>
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/libs/layer/src/layer.js"></script>
<script>
    $(".remark-save").click(function () {
        //获取弹窗所在索引
        var name = $("#name").val();
        var email = $("#email").val();
        var message = $("#message").val();
        if (name == '' && email == '' && message == ''){
            layer.msg("什么都不填,无法提交,可点击右上角关闭窗口")
        }else{
            $.ajax({
                type: 'POST',
                url: '/index/index/remarkSave',
                dataType: "json",
                data: {
                    name:name,
                    email:email,
                    message:message,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(res) {
                    if (res.code == 1){
                        layer.msg("留言成功,他会看到的,应该吧")
                    }else{
                        layer.msg(res.msg)
                    }
                    setTimeout(function () {
                        parent.layer.closeAll();
                    },2000);
                },
                error:function(res){
                    layer.msg("程序出错了,若不舒服可直接加qq1013488674骂他!");
                    setTimeout(function () {
                        parent.layer.closeAll();
                    },2000);
                }
            });
        }
    })
</script>

