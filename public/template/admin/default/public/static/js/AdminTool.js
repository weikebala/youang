;
(function() {
    //全局ajax处理
    $.ajaxSetup({
        complete: function(jqXHR) {},
        data: {},
        error: function(jqXHR, textStatus, errorThrown) {
            //请求失败处理
        }
    });

    if ($.browser && $.browser.msie) {
        //ie 都不缓存
        $.ajaxSetup({
            cache: false
        });
    }


    layui.use(['layer', 'form'], function() {
        var layer = layui.layer


    });

    //所有的添加操作，添加数据后刷新页面
    if ($('.btn-add').length) {
        $('.btn-add').on('click', function(e) {

            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');

            var width = '800px';
            var height = '600px';
            if (href.indexOf("biglayer") >= 0) {
                width = '1000px';
                height = '800px';
            }

            layer.open({
                type: 2,
                content: href,
                area: [width, height],
                title: $this.attr("title"),
                resize: false,
                zIndex: 0, //层优先级
                btn: ['确认', '取消'],


                success: function(layero, index) {},
                // 确定的操作
                yes: function(index, layero) {


                    var iframeWin = parent.parent.window[layero.find('iframe')[0]['name']]; // 重点0
                    var validate = iframeWin.myValidate();
                    var formData = iframeWin.$("form").serialize();
                    var data_params = decodeURIComponent(formData, true);


                    // getToPost(data_params);return;

                    //
                    if (validate) {

                        $.post(href, (data_params), function(data) {

                            if (data.code == 1) {

                                success(data.msg)

                                setTimeout(function() {
                                    layer.close(index);
                                    window.location.reload();
                                }, 1500);

                            } else {

                                error(data.msg)


                            }

                        });
                    }


                },
                cancel: function(index, layero) {
                    // 取消的操作
                }

            });

        });
    }

    function getToPost(str) {

        var arr = str.split('&');
        var obj = {};
        for (var item of arr) {
            console.log(item);
            var keyarr = item.split('=');
            obj[keyarr[0]] = keyarr[1];
        }

        console.log(obj);
        return;

        return obj;
    }

    //所有的编辑操作,提交数据后关闭页面


    if ($('.btn-edit').length) {
        $('.btn-edit').on('click', function(e) {

            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');

            var width = '800px';
            var height = '600px';
            if (href.indexOf("biglayer") >= 0) {
                width = '1000px';
                height = '800px';
            }
            // console.log(window.location.origin+href);
            layer.open({
                type: 2,
                content: href,
                area: [width, height],
                title: $this.attr("title"),
                resize: false,
                zIndex: 0, //层优先级
                btn: ['确认', '取消'],


                success: function(layero, index) {},
                // 确定的操作
                yes: function(index, layero) {

                    var iframeWin = parent.parent.window[layero.find('iframe')[0]['name']]; // 重点0
                    var validate = iframeWin.myValidate();
                    var formData = iframeWin.$("form").serialize();
                    var data_params = decodeURIComponent(formData, true);
                    // getToPost(data_params);return;
                    //
                    if (validate) {

                        $.post(href, (data_params), function(data) {

                            if (data.code == 1) {

                                success(data.msg)


                                setTimeout(function() {
                                    layer.close(index);
                                    window.location.reload();
                                }, 1500);

                            } else {

                                error(data.msg)


                            }

                        });
                    }
                },
                cancel: function(index, layero) {
                    // 取消的操作
                }

            });

        });
    }

    //权限选择
    if ($('.btn-auth').length) {
        $('.btn-auth').on('click', function(e) {

            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');

            layer.open({
                type: 2,
                content: href,
                area: ['480px', '600px'],
                title: $this.attr("title"),
                resize: false,
                zIndex: 0, //层优先级
                btn: ['确认', '取消'],


                success: function(layero, index) {},
                // 确定的操作
                yes: function(index, layero) {

                    var iframeWin = parent.parent.window[layero.find('iframe')[0]['name']]; // 重点0
                    var formData = iframeWin.$("form").serialize();
                    var data_params = decodeURIComponent(formData, true);

                    $.post(href, (data_params), function(data) {

                        if (data.code == 1) {

                            success(data.msg)

                            setTimeout(function() {
                                layer.close(index);
                                window.location.reload();
                            }, 1500);

                        } else {

                            error(data.msg)

                        }

                    });

                },
                cancel: function(index, layero) {
                    // 取消的操作
                }

            });

        });
    }




    //所有的删除操作，删除数据后刷新页面
    if ($('.btn-dialog').length) {

        $('.btn-dialog').on('click', function(e) {
            e.preventDefault();
            var $_this = this,
                $this = $($_this),
                href = $this.data('href'),
                refresh = $this.data('refresh'),
                msg = $this.data('msg');
            href = href ? href : $this.attr('href');

            console.log(msg);

            $.confirm({
                title: '确认!',
                content: '是否' + msg + '？',
                type: 'red',
                typeAnimated: true,
                closeIcon: true,
                buttons: {

                    ok: {
                        text: '确认', // 按钮文字
                        btnClass: 'btn-blue',
                        action: function() {

                            $.getJSON(href).done(function(data) {

                                if (data.code == '1') {
                                    success(data.msg)



                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);

                                } else if (data.code == '0') {
                                    error(data.msg)


                                }
                            });
                        }
                    },
                    cancel: {
                        text: '取消',
                        btnClass: 'btn-warning',
                        action: function() {
                            // $.alert('点击取消');
                        }
                    }

                }
            });

        });
    }

    //刷新
    if ($('.btn-refresh').length) {

        $('.btn-refresh').on('click', function(e) {
            window.location.reload()
            $(".btn-refresh .fa-sync-alt").addClass("fa-spin");
        });
    }



    /*复选框全选(支持多个，纵横双控全选)。
     *实例：版块编辑-权限相关（双控），验证机制-验证策略（单控）
     *说明：
     *  "js-check"的"data-xid"对应其左侧"js-check-all"的"data-checklist"；
     *  "js-check"的"data-yid"对应其上方"js-check-all"的"data-checklist"；
     *  全选框的"data-direction"代表其控制的全选方向(x或y)；
     *  "js-check-wrap"同一块全选操作区域的父标签class，多个调用考虑
     */

    if ($('.js-check-wrap').length) {
        var total_check_all = $('input.js-check-all');

        //遍历所有全选框
        $.each(total_check_all, function() {
            var check_all = $(this),
                check_items;

            //分组各纵横项
            var check_all_direction = check_all.data('direction');
            check_items = $('input.js-check[data-' + check_all_direction + 'id="' + check_all.data('checklist') + '"]').not(":disabled");

            //点击全选框
            check_all.change(function(e) {
                var check_wrap = check_all.parents('.js-check-wrap'); //当前操作区域所有复选框的父标签（重用考虑）

                if ($(this).prop('checked')) {
                    //全选状态
                    check_items.prop('checked', true);

                    //所有项都被选中
                    if (check_wrap.find('input.js-check').length === check_wrap.find('input.js-check:checked').length) {
                        check_wrap.find(total_check_all).prop('checked', true);
                    }

                } else {
                    //非全选状态
                    check_items.removeProp('checked');

                    check_wrap.find(total_check_all).removeProp('checked');

                    //另一方向的全选框取消全选状态
                    var direction_invert = check_all_direction === 'x' ? 'y' : 'x';
                    check_wrap.find($('input.js-check-all[data-direction="' + direction_invert + '"]')).removeProp('checked');
                }

            });

            //点击非全选时判断是否全部勾选
            check_items.change(function() {

                if ($(this).prop('checked')) {

                    if (check_items.filter(':checked').length === check_items.length) {
                        //已选择和未选择的复选框数相等
                        check_all.prop('checked', true);
                    }

                } else {
                    check_all.removeProp('checked');
                }

            });


        });

    }


})();

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

