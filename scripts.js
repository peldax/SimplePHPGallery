var links = [];

// Alert boxes

function displayAlert(message, type)
{
    var element = document.getElementById('alert');
    element.innerHTML = '<p>'+message+'</p>';
    element.style.display = 'block';
    element.classList.add(type);
    element.onclick = hideAlert;
}

function hideAlert()
{
    var element = document.getElementById('alert');
    element.innerHTML = '';
    element.style.display = 'none';
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
    window.location.hash = string;
}

// Clear

function clear()
{
    document.getElementById('container').classList.remove('blur');
    document.getElementById('footer').classList.remove('blur');
    window.location.hash = "";
}

// Slideshow functions

var slideshow_mode = false;

function slideshow()
{
    if (links.length === 0)
        return displayAlert('There are no images in this folder.', 'error');

    displayAlert('Loading...', 'info');
    blur();
    setHash("slideshow");
    slideshow_mode = true;
    document.getElementById('scenter').innerHTML= '<img src="' + links[0].innerHTML + '" onload="hideAlert()"/>';
    setTimeout(slideshowNext, 7000, 1);
}

function slideshowNext(index)
{
    if (!slideshow_mode)
        return;
    if (index === links.length)
        exitSlideshow();

    displayAlert('Loading...', 'info');
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
    displayAlert('Loading...', 'info');
    blur();
    current_index = index;
    fullscreen_mode = true;
    setHash(current_index.toString());
    document.getElementById('fcenter').innerHTML= '<img src="' + links[index].innerHTML + '" onload="hideAlert()"/>';
}

function exitFullscreen()
{
    clear();
    fullscreen_mode = false;
    document.getElementById('fcenter').innerHTML = '';
}

function previous()
{
    displayAlert('Loading...', 'info');
    current_index--;
    if (current_index === -1)
        current_index = links.length - 1;
    setHash(current_index.toString());
    document.getElementById('fcenter').getElementsByTagName('img')[0].src = links[current_index].innerHTML;
}

function next()
{
    displayAlert('Loading...', 'info');
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
    var hash = window.location.hash;
    if (hash === "")
        return;
    if (hash === "slideshow")
        return slideshow();

    var index = parseInt(hash, 10);
    if (!isNaN(index) && index < links.length)
        return fullscreen(index);
}

document.onkeydown = checkKey;
window.onload = initiate;
