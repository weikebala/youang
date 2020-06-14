function uploadImg(input, file, path, label) {

    var files = event.target.files[0];
    // 只选择图片文件
    if (!files.type.match('image.*')) {
        error('只能上传图片');
    }
    var reader = new FileReader();
    reader.readAsDataURL(files); // 读取文件
    reader.onload = function(arg) {

        var self = this,
            base64String = this.result,
            blob = dataURItoBlob(base64String),
            canvas = document.createElement('canvas'),
            dataURL = canvas.toDataURL('image/jpeg', 0.5),
            fd = new FormData(document.forms[0]);

        fd.append("file", blob, files.name);


        $.ajax({
            url: "/user/user/avatar",
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                success('上传成功');
                //渲染到页面
                $("#" + path + " img").attr('src', arg.target.result);
                //添加路径到页面
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(error) {
                console.log(error)
            }
        })
        return this.result;

    }
}

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