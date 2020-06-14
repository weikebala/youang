$('#content').validate({
    rules: {
        title: {
            required: true,
        },
    },
    messages: {
        title: {
            required: "请输入分类名称",
        }
    },
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },

});