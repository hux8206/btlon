<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ADMIN')</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <link rel="stylesheet" href="{{ asset('profile.css') }}">
    
    @yield('css')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-main': '#00c7e0',
                        'custom-dark': '#004c66',
                        'custom-light-bg': '#f0f8ff',
                        'custom-text': '#333333',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        h4, h5 {
            color: #CF8507;
        }
        /* Sidebar Link Active State (Example) */
        .sidebar-link.active {
            background-color: #f0f8ff;
            color: #00c7e0;
            border-right: 4px solid #00c7e0;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <header>
        <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('admin') }}" class="text-2xl font-extrabold text-custom-dark flex items-center no-underline hover:no-underline">
                            <i class="fas fa-book-open mr-2 text-custom-main"></i>ADMIN
                        </a>
                    </div>

                    @auth
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin') }}" class="text-sm font-medium text-gray-600 hover:text-custom-main hidden sm:block no-underline">
                            <i class="fas fa-home"></i> Home
                        </a>
                        
                        <div class="flex items-center space-x-2">
                            <a class="text-sm font-semibold text-custom-dark hidden sm:block no-underline" href="{{ route('profile') }}">
                                {{ auth()->user()->fullName }}
                            </a>
                        </div>
                        
                        <a class="px-4 py-1.5 text-sm font-medium rounded-full text-white bg-custom-main hover:bg-custom-dark transition shadow-md no-underline" href="{{ route('logout') }}">
                            Logout
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid mt-4 flex-grow-1">
        <div class="row">
            
            <div class="col-md-3 col-lg-2 mb-4">
                <div class="bg-white rounded-lg shadow-sm p-3 sticky top-20">
                    <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3">Management</h6>
                    
                    <nav class="flex flex-col space-y-1">
                        <a href="{{ route('admin') }}" class="sidebar-link flex items-center px-3 py-2 text-custom-dark rounded-md hover:bg-custom-light-bg hover:text-custom-main transition duration-150 group no-underline">
                            <i class="fas fa-users w-6 text-center text-gray-400 group-hover:text-custom-main"></i>
                            <span class="font-medium">Users</span>
                        </a>

                        <a href="{{ route('tests') }}" class="sidebar-link flex items-center px-3 py-2 text-custom-dark rounded-md hover:bg-custom-light-bg hover:text-custom-main transition duration-150 group no-underline">
                            <i class="fas fa-clipboard-list w-6 text-center text-gray-400 group-hover:text-custom-main"></i>
                            <span class="font-medium">Tests</span>
                        </a>

                        <a href="{{ route('statisticAdmin') }}" class="sidebar-link flex items-center px-3 py-2 text-custom-dark rounded-md hover:bg-custom-light-bg hover:text-custom-main transition duration-150 group no-underline">
                            <i class="fas fa-chart-line w-6 text-center text-gray-400 group-hover:text-custom-main"></i>
                            <span class="font-medium">Analytics</span>
                        </a>
                    </nav>
                </div>
            </div>

            <div class="col-md-9 col-lg-10">
                
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h1 class="text-2xl font-bold text-custom-dark">
                        @yield('page-title', 'Users list')
                    </h1>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm min-h-[500px]">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @yield('content')
                    
                </div>
            </div></div>
    </div>

    <footer class="mt-auto py-3 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} VOCABIZ. All rights reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>

</html>