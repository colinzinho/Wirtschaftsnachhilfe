let header,pageContainer,toTopBtn,thresholdToTopBtn=-1;const imaginarySpace=20;function toTopBtnHandling(t,e){t>=thresholdToTopBtn&&e&&(toTopBtn.hasClass("fadeOut")&&toTopBtn.removeClass("fadeOut"),showElement(toTopBtn)),t<thresholdToTopBtn&&!e&&hideElementSmoothly(toTopBtn)}function showElement(t){t.removeClass("hidden"),t.addClass("fadeIn")}function hideElementSmoothly(t){t.addClass("fadeOut"),setTimeout(function(){t.addClass("hidden")},2e3)}function onClick(){$("#logo-link").click(function(t){window.location.reload(0)}),$("#navigation a").click(function(t){let e;scrollingTo($(this).data("nav-section"))||t.preventDefault()}),$("#to-top-button").click(function(t){window.history.pushState("","","/index.html"),$(window).scrollTop(0)})}function onContactBtnClicked(t){scrollingTo("contact")||t.preventDefault()}function scrollingTo(t){let e=t.toLowerCase(),o=!header.hasClass("fixed-header")&&625>$(window).width()?19:-1;if(!$('div[data-section="'+e+'"]').length)return!1;{let n=getHeaderHeight()+o;return $("html").animate({scrollTop:$('div[data-section="'+e+'"]').offset().top-n},250),$("#menubutton").hasClass("show-menu")&&$("#menubutton").removeClass("show-menu"),!0}}function getHeaderHeight(){return $("#page-header")[0].getBoundingClientRect().height}$(document).ready(function(){header=$("#page-header"),pageContainer=$("#page-container"),toTopBtn=$("#to-top-button")}),$(window).scroll(function(){let t=$(window).scrollTop(),e=header.hasClass("fixed-header"),o=toTopBtn.hasClass("hidden");t>20&&!e?(header.addClass("fixed-header"),thresholdToTopBtn=$('div[id="offers"]').offset().top-getHeaderHeight(),625>$(window).width()&&(this.thresholdToTopBtn-=20),pageContainer.addClass("animation"),pageContainer.css("margin-top","-41px")):t<=0&&e&&(header.removeClass("fixed-header"),pageContainer.css("margin-top","-1px")),-1!==thresholdToTopBtn&&toTopBtnHandling(t,o)});