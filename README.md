# YTB AnLVD
Trang web **ytb.anlvd.id.vn** được phát triển để cung cấp công cụ xem, tìm kiếm và quản lý video YouTube một cách nhanh chóng và thân thiện.

## 🎯 Mục đích
- Cho phép người dùng xem, tìm kiếm, hoặc quản lý video từ YouTube thông qua giao diện riêng.
- Cung cấp trải nghiệm đơn giản, thân thiện với người dùng, tương thích với thiết bị di động và desktop.
- Dễ dàng mở rộng và tích hợp thêm các tính năng quản lý nội dung.

## 🚀 Tính năng chính
- Hiển thị danh sách video kèm tiêu đề, thumbnail, mô tả ngắn.
- Tìm kiếm video theo từ khóa.
- Xem video trực tiếp trong trang web.
- Giao diện responsive phù hợp với mọi màn hình.
- (Tùy chọn) Quản lý backend: thêm/bớt video, phân loại video theo chủ đề.

## 🧰 Công nghệ sử dụng
- **Frontend**: HTML5, CSS3, JavaScript (jQuery/AJAX)
- **Backend**: PHP
- **Công cụ xử lý video**: [yt-dlp](https://github.com/yt-dlp/yt-dlp) – dùng để lấy thông tin và liên kết phát video từ YouTube.
- **Web Server**: Apache hoặc Nginx

## ⚙️ Cài đặt & chạy
1. Clone hoặc tải repository:
   ```bash
   git clone https://github.com/levoduyan/yt-dlp.git
   ```
. Cài đặt **yt-dlp** (nếu dùng trên server có Python):
   ```bash
   pip install -U yt-dlp
   ```
3. Chạy server local (XAMPP / Laragon) hoặc upload lên host.
4. Truy cập `http://localhost/ytb-anlvd/` hoặc `https://ytb.anlvd.id.vn/` để xem.

## 💡 Hướng dẫn sử dụng
- Nhập từ khóa để tìm video → nhấn "Tìm kiếm".
- Nhấn thumbnail để xem video trực tiếp.
- (Nếu có) đăng nhập trang quản trị để thêm/sửa video.

## 🧩 Cấu trúc thư mục
```
config.php        # file cấu hình chung
/public           # HTML, CSS, JS, IMG
/phpjquery        # code PHP xử lý backend
```

## 🤝 Đóng góp
Nếu bạn muốn đóng góp, vui lòng tạo **Pull Request** hoặc **Issue** trong repository.
Quy tắc:
- Ghi rõ nội dung commit.
- Viết code rõ ràng, tuân thủ chuẩn PSR.
- Kiểm tra kỹ trước khi gửi PR.

## 📄 Bản quyền
© 2025 Lê Võ Duy An. Mọi quyền được bảo lưu.
Bạn được phép sử dụng và chỉnh sửa mã nguồn này với điều kiện ghi rõ nguồn gốc.
