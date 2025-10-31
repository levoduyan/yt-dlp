<?php
header('Content-Type: application/json');
// Tắt giới hạn thời gian thực thi lệnh (rất quan trọng cho file lớn)
set_time_limit(0);

// ********** THAY ĐỔI ĐƯỜNG DẪN NÀY **********
// const YT_DLP_PATH = 'export TMPDIR=~/tmp && /home/kumntbzh/ytb.anlvd.id.vn/downloader/bin/yt-dlp -f "bestvideo[height=1080][ext=mp4]+bestaudio[ext=m4a]/best[height=1080][ext=mp4],bestvideo[height=720][ext=mp4]+bestaudio[ext=m4a]/best[height=720][ext=mp4],bestvideo[height=480][ext=mp4]+bestaudio[ext=m4a]/best[height=480][ext=mp4]" ';
const YT_DLP_PATH = 'export TMPDIR=~/tmp && /home/kumntbzh/ytb.anlvd.id.vn/downloader/bin/yt-dlp ';
// *******************************************
const FFMPEG_PATH = '/home/kumntbzh/ffmpeg/ffmpeg'; // Đường dẫn đến ffmpeg, nếu có
// *******************************************

$nod = $main->get('nod');

if ($act == 'info') {
    $youtube_url = $main->post('url');

    if (isset($youtube_url) || !empty($youtube_url)) {
        if (strpos($youtube_url, 'youtube.com') || strpos($youtube_url, 'youtu.be')) {

            // Escaping shell arguments để ngăn chặn RCE (Remote Code Execution)
            $safe_url = escapeshellarg($youtube_url);

            $ffmpeg_path_escaped = escapeshellarg(FFMPEG_PATH);
            $ffmpeg_option = FFMPEG_PATH ? "--ffmpeg-location $ffmpeg_path_escaped" : "";

            // Lệnh gọi yt-dlp để in ra định dạng JSON thông tin video
            // -j: output JSON
            // --flat-playlist: cần thiết để xử lý URL playlist
            $command = YT_DLP_PATH . " $ffmpeg_option -j --flat-playlist $safe_url 2>&1";

            $output = null;
            $return_var = null;
            exec($command, $output, $return_var);
            // print_r($output);
            // exit;

            if ($return_var === 0) {
                $json_data = json_decode(end($output), true); // Giả sử dòng thứ 8 chứa JSON (cần kiểm tra kỹ)
                // print_r($json_data);
                // exit;

                if (empty($json_data['formats'])) {
                    echo 'done##', $main->toJsonData(403, 'Không tìm thấy định dạng video hợp lệ.', null);
                } else {

                    $formats = [];
                    // foreach ($json_data['formats'] as $f) {
                    //     // Lọc theo phần mở rộng chỉ lấy mp4 hoặc m4a
                    //     if (!in_array($f['ext'], ['mp4', 'm4a'])) {
                    //         continue;
                    //     }

                    //     // Kiểm tra có kích thước (filesize hoặc filesize_approx)
                    //     if (isset($f['filesize']) && $f['filesize'] > 0) {
                    //         $size_bytes = $f['filesize'];
                    //     } elseif (isset($f['filesize_approx']) && $f['filesize_approx'] > 0) {
                    //         $size_bytes = $f['filesize_approx'];
                    //     } elseif (isset($f['size_bytes']) && $f['size_bytes'] > 0) {
                    //         $size_bytes = $f['size_bytes'];
                    //     } else {
                    //         continue;
                    //     }
                    //     // Xác định resolution
                    //     $resolution = $f['height'] ? ($f['height'] . 'p') : ($f['vcodec'] === 'none' ? 'Audio Only' : 'Unknown');

                    //     // Hàm chuyển đổi bytes sang MB/GB
                    //     $display_size = function ($bytes) {
                    //         if ($bytes >= 1073741824) {
                    //             return number_format($bytes / 1073741824, 2) . ' GB';
                    //         } elseif ($bytes >= 1048576) {
                    //             return number_format($bytes / 1048576, 2) . ' MB';
                    //         } else {
                    //             return number_format($bytes / 1024, 2) . ' KB';
                    //         }
                    //     };

                    //     // Nếu cùng resolution thì chỉ giữ bản có size lớn nhất
                    //     if (!isset($formats[$resolution]) || $size_bytes > $formats[$resolution]['size_bytes']) {
                    //         $formats[$resolution] = [
                    //             'format_id' => $f['format_id'],
                    //             'ext' => $f['ext'],
                    //             'resolution' => $resolution,
                    //             'size' => $display_size($size_bytes),
                    //             'size_bytes' => $size_bytes,
                    //             'note' => $f['format_note'] ?? ($f['note'] ?? ''),
                    //         ];
                    //     }
                    // }

                    foreach ($json_data['formats'] as $f) { 
                        // Lọc ra các định dạng hữu ích có chứa ước tính kích thước (filesize) 
                        if (isset($f['filesize']) && $f['filesize'] > 0 || isset($f['filesize_approx']) && $f['filesize_approx'] > 0) {
                            $size_bytes = $f['filesize'] ?? $f['filesize_approx']; 
                            // Hàm chuyển đổi bytes sang MB/GB 
                            $display_size = function ($bytes) { 
                                if ($bytes >= 1073741824) { 
                                    return number_format($bytes / 1073741824, 2) . ' GB'; 
                                } elseif ($bytes >= 1048576) { 
                                    return number_format($bytes / 1048576, 2) . ' MB'; 
                                } else { 
                                    return number_format($bytes / 1024, 2) . ' KB'; 
                                }
                            };
                            
                            $formats[] = [ 'format_id' => $f['format_id'], 
                            'ext' => $f['ext'], 
                            'resolution' => $f['height'] ? ($f['height'] . 'p') : ($f['vcodec'] === 'none' ? 'Audio Only' : 'Unknown'),
                            'size' => $display_size($size_bytes),
                            'size_bytes' => $size_bytes, 
                            // Giữ lại giá trị bytes để sắp xếp 
                            'note' => $f['format_note'] ?? '' ]; 
                        } 
                    }

                    // Sắp xếp các định dạng theo kích thước (để dễ hiển thị cho người dùng)
                    usort($formats, fn($a, $b) => $b['size_bytes'] <=> $a['size_bytes']);

                    $result = [
                        'title' => $json_data['title'] ?? 'Video Title',
                        'formats' => $formats,
                        'thumbnail' => $json_data['thumbnail'] ?? '',
                        'webpage_url' => $json_data['webpage_url'] ?? $youtube_url,
                        'duration_string' => $json_data['duration_string'] ?? ''
                    ];

                    echo 'done##', $main->toJsonData(200, 'success', $result);
                }
            } else {
                echo 'done##', $main->toJsonData(403, 'Lỗi máy chủ khi lấy thông tin video.', null);
            }
        } else {
            echo 'done##', $main->toJsonData(403, 'URL không hợp lệ. Chỉ hỗ trợ YouTube.', null);
        }
    } else {
        echo 'done##', $main->toJsonData(403, 'Vui lòng cung cấp URL video.', null);
    }
} else if ($act == 'download') {
    $format_id   = $main->post('format_id');
    $youtube_url = $main->post('url');

    if (!isset($format_id)) {
        echo 'done##', $main->toJsonData(403, 'Thiếu format_id để tải xuống.', null);
    } else {

        // --- 1. Thiết lập Thư mục Tạm thời ---
        $temp_dir = __DIR__ . '/../downloads/';
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }

        // --- 2. Chuẩn bị Biến và Bảo mật ---
        // Escaping shell arguments để ngăn chặn RCE (Remote Code Execution)
        $safe_format_id = escapeshellarg($format_id);
        $safe_url = escapeshellarg($youtube_url);

        // Sử dụng file_id làm tiền tố TẠM THỜI để dễ dàng tìm kiếm tệp sau khi tải xong.
        // yt-dlp sẽ tự động xóa tiền tố này khỏi tên tệp cuối cùng.
        $file_prefix = uniqid();

        // Mẫu tên tệp đầu ra: Chỉ sử dụng %(title)s.%(ext)s, nhưng thêm tiền tố tạm thời
        // để giúp hàm glob() tìm kiếm sau này.
        // **LƯU Ý QUAN TRỌNG:** yt-dlp sẽ tự động làm sạch và bỏ qua các ký tự không hợp lệ trong title.
        $output_template = $temp_dir . $file_prefix . '_%(title)s.%(ext)s';
        $safe_output = escapeshellarg($output_template);

        // --- 3. Thực thi Lệnh Tải xuống ---
        // Ví dụ: Giả sử họ cung cấp đường dẫn 
        $ffmpeg_path_escaped = escapeshellarg(FFMPEG_PATH);
        $ffmpeg_option = FFMPEG_PATH ? "--ffmpeg-location $ffmpeg_path_escaped" : "";

        // Buộc FFmpeg sử dụng audio M4A (AAC) và video MP4
        $target_format = "bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]";

        // Thêm --recode-video mp4 để FFmpeg đảm bảo tệp đầu ra là MP4
        $recode_option = "--recode-video mp4";

        // Lệnh gọi yt-dlp để tải xuống với format_id cụ thể
        // $command = YT_DLP_PATH . " -f $safe_format_id -o $safe_output $safe_url 2>&1";
        $command = YT_DLP_PATH . " $ffmpeg_option -f $safe_format_id -o $safe_output $safe_url 2>&1";
        // $command = YT_DLP_PATH . " $ffmpeg_option $recode_option -f $target_format -o $safe_output $safe_url 2>&1";

        $output = null;
        $return_var = null;
        exec($command, $output, $return_var);

        // --- 4. Xử lý Kết quả ---
        if ($return_var === 0) {
            // Tải xuống thành công, tìm tên file thực tế
            // Tệp thực tế sẽ bắt đầu bằng $file_prefix đã thêm vào
            $actual_files = glob($temp_dir . $file_prefix . '_*');

            if (count($actual_files) > 0) {
                $filename = basename($actual_files[0]);
                $download_link = 'download.php?file=' . $filename;

                $result = [
                    'download_link' => $download_link,
                    // Để tên file chỉ là tên video, chúng ta loại bỏ tiền tố tạm thời khỏi $filename
                    // Tên file mà người dùng thấy sẽ là phần sau tiền tố và dấu gạch dưới.
                    'filename' => substr($filename, strlen($file_prefix) + 1)
                ];

                echo 'done##', $main->toJsonData(200, 'success', $result);
            } else {

                echo 'done##', $main->toJsonData(403, 'Lỗi: Không tìm thấy tệp đã tải xuống.', null);
            }
        } else {
            // Lỗi khi thực thi lệnh
            error_log("YT_DLP/FFmpeg Critical Error: " . implode("\n", $output));
            echo 'done##', $main->toJsonData(403, 'Lỗi máy chủ khi tải video. Vui lòng thử lại.', $output);
        }
    }
} else {
    echo "Lỗi: Không tìm thấy hành động phù hợp!";
}
