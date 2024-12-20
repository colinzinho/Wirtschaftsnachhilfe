var header;
var pageContainer;
var toTopBtn;
var thresholdToTopBtn = -1;
const imaginarySpace = 20; /* stands for the imagniary space in px above the header */ 

$(document).ready(function() {
    /* Set variable values after initialization of site */
    header = $("#page-header");
    pageContainer = $("#page-container");
    toTopBtn = $("#toTopBtn");
});

$(window).scroll(function() {
var windowpos = $(window).scrollTop(); /* gets vertical scrollbar position of window element(top left corner of window) */
var headerIsFixed = header.hasClass("fixed-header");
var btnIsHidden = toTopBtn.hasClass("hidden");

if(windowpos > imaginarySpace && !headerIsFixed) {
    header.addClass("fixed-header"); /* Changes/ decreases height of header */
    thresholdToTopBtn = $('div[id="offers"]').offset().top - getHeaderHeight(); /* Estimates 'space' between bottom left corner of navbar and top left corner of offers section  */
    if($(window).width() < 625) {
        this.thresholdToTopBtn -= 20; /* If not set 'toTopBtn' shows to late and hides to early... */
    }
    pageContainer.addClass("animation"); /* enables smooth animation (ease-in-ease-out) */
    pageContainer.css('margin-top', -41 + 'px'); /* whole website content hops up by 41px - is being used for smooth animation/ transition */
}else if(windowpos <= 0 && headerIsFixed) { /* restore default size of header (height) */
    header.removeClass("fixed-header");
    pageContainer.css('margin-top', -1 + 'px'); /* whole website content hops down by 40px - is being used for smooth animation/ transition */
}

/* thresholdToTopBtn only turns positive value as website has being scrolled by more than 20px */
if(thresholdToTopBtn !== -1) {
    toTopBtnHandling(windowpos, btnIsHidden);
}
});

/* Controls Visibility of 'backToTop' button */
function toTopBtnHandling(windowpos, btnIsHidden) {
if(windowpos >= thresholdToTopBtn && btnIsHidden) {
    if(toTopBtn.hasClass("fadeOut")) {
        toTopBtn.removeClass("fadeOut");
    }
    showElement(toTopBtn);
    }
    if(windowpos < thresholdToTopBtn && !btnIsHidden) {
    hideElementSmoothly(toTopBtn);
    }
};

/* Helper function: show 'backToTop' button */
function showElement(element) {
    element.removeClass("hidden");
    element.addClass("fadeIn");
};

/* Helper function: hide 'backToTop' button smoothly (animation) */
function hideElementSmoothly(element) {
    element.addClass("fadeOut");  
    setTimeout( function () {
        element.addClass("hidden");
    }, 2000);
};

function onClick() {
    /* Reload as Logo in top left corner is clicked */
    $("#logo-link").click(function (event) {
        window.location.reload(0);
    });

    /* Sets behavior as links in navbar are being clicked */
    $("#navigation a").click(function (event) {
        let sectionName = $(this).data("nav-section"); 
        let result = scrollingTo(sectionName);
        if(!result) {
        event.preventDefault(); // what does this do - exception handler??
        }
    });

    
    $(".contact-btn").click(function (event) {
        
    });

    /* Arrow up button which hides in the bottom right corner of the website */
    $("#toTopBtn").click(function (event) {
        window.history.pushState('', '', '/index.html');
        $(window).scrollTop(0)
    });
};

/* Sets behavior as one of the various contact buttons is being clicked  */
function onContactBtnClicked(event) {
    let result = scrollingTo('contact');
    if(!result) {
    event.preventDefault(); // what does this do - exception handler??
    }
}

/* Helper function: enables scrolling on whole webpage */
function scrollingTo(input) {
    var sectionName = input.toLowerCase();
    /* If not set to 19 on small devices (< 625px) scrolling mechanism scrolls to far */
    var collapsingValueNavBar = (!header.hasClass('fixed-header') && $(window).width() < 625) ? 19 : -1;

    if ($('div[data-section="' + sectionName + '"]').length) {
    var headerHeight = getHeaderHeight() + collapsingValueNavBar;
    $("html").animate(
        {
        scrollTop:
            $('div[data-section="' + sectionName + '"]').offset().top - headerHeight
        },
        250
    );
    if($("#menubutton").hasClass('show-menu')) {
        $("#menubutton").removeClass('show-menu');
    }
    return true;
    } else {
    return false;
    }
};

/* Helper function: get current height of webpage header */
function getHeaderHeight() {
    return $('#page-header')[0].getBoundingClientRect().height;
};