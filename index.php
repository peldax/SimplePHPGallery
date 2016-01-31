<!DOCTYPE html>

<html lang="en">
    <head>
		<title>peldax | Gallery</title>
		<meta charset="UTF-8">
		<meta name="author" content="Václav Pelíšek">
		<meta name="description" content="Václav Pelíšek's gallery - A collection of photos from his experiences and adventures">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="keywords" content="václav, pelíšek, pelda, peldax, personal, page">
        <meta name="robots" content="index, follow">
		<script src="scripts.js"></script>
        <link href="favicon.png" rel="icon" type="image/png" sizes="48x48" />
		<link href="stylesheet.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lato:300,700,400' rel='stylesheet' type='text/css'>
	</head>
	<body>
        <div id="loading">
            <p>Loading...</p>
        </div>
        <div id="fullscreen">
            <div id="left" onclick="previous()"></div>
            <div id="fcenter" onclick="exitFullscreen()"></div>
            <div id="right" onclick="next()"></div>
        </div>
        <div id="slideshow">
            <div id="scenter" onclick="exitSlideshow()"></div>
        </div>
	    <div id="container">
            <div id="buttons">
                <a id ="hbutton" href="http://www.peldax.com">
                    <div id="arrow"></div>
                    <p>Home</p>
                </a>
                <div id="sbutton" onclick="slideshow()">
                    <div id="square"></div>
                    <p>Slideshow</p>
                </div>
            </div>
            <div id="album-div">
            <h1>peldax's Gallery</h1>
<?php
require 'displayContent.php';
?>
            </div>
        </div>
        <div id="footer">
            <p>©Václav Pelíšek, 2016</p>
        </div>
    </body>
</html>