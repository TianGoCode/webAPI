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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.1/uuid.min.js" integrity="sha512-4JH7nC4nSqPixxbhZCLETJ+DUfHa+Ggk90LETm25fi/SitneSvtxkcWAUujvYrgKgvrvwv4NDAsFgdwCS79Dcw==" crossorigin="anonymous"></script>

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
                <input type="hidden" name="token" id="tokenInput"/>
                <input type="submit" value="Login" class="btn solid"/>

            </form>


            <form action="/signup" method="post" class="sign-up-form">
                @csrf
                <h2 class="title">Sign up!!</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="phone" placeholder="Phonenumber"/>


                </div>

                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="pass" placeholder="Password"/>


                </div>
                <input type="submit" class="btn" value="Sign up"/>
                <input type="hidden" name="uuid" class="form-control">
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
@dump(session()->all())
<script src="{{ URL::asset("js/home.js") }}"></script>
<script>
    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    const gen = function genUuid(){
        document.getElementById('tokenInput').value = makeid(6);
        localStorage.setItem('token',document.getElementById('tokenInput').value)
    }
    gen();
</script>
</body>
</html>
