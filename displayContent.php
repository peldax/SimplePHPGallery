<?php
error_reporting(E_ALL | E_STRICT);

// Parse parameters

$year = isset($_GET["year"]) ? $_GET["year"] : "";
$month = isset($_GET["month"]) ? $_GET["month"] : "";
$folder = isset($_GET["folder"]) ? $_GET["folder"] : "";

if ($year == "")
{
    $current_dir = "album";
    $next_parameter = "?year=";
}
else if ($month == "")
{
    $current_dir = "album/{$year}";
    $next_parameter = "?year={$year}&month=";
}
else if ($folder == "")
{
    $current_dir = "album/{$year}/{$month}";
    $next_parameter = "?year={$year}&month={$month}&folder=";
}
else
    $current_dir = "album/{$year}/{$month}/{$folder}";

// Read directory

$dirs=array();
$images=array();

$directory = opendir($current_dir);
if ($directory == FALSE)
{
    return;
}

while(($var=readdir($directory)) !== FALSE)
{
    // Skip hidden files
    if (substr($var, 0, 1) == '.')
    {
        continue;
    }

    // Read dir
    else if (is_dir($current_dir.'/'.$var))
    {
        array_push($dirs, $var);
    }

    // Read file
    else
    {
        array_push($images, $var);
    }
}
closedir($directory);

// Display items in table

$items_in_row = 0;
$max_items_in_row = 5;

foreach ($dirs as $x)
{
    if ($items_in_row == 0)
    {
        echo '<tr>';
    }
    echo '
<td>
    <a href="index.php'.$next_parameter.$x.'">
        <div class="item">
            <p>'.$x.'</p>
        </div>
    </a>
</td>
';
    $items_in_row++;
    if ($items_in_row == $max_items_in_row)
    {
        echo '</tr>';
        $items_in_row = 0;
    }
}

foreach ($images as $x)
{
    if ($items_in_row == 0)
    {
        echo '<tr>';
    }
    echo '
<td>
    <div class="item">
        <img width="100%" height="100%" src="'.$current_dir.'/'.$x.'" onclick="fullscreen(\''.$current_dir.'/'.$x.'\')" />
    </div>
</td>';
    $items_in_row++;
    if ($items_in_row == $max_items_in_row)
    {
        echo '</tr>';
        $items_in_row = 0;
    }
}

if ($items_in_row != $max_items_in_row)
{
    echo '</tr>';
}