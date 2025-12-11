<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vocabulary Detail</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        h4,
        h5 {
            color: #CF8507;
        }

        .error-message {
            position: absolute;
            bottom: -25px;
            left: 10px;
            font-size: 15px;
            color: red;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-12">
                <header>
                    <h4>Vocabulary Detail</h4>
                </header>
                <hr class="border-black">
            </div><!--header-->
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{-- @yield: display content of section --}}
                @yield('content')
                {{-- or use code below: @section('content') @show --}}
            </div><!--content-->
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr class="border-dark">
                <footer>
                    <p class="font-weight-bold d-flex justify-content-end">
                        &copy; <?php echo date("Y"); ?> Vocabulary
                    </p>
                </footer>
            </div><!--footer-->
        </div>
    </div>
</body>

</html>