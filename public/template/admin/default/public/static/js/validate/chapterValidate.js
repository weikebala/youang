$('#content').validate({
    rules: {
        title: {
            required: true,
        },
        course_id: {
            required: true,
        },
    },
    messages: {
        title: {
            required: "请输入标题",
        },
        course_id: {
            required: "请选择关联课程",
        },
    },
    // errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.col-sm-10').append(error);
    },
    // highlight: function(element, errorClass, validClass) {
    //     $(element).addClass('is-invalid');
    // },
    // unhighlight: function(element, errorClass, validClass) {
    //     $(element).removeClass('is-invalid');
    // }
});
