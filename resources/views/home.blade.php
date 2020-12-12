<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script
        src="https://kit.fontawesome.com/64d58efce2.js"
        crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css"/>
    <title>OurMedia</title>
    <link rel="stylesheet" href="{{ URL::asset("css/home.css") }}">

</head>
<body>
<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <form action="/login" method="post" class="sign-in-form">
                @csrf
                <h2 class="title">Sign in</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Phonenumber" name="phonenumber"/>
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password"/>
                </div>
                <input type="submit" value="Login" class="btn solid"/>

            </form>



            <form action="/signup" method="post" class="sign-up-form">
                @csrf
                <h2 class="title">Sign up!!</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="phone" placeholder="Username"/>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="pass" placeholder="Password"/>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
                <input type="submit" class="btn" value="Sign up"/>
                <input type="hidden" name="uuid" class="form-control" >
            </form>
        </div>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>New here ?</h3>
                <p>
                    Welcome to our Media!
                </p>
                <button class="btn transparent" id="sign-up-btn">
                    Sign up
                </button>
            </div>
            <img src="img/log.svg" class="image" alt=""/>
        </div>
        <div class="panel right-panel">
            <div class="content">
                <h3>One of us ?</h3>
                <p>
                    If you are one of us, sign in now!
                </p>
                <button class="btn transparent" id="sign-in-btn">
                    Sign in
                </button>
            </div>
            <img src="img/register.svg" class="image" alt=""/>
        </div>
    </div>
</div>

<script src="{{ URL::asset("js/home.js") }}"></script>

</body>
</html>
