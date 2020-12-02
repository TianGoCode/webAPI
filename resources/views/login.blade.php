<!DOCTYPE html>
<html>
<head>
    <title>Sociable</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>
<div class="headerMenu">
    <div id="wrapper">


    </div>
</div>
<div style="width :800px; margin: 0px auto 0px auto"></div>
<table>
    <tr>
        <td width="40%" valign="top">
            <h2>Sign Up Now!</h2>
            <form action="/login" method="POST">
                @csrf
                <input type="text" name="fname" size="25" placeholder="First Name"/> <br></br>
                <input type="text" name="lname" size="25" placeholder="Last Name"/><br></br>
                <input type="text" name="username" size="25" placeholder="Username"/><br></br>
                <input type="text" name="email" size="25" placeholder="Email"/><br></br>
                <input type="text" name="password" size="25" placeholder="Password"/><br></br>
                <input type="text" name="password2" size="25" placeholder="Repeat Password"/><br></br>
                <input type="submit" name="submit" value="Sign Up!!"/>
            </form>
        </td>
    </tr>
</table>
</body>
</html>
