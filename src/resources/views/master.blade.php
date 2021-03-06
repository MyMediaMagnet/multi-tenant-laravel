<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Multi Tenant</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .blocks {
                display:flex;
                width:100%;
                flex-direction:row;
                align-items:center;
                justify-content: space-around;
            }
            .block {
                display:flex;
                flex-direction:row;
                align-items:center;
                justify-content: center;
                width:300px;
                height:200px;
                border:1px solid #ccc;
                border-radius:5px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                    <div class="blocks">
                        <span>Welcome {{ \Auth::user()->name }}</span>
                        <a href="{{ url('/') }}">Home</a>
                        @if(session()->has('tenant.id'))
                            <form method="POST" action="{{route('change-tenant')}}">
                                @csrf
                                <button type="submit">Change Tenant</button>
                            </form>
                        @endif
                        <form method="POST" action="{{route('logout')}}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
