<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Do Test</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('dotest.css') }}">
  <script src="{{ asset('dotest.js') }}"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'custom-main': '#00c7e0',
            'custom-dark': '#004c66',
            'custom-light-bg': '#f0f8ff',
            'custom-correct': '#4CAF50',
            'custom-wrong': '#F44336',
            'custom-text': '#333333',
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-50">

  @php
    $vocabIndex = session('vocabIndex', 0);
    $testID = session('testID');
    
    // Lấy thông tin bài test và câu hỏi
    $test = DB::table('test')->where('testID', $testID)->first(); 
    $vocabs = DB::table('vocabulary')->where('testID', $testID)->get();

    // Lấy câu hỏi hiện tại theo index (thay vì lấy cứng câu số 0)
    $currentVocab = $vocabs[$vocabIndex] ?? null;
    $total = $vocabs->count();
    
    // Tính phần trăm
    $progress = ($total > 0) ? (($vocabIndex + 1) / $total) * 100 : 0;
  @endphp

  <form method="POST" action="{{ route('postDoTest') }}">
    @csrf
    <header class="quiz-header-bg p-4 shadow-xl bg-custom-main">
      <div class="max-w-6xl mx-auto flex justify-between items-center text-white">

        <a href="{{ route('create') }}" class="exit-quiz-btn flex items-center space-x-2 text-sm">
          <i class="fas fa-arrow-left"></i>
          <span>Thoát Quiz</span>
        </a>

        <div class="flex items-center space-x-6">
          <div class="flex items-center space-x-3">
            <span id="progress-text" class="text-xl font-bold">{{ $vocabIndex + 1 }}/{{ $total }}</span>
            <div class="w-24 h-2 bg-gray-400 rounded-full">
              <div id="quiz-progress" class="h-full bg-white rounded-full" style="width: {{ $progress }}%;"></div>
            </div>
          </div>

          <h1 class="text-2xl font-extrabold hidden lg:block">{{ $test->title ?? 'Test' }}</h1>
        </div>

        <div class="flex items-center space-x-4">
          <div class="text-right">
            <p class="text-sm opacity-80">Điểm:</p>
            <p id="current-score" class="text-xl font-bold">0</p>
          </div>
          <div class="text-right">
            <p class="text-sm opacity-80">Thời gian:</p>
            <p id="time-left" class="text-xl font-bold text-yellow-300" data-time="{{ $test->timeEachQuestion ?? 60 }}">{{ $test->timeEachQuestion ?? 60 }}</p>
          </div>
        </div>
      </div>
    </header>

    <main class="flex-grow p-4 md:p-8">
      <div class="max-w-6xl mx-auto">

        <div class="bg-white p-6 md:p-10 rounded-xl shadow-lg mb-8">
          <p id="question-number" class="text-sm font-semibold text-custom-main mb-2">Câu hỏi {{ $vocabIndex + 1 }}:</p>
          <h2 class="text-2xl md:text-3xl font-bold text-custom-dark leading-snug">
            {{ $currentVocab ? $currentVocab->question : 'Đang tải...' }}
          </h2>
        </div>
        
        <input type="text" name="answer" class="w-full border p-2 rounded mb-4 text-black" placeholder="Nhập đáp án..." required autocomplete="off" autofocus>
      
        <div class="text-center mt-10">
            <button id="submit-answer-btn" class="px-10 py-3 bg-custom-main text-white font-bold text-lg rounded-full shadow-lg hover:bg-custom-dark transition duration-300 transform scale-100 opacity-100 disabled:opacity-50 disabled:cursor-not-allowed">
            Gửi Đáp án
            </button>
        </div>

      </div>
    </main>

    @if(session('message'))
    <div id="server-result" 
         data-status="{{ session('status') }}" 
         data-message="{{ session('message') }}"
         class="hidden">
    </div>
    @endif

    <div id="feedback-message" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl text-center max-w-sm w-full transform transition-all scale-100">
            
            <i id="feedback-icon" class="text-6xl mb-4"></i>
            
            <h3 id="feedback-title" class="text-2xl font-bold mb-3"></h3>
            
            <p id="feedback-details" class="text-gray-600 mb-6"></p>
            
            <button type="button" id="close-popup-btn" class="px-8 py-2 bg-custom-main text-white font-semibold rounded-full hover:bg-custom-dark transition">
                Câu hỏi Tiếp theo
            </button>
        </div>
    </div>

  </form>
</body>
</html>