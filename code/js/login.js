$(
    function () {
        $("body").particleground(
            {
                dotColor: '#8d8d8d',//点颜色
                lineColor: '#1d5ba8'//线颜色
            }
        )
        var reg=new Vue(
            {
                el:"#registerPart",
                data:{
                    stdNum:"",
                    stdName:"",
                    direction:"",
                    password:""
                }
            }
        )
        function hideLogin() {
            $("#loginPart").animate({
                top:"0%",
                opacity:"0",
            },"slow");
            setTimeout(function () {
                $("#loginPart").hide();
            },400)
        }
        function showLogin() {
            $("#loginPart").show();
            $("#loginPart").animate({
                top:"50%",
                opacity:"1",
            },"slow");
        }
        function showRegister() {
            $("#registerPart").show();
            $("#registerPart").animate({
                top:"50%",
                opacity:"1",
            },"slow");
        }
        function hideRegister() {
            $("#registerPart").animate({
                top:"70%",
                opacity:"0",
            },"slow");
            setTimeout(function () {
                $("#registerPart").hide();
            },400)
        }
        function register()
        {
            if($(".dropdown").dropdown("get value")[0]=="")
            {
                alert("请选择方向");
                return;
            }
            else if(reg.stdName=="")
            {
                alert("请填写真实姓名");
                return;
            }
            else if(reg.stdNum=="")
            {

                alert("请填写学号");
                return;
            }
            else if(reg.password=="")
            {

                alert("请填写密码");
                return;
            }
            $.ajax({
                type:"POST",
                dataType:"json",
                data:{
                    id:reg.stdNum,
                    name:reg.stdName,
                    direction:$(".dropdown").dropdown("get value")[0],
                    password:reg.password
                },
                url:"interface/register.php",
                success:function (res) {
                    if(res.status==200)
                    {
                        alert(res.content);
                        if(res.content=="注册成功")
                        {
                            hideRegister();
                            showLogin();
                        }
                    }
                },
                error:function () {
                    alert("请求失败");
                }
            })
        }
        $("#regiterButton").click(
            function () {
                register();
            }
        )
        $(".reLogin").click(
            function (){
                hideRegister();
                showLogin();
            }
        )
        $(".reRegister").click(
            function () {
                hideLogin();
                showRegister();
            }
        )
        $(".dropdown").dropdown();
    }
)