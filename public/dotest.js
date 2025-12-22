document.addEventListener('DOMContentLoaded', () => {
    // --- 1. LẤY CÁC PHẦN TỬ ---
    const feedbackMessage = document.getElementById('feedback-message'); // Popup tổng
    const feedbackIcon = document.getElementById('feedback-icon');
    const feedbackTitle = document.getElementById('feedback-title');
    const feedbackDetails = document.getElementById('feedback-details');
    
    // Lưu ý: Trong HTML, nút này nên để là <button type="button" ...> để JS hoàn toàn kiểm soát
    const closeBtn = document.getElementById('close-popup-btn'); 
    
    const serverResult = document.getElementById('server-result'); // Thẻ chứa data từ Server
    const timerEl = document.getElementById('time-left');
    const form = document.querySelector('form');
    const inputAnswer = document.querySelector('input[name="answer"]');
    const mainSubmitBtn = document.getElementById('submit-answer-btn'); // Nút gửi bài chính

    let countdown; // Biến lưu timer

    // --- 2. LOGIC HIỂN THỊ POPUP ---
    function showPopup(status, message) {
        if (!feedbackMessage) return;

        // Hiện popup
        feedbackMessage.classList.remove('hidden');

        if (status === 'correct') {
            feedbackIcon.className = 'fas fa-check-circle text-6xl mb-4 text-custom-correct';
            feedbackTitle.textContent = 'Chính xác!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-correct';
        } else {
            feedbackIcon.className = 'fas fa-times-circle text-6xl mb-4 text-custom-wrong';
            feedbackTitle.textContent = 'Sai rồi!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-wrong';
        }

        feedbackDetails.textContent = message;
    }

    // --- 3. XỬ LÝ CHUYỂN CÂU (Khi bấm nút trong Popup) ---
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Ngăn hành vi mặc định nếu lỡ để type="submit"

            // Ẩn popup (tùy chọn, vì form submit sẽ load lại trang ngay)
            feedbackMessage.classList.add('hidden');

            if (inputAnswer) {
                // Xóa bắt buộc nhập để không bị lỗi browser chặn
                inputAnswer.removeAttribute('required');
                // Gán tín hiệu chuyển câu
                inputAnswer.value = "__next";
            }

            // Submit form
            form.submit();
        });
    }

    // --- 4. LOGIC CHÍNH (MAIN FLOW) ---

    // TRƯỜNG HỢP A: Đã có kết quả trả về từ Server (Đúng hoặc Sai)
    // Nghĩa là người dùng vừa nộp bài xong, trang reload lại và hiện kết quả
    if (serverResult) {
        const status = serverResult.getAttribute('data-status');
        const message = serverResult.getAttribute('data-message');

        // 1. Hiện Popup kết quả
        showPopup(status, message);

        // 2. KHÔNG CHẠY TIMER
        // Timer giữ nguyên số hiển thị static từ server (nếu muốn) hoặc ẩn đi.
    }

    // TRƯỜNG HỢP B: Đang làm bài (Chưa có kết quả)
    else {
        // 1. Focus ô nhập liệu để người dùng gõ luôn
        if (inputAnswer) inputAnswer.focus();

        // 2. Chạy Timer
        if (timerEl) {
            let time = parseInt(timerEl.getAttribute('data-time')) || 60;
            timerEl.textContent = time;

            countdown = setInterval(() => {
                time--;
                timerEl.textContent = time;

                // Đổi màu đỏ khi còn ít thời gian (ví dụ < 10s)
                if (time < 10) timerEl.classList.add('text-red-500');

                // KHI HẾT GIỜ
                if (time <= 0) {
                    clearInterval(countdown);
                    timerEl.textContent = "0";

                    // QUAN TRỌNG: Xóa thuộc tính required để form có thể submit rỗng
                    if (inputAnswer) {
                        inputAnswer.removeAttribute('required');
                        // Khóa ô input lại cho đẹp
                        inputAnswer.readOnly = true; 
                    }

                    // Khóa nút submit chính để tránh bấm liên tục
                    if (mainSubmitBtn) {
                        mainSubmitBtn.disabled = true;
                        mainSubmitBtn.textContent = "Đang nộp...";
                    }
                    
                    // Tự động nộp bài
                    form.submit();
                }
            }, 1000);
        }
    }
});