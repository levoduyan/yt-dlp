<?php
// Tắt báo lỗi để tránh lỗi hiển thị khi chạy qua CLI
ini_set('display_errors', 0);
error_reporting(0);

// ----------------------------------------------------
// 1. Định nghĩa Cấu hình
// ----------------------------------------------------

// Đường dẫn tuyệt đối đến thư mục downloads (Quan trọng: Dùng đường dẫn tuyệt đối)
$downloads_dir = __DIR__ . '/../downloads/'; 

// Thời gian tối đa cho phép file tồn tại (tính bằng giây).
// Ví dụ: 2 giờ = 7200 giây (60 * 60 * 2)
$max_age_seconds = 600; 

// ----------------------------------------------------
// 2. Thực hiện Dọn dẹp
// ----------------------------------------------------

if (!is_dir($downloads_dir)) {
    // Thư mục không tồn tại, kết thúc script
    exit("Thư mục downloads không tồn tại.");
}

$current_time = time();
$deleted_count = 0;

// Sử dụng glob để tìm tất cả các tệp trong thư mục
$files = glob($downloads_dir . '*');

foreach ($files as $file) {
    if (is_file($file)) {
        // Lấy thời gian lần cuối tệp được thay đổi (modification time)
        $file_mtime = filemtime($file); 

        // Nếu tệp đã cũ hơn thời gian tối đa cho phép
        if ($current_time - $file_mtime > $max_age_seconds) {
            if (unlink($file)) {
                $deleted_count++;
            } else {
                // Ghi lỗi nếu không xóa được (có thể do lỗi quyền)
                error_log("Lỗi: Không thể xóa tệp cũ: " . $file);
            }
        }
    }
}

// Ghi thông báo log cho biết quá trình dọn dẹp đã hoàn tất
error_log("Cron Job Cleanup hoàn tất. Đã xóa: " . $deleted_count . " tệp.");

// Kết thúc script
exit("Đã xóa " . $deleted_count . " tệp.");
?>