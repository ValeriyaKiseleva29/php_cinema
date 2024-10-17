<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
This is index page
<div>
    Store
    <div>
        <form action="/posts" method="Post">
            <input type="text" placeholder="value" name="title">
            <input type="submit">
        </form>
    </div>
</div>
<div>
    this is title
    <div>
        <?php

        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
        }

        ?>
    </div>
</div>
</body>
</html>
