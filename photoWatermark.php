<?php

// Start caching
ob_start();

// Define important constants
define("DEBUG_FILE", ".watermark_error_log.txt");
define("CACHE_DIR", "cache/watermarks/");
define("WATERMARK_TEXT", "gallery.peldax.com");
define("FONT", "Lato-Regular.ttf");
define("FONT_SIZE", 1/45);
define("FONT_COLOR", "FFFFFF");

if (!file_exists(CACHE_DIR))
{
    error_log("SimplePHPGallery-Watermarks: Unable to find watermarks cache directory. Watermarks won't be cached." , E_USER_NOTICE);
}

// Parse parameters
if (!isset($_GET["source"]))
{
    error_log("SimplePHPGallery-Watermarks: Source file not specified." , E_USER_NOTICE);
    return;
}
$source = $_GET["source"];

$base_name = basename(substr($source, 0, strrpos($source, '.')));
$extension = substr(strrchr($source, '.'), 1);
$hash = md5($source);

$cache_file = CACHE_DIR."/{$base_name}_{$hash}.{$extension}";

$error_str = "";
$img = null;

try
{
    // Cached watermark is ready
    if (file_exists($cache_file))
    {
        $info = getimagesize($cache_file);
        $mime = isset($info['mime']) ? $info['mime'] : "image/bmp";

        $image_data = file_get_contents($cache_file);
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
    // Source doesn't exist
    else if (!file_exists($source))
    {
        error_log("SimplePHPGallery-Watermarks: {$source} doesn't exist." , E_USER_NOTICE);
        return;
    }
    // Cached watermark is not ready
    else
    {
        $info = getimagesize($source);
        $mime = $info['mime'];
        $src_width = $info[0];
        $src_height = $info[1];

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

        // Load source image
        $img= call_user_func($create_function, $source);

        // Fill
        $red = hexdec(substr(FONT_COLOR, 0, 2));
        $green = hexdec(substr(FONT_COLOR, 2, 2));
        $blue = hexdec(substr(FONT_COLOR, 4, 2));
        $font_color = imagecolorallocate($img, $red, $green, $blue);

        // Set up font
        $font_path = realpath('.');
        putenv('GDFONTPATH='.$font_path);

        $font_size = $src_height * FONT_SIZE;

        // Write watermark
        imagettftext($img, $font_size, 0, $font_size, $font_size * 1.2, $font_color, FONT, WATERMARK_TEXT);

        // Create image and save
        call_user_func($function, $img, $cache_file);
    }

    $error_str = ob_get_contents();
    ob_end_clean();

    if (!empty($error_str))
    {
        throw new Exception($error_str);
    }

    header("Content-type: {$mime}");

    if (!call_user_func($function, $img))
    {
        throw new Exception("Can't create image using '$function' - '".print_r($img, true)."'");
    }

    imagedestroy($img);
}
catch (Exception $e)
{
    $file = fopen(DEBUG_FILE, "w");
    fwrite($file, $e->getMessage()."\n");
    fclose($file);
}
