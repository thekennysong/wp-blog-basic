// =========================================================================
// GLOBAL VARS  ============================================================
// =========================================================================
var offset = 0;
var loadingAjax = 0;

// =========================================================================
// ON DOM READY  ===========================================================
// =========================================================================
$(function() {

	// Browser Support
	supportPlaceholders();
	
	// Grabfeeds
	if ($('.bxslider').length > 0) {
		$('.bxslider').bxSlider({
			pager: false,
			mode: 'fade',
			'prevText': '<i class="fa fa-angle-left"></i>',
			'nextText': '<i class="fa fa-angle-right"></i>'
		});
	}

	// BS3 Navigation Helper
	$('.navbar .nav > li').click(function() {
		var href = $(this).find('a').first().attr('href');
		window.location.href = href;
	});
	
	// Hover Dropdowns
	$('.dropdown-menu').parent().hover(function(){
		$(this).find('.dropdown-menu').stop().addClass('show-dropdown');
	}, function(){
		$(this).find('.dropdown-menu').stop().removeClass('show-dropdown');
	});
	
	// Load posts
	infiniteLoad();
	$(window).scroll(function(){
		infiniteLoad();
	});
	$('.load-more').click(function() {
		var l = Ladda.create(this);
		loadMore($(this), l);
		return false;
	});
});

// =========================================================================
// ON WINDOW LOAD  =========================================================
// =========================================================================
$(window).load(function() {
	
});


// =========================================================================
// CUSTOM FUNCTIONS  =======================================================
// =========================================================================
function infiniteLoad() {
	if ($('.load-more').length > 0 && loadingAjax != 1) {
		var windowHeight = $(window).height();
		var scrollTop     = $(window).scrollTop(),
	    elementOffset = $('.load-more').offset().top,
	    distance      = (elementOffset - scrollTop);

		if (distance <= windowHeight * 0.75) {
			loadingAjax = 1;
			$('.load-more').click();
		}
	}
}

function loadMore(button, l) {
	l.toggle();

	// Get Query Params
	var postsPerPage = button.data('posts-per-page');
	
	if (offset == 0) {
		offset = postsPerPage;
	}
	
	var category = button.data('category');
	var search = button.data('search');

	var queryString = 'posts_per_page='+postsPerPage+'&offset='+offset+'&category='+category+'&s='+search;		
	offset = offset + postsPerPage;

	$.getJSON(window.location.protocol + '//' + window.location.hostname + '/api/get_posts?'+queryString, function (data) {

		if (data.count != 0) {
			
			// Posts!				
			$.each(data.posts, function(index, value) {
				
				// Default Fields
				var id = value.id;
				var title = value.title;
				var url = value.url;
				var date = value.date;
				date = Date.parse(date).toString('MMMM d, yyyy');
				var excerpt = value.excerpt;
				var featuredImage = '';
				if ($.isEmptyObject(value.attachments) == false) {
					featuredImage = '<a href="'+url+'" class="featured-image"><img width="700" height="317" src="'+value.attachments[0].images["feed-size"].url+'"></a>';
				}
								
				// Custom Fields
				var titleEncoded = encodeURIComponent(title);
				var urlEncoded = encodeURIComponent(url);
				var source = '';
				if (typeof value.custom_fields.source !== 'undefined') {
					source = value.custom_fields.source[0];
				}				
						
				// Template
				var template = $('#post-template').html();
				
				// Parse and Replace
				template = template.replace(/{title}/g, title);
				template = template.replace(/{title_encoded}/g, titleEncoded);
				template = template.replace(/{permalink}/g, url);
				template = template.replace(/{permalink_encoded}/g, urlEncoded);
				template = template.replace(/{date}/g, date);
				template = template.replace(/{excerpt}/g, excerpt);
				template = template.replace(/{source}/g, source);
				template = template.replace(/{featured_image}/g, featuredImage);
				
					
				$('#content .post').last().after(template);
			});
			loadingAjax = 0;
			l.toggle();
		} else {
			$('.load-more').remove();
		}
		
		if (data.count < postsPerPage) {
			$('.load-more').remove();
		}
	});
}

// =========================================================================
// CORE FUNCTIONS  =========================================================
// =========================================================================
function isEmailValid(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, null])[1]
    );
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function supportPlaceholders() {
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur();
	$('[placeholder]').parents('form').submit(function() {
		$(this).find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		})
	});
}