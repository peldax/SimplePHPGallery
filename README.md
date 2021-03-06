# Simple PHP Gallery

[![Version](https://img.shields.io/badge/version-6.0-brightgreen.svg)](https://github.com/peldax/SimplePHPGallery/releases/tag/v6.0)

[![Maintained](https://img.shields.io/badge/maintained-yes-brightgreen.svg)](https://github.com/peldax/SimplePHPGallery/releases)
[![Issues](https://img.shields.io/badge/issues-2-green.svg)](https://github.com/peldax/SimplePHPGallery/issues)

[![Code Climate](https://codeclimate.com/github/peldax/SimplePHPGallery/badges/gpa.svg)](https://codeclimate.com/github/peldax/SimplePHPGallery)

Simple web gallery written in PHP - the one I use on my personal website (http://gallery.peldax.com).
Gallery doesn't require jQuery or any other framework.

Gallery is designed to be:

* Lightweight
* Fast
* Free

Feel free to use or contribute.

## Requirements

1. `php-gd` library. 

## How to set

1. Download the sources and upload them to your server.
2. Create a directories named "cache/thumbnails", "cache/watermarks" and "album" in the same folder.
3. Copy your pictures/directories into the "album" folder (album/year/month/folder/image).

## How to customize

1. Style your gallery in stylesheet.css.
2. Change HTML elements such as head and footer to your needs in index.php.
3. Customize your error page in error.php.
4. Change important constants by modifying parameters of 'define';
    - Turn off watermarks in displayContent.php.
    - Modify thumbnail settings in photoThumbnail.php.
    - Change watermark settings in photoWatermark.php.

## Credits

[Jiří Fatka](https://github.com/NTSFka) - script for image thumbnails.

[Google Fonts - Lato](https://www.google.com/fonts/specimen/Lato) - font is included.

## Changelog

* v1.0 - Very first version
* v2.0 - Thumbnails
* v2.1 - Home button, visual upgrade
* v2.2 - Fullscreen mode - visual upgrade
* v3.0 - Cycle through images using keypress or mouse
* v3.1 - Fullscreen mode - smooth transition between images
* v3.2 - Parent dir button, SEO, Error pages
* v3.3 - Loading box
    - v3.3.1 - Important security hotfix
* v4.0 - Automatic slideshow, Sticky footer, Fullscreen mode - blur background
* v4.1 - Directories are sorted
* v5.0 - Hashlinks directly to image or slideshow
* v5.1 - Hashlink doesn't refresh page
* v5.2 - Error boxes instead of alerts
* v6.0 - Watermarks

## Issues

Nothing at the moment.
Do you miss some important feature? Feel free to contact me or create pull request yourself.
