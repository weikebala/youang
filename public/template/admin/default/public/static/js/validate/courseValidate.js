$('#content').validate({
    rules: {
        category_id: {
            required: true,
        },
        title: {
            required: true,
        },
        cource_image_url: {
            cource_image_url: true,
        },
    },
    messages: {
        category_id: {
            required: "请选择分类",
        },
        title: {
            required: "请输入课程标题",
        },
        cource_image_url: {
            required: "请上传图片",
        },
    },
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },
    
});