<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>OurMedia</title>
    <link rel="stylesheet" href="{{ URL::asset("css/layout.css") }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.1/uuid.min.js"
            integrity="sha512-4JH7nC4nSqPixxbhZCLETJ+DUfHa+Ggk90LETm25fi/SitneSvtxkcWAUujvYrgKgvrvwv4NDAsFgdwCS79Dcw=="
            crossorigin="anonymous"></script>

</head>
<body>

<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

<div id="navwrapper">
    <div id="navbar">
        <table class="tablewrapper">
            <form action="/logout" method="post">
                @csrf
                <tr>
                    <td>
                        <input type="search" name="search" id="" class="inputtext" placeholder="find something?">
                    </td>
                    <td>

                        <input type="hidden" name="token" value="{{ session()->get('token') }}">
                        <input type="submit" value="Log Out">
                    </td>
                </tr>
            </form>


        </table>


    </div>
</div>

<div id="contentwrapper">

</div>

@dump(session()->all())

</body>
</html>
