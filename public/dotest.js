document.addEventListener('DOMContentLoaded', () => {
    // --- 1. LẤY CÁC PHẦN TỬ ---
    const feedbackMessage = document.getElementById('feedback-message'); // Popup tổng
    const feedbackIcon = document.getElementById('feedback-icon');
    const feedbackTitle = document.getElementById('feedback-title');
    const feedbackDetails = document.getElementById('feedback-details');
    const closeBtn = document.getElementById('close-popup-btn'); // Nút "Câu tiếp theo" trong popup
    const serverResult = document.getElementById('server-result'); // Thẻ chứa kết quả từ Laravel
    const timerEl = document.getElementById('time-left');
    const form = document.querySelector('form');
    const inputAnswer = document.querySelector('input[name="answer"]');

    let countdown; // Biến lưu timer

    // --- 2. LOGIC HIỂN THỊ POPUP ---
    function showPopup(status, message) {
        if (!feedbackMessage) return;

        // Reset class
        feedbackMessage.classList.remove('hidden');

        if (status === 'correct') {
            feedbackIcon.className = 'fas fa-check-circle text-6xl mb-4 text-custom-correct';
            feedbackTitle.textContent = 'Chính xác!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-correct';
            // Logic tùy chọn: Phát âm thanh chúc mừng nếu muốn
        } else {
            feedbackIcon.className = 'fas fa-times-circle text-6xl mb-4 text-custom-wrong';
            feedbackTitle.textContent = 'Sai rồi!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-wrong';
        }

        feedbackDetails.textContent = message;
    }

    // --- 3. XỬ LÝ CHUYỂN CÂU (Nút Next) ---
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            // Ẩn popup
            feedbackMessage.classList.add('hidden');

            // Gán giá trị đặc biệt "__next" vào ô input
            if (inputAnswer) {
                inputAnswer.value = "__next";
            }

            // Submit form để Server biết là cần chuyển sang câu index + 1
            form.submit();
        });
    }

    // --- 4. LOGIC CHÍNH (MAIN FLOW) ---

    // TRƯỜNG HỢP A: Đã có kết quả trả về từ Server (Đúng hoặc Sai)
    if (serverResult) {
        const status = serverResult.getAttribute('data-status');
        const message = serverResult.getAttribute('data-message');

        // 1. Hiện Popup
        showPopup(status, message);

        // 2. DỪNG TIMER (Không gọi hàm setInterval)
        // Thời gian hiển thị trên màn hình sẽ đứng yên ở mức cũ (do HTML render lại từ server)
        // Nếu muốn hiển thị 0 hoặc số giây còn lại, server cần truyền lại giá trị đó.
    }

    // TRƯỜNG HỢP B: Chưa có kết quả (Đang làm bài mới)
    else {
        // 1. Focus ô nhập liệu
        if (inputAnswer) inputAnswer.focus();

        // 2. Chạy Timer
        if (timerEl) {
            let time = parseInt(timerEl.getAttribute('data-time')) || 60;
            timerEl.textContent = time;

            countdown = setInterval(() => {
                time--;
                timerEl.textContent = time;

                if (time <= 0) {
                    clearInterval(countdown);
                    // Hết giờ -> Tự động nộp bài (input có thể rỗng)
                    form.submit();
                }
            }, 1000);
        }
    }
});