/*global $, jQuery, alert, confirm */
$(function () {
	"use strict";
    
    // switch betweem login and signup
    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });
    
	// Hide placeholder on form focus
    
    //fire the selectBoxIt
    
    $("select").selectBoxIt({autoWidth: false});

	$('[placeholder]').on("focus", function () {

		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');

	}).on("blur", function () {
		$(this).attr('placeholder', $(this).attr('data-text'));
	});

	// add asterisk on required field

	$("input").each(function () {
		if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }

	});


	//confirmation messege on button delete

	$('.confirm').on("click", function () {
		return confirm('Are you Sure ? ');
	});
    
    $('.live-name').keyup(function () {
        $('.live-preview .caption h3').text($(this).val());
    });
    
    $('.live-desc').keyup(function () {
        $('.live-preview .caption p').text($(this).val());
    });
    
    $('.live-price').keyup(function () {
        $('.live-preview .price-tag').text('$' + $(this).val());
    });
    
});