<?php

// Start caching
ob_start();

define("DEBUG_FILE", ".photo_cache_error_log.txt");
define("CACHE_DIR", "cache/");

// Parse parameters
if (!isset($_GET["source"]) || !isset($_GET["height"]) || !isset($_GET["width"]))
{
    exit("Parameters are required.");
}
$source = $_GET["source"];
$width = min($_GET['width'], 2000);
$height = min($_GET['height'], 2000);
$color_css = isset($_GET['color']) ? $_GET['color'] : "202020";
$header = isset($_GET['no-header']) ? false : true;

$base_name = basename(substr($source, 0, strrpos($source, '.')));
$ext = substr(strrchr($source, '.'), 1);
$hash = md5($source);

$cache_file_name = CACHE_DIR."/{$base_name}_{$width}x{$height}_{$hash}.{$ext}";

$error_str = "";
$img = null;

try
{
    if (file_exists($cache_file_name))
    {
        $info = getimagesize($cache_file_name);
        $mime = isset($info['mime']) ? $info['mime'] : "image/bmp";

        $image_data = file_get_contents($cache_file_name);
        $img = imagecreatefromstring($image_data);
        if ($img === false)
        {
            throw new Exception("imagecreatefromstring error!");
        }

        switch ($mime)
        {
            case "image/gif":
                $function = "imagegif";
                break;
            case "image/jpeg":
                $function = "imagejpeg";
                break;
            case "image/png":
                $function = "imagegif";
                break;
            default:
                throw new Exception("Unknown mime type ('$mime')");
        }
    }
    else if (!file_exists($source))
    {
        exit($source." doesn't exist.");
    }
    else
    {
        // soubor neexistuje - vytvoříme
        // zjistíme si informace o zdrojovém obrázku
        $info = getimagesize($source);
        $mime = $info['mime'];
        $src_width = $info[0];
        $src_height = $info[1];

        // poměr obrázku
        $ratio = $src_width / $src_height;
        // požadovaný poměr
        $req_ratio = $width / $height;

        // požadovaný poměr je menší než zdrojového obrázku
        if ($ratio > $req_ratio)
        {
            // použijeme šířku (jako konstantu)
            $new_width = $width;
            $new_height = $width / $ratio;
            $dest_x = 0;
            $dest_y = ($height - $new_height) / 2 + 1;
        }
        else
        {
            // použijeme výšku (jako konstantu)
            $new_width = $height * $ratio;
            $new_height = $height;
            $dest_x = ($width - $new_width) / 2 + 1;
            $dest_y = 0;
        }

        // zvolíme funkce podle typu
        switch ($mime)
        {
            case "image/gif":
                $function = "imagegif";
                $create_function = "imagecreatefromgif";
                break;
            case "image/jpeg":
                $function = "imagejpeg";
                $create_function = "imagecreatefromjpeg";
                break;
            case "image/png":
                $function = "imagegif";
                $create_function = "imagecreatefrompng";
                break;
            default:
                throw new Exception("Unknown mime type ('$mime')");
        }

        // Check memory
        $memoryNeeded = round(($src_width * $src_height * $info['bits'] *
            $info['channels'] / 8 + Pow(2, 16)) * 1.65);

        if (function_exists('memory_get_usage') &&
            memory_get_usage() + $memoryNeeded > (integer) ini_get('memory_limit') * pow(1024, 2))
        {
            $limit = (integer) ini_get('memory_limit') + ceil(((memory_get_usage() +
                $memoryNeeded) - (integer) ini_get('memory_limit') *
                pow(1024, 2)) / pow(1024, 2));
            $set = ini_set('memory_limit', ($limit + 1) . 'M');
        }

        // Create image
        $img = imagecreatetruecolor($width, $height);
        // Load source image
        $src_image = call_user_func($create_function, $source);

        // Fill image with color
        $red = hexdec(substr($color_css, 0, 2));
        $green = hexdec(substr($color_css, 2, 2));
        $blue = hexdec(substr($color_css, 4, 2));
        $fill_color = imagecolorallocate($img, $red, $green, $blue);
        imagefill($img, 0, 0, $fill_color);

        // Resize image
        imagecopyresampled($img, $src_image,
            $dest_x, $dest_y, 0, 0,
            $new_width, $new_height, $src_width, $src_height);
        // Destroy old image
        imagedestroy($src_image);

        // Create image and save
        call_user_func($function, $img, $cache_file_name);
    }

    $error_str = ob_get_contents();
    ob_end_clean();

    if (!empty($error_str))
    {
        throw new Exception($error_str);
    }

    if ($header)
    {
        header("Content-type: {$mime}");
    }

    if (!call_user_func($function, $img))
    {
        throw new Exception("Can't create image using '$function' - '".print_r($img, true)."'");
    }

    imagedestroy($img);
}
catch (Exception $e)
{
    $file = fopen(DEBUG_FILE, "a");
    fwrite($file, $e->getMessage()."\n");
    fclose($file);
}

