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
    $parent_parameter = "";
}
else if ($folder == "")
{
    $current_dir = "album/{$year}/{$month}";
    $next_parameter = "?year={$year}&month={$month}&folder=";
    $parent_parameter = "?year={$year}";
}
else
{
    $current_dir = "album/{$year}/{$month}/{$folder}";
    $parent_parameter = "?year={$year}&month={$month}";
}
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

// Create table
echo '<table id="album">';

$items_in_row = 0;
$max_items_in_row = 5;

// Add directories to the table
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

// Add images to the table
$index = 0;
$links = array();

foreach ($images as $x)
{
    if ($items_in_row == 0)
    {
        echo '<tr>';
    }
    echo '
<td>
    <div class="item">
        <img width="100%" height="100%"
        alt="Image from Václav Pelíšek\'s gallery at gallery.peldax.com"
        title="A collection of photos from my experiences and adventures"
        src="photoThumbnail.php?source='.$current_dir.'/'.$x.'&height=150&width=150"
        onclick="fullscreen('.$index.')" />
    </div>
</td>
';
    $items_in_row++;
    $index++;
    array_push($links, $current_dir.'/'.$x);
    if ($items_in_row == $max_items_in_row)
    {
        echo '</tr>';
        $items_in_row = 0;
    }
}
if ($items_in_row == 0)
{
    echo '<tr>';
}
if ($current_dir != "album")
{
    echo '
<td>
    <a href="index.php'.$parent_parameter.'">
        <div class="item">
            <p>&#8617;</p>
        </div>
    </a>
</td>
</tr>
    ';
}

// Add full image links to invisible div
echo '</table><div id="links">';
foreach ($links as $x)
{
    echo '<p>'.$x.'</p>';
}
echo '</div>';