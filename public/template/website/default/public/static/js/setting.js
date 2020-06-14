if ($('#bandMobile').length) {
    $('#bandMobile').on('click', function(e) {


        e.preventDefault();

        var url = $("#bandMobile").attr('href');

        console.log(url);

        $.confirm({
            title: '绑定手机号!',
            content: $("#bandMobileForm").html(),
            type: 'orange',
            typeAnimated: true,
            buttons: {
                formSubmit: {
                    text: '确定',
                    btnClass: 'btn-blue',
                    type: 'red',
                    typeAnimated: true,

                    action: function() {
                        var mobile = this.$content.find('.mobile').val();
                        var smscode = this.$content.find('.smscode').val();

                        if (!mobile || !smscode) {
                            $.alert('手机号/验证码不可为空');
                            return false;
                        }

                        $.post(url, { mobile: mobile, smscode: smscode }, function(json) {

                            if (json.code == 1) {

                                $.alert(json.msg);

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);


                            } else {

                                $.alert(json.msg);
                                return false;
                            }

                        }, "json");


                    }
                },
                取消: function() {},
            },
            onContentReady: function() {
                var jc = this;
                this.$content.find('form').on('submit', function(e) {
                    e.preventDefault();
                    jc.$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });








        // e.preventDefault();




        // url = $("#bandMobile").attr('href');
        // var nickname = $('input[name="nickname"]').val();
        // var password = $('input[name="password"]').val();
        // var repassword = $('input[name="repassword"]').val();
        // var clause = $("input[type='checkbox']").is(':checked');

        // if (!clause) {
        //     return error('请勾选使用条款');
        // }

        // $.post(url, { nickname: nickname, password: password, repassword: repassword, clause: clause }, function(json) {

        //     if (json.code == 1) {

        //         success(json.msg);
        //         // $('meta[name="csrf-token"]').val(json.data.token);
        //         // $('meta[name="csrf-token"]').val('8888888888');
        //         setTimeout(function() {
        //             window.location.href = "/"
        //         }, 1000);


        //     } else {

        //         return error(json.msg);
        //     }

        // }, "json");
    });
}


function sendSms() {


    var mobile = $(".jconfirm-content #sms-login .form-group input").val();
    var url = '/user/login/getSmsCode';

    //验证手机号
    switch (CheckMobile(mobile)) {
        case 0:
            $.alert('手机号不能为空...');

            break;
        case 2:
            $.alert('请输入正确的手机号...');

            break;
        default:
    }

    //请求短信接口
    $.post(url, { mobile: mobile, __token__: $('[name="__token__"]').val() }, function(data) {
        if (data.code == 1) {
            //展示文案
            $('[name="__token__"]').val(data.data.token)
            $.alert(data.msg);

            //todo
        } else {

            $('[name="__token__"]').val(data.data.token)
            $.alert(data.msg);

        }
    });

    //更改点击状态
    $(".jconfirm-content #basic-addon").addClass('sms-second');

    //验证码定时器
    timesRun = 30;
    $(".jconfirm-content #basic-addon").text("剩余" + timesRun + 's');
    var interval = setInterval(function() {
        timesRun -= 1;
        $(".jconfirm-content #basic-addon").text("剩余" + timesRun + 's');
        if (timesRun === 0) {
            $(".jconfirm-content #basic-addon").text('重新获取');
            $(".jconfirm-content #basic-addon").removeClass('sms-second');
            clearInterval(interval);
        }
    }, 1000);

}