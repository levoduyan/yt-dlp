
<!-- ===== LANDING PAGE ===== -->
<div class="landing-page" id="landingPage">
    <div class="logo-section">
        <div class="logo-icons">▶️ ⬇️</div>
        <h1>Tải Xuống Video YouTube</h1>
        <p class="subtitle">Trình tải xuống video YouTube tốt nhất thế giới. Dán URL để tải xuống video. Miễn phí, nhanh
            và không có quảng cáo. Không cần đăng nhập.</p>
    </div>

    <div class="input-group">
            <input id="url" name="url" type="text" placeholder="Dán URL video YouTube tại đây..." required>
            <button class="btn-download">TẢI XUỐNG</button>
    </div>

    <div class="features">
        <div class="feature">
            <div class="feature-icon">⚡</div>
            <div class="feature-title">Nhanh Chóng</div>
            <div class="feature-desc">Tải xuống trong vài giây</div>
        </div>
        <div class="feature">
            <div class="feature-icon">🎬</div>
            <div class="feature-title">Đa Định Dạng</div>
            <div class="feature-desc">Video, Audio, HD, 4K</div>
        </div>
        <div class="feature">
            <div class="feature-icon">🔒</div>
            <div class="feature-title">An Toàn</div>
            <div class="feature-desc">Không virus, không malware</div>
        </div>
        <div class="feature">
            <div class="feature-icon">💯</div>
            <div class="feature-title">Miễn Phí</div>
            <div class="feature-desc">Hoàn toàn miễn phí mãi mãi</div>
        </div>
    </div>

    <div class="warning">
        ⚠️ <strong>Lưu ý:</strong> Hãy đảm bảo bạn không vi phạm quyền của người khác khi tải xuống. Không thể tải xuống
        nhạc có bản quyền với công cụ này.
    </div>
</div>

<!-- ===== DOWNLOAD PAGE ===== -->
<div class="download-page" id="downloadPage">
    <div class="container">
        <div class="header">
            <a class="back-link" onclick="goBack()">← Quay lại</a>
            <div class="video-info">
                <div class="video-thumbnail">
                    <img src="{$domain}/public/images/thumbnail_youtube.jpg" id="thumbnail_youtube" alt="Video thumbnail">
                </div>
                <div class="video-details">
                    <h2 id="title_youtube">Tên Video YouTube</h2>
                    <p>Link: <span id="webpage_url"></span></p>
                    <p>Thời lượng: <span id="duration_string"></span></p>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="switchTab(event, 'video')">🎬 Video (MP4)</button>
            <button class="tab" onclick="switchTab(event, 'audio')">🎵 Audio (MP3)</button>
        </div>

        <div class="content">
            <div id="video" class="tab-content active">
                <div class="download-list" id="download_list_mp4">
                    
                </div>
            </div>

            <div id="audio" class="tab-content">
                <div class="download-list" id="download_list_mp3">
                    
                </div>
            </div>

            <div class="info-box">
                ℹ️ <strong>Mẹo:</strong> Chọn chất lượng cao nếu bạn muốn chất lượng tốt nhất, hoặc chất lượng thấp để
                tiết kiệm dung lượng lưu trữ.
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{$domain}/public/js/ajax/home.js?{$version}"></script>