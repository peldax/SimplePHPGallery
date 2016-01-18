var current_index = 0;
var fullscreen_mode = false;
var links = [];

function refreshLinks()
{
    links = document.getElementById('links').getElementsByTagName('p');
}

function fullscreen(index)
{
    refreshLinks();
    current_index = index;
    fullscreen_mode = true;
    var element = document.getElementById('center');
    element.innerHTML= '<img src="' + links[index].innerHTML + '"/>';
}

function exitFullscreen()
{
    fullscreen_mode = false;
    var element = document.getElementById('center');
    element.innerHTML= '';
}

function previous()
{
    current_index--;
    var element = document.getElementById('center');
    if (current_index == -1)
        current_index = links.length - 1;
    element.innerHTML= '<img src="' + links[current_index].innerHTML + '"/>';
}

function next()
{
    current_index++;
    var element = document.getElementById('center');
    if (current_index == links.length)
        current_index = 0;
    element.innerHTML= '<img src="' + links[current_index].innerHTML + '"/>';
}

document.onkeydown = checkKey;

function checkKey(e)
{
    if (!fullscreen_mode)
        return;
    e = e || window.event;
    switch (e.keyCode)
    {
        case 37: // left arrow
            previous(); return;
        case 38: // up arrow
            previous(); return;
        case 39: // right arrow
            next(); return;
        case 40: // down arrow
            next(); return;
        case 27: // escape
            exitFullscreen(); return;
    }
}