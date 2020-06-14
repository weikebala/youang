jQuery.validator.addMethod("isMobile", function(value, element) {
    var length = value.length;
    var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "请填写正确的手机号码"); //可以自定义默认提示信息

$('#content').validate({
    rules: {
        nickname: {
            required: true,
        },
        password: {
            required: true,
        },
        mobile: {
            required: true,
            isMobile: true,
        },
        
    },
    messages: {
        nickname: {
            required: "请输入用户昵称",
        },
        password: {
            required: "请输入用户密码",
        },
        mobile: {
            required: "请输入用户手机号",
            isMobile: "您填写的手机号不正确",
        },
    },
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },
});
