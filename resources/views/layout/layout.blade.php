<!DOCTYPE html>
<html>

<head>
    <!-- Site made with Mobirise Website Builder v5.2.0, https://mobirise.com -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Mobirise v5.2.0, mobirise.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon"
          href="https://icons.iconarchive.com/icons/iconarchive/red-orb-alphabet/256/Letter-H-icon.png"
          type="image/png">
    <meta name="description" content="">


    <title>My social media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css"
          integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset("hp-assets/web/assets/mobirise-icons2/mobirise2.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/tether/tether.min.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/bootstrap/css/bootstrap-grid.min.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/bootstrap/css/bootstrap-reboot.min.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/dropdown/css/style.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/socicon/css/styles.css") }}">
    <link rel="stylesheet" href="{{ asset("hp-assets/theme/css/style.css") }}">
    <link rel="preload" as="style" href="">{{ asset("hp-assets/mobirise/css/mbr-additional.css") }}
    <link rel="stylesheet" href="{{ asset("hp-assets/mobirise/css/mbr-additional.css") }}" type="text/css">
    @yield('ex-style')
</head>

<body>

<!--Nav bar-->
<section class="menu menu1 cid-sgljdZk8Yc" once="menu" id="menu1-y">

    <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg">
        <div class="container">
            <div class="navbar-brand">

                    <span class="navbar-caption-wrap">
                        <a class="navbar-caption text-black display-7" href="/home">
                            MXH-INPG16
                        </a>
                    </span>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-dropdown nav-right" data-app-modern-menu="true">

                    <li class="nav-item">
                        <a class="nav-link link text-black display-4" >
                            <input class="form-control mr-sm-1" type="text" placeholder="Tìm kiếm gì đó..."
                                   aria-label="Search">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link text-black display-4" href="#">
                            <button class="btn btn-outline-dark my-2 my-sm-0">{{ session()->get('data')->name }}</button>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link link text-black display-4" href="#">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link text-black display-4" href="#">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link text-black display-4" href="#">
                            <i class="fas fa-bell"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle link text-black display-4" href="#" id="navbarDropdown"
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">My profile</a>
                            <a class="dropdown-item" href="#">Account setting</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">log out</a>
                        </div>
                        <form id="logout-form" action="/logout" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="token" value="{{ session()->get('token') }}">

                        </form>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
</section>

<!--Body-->

@yield('content')

<!--Script-->
<section
    style="background-color: #fff; color:#aaa; font-size:12px; padding: 0; align-items: center; display: flex;">
    <a href="https://mobirise.site/a" style="flex: 1 1; height: 3rem; padding-left: 1rem;"></a>
    <p style="flex: 0 0 auto; margin:0; padding-right:1rem;"><a href="https://mobirise.site/n"
                                                                style="color:#aaa;"></a></p>
</section>
<script src="{{ asset("hp-assets/web/assets/jquery/jquery.min.js") }}"></script>
<script src="{{ asset("hp-assets/popper/popper.min.js") }}"></script>
<script src="{{ asset("hp-assets/tether/tether.min.js") }}"></script>
<script src="{{ asset("hp-assets/bootstrap/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("hp-assets/smoothscroll/smooth-scroll.js") }}"></script>
<script src="{{ asset("hp-assets/dropdown/js/nav-dropdown.js") }}"></script>
<script src="{{ asset("hp-assets/dropdown/js/navbar-dropdown.js") }}"></script>
<script src="{{ asset("hp-assets/touchswipe/jquery.touch-swipe.min.js") }}"></script>
<script src="{{ asset("hp-assets/theme/js/script.js") }}"></script>


</body>

</html>




























