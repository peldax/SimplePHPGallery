<!DOCTYPE html>

<html lang="en">
<head>
    <title>peldax | Gallery</title>
    <meta charset="UTF-8">
    <meta name="author" content="Václav Pelíšek">
    <meta name="description" content="Václav Pelíšek\'s gallery - A collection of photos from his experiences and adventures">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="václav, pelíšek, pelda, peldax, personal, page">
    <meta name="robots" content="index, follow">
    <script src="scripts.js"></script>
    <link href="favicon.png" rel="icon" type="image/png" sizes="48x48" />
    <link href="stylesheet.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <a id ="home" href="http://www.peldax.com">
        <div id="arrow"></div>
        <p>Home</p>
    </a>
    <div id="showtime">
        <div id="left" onclick="previous()"></div>
        <div id="center" onclick="exitFullscreen()"></div>
        <div id="right" onclick="next()"></div>
    </div>
    <div id="album-div">
<?php
if (http_response_code() < 400)
{
    header("Location: index.php");
    return;
}
$errorcode = $_SERVER["REDIRECT_STATUS"];
echo "
<h1>Oups! Something went wrong (code {$errorcode})</h1>
";
?>
        <p>Please use <a class="redirect" href="/">this</a> link to redirect.</p>
    </div>
    <div id="footer">
        <p>©Václav Pelíšek, 2016</p>
    </div>
</div>
</body>
</html>