/**
 * 页面跳转
 * @param url 要打开的页面地址
 */
function redirect(url) {
    location.href = url;
}

/**
 * 读取cookie
 * @param name
 * @returns
 */
function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

/**
 * 设置cookie
 */
function setCookie(name, value, options) {
    options = options || {};
    if (value === null) {
        value = '';
        options.expires = -1;
    }
    var expires = '';
    if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
        var date;
        if (typeof options.expires == 'number') {
            date = new Date();
            date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
        } else {
            date = options.expires;
        }
        expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
    }
    var path = options.path ? '; path=' + options.path : '';
    var domain = options.domain ? '; domain=' + options.domain : '';
    var secure = options.secure ? '; secure' : '';
    document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
}


$(function() {

    $('#reservation').daterangepicker({
        // autoUpdateInput: true,
        // timePicker: true,
        // timePickerIncrement: 30,
        startDate: moment(), //表示今天的基础上加一天
        // endDate
        locale: {
            applyLabel: '确定',
            cancelLabel: '取消',
            format: 'YYYY/MM/DD',
            // fromLabel: '2020/01/01',
            // toLabel: '2029/01/01',
            daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            firstDay: 1
        }
    })

    $('#reservation').val('')

})

//统一验证器
function myValidate() {
    var content = $("form").attr("id");
    var valid = $("#" + content).valid();
    return valid;
}

