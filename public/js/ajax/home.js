var thisPage = {};
$( function ()
{

} );

$(document).on("click", ".btn-download", function(){
    const url = document.getElementById('url').value;
    if (url.trim()) {
        let html_mp4 = '';
        let html_mp3 = '';
        var data = new FormData();
        data.append('url', url);
        _doAjax('POST', data, 'youtube', 'info', true, function(res){
            
            // Cập nhật thông tin video
            $('#title_youtube').html(res.data.title);
            $('#thumbnail_youtube').attr("src",res.data.thumbnail);
            $('#webpage_url').html(res.data.webpage_url);
            $('#duration_string').html(res.data.duration_string);

            // Tạo danh sách định dạng tải xuống
            res.data.formats.forEach(item => {
                if(item.ext == 'mp4' || item.ext == 'webm'){
                    html_mp4 += `<div class="download-item">
                                    <div class="item-info">
                                        <div class="item-format">`+item.resolution+` | `+item.ext+`</div>
                                        <div class="item-details">Kích thước: `+item.size+` </div>
                                    </div>
                                    <button format_id="`+item.format_id+`" class="download-btn">⬇️ Tải Xuống</button>
                                </div>`;
                }else{
                    html_mp3 += `<div class="download-item">
                                    <div class="item-info">
                                        <div class="item-format">`+item.resolution+`</div>
                                        <div class="item-details">Kích thước: `+item.size+` </div>
                                    </div>
                                    <button format_id="`+item.format_id+`" class="download-btn">⬇️ Tải Xuống</button>
                                </div>`;
                }
                
            });
            $('#download_list_mp4').html(html_mp4);
            $('#download_list_mp3').html(html_mp3);

            // Ẩn landing page, hiển thị download page
            document.getElementById('landingPage').classList.add('hidden');
            document.getElementById('downloadPage').classList.add('active');

            // Scroll to top
            window.scrollTo(0, 0);
        });
    }else{
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
            icon: "error",
            title: "Vui lòng nhập URL video YouTube!"
        });
    }
});

$(document).on("click", ".download-btn", function(){
    const url = document.getElementById('url').value;
    const format_id = $(this).attr('format_id');
    if (url.trim() && format_id) {
        var data = new FormData();
        data.append('url', url);
        data.append('format_id', format_id);
        _doAjax('POST', data, 'youtube', 'download', true, function(res){
            Swal.fire({
                title: "Thông báo",
                icon: "info",
                html: 'Click để tải file về: <a class="color_blue" download="" href="' + res.data.download_link + '"> Click chọn để tải </a>',
                confirmButtonText: "Hoàn Tất",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    goBack();
                }
            });
        });
    }else{
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
            icon: "error",
            title: "Vui lòng thử lại!"
        });
    }
});

function goBack() {
    document.getElementById('landingPage').classList.remove('hidden');
    document.getElementById('downloadPage').classList.remove('active');

    document.getElementById('url').value = '';
    window.scrollTo(0, 0);
}

function switchTab(event, tabName) {
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));

    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => tab.classList.remove('active'));

    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}