<!DOCTYPE html>

<html lang="en">
    <head>
		<title>peldax | Gallery</title>
		<meta charset="UTF-8">
		<meta name="author" content="Václav Pelíšek">
		<meta name="description" content="Václav Pelíšek\'s gallery">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="keywords" content="václav, pelíšek, pelda, peldax, personal, page">
		<script src="scripts.js"></script>
        <link href="favicon.png" rel="icon" type="image/png" sizes="48x48" />
		<link href="stylesheet.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700" rel="stylesheet" type="text/css">
	</head>
	<body>
	    <div id="container">
            <div id="showtime">
                <div id="left" onclick="previous()"></div>
                <div id="center" onclick="exitfullscreen()">
                </div>
                <div id="right" onclick="next()"></div>
            </div>
	        <div id="album-div">
	            <table id="album">
<?php
require 'displayContent.php';
?>
                </table>
            </div>
            <div id="footer">
                <p>©Václav Pelíšek, 2016</p>
            </div>
        </div>
    </body>
</html>