$(function() {


    var has = $('select').hasClass("select2bs4");
    if (has) {

        //Initialize Select2 Elements
        $('.select2').select2()

        // Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        $(".select2").change(function() {
            select_ops = $(".select2").val();
            console.log(select_ops);
        });
    }


})



function formLogin() {

    event.preventDefault();
    href = $("#formLogin").attr('href');

    var username = $('input[name="username"]').val();
    var verifycode = $('input[name="verifycode"]').val();
    var password = $('input[name="password"]').val();
    var token = $('input[name="__token__"]').val();

    $.post(href, { username: username, password: password, __token__: token, verifycode: verifycode }, function(json) {
        if (json.code == 1) {

            success(json.msg)
            setTimeout(function() {
                // window.location.reload();
                window.location.href = "/admin"
            }, 1000);


        } else {
            error(json.msg)


        }

    }, "json");


}


$("#sign-out").click(function() {

    event.preventDefault();
    href = $("#sign-out").attr('href');

    $.post(href, function(json) {

        if (json.code == 1) {
            success(json.msg)
            setTimeout(function() {
                window.location.href = "/admin/login/login"
            }, 1000);

        } else {
            error(json.msg)
        }


    });

});



function uploadFile(input, file, path, label) {

    var files = event.target.files[0];

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
            url: "/admin/File/uploadFile",
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {

                if (data.code == 1) {

                    success(data.msg);

                    $('#' + file).val('');
                    //渲染到页面
                    $("#" + path + " img").attr('src', arg.target.result);
                    //添加路径到页面
                    console.log(data)
                    $("#" + input).val(data.data.path);
                    //更改文案
                    $("#" + label).html('重新上传');

                } else {
                    error(data.msg);
                }

            },
            error: function(error) {
                console.log(error)
            }
        })
        return this.result;

    }
}



function uploadVideo(input, file, path, label) {

    // alert(123123);
    var files = event.target.files[0];
    // 只选择图片文件
    if (!files.type.match('video/mp4')) {
        error('只能上传视频');
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
            url: "/admin/File/uploadVideo",
            type: 'post',
            data: fd,
            processData: false,
            beforeSend: function() {
                var index = layer.load(1, {
                    shade: [0.1, '#fff'] //0.1透明度的白色背景
                });
            },
            contentType: false,
            dataType: "json",
            success: function(data) {



                if (data.code == 1) {

                    success(data.msg);
                    $('#' + file).val('');
                    //渲染到页面
                    $("#" + path + " img").attr('src', arg.target.result);
                    //添加路径到页面
                    $("#" + input).val(data.data.path);
                    //更改文案
                    $("#" + label).html('重新上传');

                } else {
                    error(data.msg);
                }



            },
            complete: function() {
                layer.closeAll('loading');
            },
            error: function(error) {
                console.log(error)
            }
        })
        return this.result;

    }
}

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
            url: "/admin/File/imageUpload",
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {

                if (data.errno == 0) {

                    success(data.msg);
                    //渲染到页面
                    $("#" + path + " img").attr('src', arg.target.result);
                    //添加路径到页面
                    $("#" + input).val(data.path);
                    //更改文案
                    $("#" + label).html('重新上传');
                } else {
                    error(data.msg);
                }

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