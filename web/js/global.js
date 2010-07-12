$(document).ready(function() {
	
	var SLIDER_STATE_COOKIE_NAME = 'test_cookie';
    var cookie_options = { path: '/', expires: 10 };
    
    $(".slideToggleAction").click( function() {
        if ($("#slider").hasClass("closed")) {
        	$("#sliderPrimaryContent").animate({ 
                height: "150px",
                marginTop: "0px"
                }, 250 );
        	$("#sliderSecondaryContent").show();
            $("#sliderHandle span").html('Click to Close');
            $("#slider").removeClass("closed");
            $.cookie(SLIDER_STATE_COOKIE_NAME, 'open', cookie_options);
            return false;
        } else {		
	        $("#sliderSecondaryContent").hide();	    	
        	$("#sliderPrimaryContent").animate({ 
                height: "0px",
				marginTop: "0px"
                }, 250 );
            $("#sliderHandle span").html('Show All Feeds On This Page');
            $("#slider").addClass("closed");
            $.cookie(SLIDER_STATE_COOKIE_NAME, 'closed', cookie_options);
            return false;
        }
    });

    if ($.cookie(SLIDER_STATE_COOKIE_NAME) && $.cookie(SLIDER_STATE_COOKIE_NAME) == "open") {
    	$("#sliderSecondaryContent").show();
    	$("#sliderPrimaryContent").animate({ 
            height: "150px",
            marginTop: "0px"
            }, 0 );
        $("#sliderHandle span").html('Click to Close');
        $("#slider").removeClass("closed");
	}

    $(".feedDetailDisplayAction").click( function() {
        if ($(this).hasClass("closed")) {
        	$(".feedDetailDisplayAction").removeClass("open").addClass("closed");
        	$(this).removeClass("closed").addClass("open");
        	$("#sliderSecondaryContent").show();
            $("#sliderSecondaryContentInner").show();
            $(".feedDetail").hide();
			$($(this).attr("href")).show();
			return false;
        } else {
        	$("#sliderSecondaryContent").hide();
        	$("#sliderSecondaryContentInner").hide();
        	$(".feedDetailDisplayAction").removeClass("open").addClass("closed");
	    	$(".feedDetail").hide();
	    	return false;
        }
    });
    
    $(".feedViewAction").click( function() {
    	if ($("#slider").hasClass("closed")) {
        	$("#sliderPrimaryContent").animate({ 
                height: "150px",
                marginTop: "0px"
                }, 250 );
        	$("#sliderSecondaryContent").show();
            $("#sliderHandle span").html('Click to Close');
            $("#slider").removeClass("closed");
            $.cookie(SLIDER_STATE_COOKIE_NAME, 'open', cookie_options);
        }
    	feed_link = $($(this).attr("href") + "_link");
    	if (feed_link.hasClass("closed")) {
        	$(".feedDetailDisplayAction").removeClass("open").addClass("closed");
        	feed_link.removeClass("closed").addClass("open");
        	$("#sliderSecondaryContent").show();
            $("#sliderSecondaryContentInner").show();
            $(".feedDetail").hide();
			$($(this).attr("href")).show();
        }
    	return false;
    });

    $(".feedDetailCloseAction").click( function() {
    	$("#sliderSecondaryContentInner").hide();
    	$(".feedDetailDisplayAction").removeClass("open").addClass("closed");
    	$(".feedDetail").hide();
    	return false;
    });
     
    $(".feedContent").hover(
    	function () {
    		$(this).addClass('feedContentHover');
    	},
    	function () {
    		$(this).removeClass('feedContentHover');
    	}
    );
    
});