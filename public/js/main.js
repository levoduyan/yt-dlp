var domain = window.location.protocol + "//" + window.location.host + "";

$.ajaxSetup({ global: true });
$.ajaxGlobalRunning = false; //Giá trị global để xác định ajax đang chạy, để cấu hình ngăn ko cho các ajax khác chạy

//Khi ajax bắt đầu chạy thì show thành loading lên
$(document).ajaxStart(function () {
    $.ajaxGlobalRunning = true;
    document.getElementById('loadingPage').classList.add('active');
});

//Ajax complete thì ẩn loading
$(document).ajaxComplete(function () {
    document.getElementById('loadingPage').classList.remove('active');
    $.ajaxGlobalRunning = false;
});

//Ajax dừng lại thì cho chạy tiếp lệnh tiếp theo
$(document).ajaxStop(function () {
    $.ajaxGlobalRunning = false;
});

function _doAjaxNod(type_, data_, m_, act_, nod_, global_, doSomeThing) {
    if (data_ == "") {
        data_ = new FormData();
        data_.append("browser", _getBrowserName());
    } else {
        data_.append("browser", _getBrowserName());
    }

    data_.append("csrf_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    $.ajax({
        type: type_,
        url: domain + "/phpjquery/?m=" + m_ + "&act=" + act_ + "&nod=" + nod_,
        data: data_,
        processData: false,
        contentType: false,
        async: true,
        cache: false,
        global: global_,
        success: function (respone) {
            var kq = respone.split("##");
            if (kq.length == 2) {
                debug_ajaxRunning(kq[0]);
                var obj = $.parseJSON(kq["1"]);
                if (obj.status == 200)
                    doSomeThing(obj); //success respone data from server
                else if (obj.status == 401) alert_void(obj.message, 0);
                else if (obj.status == 403) alert_void(obj.message, 0);
                else alert_void(obj.message, 0);
            } else {
                alert_void(respone, 0);
            }
        },
    });
    printLog("doAjax ...");
    return;
}

function _doAjax(type_, data_, m_, act_, global_, doSomeThing) {
  if (data_ == "") {
        data_ = new FormData();
        data_.append("browser", _getBrowserName());
    } else {
        data_.append("browser", _getBrowserName());
    }

    data_.append("csrf_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    $.ajax({
        type: type_, // "GET" hoặc "POST"
        url: domain + "/phpjquery/?m=" + encodeURIComponent(m_) + "&act=" + encodeURIComponent(act_),
        data: data_,
        processData: false,
        contentType: false,
        async: true,
        cache: false,
        global: global_,
        dataType: "text", // đảm bảo respone là chuỗi để split được
        success: function (response) {
            var kq = response.split("##");
            if (kq.length === 2) {
                debug_ajaxRunning(kq[0]);

                // Ở đây bạn viết nhầm kq["1"], nên đổi thành kq[1]
                var obj;
                try {
                    obj = JSON.parse(kq[1]);
                } catch (e) {
                    console.error("JSON parse error:", e);
                    alert_void("Dữ liệu trả về không hợp lệ!");
                    return;
                }

                switch (obj.status) {
                    case 200:
                        doSomeThing(obj);
                        break;
                    case 401:
                        alert_void(obj.message || "Yêu cầu đăng nhập!");
                        break;
                    case 403:
                        alert_void(obj.message || "Truy cập bị từ chối!");
                        break;
                    default:
                        alert_void(obj.message || "Có lỗi xảy ra!");
                        break;
                }
            } else {
                alert_void(response);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert_void("Không thể kết nối máy chủ!");
        }
    });

    printLog("doAjax ...");
    return;
}

function debug_ajaxRunning(kq_0) {
    if (kq_0 != "") {
        if (kq_0 != "" && kq_0 != "done") {
            alert_void(kq_0.substring(0, kq_0.length - 4));
            printLog("debug_ajaxRunning: Ok: " + kq_0);
        }
    }
    printLog("debug_ajaxRunning:" + kq_0);
    return true;
}

function alert_void(_message, _success) {

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 7200,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: _success == 1 ? "success" : "error",
        title: _message
    });
    
    return false;
    
}

function printLog(str) {
    console.log(str);
}

function alert_dialog(message) {
    Swal.fire({
        title: "Thông báo",
        text: message,
        icon: "warning"
    });
}

function _getBrowserName() {
    var nVer = navigator.appVersion;
    var nAgt = navigator.userAgent;
    var browserName = navigator.appName;
    var fullVersion = "" + parseFloat(navigator.appVersion);
    var majorVersion = parseInt(navigator.appVersion, 10);
    var nameOffset, verOffset, ix;

    // In Opera, the true version is after "Opera" or after "Version"
    if ((verOffset = nAgt.indexOf("Opera")) != -1) {
        browserName = "Opera";
        fullVersion = nAgt.substring(verOffset + 6);
        if ((verOffset = nAgt.indexOf("Version")) != -1)
            fullVersion = nAgt.substring(verOffset + 8);
    }
    // In MSIE, the true version is after "MSIE" in userAgent
    else if ((verOffset = nAgt.indexOf("MSIE")) != -1) {
        browserName = "Microsoft Internet Explorer";
        fullVersion = nAgt.substring(verOffset + 5);
    }
    // In Chrome, the true version is after "Chrome"
    else if ((verOffset = nAgt.indexOf("Chrome")) != -1) {
        browserName = "Chrome";
        fullVersion = nAgt.substring(verOffset + 7);
    }
    // In Safari, the true version is after "Safari" or after "Version"
    else if ((verOffset = nAgt.indexOf("Safari")) != -1) {
        browserName = "Safari";
        fullVersion = nAgt.substring(verOffset + 7);
        if ((verOffset = nAgt.indexOf("Version")) != -1)
            fullVersion = nAgt.substring(verOffset + 8);
    }
    // In Firefox, the true version is after "Firefox"
    else if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
        browserName = "Firefox";
        fullVersion = nAgt.substring(verOffset + 8);
    }
    // In most other browsers, "name/version" is at the end of userAgent
    else if (
        (nameOffset = nAgt.lastIndexOf(" ") + 1) <
        (verOffset = nAgt.lastIndexOf("/"))
    ) {
        browserName = nAgt.substring(nameOffset, verOffset);
        fullVersion = nAgt.substring(verOffset + 1);
        if (browserName.toLowerCase() == browserName.toUpperCase()) {
            browserName = navigator.appName;
        }
    }

    return browserName;
}

function _downloadTheLink(_link) {
    Swal.fire({
        title: "Thông báo",
        icon: "info",
        html: 'Click để tải file về: <a class="color_blue" download="" href="' + _link + '"> Click chọn để tải </a>'
    });
}

var randomString = (len) => {
    var text = "";
    var possible = "ABCDEFGHIJKMNLOPQRSTUVWXYZ1234567890";
    for (var i = 0; i < len; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text.toUpperCase();
};
