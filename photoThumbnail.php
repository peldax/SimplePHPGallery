<?php

// Start caching
ob_start();

// Define important constants
define("DEBUG_FILE", ".thumbnail_error_log.txt");
define("CACHE_DIR", "cache/thumbnails/");
define("THUMB_SIZE_X", 150);
define("THUMB_SIZE_Y", 150);
define("BACK_COLOR", "202020");

if (!file_exists(CACHE_DIR))
{
    error_log("SimplePHPGallery-Thumbnails: Unable to find thumbnails cache directory. Thumbnails won't be cached." , E_USER_NOTICE);
}

// Parse parameters
if (!isset($_GET["source"]))
{
    error_log("SimplePHPGallery-Thumbnails: Source file not specified." , E_USER_NOTICE);
    return;
}
$source = $_GET["source"];

$base_name = basename(substr($source, 0, strrpos($source, '.')));
$extension = substr(strrchr($source, '.'), 1);
$hash = md5($source);

$cache_file = CACHE_DIR."/{$base_name}_".THUMB_SIZE_X."x".THUMB_SIZE_Y."_{$hash}.{$extension}";

$error_str = "";
$img = null;

try
{
    // Cached thumbnail is ready
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
        error_log("SimplePHPGallery-Thumbnails: {$source} doesn't exist." , E_USER_NOTICE);
        return;
    }
    // Cached thumbnails is not ready
    else
    {
        $info = getimagesize($source);
        $mime = $info['mime'];
        $src_width = $info[0];
        $src_height = $info[1];

        $ratio = $src_width / $src_height;
        $req_ratio = THUMB_SIZE_X / THUMB_SIZE_Y;

        if ($ratio > $req_ratio)
        {
            $new_width = THUMB_SIZE_X;
            $new_height = THUMB_SIZE_X / $ratio;
            $dest_x = 0;
            $dest_y = (THUMB_SIZE_Y - $new_height) / 2 + 1;
        }
        else
        {
            $new_width = THUMB_SIZE_Y * $ratio;
            $new_height = THUMB_SIZE_Y;
            $dest_x = (THUMB_SIZE_X - $new_width) / 2 + 1;
            $dest_y = 0;
        }

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
        $img = imagecreatetruecolor(THUMB_SIZE_X, THUMB_SIZE_Y);

        // Load source image
        $src_image = call_user_func($create_function, $source);

        // Fill image with color
        $red = hexdec(substr(BACK_COLOR, 0, 2));
        $green = hexdec(substr(BACK_COLOR, 2, 2));
        $blue = hexdec(substr(BACK_COLOR, 4, 2));
        $fill_color = imagecolorallocate($img, $red, $green, $blue);
        imagefill($img, 0, 0, $fill_color);

        // Resize image
        imagecopyresampled($img, $src_image,
            $dest_x, $dest_y, 0, 0,
            $new_width, $new_height, $src_width, $src_height);

        // Destroy old image
        imagedestroy($src_image);

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

