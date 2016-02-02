var links = [];

// Loading box

function imageLoading()
{
    document.getElementById('loading').style.display = 'block';
}

function imageLoaded()
{
    document.getElementById('loading').style.display = 'none';
}

// Slideshow functions

var slideshow_mode = false;

function slideshow()
{
    if (links.length === 0)
    {
        alert("There are no images in this folder.");
        return;
    }
    imageLoading();
    slideshow_mode = true;
    document.getElementById('container').classList.add('blur');
    document.getElementById('footer').classList.add('blur');
    document.getElementById('scenter').innerHTML= '<img src="' + links[0].innerHTML + '" onload="imageLoaded()"/>';
    setTimeout(slideshowNext, 7000, 1);
}

function slideshowNext(index)
{
    if (!slideshow_mode)
        return;
    if (index === links.length)
        exitSlideshow();
    imageLoading();
    document.getElementById('scenter').getElementsByTagName('img')[0].src = links[index].innerHTML;
    setTimeout(slideshowNext, 7000, index + 1);
}

function exitSlideshow()
{
    slideshow_mode = false;
    document.getElementById('container').classList.remove('blur');
    document.getElementById('footer').classList.remove('blur');
    document.getElementById('scenter').innerHTML = '';
}

// fullscreen functions

var fullscreen_mode = false;
var current_index = 0;

function fullscreen(index)
{
    imageLoading();
    current_index = index;
    fullscreen_mode = true;
    document.getElementById('container').classList.add('blur');
    document.getElementById('footer').classList.add('blur');
    document.getElementById('fcenter').innerHTML= '<img src="' + links[index].innerHTML + '" onload="imageLoaded()"/>';
}

function exitFullscreen()
{
    fullscreen_mode = false;
    document.getElementById('container').classList.remove('blur');
    document.getElementById('footer').classList.remove('blur');
    document.getElementById('fcenter').innerHTML = '';
}

function previous()
{
    imageLoading();
    current_index--;
    if (current_index === -1)
        current_index = links.length - 1;
    document.getElementById('fcenter').getElementsByTagName('img')[0].src = links[current_index].innerHTML;
}

function next()
{
    imageLoading();
    current_index++;
    if (current_index === links.length)
        current_index = 0;
    document.getElementById('fcenter').getElementsByTagName('img')[0].src = links[current_index].innerHTML;
}

// keypress function

function checkKey(e)
{
    e = e || window.event;
    if (slideshow_mode)
    {
        switch (e.keyCode)
        {
            case 27: // escape
                return exitSlideshow();
            default: return;
        }
    }
    if (fullscreen_mode)
    {
        switch (e.keyCode)
        {
            case 37: // left arrow
                return previous();
            case 39: // right arrow
                return next();
            case 27: // escape
                return exitFullscreen();
            default: return;
        }
    }
}

function refreshLinks()
{
    links = document.getElementById('links').getElementsByTagName('p');
}

document.onkeydown = checkKey;
window.onload = refreshLinks;
