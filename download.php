<?php
// Tắt giới hạn thời gian thực thi lệnh (rất quan trọng cho file lớn)
set_time_limit(0); 

// Đường dẫn tương đối đến thư mục chứa các tệp đã tải xuống
$temp_dir = 'downloads/'; 

// ----------------------------------------------------
// 1. Kiểm tra và Xác thực Tên tệp
// ----------------------------------------------------

// Đảm bảo tệp được yêu cầu tồn tại trong URL
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    die("Lỗi: Tên tệp không hợp lệ.");
}

// Chỉ lấy tên tệp thô để ngăn chặn việc truy cập thư mục cha (security fix)
$filename = basename($_GET['file']); 
$filepath = realpath($temp_dir . $filename);

// Kiểm tra bảo mật quan trọng: 
// 1. Tệp phải tồn tại.
// 2. Tệp phải nằm trong thư mục $temp_dir đã định nghĩa (để ngăn chặn Directory Traversal).
if ($filepath === false || strpos($filepath, realpath($temp_dir)) !== 0 || !is_file($filepath)) {
    http_response_code(404);
    die("Lỗi: Tệp không tồn tại hoặc không được phép truy cập.");
}

// ----------------------------------------------------
// 2. Thiết lập Header để Bắt đầu Tải xuống
// ----------------------------------------------------

// Header bắt buộc để trình duyệt hiểu đây là một tệp để tải về
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream'); // Loại MIME chung cho tệp nhị phân
header('Content-Disposition: attachment; filename="' . $filename . '"'); // Tên tệp hiển thị cho người dùng

// Header cho Cache (nên tắt để đảm bảo người dùng nhận được tệp mới)
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Kích thước tệp (rất quan trọng để hiển thị tiến trình tải xuống)
header('Content-Length: ' . filesize($filepath));

// ----------------------------------------------------
// 3. Truyền tải Tệp
// ----------------------------------------------------

// Đọc tệp và truyền nó qua đầu ra HTTP
readfile($filepath);

// ----------------------------------------------------
// 4. Dọn dẹp (Tùy chọn)
// ----------------------------------------------------

// **LƯU Ý:** Việc xóa tệp ngay lập tức có thể gây lỗi nếu quá trình truyền tải
// bị gián đoạn. Thường nên dùng một cron job để xóa các tệp cũ sau vài giờ.
// 
// Nếu bạn muốn xóa ngay:
// unlink($filepath); 

exit;
?>