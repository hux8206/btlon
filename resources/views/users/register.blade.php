  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('register.css') }}">
  </head>

  <body>
    <div
      id="slideBox"
      class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-xl shadow-2xl">
      <div class="topLayer">

        <!--FORM ĐĂNG KÝ (Registration Form) - LEFT -->
        <div class="panel custom-scrollbar">
          <div class="content">
            <h2 class="font-bold text-4xl mb-6" style="color: var(--color-dark-text);">Registration</h2>
            @if (session('message'))
                 <div class="alert alert-success">
                     {{ session('message') }}
                 </div>
            @endif
            <form method="POST" action="{{ route('postRegister') }}" id="form-signup" class="w-full max-w-sm" novalidate>
              <!-- Username Field -->
              @csrf
              <div class="input-group">
                <i class="input-icon fas fa-user"></i>
                <input class="input-field" type="text" name="fullname" placeholder="Fullname" value="{{ old('fullname') }}">
                @error('fullname')
                <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              <!-- Email Field -->
              <div class="input-group">
                <i class="input-icon fas fa-envelope"></i>
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

              <!-- Register Button -->
              <div class="form-element mt-8 w-full">
                <button class="register-btn-form" type="submit" name="signup">Register</button>
              </div>

              <!-- Social Login Divider -->
              <div class="text-center my-6 text-sm text-gray-400 w-full">
                or register with social platforms
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

        <!--PANEL CHÀO MỪNG QUAY LẠI (Welcome Back/Login Prompt) - RIGHT -->
        <div class="panel custom-scrollbar">
          <div class="content">
            <h2 class="font-bold text-5xl mb-2 text-white">Welcome Back!</h2>
            <p class="mb-6 text-white text-opacity-80">Already have an Account?</p>
            <a class="login-btn-prompt" href="{{ route('login') }}">Login</a>
            <p class="mt-4 text-xs text-white text-opacity-60">Log in to continue your journey!</p>
          </div>
        </div>

      </div>
    </div>
  </body>

  </html>