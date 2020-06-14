;
(function() {
    //全局ajax处理
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('[name="__token__"]').val()
    //     }
    // });





    if ($('#register').length) {
        $('#register').on('click', function(e) {

            e.preventDefault();
            url = $("#register").attr('href');
            var nickname = $('input[name="nickname"]').val();
            var password = $('input[name="password"]').val();
            var repassword = $('input[name="repassword"]').val();
            var clause = $("input[type='checkbox']").is(':checked');

            if (!clause) {
                return error('请勾选使用条款');
            }

            $.post(url, { nickname: nickname, password: password, repassword: repassword, clause: clause }, function(json) {

                if (json.code == 1) {

                    success(json.msg);
                    // $('meta[name="csrf-token"]').val(json.data.token);
                    // $('meta[name="csrf-token"]').val('8888888888');
                    setTimeout(function() {
                        window.location.href = "/"
                    }, 1000);


                } else {

                    return error(json.msg);
                }

            }, "json");
        });
    }



    if ($('#sendSms').length) {
        $('#sendSms').on('click', function(e) {

            e.preventDefault();

            url = $("#sendSms").attr('href');

            var mobile;
            mobile = $(".mobile").val();
            //验证手机号
            switch (CheckMobile(mobile)) {
                case 0:
                    return error('手机号不能为空')
                    break;
                case 2:
                    return error('请输入正确的手机号')
                    break;
                default:
            }

            //请求短信接口
            $.post(url, { mobile: mobile, __token__: $('[name="__token__"]').val() }, function(data) {
                if (data.code == 1) {
                    //展示文案
                    $('[name="__token__"]').val(data.data.token)
                    return success(data.msg);
                    //todo
                } else {

                    $('[name="__token__"]').val(data.data.token)
                    return error(data.msg);
                }
            });

            //更改点击状态
            $("#basic-addon").addClass('sms-second');

            //验证码定时器
            timesRun = 30;
            $("#basic-addon").find("a").text("剩余" + timesRun + 's');
            var interval = setInterval(function() {
                timesRun -= 1;
                $("#basic-addon").find("a").text("剩余" + timesRun + 's');
                if (timesRun === 0) {
                    $("#basic-addon").find("a").text('重新获取');
                    $("#basic-addon").removeClass('sms-second');
                    clearInterval(interval);
                }
            }, 1000);



        });
    }


    if ($('#formLogin').length) {
        $('#formLogin').on('click', function(e) {

            e.preventDefault();
            url = $("#formLogin").attr('href');

            var nickname = $('input[name="nickname"]').val();
            var mobile = $('input[name="mobile"]').val();
            var password = $('input[name="password"]').val();
            var smscode = $('input[name="smscode"]').val();
            var type = $('input[name="logintype"]').val();


            $.post(url, { __token__: $('[name="__token__"]').val(), nickname: nickname, password: password, mobile: mobile, smscode: smscode, type: type }, function(json) {
                if (json.code == 1) {

                    console.log(json.url);
                    success(json.msg);
                    setTimeout(function() {
                        window.location.href = json.url
                    }, 1000);


                } else {

                    // console.log($('[name="__token__"]').val())
                    return error(json.msg);
                }

            }, "json");


        });
    }


    if ($('#forgetForm').length) {
        $('#forgetForm').on('click', function(e) {

            e.preventDefault();
            url = $("#forgetForm").attr('href');

            var mobile = $('input[name="mobile"]').val();
            var password = $('input[name="password"]').val();
            var smscode = $('input[name="smscode"]').val();

            $.post(url, { mobile: mobile, password: password, smscode: smscode, __token__: $('[name="__token__"]').val() }, function(json) {

                if (json.code == 1) {

                    success(json.msg);
                    setTimeout(function() {
                        window.location.href = "/user/login/login"
                    }, 1000);


                } else {

                    return error(json.msg);
                }

            }, "json");


        });
    }


    if ($('#userSetting').length) {
        $('#userSetting').on('click', function(e) {

            e.preventDefault();
            url = $("#userSetting").attr('href');

            var nickname = $('input[name="nickname"]').val();
            var password = $('input[name="password"]').val();
            var introduce = $('textarea[name="introduce"]').val();
            var sex = $('select[name="sex"] option:selected').val();

            $.post(url, { nickname: nickname, password: password, introduce: introduce, sex: sex }, function(json) {

                if (json.code == 1) {

                    success(json.msg);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);


                } else {

                    return error(json.msg);
                }

            }, "json");


        });
    }




    //



})();


function changingLogin() {

    var val = $(".logintype").val();
    var setval = val == 0 ? 1 : 0;

    $(".logintype").val(setval);
    $("#nickname-login").toggle();
    $("#sms-login").toggle();
    $(".nickname-login").toggle();
    $(".sms-login").toggle();

}