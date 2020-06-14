$('#content').validate({
    rules: {
        url: {
            required: true,
        },
        title: {
            required: true,
        },
    },
    messages: {
        url: {
            required: "请输入路径",
        },
        title: {
            required: "请输入菜单名称",
        },
    },
    // errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },

});