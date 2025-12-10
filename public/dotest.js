document.addEventListener('DOMContentLoaded', () => {
    // 1. Tìm các phần tử trong HTML của bạn
    const feedbackMessage = document.getElementById('feedback-message');
    const feedbackIcon = document.getElementById('feedback-icon');
    const feedbackTitle = document.getElementById('feedback-title');
    const feedbackDetails = document.getElementById('feedback-details');
    const closeBtn = document.getElementById('close-popup-btn');
    const serverResult = document.getElementById('server-result'); // Hộp chứa kết quả từ Laravel

    // 2. Logic Hiển thị Popup
    // Nếu Laravel có gửi kết quả về (thẻ server-result tồn tại)
    if (serverResult && feedbackMessage) {
        
        const status = serverResult.getAttribute('data-status');
        const message = serverResult.getAttribute('data-message');

        // Setup giao diện Đúng/Sai
        if (status === 'correct') {
            feedbackIcon.className = 'fas fa-check-circle text-6xl mb-4 text-custom-correct';
            feedbackTitle.textContent = 'Chính xác!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-correct';
        } 
        if (status === 'wrong'){
            feedbackIcon.className = 'fas fa-times-circle text-6xl mb-4 text-custom-wrong';
            feedbackTitle.textContent = 'Sai rồi!';
            feedbackTitle.className = 'text-2xl font-bold mb-3 text-custom-wrong';
        }

        // Điền nội dung
        feedbackDetails.textContent = message;

        // BẬT POPUP LÊN (Xóa class hidden)
        feedbackMessage.classList.remove('hidden');
    }

    // 3. Logic đóng Popup khi bấm nút
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            // Ẩn Popup đi
            feedbackMessage.classList.add('hidden');
            
            // Focus vào ô nhập liệu để làm câu tiếp theo luôn
            const input = document.querySelector('input[name="answer"]');
            if(input) input.focus();
        });
    }

    // 4. Logic Đếm ngược thời gian (Timer) - Giữ nguyên logic đơn giản
    const timerEl = document.getElementById('time-left');
    if(timerEl) {
        let time = parseInt(timerEl.getAttribute('data-time')) || 60;
        const countdown = setInterval(() => {
            time--;
            timerEl.textContent = time;
            if(time <= 0) {
                clearInterval(countdown);
                document.querySelector('form').submit(); // Hết giờ tự nộp
            }
        }, 1000);
    }
});