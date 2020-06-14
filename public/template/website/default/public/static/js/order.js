function coursePay() {

    event.preventDefault();
    
    url = $("#coursePay").attr('href');
    var payType = $('#payType').val();

    $.post(url, { payType: payType }, function(json) {

        console.log(json)
        if (json.code == 1) {

            if (json.data.mobile == 1) {

                success(json.msg);
                setTimeout(function() {
                    window.open(json.data.src)
                }, 1000);
                // StatisticsPanel

            } else {

                $("#payImage").empty();
                $("#payImage").qrcode(json.data.src);


                var getting = {

                    url: "/pay/payStatus",
                    dataType: 'json',
                    data: { "order_id": json.data.order_id },
                    success: function(res) {
                        if (res.code == 1) {

                            success(json.msg);
                            setTimeout(function() {
                                window.location.href = "/";
                            }, 1000);

                        }
                    }
                };



                var timesRun = 0;
                var interval = setInterval(function() {
                    timesRun += 1; //每刷新一次 timesRun 就+1
                    if (timesRun === 9) {
                        clearInterval(interval);
                    }
                    $.ajax(getting)
                    //这里写你的代码
                }, 3000);

            }

        } else {

            error(json.msg);
            setTimeout(function() {
                window.location.href = "/";
            }, 1000);
        }

    }, "json");


}