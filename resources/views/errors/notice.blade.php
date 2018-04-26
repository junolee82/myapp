<!DOCTYPE <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <style>
        * {line-height: 1.5; margin: 0;}
        html {color: #888; font-family: sans-serif; text-align: center;}
        body {left: 50%; margin: -43px 0 0 -150px; position: absolute; top: 50%; width: 300px;}
        h1 {color: #555; font-size: 2em; font-weight: 400;}
        p {line-height: 1.2;}
        @meida only screen and (max-width: 400px) {
            body { margin: 10px auto; position: static; width: 95%; }
            h1 { font-size: 1.5em; }
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $description }}</p>
</body>
</html>