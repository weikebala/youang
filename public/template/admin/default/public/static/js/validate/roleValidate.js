$('#content').validate({
    rules: {
        role_name: {
            required: true,
        },
    },
    messages: {
        role_name: {
            required: "请输入角色组名称",
        },
    },
    // errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },
});