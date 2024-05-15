<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- JavaScript initialization for Bootstrap dropdown
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script> -->

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pharmacy</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <style>
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://th.bing.com/th/id/OIP.Wodo56untx1RPVCSTz62WQHaE3?w=301&h=198&c=7&r=0&o=5&dpr=1.3&pid=1.7');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }

        .background-pharmacy {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://i.dailymail.co.uk/i/pix/2013/01/15/article-2262756-16F4CF9E000005DC-233_634x413.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }
        .background-user {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://th.bing.com/th/id/OIP.nD4AP-8pV9bTEmImvxTQAwHaE8?w=246&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }
        .card-login {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            width: 400px;
            height: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Pharmacy') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    @auth
                        @if (auth()->user()->type == 'pharmacy')
                        @include('layouts.admin-menu')
                        @elseif(auth()->user()->type == 'user')
                        @include('layouts.user-menu')
                        @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    @yield('script')
    <script>
        function viewQuotationItem(id) {
            // Clear the contents of the item-body element before loading new data
            $('#item-body').html('');
            // Make an AJAX request to the server to get the quotation item data
            $.ajax({
                url: `{{url('/quotation/items/${id}')}}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Loop through the response data and create HTML for each item
                    $.each(response, function(index, item) {
                        var row = '<tr>';
                        row += '<td>' + item.drug + '</td>';
                        row += '<td>' + item.quantity + '</td>';
                        row += '<td>' + item.amount + '</td>';
                        row += '</tr>';
                        $('#item-body').append(row);
                    });
                    // Show the modal with the data
                    $('#modal-quotation').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>
