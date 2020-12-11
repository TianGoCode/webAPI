<!DOCTYPE html>
<html>
<head>
    <title>Sociable</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.1/uuid.min.js" integrity="sha512-4JH7nC4nSqPixxbhZCLETJ+DUfHa+Ggk90LETm25fi/SitneSvtxkcWAUujvYrgKgvrvwv4NDAsFgdwCS79Dcw==" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
 
</head>
<body>
<div class="headerMenu">
    <div id="wrapper">
        <div class="logo">
            <img src="./logo.png">
        </div>
        <div class="searchbox">
            <form action="search" method="GET" id="search">
                <input type="text" name="q" size="60" placeholder="Search"/>
            </form>
        </div>
        <div id="menu">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Sign Up</a>
            <a href="#">Sign In</a>
        </div>
    </div>
</div>
<div style="width :800px; margin: 0px auto 0px auto"></div>
<table>
    <tr>
        <td width="60%" valign="top">
            <h2>Join us!</h2>
        </td>
        <td width="40%" valign="top">
            <h2>đăng ký</h2>
            <form action="/signup" method="post">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">phone</label>
                    <input type="text" name="phone" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="pass" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3">

                    <input type="hidden" name="uuid" class="form-control" >
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </td>
    </tr>
</table>
</body>
</html>
