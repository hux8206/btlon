<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('login.css') }}">

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toast = document.getElementById('toast');

      // Nếu tìm thấy thông báo (tức là có lỗi session)
      if (toast) {
        // Đợi 3 giây (3000ms)
        setTimeout(() => {
          // 1. Làm mờ dần (Hiệu ứng fade out)
          toast.classList.add('opacity-0', 'pointer-events-none');

          // 2. Sau khi mờ xong (0.5s) thì xóa hẳn khỏi giao diện
          setTimeout(() => {
            toast.remove();
          }, 500);
        }, 3000);
      }
    });
  </script>
  <div
    id="slideBox"
    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-xl shadow-2xl">
    <div class="topLayer">

      <!--PANEL CHÀO MỪNG (Welcome/Register Prompt) - LEFT -->
      <div class="panel custom-scrollbar">
        <div class="content">
          <h2 class="font-bold text-5xl mb-2 text-white">Hello, Welcome</h2>
          <p class="mb-6 text-white text-opacity-80">Don't have an Account?</p>
          <a class="register-btn-prompt" href="{{ route('register') }}">Register</a>
          <p class="mt-4 text-xs text-white text-opacity-60">Sign up and join the fun!</p>
        </div>
      </div>
      @if(session('unvalid'))
      <div id="toast"
        class="fixed top-6 left-1/2 -translate-x-1/2
         bg-red-500 text-white
         px-6 py-3 rounded-xl shadow-xl
         text-sm z-50 transition-opacity duration-500">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        {{ session('unvalid') }}
      </div>
      @endif

      <!-- FORM ĐĂNG NHẬP (Login Form) - RIGHT -->
      <div class="panel custom-scrollbar">
        <div class="content">
          <h2 class="font-bold text-4xl mb-12" style="color: var(--color-dark-text);">Login</h2>
          <form method="POST" action="{{ route('postLogin') }}" id="form-login" class="w-full max-w-sm" novalidate>
            <!-- Username/Email Field -->
            @csrf
            <div class="input-group">
              <i class="input-icon fas fa-user"></i>
              <input class="input-field" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
              @error('email')
              <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <!-- Password Field -->
            <div class="input-group">
              <i class="input-icon fas fa-lock"></i>
              <input class="input-field" type="password" name="password" placeholder="Password">
              @error('password')
              <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <!-- Forgot Password -->
            <div class="text-right mb-8 w-full max-w-sm">
              <a href="#" class="text-xs text-gray-500 hover:text-gray-700">Forgot Password</a>
            </div>

            <!-- Login Button -->
            <div class="form-element mb-6 w-full">
              <button class="login-btn" type="submit" name="login">Login</button>
            </div>

            <!-- Social Login Divider -->
            <div class="text-center my-6 text-sm text-gray-400 w-full">
              or login with social platforms
            </div>

            <!-- Social Icons -->
            <div class="flex justify-center space-x-4 w-full">
              <a href="#" class="social-icon-btn"><i class="fab fa-google"></i></a>
              <a href="#" class="social-icon-btn"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-icon-btn"><i class="fab fa-github"></i></a>
              <a href="#" class="social-icon-btn"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </body>

</html>