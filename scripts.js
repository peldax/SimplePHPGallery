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

// Blur

function blur()
{
    document.getElementById('container').classList.add('blur');
    document.getElementById('footer').classList.add('blur');
}

// Hash

function setHash(string)
{
    window.location.href = window.location.href.split('#')[0] + '#' + string;
}

// Clear

function clear()
{
    document.getElementById('container').classList.remove('blur');
    document.getElementById('footer').classList.remove('blur');
    window.location.href = window.location.href.split('#')[0];
}

// Slideshow functions

var slideshow_mode = false;

function slideshow()
{
    if (links.length === 0)
        return alert("There are no images in this folder.");

    blur();
    imageLoading();
    setHash("slideshow");
    slideshow_mode = true;
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
    clear();
    slideshow_mode = false;
    document.getElementById('scenter').innerHTML = '';
}

// fullscreen functions

var fullscreen_mode = false;
var current_index = 0;

function fullscreen(index)
{
    blur();
    imageLoading();
    current_index = index;
    fullscreen_mode = true;
    setHash(current_index.toString());
    document.getElementById('fcenter').innerHTML= '<img src="' + links[index].innerHTML + '" onload="imageLoaded()"/>';
}

function exitFullscreen()
{
    clear();
    fullscreen_mode = false;
    document.getElementById('fcenter').innerHTML = '';
}

function previous()
{
    imageLoading();
    current_index--;
    if (current_index === -1)
        current_index = links.length - 1;
    setHash(current_index.toString());
    document.getElementById('fcenter').getElementsByTagName('img')[0].src = links[current_index].innerHTML;
}

function next()
{
    imageLoading();
    current_index++;
    if (current_index === links.length)
        current_index = 0;
    setHash(current_index.toString());
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

function initiate()
{
    links = document.getElementById('links').getElementsByTagName('p');
    var url = window.location.href.split('#');
    if (url.length === 1)
        return;
    if (url[1] === "slideshow")
        return slideshow();
    var index = parseInt(url[1]);
    if (!isNaN(index) && index < links.length)
        return fullscreen(index);
}

document.onkeydown = checkKey;
window.onload = initiate;
