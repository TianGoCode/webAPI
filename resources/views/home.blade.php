<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://freetuts.net/public/javascript/jquery.min.js"></script>
    <script language="javascript">
        // Hàm kiểm tra Email
        function isEmail(emailStr) {
            var emailPat = /^(.+)@(.+)$/
            var specialChars = "\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
            var validChars = "\[^\\s" + specialChars + "\]"
            var quotedUser = "(\"[^\"]*\")"
            var ipDomainPat = /^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
            var atom = validChars + '+'
            var word = "(" + atom + "|" + quotedUser + ")"
            var userPat = new RegExp("^" + word + "(\\." + word + ")*$")
            var domainPat = new RegExp("^" + atom + "(\\." + atom + ")*$")
            var matchArray = emailStr.match(emailPat)
            if (matchArray == null) {
                return false
            }
            var user = matchArray[1]
            var domain = matchArray[2]

            // See if "user" is valid
            if (user.match(userPat) == null) {
                return false
            }
            var IPArray = domain.match(ipDomainPat)
            if (IPArray != null) {
                // this is an IP address
                for (var i = 1; i <= 4; i++) {
                    if (IPArray[i] > 255) {
                        return false
                    }
                }
                return true
            }
            var domainArray = domain.match(domainPat)
            if (domainArray == null) {
                return false
            }

            var atomPat = new RegExp(atom, "g")
            var domArr = domain.match(atomPat)
            var len = domArr.length

            if (domArr[domArr.length - 1].length < 2 ||
                domArr[domArr.length - 1].length > 3) {
                return false
            }

            if (len < 2) {
                return false
            }

            return true;
        }

        $(document).ready(function () {
            $('#form_register').submit(function () {

                // BƯỚC 1: Lấy dữ liệu từ form
                var username = $.trim($('#username').val());
                var password = $.trim($('#password').val());
                var re_password = $.trim($('#re_password').val());
                var email = $.trim($('#email').val());
                var phone = $.trim($('#phone').val())

                // BƯỚC 2: Validate dữ liệu
                // Biến cờ hiệu
                var flag = true;

                // Username
                if (username == '' || username.length < 4) {
                    $('#username_error').text('Tên đăng nhập phải lớn hơn 4 ký tự');
                    flag = false;
                } else {
                    $('#username_error').text('');
                }

                // Password
                if (password.length <= 0) {
                    $('#password_error').text('Bạn phải nhập mật khẩu');
                    flag = false;
                } else {
                    $('#password_error').text('');
                }

                // Re password
                if (password != re_password) {
                    $('#re_password_error').text('Mật khẩu nhập lại không đúng');
                    flag = false;
                } else {
                    $('#re_password_error').text('');
                }

                // Email
                if (!isEmail(email)) {
                    $('#email_error').text('Email không được để trống và phải đúng định dạng');
                    flag = false;
                } else {
                    $('#email_error').text('');
                }

                return flag;
            });
        });
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: -20px 0 50px;
        }

        h1 {
            font-weight: bold;
            margin: 0;
        }

        h2 {
            text-align: center;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        span {
            font-size: 12px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #FF4B2B;
            background-color: #FF4B2B;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
            0 10px 10px rgba(0, 0, 0, 0.22);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {

            0%,
            49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%,
            100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: #FF416C;
            background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
            background: linear-gradient(to right, #FF4B2B, #FF416C);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .social-container {
            margin: 20px 0;
        }

        .social-container a {
            border: 1px solid #DDDDDD;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
        }

        footer {
            background-color: #222;
            color: #fff;
            font-size: 14px;
            bottom: 0;
            position: fixed;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 999;
        }

        footer p {
            margin: 10px 0;
        }

        footer i {
            color: red;
        }

        footer a {
            color: #3c97bf;
            text-decoration: none;
        }

    </style>
</head>

<body>
<h2>check validate branch</h2>
<h2>Sign in/up Form</h2>
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="/signup" method="POST">
            @csrf
            <h1>Create Account</h1>
            <span>or use your email for registration</span>
            <input type="text" name="name" placeholder="Name"/>
            <input type="text" name="phonenumber" placeholder="Phone"/>
            <input type="password" name="password" placeholder="Password"/>
            <input type=" password" name="repass" placeholder="Enter the Password"/>
            <input class="btn" type="submit" value="sign up">

        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="#">
            <h1>Sign in</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <input type="email" placeholder="Email"/>
            <input type="password" placeholder="Password"/>
            <a href="#">Forgot your password?</a>
            <button>Sign In</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add('right-panel-active');
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove('right-panel-active');
    });
</script>

<body>
{{ $test = "942188741" }}
{{ $test[0] }}
</html>
