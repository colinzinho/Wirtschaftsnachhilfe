let windowpos;
let header;
let mainContentContainer;
let toTopBtn;
let thresholdToTopBtn;
let headerIsFixed;
const imaginarySpace = 20; /* Slows down the header animation by preventing the animation to start as user starts to scroll */ 
  
/* Ensuring that the elements are loaded before accessing them. DOMContentLoaded get trigger before $(document).ready script runs */
document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('load', adjustFollowingElementPosition);
});

$(document).ready(function() {
    /* Set variable values after initialization of site */
    windowpos = $(window).scrollTop(); /* Gets vertical scrollbar position of window element(top left corner of window) */
    header = $('#page-header');
    mainContentContainer = $('#main-content');
    toTopBtn = $('#to-top-button');
});

/* Helper function: Sets margin-top of main content manually. This avoids overlapping with fixed header element! */
function adjustFollowingElementPosition() {
    const fixedElement = document.getElementById('page-header');
    const followingElement = document.getElementById('main-content');
    const fixedHeight = fixedElement.offsetHeight;

    followingElement.style.marginTop = fixedHeight + 'px';
}

function onClick() {
    /* Reload as Logo in top left corner is clicked */
    $('#logo-link').click(function (event) {
        window.location.reload(0);
    });

    /* Sets behavior as links in navbar are being clicked */
    $('#navigation a').click(function (event) {
        let sectionName = $(this).data('nav-section'); 
        scrollingTo(sectionName);
    });

    /* Arrow up button which hides in the bottom right corner of the website */
    $('#to-top-button').click(function (event) {
        window.history.pushState('', '', '/index.html');
        $(window).scrollTop(0)
    });
};

/* Sets behavior as one of the various contact buttons is being clicked  */
function onContactBtnClicked(event) {
    scrollingTo('contact');
}

$(window).scroll(function() {
    windowpos = $(window).scrollTop(); /* Gets vertical scrollbar position of window element(top left corner of window) */
    headerIsFixed = header.hasClass('fixed-header');

    if(windowpos > imaginarySpace && !headerIsFixed) {
        header.addClass('fixed-header'); /* Changes decrease height of header */
        scrollAnimationHeader(-40);
        thresholdToTopBtn = Math.round($('div[id="offers"]').offset().top) - getHeaderHeight(); /* Estimates 'space' between bottom left corner of navbar and top left corner of offers section  */
    }
    
    if(windowpos <= 0 && headerIsFixed) {
        header.removeClass('fixed-header'); /* Restore default size of header (height) */
        scrollAnimationHeader(40);
    }

    toTopBtnHandling(windowpos, toTopBtn.hasClass('hidden'));
});

/* Helper function: animates main content by setting it's 'margin-top'. The resulting animation is ether hop up or down! */
function scrollAnimationHeader(amount) {
    if(!mainContentContainer.hasClass('animation')) {
        mainContentContainer.addClass('animation'); /* Can't be set by default in css! 'margin-top' is being set while web page built and otherwise it would apply smooth transition effect while building web page... */
    }
    const decreasedValue = parseInt(mainContentContainer.css('margin-top')) + amount; /* Without parsing jQuery .css function returns number + px at the end */
    mainContentContainer.css('margin-top', decreasedValue + 'px'); /* Main website content hops up by 40px - is being used for smooth animation/ transition */
    /* If amount is bigger than 0/positive then we arrived at the top of the page and the value for windowpos remains 0! */
    if(amount < 0) {
        windowpos += 40; /* $(window).scrollTop() value is not affected by negative margin-top (main website content hops up)! */
    }
}

/* Helper function: enables scrolling on whole webpage */
function scrollingTo(input) {
    let sectionName = input.toLowerCase();
    let headerHeight = getHeaderHeight();
    if(!header.hasClass('fixed-header')){
        if($(window).width() < 625) {
            headerHeight += 20; /* The difference in the sum of padding top plus bottom of fixed and collapsed header on small devices is only 20px... as soon as main content hops up by 40px it hops up too far by 20px! */
        }
        header.addClass('fixed-header'); /* Changes decrease height of header */
        scrollAnimationHeader(-40);
    }

    thresholdToTopBtn = Math.round($('div[id="' + sectionName + '"]').offset().top) - headerHeight; /* Estimates 'space' between bottom left corner of navbar and top left corner of offers section  */
    $('html').animate(
        {
        scrollTop:
            $('div[data-section="' + sectionName + '"]').offset().top - headerHeight + 1
        },
        250
    );
    /* Hide collapsed site menu after being clicked and routing completed */
    if($('#menubutton').hasClass('show-menu')) {
        $('#menubutton').removeClass('show-menu');
    }
};

/* Controls Visibility of 'backToTop' button */
function toTopBtnHandling(windowpos, btnIsHidden) {
    if(windowpos >= thresholdToTopBtn && btnIsHidden) {
        if(toTopBtn.hasClass('fadeOut')) {
            toTopBtn.removeClass('fadeOut');
        }
        showElement(toTopBtn);
    }
    if(windowpos < thresholdToTopBtn && !btnIsHidden) {
        hideElementSmoothly(toTopBtn);
    }
    if(btnIsHidden == undefined) {
        showElement(toTopBtn);
    }
};

/* Helper function: show 'backToTop' button */
function showElement(element) {
    element.removeClass('hidden');
    element.addClass('fadeIn');
};

/* Helper function: hide 'backToTop' button smoothly (animation) */
function hideElementSmoothly(element) {
    element.addClass('fadeOut');  
    setTimeout( function () {
        element.addClass('hidden');
    }, 2000);
};

/* Helper function: get current height of webpage header */
function getHeaderHeight() {
    return $('#page-header').outerHeight();
};