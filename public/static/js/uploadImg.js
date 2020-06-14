function uploadImage(uploadConfig) {
    var uploadConfig = uploadConfig
    // 将base64转换为blob对象
    function dataURItoBlob(base64Data) {
        var byteString;
        if (base64Data.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(base64Data.split(',')[1]);
        else
            byteString = unescape(base64Data.split(',')[1]);

        var mimeString = base64Data.split(',')[0].split(':')[1].split(';')[0];
        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ia], { type: mimeString });
    }

    // 获取上传进度
    function getUploadProgress(e) {
        var myXhr = $.ajaxSettings.xhr();
        if (uploadConfig.isShowProgress && myXhr.upload) {
            try {
                myXhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percent = e.loaded / e.total * 100;
                        uploadConfig.loadProgress(percent)
                    }
                }, false);
            } catch (e) {
                console.log(e);
            }
        }
        return myXhr;
    }

    // 转换成base64，上传
    function uploadImg2Base64() {
        /*调用示例：document.getElementById("img_upload").addEventListener('change', uni.ImgToBase64, false);！！！必须用原生js*/
        var file = this.files[0]
        if (!/image\/\w+/.test(file.type)) {

            $.alert({
                type: 'blue',
                title: '操作提示',
                content: '请确保文件为图像类型',
                icon: 'glyphicon glyphicon-info-sign'
            });
            return false;
        }
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e) {
            var self = this,
                base64String = this.result,
                blob = dataURItoBlob(base64String),
                canvas = document.createElement('canvas'),
                dataURL = canvas.toDataURL('image/jpeg', 0.5),
                fd = new FormData(document.forms[0]);
            // 构建上传参数
            for (var i in uploadConfig.params) {
                fd.append(i, uploadConfig.params[i]);
            }
            fd.append("file", blob, file.name);

            $.ajax({
                url: uploadConfig.url,
                type: 'post',
                data: fd,
                beforeSend: uploadConfig.beforeSend,
                processData: false,
                contentType: false,
                dataType: "json",
                xhr: getUploadProgress,
                success: function(data) {
                    uploadConfig.loadSuccess(data)
                    uploadConfig.base64String(self.result)
                },
                error: function(error) {
                    console.log(error)
                }
            })
            return this.result;
        }
    }
    $('#' + uploadConfig.triggerId).click(function() {
        $('#' + uploadConfig.inputId).trigger('click')
    })
    document.getElementById(uploadConfig.inputId).addEventListener('change', uploadImg2Base64, false);


    // 点击触发触发上传
    // $('#' + uploadConfig.inputId).click()
    // document.getElementById(uploadConfig.inputId).addEventListener('change', uploadImg2Base64, false);
}