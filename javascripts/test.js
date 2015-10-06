$d = $(document);
$d.ready(function() {
    $s = $('.slider');
    $dim = $('.dimmer');
});


// Hover Effect on Navigation Bar Items

$(function(){
    $('.navbar-link').mouseenter(function(){
        $(this).addClass('hovered');
    });
    $('.navbar-link').mouseleave(function(){
        $(this).removeClass('hovered');
    });
});

// Sliding In/Out the Account Dropdown Menu

$(function(){
    $menu = $('.account-menu-div');
    $('#user').click(function(){
        toggleAccountMenu();
    });
});

function toggleAccountMenu() {
    $menu = $('.account-menu-div');
    var x = $('#user').offset();
    var width = $menu.width();
    if ($menu.is(':visible')){
            $menu.animate({opacity: 0, top:'2.5em'}, 'fast', function(){
            $menu.hide();
        });
    } else {
        $menu.css('left', x.left - width/2);
        $menu.css('opacity', '0');
        $menu.show();
        $menu.animate({opacity: 1, top:'3.2em'}, 'fast');
    }
}

// Hover Effect on Account Dropdown Menu Items

$(function (){
    $('.account-menu-item').mouseenter(function(){
        $(this).addClass('menu-item-highlighted');
    });
    $('.account-menu-item').mouseleave(function(){
        $(this).removeClass('menu-item-highlighted');
    });
})
