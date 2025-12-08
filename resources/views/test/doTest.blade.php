<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do Test</title>
    <!-- Tải Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải Font Awesome cho Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Tải font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Cấu hình Tailwind cho màu sắc xanh chủ đạo -->
     <link rel="stylesheet" href="{{ asset('dotest.css') }}">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'custom-main': '#00c7e0', // Tông xanh chủ đạo
              'custom-dark': '#004c66', // Tông xanh đậm
              'custom-light-bg': '#f0f8ff', // Nền xanh nhạt
              'custom-correct': '#4CAF50', // Xanh lá cây
              'custom-wrong': '#F44336', // Đỏ
              'custom-text': '#333333',
            }
          }
        }
      }
    </script>
  </head>
  <body>

    <!-- 1. HEADER - TIẾN TRÌNH VÀ ĐIỂM SỐ -->
    <header class="quiz-header-bg p-4 shadow-xl">
      <div class="max-w-6xl mx-auto flex justify-between items-center text-white">
        
        <!-- Nút Quay lại Khóa học/Thoát -->
        <a href="../index/index.html" class="exit-quiz-btn flex items-center space-x-2 text-sm">
            <i class="fas fa-arrow-left"></i>
            <span>Thoát Quiz</span>
        </a>

        <!-- Thông tin giữa: Tiến trình & Chủ đề -->
        <div class="flex items-center space-x-6">
             <!-- Tiến trình (Progress) -->
            <div class="flex items-center space-x-3">
              <span id="progress-text" class="text-xl font-bold">1/10</span>
              <div class="w-24 h-2 bg-gray-400 rounded-full">
                <div id="quiz-progress" class="h-full bg-custom-main rounded-full" style="width: 10%;"></div>
              </div>
            </div>
            
            <!-- Chủ đề Quiz -->
            <h1 class="text-2xl font-extrabold hidden lg:block">Vocabulary: Academic Words</h1>
        </div>
       
        <!-- Điểm số và Thời gian -->
        <div class="flex items-center space-x-4">
          <div class="text-right">
            <p class="text-sm opacity-80">Điểm:</p>
            <p id="current-score" class="text-xl font-bold">0</p>
          </div>
          <div class="text-right">
            <p class="text-sm opacity-80">Thời gian:</p>
            <p id="time-left" class="text-xl font-bold text-yellow-300">30s</p>
          </div>
        </div>
      </div>
    </header>

    <!-- 2. KHU VỰC CHÍNH CỦA QUIZ -->
    <main class="flex-grow p-4 md:p-8">
      <div class="max-w-6xl mx-auto">
        
        <!-- Khung Câu hỏi -->
        <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg mb-8">
          <p id="question-number" class="text-sm font-semibold text-custom-main mb-2">Câu hỏi Số 1</p>
          <h2 id="question-text-container" class="text-2xl md:text-3xl font-bold text-custom-dark leading-snug">
            <!-- Content will be inserted here by JS -->
          </h2>
        </div>

        <!-- Khu vực Đáp án (Grid) -->
        <div id="answers-container" class="grid answer-grid gap-4 md:gap-6">
          
          <!-- Answer Card 1 -->
          <button data-answer-id="1" class="answer-card bg-white p-5 rounded-xl text-left border-2 border-gray-200 hover:border-custom-main focus:outline-none">
            <span class="text-xl font-semibold text-gray-800"></span>
          </button>
          
          <!-- Answer Card 2 -->
          <button data-answer-id="2" class="answer-card bg-white p-5 rounded-xl text-left border-2 border-gray-200 hover:border-custom-main focus:outline-none">
            <span class="text-xl font-semibold text-gray-800"></span>
          </button>
          
          <!-- Answer Card 3 -->
          <button data-answer-id="3" class="answer-card bg-white p-5 rounded-xl text-left border-2 border-gray-200 hover:border-custom-main focus:outline-none">
            <span class="text-xl font-semibold text-gray-800"></span>
          </button>
          
          <!-- Answer Card 4 -->
          <button data-answer-id="4" class="answer-card bg-white p-5 rounded-xl text-left border-2 border-gray-200 hover:border-custom-main focus:outline-none">
            <span class="text-xl font-semibold text-gray-800"></span>
          </button>

        </div>
        
        <!-- Nút Gửi Đáp án (Chỉ hiện khi có đáp án được chọn) -->
        <div class="text-center mt-10">
            <button id="submit-answer-btn" class="px-10 py-3 bg-custom-main text-white font-bold text-lg rounded-full shadow-lg hover:bg-custom-dark transition duration-300 transform scale-100 opacity-100 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Gửi Đáp án
            </button>
        </div>

      </div>
    </main>
    
    <!-- 3. HỆ THỐNG PHẢN HỒI (Pop-up/Message Box) -->
    <div id="feedback-message" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl text-center max-w-sm w-full transform transition-all duration-500 scale-90">
            <i id="feedback-icon" class="text-6xl mb-4"></i>
            <h3 id="feedback-title" class="text-2xl font-bold mb-3"></h3>
            <p id="feedback-details" class="text-gray-600 mb-6"></p>
            <button id="next-question-btn" class="px-8 py-2 bg-custom-main text-white font-semibold rounded-full hover:bg-custom-dark transition">
                Câu hỏi Tiếp theo
            </button>
        </div>
    </div>


    <!-- JAVASCRIPT ĐỂ XỬ LÝ LƯỢC ĐỒ VÀ PHẢN HỒI -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const answerButtons = document.querySelectorAll('.answer-card');
            const answerSpans = document.querySelectorAll('.answer-card span');
            const submitButton = document.getElementById('submit-answer-btn');
            const feedbackMessage = document.getElementById('feedback-message');
            const feedbackIcon = document.getElementById('feedback-icon');
            const feedbackTitle = document.getElementById('feedback-title');
            const feedbackDetails = document.getElementById('feedback-details');
            const nextQuestionBtn = document.getElementById('next-question-btn');
            const questionTextContainer = document.getElementById('question-text-container');
            const questionNumberElement = document.getElementById('question-number');
            const progressText = document.getElementById('progress-text');
            const quizProgress = document.getElementById('quiz-progress');
            const currentScoreElement = document.getElementById('current-score');
            
            let currentQuestionIndex = 0;
            let selectedAnswer = null;
            let totalScore = 0;

            const questions = [
                {
                    word: "DILIGENT",
                    prompt: "Tìm từ đồng nghĩa phù hợp nhất với từ:",
                    options: ["A. Industrious", "B. Lazy", "C. Ignorant", "D. Careless"],
                    correctId: 1, // Vị trí đáp án đúng (1-4)
                    points: 20
                },
                {
                    word: "EVIDENT",
                    prompt: "Tìm từ trái nghĩa phù hợp nhất với từ:",
                    options: ["A. Obvious", "B. Hidden", "C. Clear", "D. Apparent"],
                    correctId: 2,
                    points: 30
                },
                {
                    word: "COMPREHENSIVE",
                    prompt: "Chọn từ có nghĩa là bao quát/toàn diện:",
                    options: ["A. Narrow", "B. Limited", "C. Exclusive", "D. Extensive"],
                    correctId: 4,
                    points: 25
                }
            ];
            
            const totalQuestions = questions.length;
            
            // Hàm tải câu hỏi
            function loadQuestion(index) {
                if (index >= totalQuestions) {
                    // Xử lý kết thúc Quiz
                    questionTextContainer.innerHTML = `Chúc mừng! Bạn đã hoàn thành Quiz!`;
                    questionNumberElement.textContent = 'Kết thúc';
                    progressText.textContent = `${totalQuestions}/${totalQuestions}`;
                    quizProgress.style.width = '100%';
                    submitButton.style.display = 'none';
                    
                    // Gán href cho nút Quay lại Trang chủ
                    nextQuestionBtn.textContent = 'Quay lại Trang chủ';
                    // Đặt mục tiêu chuyển hướng là trang chủ (ví dụ: '/')
                    nextQuestionBtn.setAttribute('onclick', "window.location.href = '../Profile/profile.html';");
                    
                    feedbackMessage.classList.remove('hidden');
                    feedbackIcon.className = 'text-6xl mb-4 fas fa-trophy text-yellow-500';
                    feedbackTitle.textContent = `Tổng Điểm: ${totalScore}`;
                    feedbackDetails.textContent = `Bạn đã hoàn thành ${totalQuestions} câu hỏi.`;
                    return;
                }
                
                const q = questions[index];
                
                // Reset trạng thái
                answerButtons.forEach(btn => {
                    btn.classList.remove('selected', 'correct', 'wrong');
                    btn.disabled = false;
                });
                selectedAnswer = null;
                submitButton.disabled = true;
                
                // Cập nhật nội dung câu hỏi
                questionNumberElement.textContent = `Câu hỏi Số ${index + 1}`;
                progressText.textContent = `${index + 1}/${totalQuestions}`;
                quizProgress.style.width = `${((index + 1) / totalQuestions) * 100}%`;
                
                questionTextContainer.innerHTML = `${q.prompt} <span class="text-custom-main font-extrabold block mt-2">${q.word}</span>`;
                
                // Cập nhật đáp án
                answerSpans.forEach((span, i) => {
                    span.textContent = q.options[i];
                });

                feedbackMessage.classList.add('hidden');
            }

            // Xử lý khi chọn đáp án
            answerButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Kiểm tra xem đã có đáp án được gửi chưa
                    if (button.disabled) return; 
                    
                    // Loại bỏ lớp 'selected' khỏi tất cả
                    answerButtons.forEach(btn => btn.classList.remove('selected'));
                    
                    // Thêm lớp 'selected' cho nút hiện tại
                    button.classList.add('selected');
                    selectedAnswer = parseInt(button.dataset.answerId);
                    submitButton.disabled = false;
                });
            });

            // Xử lý khi gửi đáp án
            submitButton.addEventListener('click', () => {
                // Tạm thời vô hiệu hóa nút gửi và các nút đáp án
                submitButton.disabled = true;
                answerButtons.forEach(btn => btn.disabled = true);

                const currentQuestion = questions[currentQuestionIndex];
                const isCorrect = selectedAnswer === currentQuestion.correctId;
                const selectedButton = document.querySelector(`.answer-card[data-answer-id="${selectedAnswer}"]`);
                const correctButton = document.querySelector(`.answer-card[data-answer-id="${currentQuestion.correctId}"]`);
                
                // Đánh dấu đáp án được chọn (Đúng/Sai)
                if (isCorrect) {
                    selectedButton.classList.add('correct');
                    totalScore += currentQuestion.points;
                    
                    feedbackIcon.className = 'text-6xl mb-4 fas fa-check-circle text-custom-correct';
                    feedbackTitle.textContent = `Chính xác! (+${currentQuestion.points} điểm)`;
                    feedbackDetails.textContent = 'Bạn đã trả lời đúng. Rất tốt!';
                    currentScoreElement.textContent = totalScore; // Cập nhật điểm
                } else {
                    selectedButton.classList.add('wrong');
                    correctButton.classList.add('correct');
                    
                    feedbackIcon.className = 'text-6xl mb-4 fas fa-times-circle text-custom-wrong';
                    feedbackTitle.textContent = `Sai rồi! (0 điểm)`;
                    feedbackDetails.textContent = `Đáp án đúng là ${correctButton.textContent.trim()}.`;
                }

                // Hiển thị hộp phản hồi
                feedbackMessage.classList.remove('hidden');
            });
            
            // Xử lý chuyển câu hỏi
            nextQuestionBtn.addEventListener('click', () => {
                // Kiểm tra xem đã ở trạng thái kết thúc Quiz chưa
                if (currentQuestionIndex < totalQuestions) {
                    currentQuestionIndex++;
                    loadQuestion(currentQuestionIndex);
                }
            });

            // Bắt đầu Quiz
            loadQuestion(currentQuestionIndex);
        });
    </script>
  </body>
</html>

