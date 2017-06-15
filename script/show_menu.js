
function menu_open ()
{
    var head = document.getElementById('head');
    var menu = document.getElementById('menu');

    menu.classList.remove('hidden-xs');
    menu.classList.remove('hidden-sm');     
    head.classList.add('hidden-xs');
    head.classList.add('hidden-sm'); 
    
}

function menu_close ()
{
    var head = document.getElementById('head');
    var menu = document.getElementById('menu');

    head.classList.remove('hidden-xs');
    head.classList.remove('hidden-sm');     
    menu.classList.add('hidden-xs');
    menu.classList.add('hidden-sm');    
}

function active_search ()
{
    var search = document.getElementById('search');
    var icons = document.getElementById('icons');
    search.classList.remove('col-sm-4');
    search.classList.add('col-sm-7');
    icons.classList.remove('col-sm-8');
    icons.classList.add('col-sm-5');
    
    search.classList.remove('col-xs-1');
    search.classList.add('col-xs-12');
    icons.classList.remove('col-xs-11');
    icons.classList.add('hidden-xs');
    
}

function blur_search ()
{
    var search = document.getElementById('search');
    var icons = document.getElementById('icons');
    search.classList.add('col-sm-4');
    search.classList.remove('col-sm-7');
    icons.classList.add('col-sm-8');
    icons.classList.remove('col-sm-5');
    
    search.classList.add('col-xs-1');
    search.classList.remove('col-xs-12');
    icons.classList.add('col-xs-11');
    icons.classList.remove('hidden-xs');
}