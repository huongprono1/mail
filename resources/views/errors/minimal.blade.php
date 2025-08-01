<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .page_404 {
            padding: 40px 0;
            background: #fff;
            font-family: 'Space Grotesk', serif;
        }

        .page_404 img {
            width: 100%;
        }

        .four_zero_four_bg {
            background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
            background-repeat: no-repeat;
            height: 400px;
            background-position: center;
        }


        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #39ac31;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_404 {
            margin-top: -50px;
        }
    </style>
</head>
<body>
<section class="page_404">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="col-sm-10 col-sm-offset-1  text-center">
                    <div class="four_zero_four_bg">
                        <h1 class="text-center ">@yield('code')</h1>


                    </div>

                    <div class="contant_box_404">
                        <h3 class="h2">
                            Uh-oh!
                        </h3>

                        <p>@yield('message')</p>

                        <a href="{{route('filament.app.pages.home')}}" class="link_404">Go to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


</body>
</html>
