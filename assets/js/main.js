//console.log(WURFL);

$(window).load(function(){
	$('#preloader').delay(500).fadeOut(700);
	if ($(window).width() <= 360 || screen.width <= 360){
		$('.bsr-filter-top .field-wrp > span + .selecter.xsmall').before('<br/>');
	}
});

//--- Main navigation
/* cbpHorizontalMenu.js v1.0.0
 * http://www.codrops.com
 * Licensed under the MIT license - http://www.opensource.org/licenses/mit-license.php
 * Copyright 2013, Codrops */
var cbpHorizontalMenu = (function(){
	var $listItems = $('#nav-menu > .nav-main > li'),
		$menuItems = $listItems.children('a[href="#"]'),
		$container = $('#container'),
		current = -1;
	function init(){
		$menuItems.on('click', open);
		$listItems.on('click', function(event){
			event.stopPropagation();
		});
	}
	function open(event){
		if (current !== -1){
			$listItems.eq(current)
				//.removeClass('cbp-hropen')
				.find('.nav-mega-wrapper').hide();
		}
		var $item = $(event.currentTarget).parent('li'),
			idx = $item.index();
		if (current === idx){
			$item
				//.removeClass('cbp-hropen')
				.find('.nav-mega-wrapper').hide();
			current = -1;
		} else {
			$item
				//.addClass('cbp-hropen')
				.find('.nav-mega-wrapper').show();
			current = idx;
			$container.off('click').on('click', close);
		}
		$('#nav-menu .nav-level-2nd > li, #nav-menu .nav-level-3rd > li').removeClass('current');
		$('#nav-menu .nav-level-3rd').hide();
		$('#nav-menu > .nav-main > li:nth-child(2) .nav-level-2nd > li:first-child').addClass('current');
		$('#nav-menu > .nav-main > li:nth-child(2) .nav-level-2nd > li:first-child > ul').each(function(){
			$(this).css('display', 'block');
			/*$(this).css('height', $(this).closest('.nav-mega-wrapper').find('.line-horz').height());
			$(this).mCustomScrollbar({theme: 'inset-dark', scrollbarPosition: 'outside'});*/
		});
		setTimeout(function(){
			//$('.nav-main > li:nth-child(2) > .nav-mega-wrapper').css('height', $('.click-highlight-2 > .nav-level-3rd').height() + 46);
		}, 50);
		return false;
	}
	function close(event){
		$listItems.eq(current)
			//.removeClass('cbp-hropen')
			.find('.nav-mega-wrapper').hide();
		current = -1;
		$('#nav-menu .nav-level-2nd > li, #nav-menu .nav-level-3rd > li').removeClass('current');
		$('#nav-menu .nav-level-3rd').hide();
	}
	return {init: init};
})();

$(document).ready(function(){
	FastClick.attach(document.body);
	//--- Main navigation
	cbpHorizontalMenu.init();
	// Mega menu
	$('#nav-menu .nav-mega-wrapper').prepend('<span class="line-horz" />');
	$('#nav-menu .nav-mega-wrapper > .nav-level-2nd').addClass('column-count-2');
	$('#nav-menu .nav-mega-wrapper > .nav-level-2nd > li').has('ul').parent().removeClass('column-count-2');
	// 2nd nav clicking
	$('#nav-menu .nav-level-2nd > li > a[href="#"]').click(function(){
		$('#nav-menu .nav-level-2nd > li').removeClass('current');
		$(this).parent().addClass('current');
		$('#nav-menu .nav-level-3rd').hide();
		$(this).next().show();
		/*$(this).next().css('height', $(this).closest('.nav-mega-wrapper').find('.line-horz').height());
		$('#nav-menu .nav-level-3rd').mCustomScrollbar({theme: 'inset-dark', scrollbarPosition: 'outside'});
		setTimeout(function(){
			$(this).next().mCustomScrollbar('update');
		}, 50);*/
		return false;
	});
	// Sundays at Bethany's 2nd nav clicking
	$('#nav-menu > .nav-main > li:nth-child(2) .nav-level-2nd > li').each(function(i){
		$(this).addClass('click-highlight-'+ (i + 1));
	});
	var $clickDiv = $('#nav-menu > .nav-main > li:nth-child(2) .nav-mega-highlight > div');
	$clickDiv.each(function(i){
		$(this).addClass('click-highlight-'+ (i + 1));
	});
	$('#nav-menu > .nav-main > li:nth-child(2) .nav-level-2nd > li > a').click(function(){
		var className = $(this).parent().attr('class').match(/click-highlight-\d+/);
		$clickDiv.addClass('hidden');
		$('#nav-menu > .nav-main > li:nth-child(2) .nav-mega-highlight > div.'+ className).removeClass('hidden');
	});
	//--- Mobile navigation
	$('body').prepend('<div id="mm-nav-menu" />');
	$('.nav-main').clone().appendTo('#mm-nav-menu');
	/*if (WURFL.form_factor == 'Smartphone'){
		$('#mm-nav-menu')
			.mmenu({
				classes: 'mm-light',
				offCanvas: {position: 'right'},
				dragOpen: {open: true}
			}).on('init', function(){
				$('#mm-nav-menu .nav-mega-highlight, #mm-nav-menu .line-horz').remove();
				setTimeout(function(){
					$('.nav-top > li').clone().appendTo('#mm-nav-menu .nav-main');
				}, 50);
			}).trigger('init');
	} else {*/
		$('#mm-nav-menu')
			.mmenu({
				classes: 'mm-light',
				offCanvas: {position: 'right'}
			}).on('init', function(){
				$('#mm-nav-menu .nav-mega-highlight, #mm-nav-menu .line-horz').remove();
				setTimeout(function(){
					$('.nav-top > li').clone().appendTo('#mm-nav-menu .nav-main');
				}, 50);
			}).trigger('init');
	//}
	//--- Main banners
	if ($('#banner-main .box-banner').size() > 0){
		$('#banner-main').removeClass('without-info');
	} else {
		$('#banner-main').addClass('without-info');
	}
	//--- Sliders & carousels
	$('.carousel-announcement').slick({autoplay: true, autoplaySpeed: 5000, dots: true});
	$('.carousel-memory-verse').slick({adaptiveHeight: true});
	$('.slider-generic').slick({
		adaptiveHeight: true,
		onInit: function(){
			if ($('.slider-generic .slick-track > .slick-slide').length == 1){
				$('.slider-generic .slick-slide').addClass('one-slide');
			}
		}
	});
	$('.slider-events').slick({adaptiveHeight: true, arrows: false, asNavFor: '.carousel-events', draggable: false, swipe: false});
	$('.carousel-events').slick({asNavFor: '.slider-events'});
	//--- Lightboxes
	//$('.lightbox-content').mCustomScrollbar({theme: 'inset-3', scrollbarPosition: 'outside'});
	$('.lightbox').fancybox({
		padding: 0, margin: 0, wrapCSS: 'fancybox-gallery',
		afterLoad: function(){
			setTimeout(function(){
				$('.slider-gallery').slick({asNavFor: '.carousel-gallery'});
				$('.carousel-gallery').slick({
					slidesToShow: 6,
					slidesToScroll: 1,
					focusOnSelect: true,
					asNavFor: '.slider-gallery',
					responsive: [
						{
							breakpoint: 541,
							settings: {slidesToShow: 4}
						}, {
							breakpoint: 361,
							settings: {slidesToShow: 3}
						}
					]
				});
				//$('.lightbox-content').mCustomScrollbar('update');
			}, 50);
		},
		afterClose: function(){
			$('.slider-gallery, .carousel-gallery').unslick();
			//$('.lightbox-content').mCustomScrollbar('disable');
		}
	});
	$('.lightbox-styled').fancybox({padding: 0, margin: [28, 20, 28, 20], width: 1000, maxHeight: 600, autoSize: false, wrapCSS: 'fancybox-content'});
	//--- Tabs
	$('.tabs-home, .tabs-generic').responsiveTabs({
		load: function(event, tab){tabsMobileInit();}
	});
	$('.tabs:not(.inside-nav)').responsiveTabs({
		setHash: true,
		load: function(event, tab){
			ourHeritage();
			if (window.location.hash){
				if ($('html').hasClass('resp-mobile')){
					$('.tabs:not(.inside-nav) .nav-tabs > li > a[href*="#"]').each(function(){
						var target = this.hash,
							$target = $(target);
						setTimeout(function(){
							$('html, body').animate({scrollTop: $target.offset().top - 45}, 500);
						}, 50);
					});
					// setTimeout(function(){
						// $('.sticky-side-nav').parent().css('height', $('.sticky-side-nav').outerHeight(true));
					// }, 1500);
				} else {
					$('.tabs:not(.inside-nav) .nav-tabs > li > a[href*="#"]').each(function(){
						var target = this.hash,
							$target = $(target);
						setTimeout(function(){
							$('html, body').animate({scrollTop: $target.offset().top - 113}, 500);
						}, 50);
					});
				}
				setTimeout(function(){
					if ($('html').hasClass('resp-mobile')){
						// $('.sticky-side-nav').waypoint('sticky', {offset: 0, wrapper: '<div class="sticky-side-wrapper"/>'});
					}
					else {
						if (window.location.hash.substr(1) != '' && window.location.hash.substr(1) != undefined && window.location.hash.substr(1) != null ) {
						
							var current_tab = "#" +window.location.hash.substr(1) + ' .sticky-side-nav';
							$("#content").stickem({item: current_tab, container: '.r-tabs-panel .col-full', stickClass: 'stuck', endStickClass: 'stuck-end', offset: 100});
							
						}
						
					}
				}, 250);
				if (!$('html').hasClass('resp-mobile')){
				}
			}
			// On tabs click
			$('.tabs:not(.inside-nav) .nav-tabs > li > a[href^="#"]').click(function(e){
				var target = this.hash,
					$target = $(target);
				imagesLoaded('.tabs', function(){
					setTimeout(function(){
						$('html, body').stop().animate({scrollTop: $target.offset().top - 113}, 500);
					}, 50);
				});
				$('.slider-generic, .slider-events, .carousel-events').unslick();
				$('.nav-our-heritage > li.active').removeClass('active');
				ourHeritage();
				// setTimeout(function(){
					$('.slider-generic').slick({adaptiveHeight: true});
					$('.slider-events').slick({adaptiveHeight: true, arrows: false, asNavFor: '.carousel-events', draggable: false, swipe: false});
					$('.carousel-events').slick({asNavFor: '.slider-events'});
					$('.pagination').jPages({
						containerID: 'listing', previous: 'Prev', next: 'Next', perPage: 20, minHeight: false,
						callback: function(pages, items){
							$('.pagination-legend').html(items.range.start +'-'+ items.range.end +' of '+ items.count);
						}
					});
					// $('.our-heritage-section:last').remove();
				// }, 250);
				setTimeout(function(){
					if (window.location.hash.substr(1) != '' && window.location.hash.substr(1) != undefined && window.location.hash.substr(1) != null ) {
						var current_tab = "#" +window.location.hash.substr(1) + ' .sticky-side-nav';
						$("#content").stickem({item: current_tab, container: '.r-tabs-panel .col-full', stickClass: 'stuck', endStickClass: 'stuck-end', offset: 100});
					}
				}, 300);
				e.preventDefault();
			});
			tabsMobileInit();
		}
	});
	function tabsMobileInit(){
		// On accordion click
		$('.r-tabs-accordion-title > a[href^="#"]').click(function(e){
			var target = this.hash,
				$target = $(target);
			imagesLoaded('#content', function(){
				setTimeout(function(){
					$('html, body').stop().animate({scrollTop: $target.offset().top - 130}, 500);
				}, 50);
			});
			$('.slider-generic, .slider-events, .carousel-events').unslick();
			$('.nav-our-heritage > li.active').removeClass('active');
			ourHeritage();
			if (window.location.hash.substr(1) != '' && window.location.hash.substr(1) != undefined && window.location.hash.substr(1) != null ) {
				var current_tab = "#" +window.location.hash.substr(1);
				var item_ele = current_tab + " .sticky-side-nav"

				if($(current_tab).find('.sticky-side-nav').length > 0) {
					if($(current_tab).find('.sticky-side-wrapper').length < 1) {
						$(current_tab).find('.sticky-side-nav').waypoint('sticky', {offset: 100, wrapper: '<div class="sticky-side-wrapper"/>'});
						$("#content").stickem({item: item_ele, container: '.col-full', stickClass: 'stuck', endStickClass: 'stuck-end', offset: 100});
					}	
					
				}
			}
			setTimeout(function(){
				$('.slider-generic').slick();
				$('.slider-events').slick({adaptiveHeight: true, arrows: false, asNavFor: '.carousel-events', draggable: false, swipe: false});
				$('.carousel-events').slick({asNavFor: '.slider-events'});
				$('.pagination').jPages({
					containerID: 'listing', previous: 'Prev', next: 'Next', perPage: 20, minHeight: false,
					callback: function(pages, items){
						$('.pagination-legend').html(items.range.start +'-'+ items.range.end +' of '+ items.count);
					}
				});
				$('.nav-our-heritage > li:first').addClass('active');
			}, 50);
			e.preventDefault();
		});
	}
	// Adjusting width of tabs
	$('.nav-tabs').each(function(){
		var tabsCount = $('.nav-tabs > li').length;
		if (tabsCount <= 4){
			$(this).addClass('more-padding');
		} else {
			$(this).removeClass('more-padding');
		}
	});
	//--- Expanded grid
	var $el = $('.grid-expand > li'),
		$expander = $('.grid-expanded');
	/* Expand the details */
	$expander.append('<a class="grid-close ion-ios-close-empty" href="#"></a>');
	$('.grid-close').click(function(e){
		hideAllGrid();
		e.preventDefault();
	});
	$el.find('.grid-click').click(function(e){
		hideAllGrid();
		$(this).next().show();
		$(this).closest('li').addClass('expanded').css('height', $(this).outerHeight() + $(this).next('.grid-expanded').outerHeight() + 10);
		$('.grid-expand > li:not(.expanded) img').css({opacity: .5});
		$(this).find('img').css({opacity: 1});
		if ($('html').hasClass('resp-mobile')){
			$('html, body').animate({scrollTop: $(this).closest('li').offset().top - 10}, 500);
		} else {
			$('html, body').animate({scrollTop: $(this).closest('li').offset().top - 132}, 500);
		}
		e.preventDefault();
	});
	function hideAllGrid(){
		$expander.hide();
		$el.removeClass('expanded').removeAttr('style');
		$('.slick-list').css('height', 'auto');
		$('.grid-expand > li img').css({opacity: 1});
	}
	//--- hoverIntent
	function mouseIn(){$(this).addClass('hover');}
	function mouseOut(){$(this).removeClass('hover');}
	$('.grid-gallery > li > figure').hoverIntent(mouseIn, mouseOut);
	//--- Video
	$('.video-overlay').click(function(){
		$(this).prev('img').hide();
		$(this).next('.video-iframe').fadeIn().find('iframe[src*="http://www.youtube.com/embed/"]')[0].src += '&autoplay=1';
	});
	$('.nav-tabs > li > a').click(function(){
		$('.video-iframe > iframe[src*="http://www.youtube.com/embed/"]').each(function(i){
			this.contentWindow.postMessage('{"event": "command", "func": "stopVideo", "args": ""}', '*');
		});
		//$('.video-iframe > iframe[src*="http://www.youtube.com/embed/"]').stopVideo();
	});
	//--- Events filtering
	$('.filter-view > a:first, .events-filter .events-filter-cat > a:first').addClass('selected');
	// Shared Events & Bible Study Resources function
	$('.btn-expand-filter').click(function(){
		var txt = $(this).closest('.filter-top').next('.filter-category').is(':visible') ? 'ion-ios-arrow-down' : 'ion-ios-arrow-up';
		$(this).find('i').removeClass().addClass(txt);
		$(this).toggleClass('active');
		$(this).closest('.filter-top').next('.filter-category').toggle();
		if ($('.bsr-filter-cat').is(':hidden')){
			$('.bsr-filter-cat > a, .bsr-filter-wrp .filter-category > a').removeClass('selected');
			$('.bsr-filter-wrp').addClass('hidden');
		}
		return false;
	});
	$('.filter-category > a').click(function(){
		$(this).parent().find('a.selected').removeClass('selected');
		$(this).addClass('selected');
		return false;
	});
	// List view
	$('.filter-view > a:first').click(function(){
		$('.filter-view > a:last').removeClass('selected');
		$(this).addClass('selected');
		$('.view-calendar').fadeOut().addClass('hidden');
		$('.view-list').fadeIn().removeClass('hidden');
		hideAllGrid();
		return false;
	});
	// Calendar view
	$('.view-calendar .grid-expanded').prepend('<div class="no-events">No events on selected filter</div>');
	$('.filter-view > a:last').click(function(){
		$('.filter-view > a:first').removeClass('selected');
		$(this).addClass('selected');
		$('.view-list').fadeOut().addClass('hidden');
		$('.view-calendar').fadeIn().removeClass('hidden');
		hideAllGrid();
		return false;
	});
	// Category filter
	$('.events-filter-cat > a').not(':first').click(function(){
		var filterVal = $(this).attr('rel');
		$('.list-events > li').each(function(){
			if (!$(this).hasClass(filterVal)){
				$(this).fadeOut().addClass('hidden');
			} else {
				$(this).fadeIn().removeClass('hidden');
			}
		});
		hideAllGrid();
	});
	$('.events-filter-cat > a:first').click(function(){
		$('.events-filter-cat > a.selected').removeClass('selected');
		$(this).addClass('selected');
		$('.list-events > li').fadeIn().removeClass('hidden');
		hideAllGrid();
		return false;
	});
	// Category filter on calendar view
	$('.grid-expanded > .list-events').each(function(){
		var numVisibleFilter = $(this).find('> li:not(.hidden)').length;
		$(this).closest('.grid-expanded').prev().find('.events-number').append(new Array(++numVisibleFilter).join('<i/>'));
	});
	$('.events-filter-cat > a').click(function(){
		$('.grid-expanded > .list-events').each(function(){
			var numVisibleFilter = $(this).find('> li:not(.hidden)').length;
			$(this).closest('.grid-expanded').prev().find('.events-number').empty().append(new Array(++numVisibleFilter).join('<i/>'));
		});
	});
	//--- Bible Study Resources
	// Category filter
	$('.bsr-filter-wrp .filter-category').css('display', 'block');
	$('.bsr-filter-cat > a').click(function(){
		$('.bsr-filter-wrp').removeClass('hidden');
		var filterVal = $(this).attr('rel');
		$('.bsr-filter-wrp > div').each(function(){
			if (!$(this).hasClass(filterVal)){
				$(this).fadeOut().addClass('hidden');
			} else {
				$(this).fadeIn().removeClass('hidden');
			}
		});
	});
	$('.field-search > input').keyup(function(){
		if ($(this).val().length == 0){
			$(this)
				.closest('.filter-top').find('.txt-right').addClass('hidden')
				.closest('.filter-top').next('.filter-category').addClass('hidden');
		} else {
			$(this)
				.closest('.filter-top').find('.txt-right').removeClass('hidden')
				.closest('.filter-top').next('.filter-category').removeClass('hidden');
		}
	});
	// Tabs
	$('#bible-study-resources .r-tabs-panel').prepend('<span class="line-horz" />');
	// If there's no Bible's chapters
	$('.list-bsr-chapters').not(':has(.book-chapter)').prev().addClass('no-accordion');
	$('.list-bsr > p.no-accordion').each(function(){
		$(this).find('a').removeAttr('href');
	});
	// Article listing
	$('.no-touch .list-articles > div').hover(function(){
		$(this).find('.nav-features').show();
	}, function(){
		$(this).find('.nav-features').hide();
	});
	// Sliding listing menu on the left
	$('.nav-bsr-listing').mmenu({classes: 'mm-light', 'labels': true});
	//--- eCards
	// WYSIWYG HTML Editor
	$('#ecard-editor').htmlarea({
		toolbar: [
			[/*'h3', '|', 'p', '|', */'bold', '|', 'italic', '|', 'underline', '|', 'forecolor', '|', {
				css: 'bgcolor',
				text: "Background Color",
				action: function(btn){
					jHtmlAreaColorPickerMenu($('.bgcolor', this.toolbar), {
						colorChosen: function(color){
							$('.jHtmlArea iframe').contents().find('body').css('background-color', color);
						}
					});
				}
			}]
		],
		toolbarText: {
			/*h3: "Header", p: "Paragraph", */bold: 'Bold', italic: 'Italic', underline: 'Underline', forecolor: 'Text Color'
		},
		loaded: function(){
			$('.ToolBar > ul > li').click(function(){
				$('.ToolBar > ul > li.clicked').removeClass('clicked');
				$(this).addClass('clicked');
			});
		}
	});
	// Tweaking the iframe
	$('.jHtmlArea iframe').contents().find('body')
		.css({
			'font': '16px/1.3 Arial, Helvetica, san-serif',
			'color': '#212121',
			'margin': '.8rem'
		})
		.focus(function(){
			$(this).find('p').hide();
		});
	// Form
	$('#ecard').find('.field-wrp.hidden').prepend('<a class="field-close link-txt" href="javascript:;"><i class="ion-close-round"/> Remove</a>');
	//--- Wall Display
	$('.wall-display').each(function(){
		if ($(this).find('li').length <= 4){
			$(this).find('li.box-gray').addClass('hide-before');
		}
	});
	$('.wall-display > li:nth-child(4)').after('<li/>');
	//--- Sitemap
	$('.listing-sitemap > li').each(function(){
		if ($(this).children('figure').length){
			$(this).addClass('has-figure');
			$(this).parent().addClass('c-two');
		}
		setTimeout(function(){
			$('.grid-sitemap-sect').each(function(){
				$(this).find('.listing-sitemap > li:not(.has-figure)').wrapAll('<div class="no-figure"/>');
			});
		}, 50);
	});
	//--- Footer
	$('#footer-links > li > a[href*="#"]').click(function(){
		var target = this.hash,
			$target = $(target);
		setTimeout(function(){
			$('html, body').animate({scrollTop: $target.offset().top - 113}, 500);
		}, 50);
	});
	//--- Form
	$('.btn-add-address').click(function(){
		$('.field-wrp.hidden').eq(0).removeClass('hidden');
		if ($('.field-wrp.last').is(':visible')){
			$(this).parent().hide();
		}
		return false;
	});
	$('.btn-add-attendee').click(function(){
		$(this).parent().hide();
		$(this).closest('fieldset').next().removeClass('hidden');
		return false;
	});
	$('fieldset .field-close').click(function(){
		$(this).parent().addClass('hidden');
		$(this).parent().prev().find('.btn-add-attendee').parent().show();
		if ($('.field-wrp:hidden').length){
			$('.btn-add-address').parent().show();
		}
		return false;
	});
	// Selecter
	$('.select').dropdown({customClass: 'xsmall',mobile: true});
	$('.select-age').dropdown({label: "Age",mobile: true});
	$('.select-gender').dropdown({label: "Gender",mobile: true});
	$('.select-month').dropdown({label: "Month", customClass: 'filterDate',mobile: true});
	$('.select-year').dropdown({label: "Year", customClass: 'filterDate',mobile: true});
	$('.select-year-link').dropdown({customClass: 'small',mobile: true});
	$('.select-book').dropdown({label: "Select a book", customClass: 'selectBook',mobile: true});
	$('.select-category').dropdown({label: "Select a category", customClass: 'selectCategory',mobile: true});
	$('.select-session').dropdown({label: "Filter Messages",mobile: true});
	$('.select-day').dropdown({label: "Search by Day",mobile: true});
	// $('.fs-dropdown .fs-dropdown-options').mCustomScrollbar({theme: 'minimal-dark'});
	// Picker
	$('input[type=checkbox], input[type=radio]').picker();
	//--- jQuery UI
	$('.accordion').accordion({collapsible: true, heightStyle: 'content'});
	$('.accordion-bsr').accordion({collapsible: true, active: false, header: 'p:not(.no-accordion)', heightStyle: 'content'});
	//--- Misc.
	// Sticky tabs, inside nav, box
	$('.nav-tabs.sticky').prepend('<div class="sticky-tabs-header clearfix"/>');
	$('.sticky-tabs-header').prepend('<div class="sticky-tabs-logo"><span/></div><a class="sticky-tabs-back-top" href="javascript:;">BACK TO TOP</a>');
	$('.sticky-tabs-logo > span').html($('#banner-main').find('.title').html());
	$('.sticky').waypoint('sticky', {offset: 60, handler: function(){
		if ($('.nav-tabs').hasClass('stuck')){
			$('.sticky-tabs-header').addClass('fadeIn');
			$('.sticky-tabs-logo, .sticky-tabs-back-top').stop().fadeIn();
		} else {
			$('.sticky-tabs-header').removeClass('fadeIn');
			$('.sticky-tabs-logo, .sticky-tabs-back-top').stop().fadeOut();
		}
	}});
	$('.tabs.inside-nav').prepend('<a id="nav-inside-mobile" href="javascript:;">MENU</a>');
	if ($('#nav-inside-mobile').is(':visible')){
		// $('#nav-inside-mobile').waypoint('sticky', {offset: 0, wrapper: '<div class="sticky-side-wrapper"/>'});
		$('#nav-inside-mobile').click(function(){
			$('.nav-tabs').toggleClass('expanded');
		});
	}
	$(window).resize(function(){
		if ($('#nav-inside-mobile').is(':hidden')){
			$('.tabs.inside-nav .nav-tabs').css('display', 'block');
		}
	});
	setTimeout(function(){
		if (window.location.hash.substr(1) != '' && window.location.hash.substr(1) != undefined && window.location.hash.substr(1) != null ) {
			
			var current_tab = "#" +window.location.hash.substr(1) + ' .sticky-side-nav';
			$("#content").stickem({item: current_tab, container: '.col-full', stickClass: 'stuck', endStickClass: 'stuck-end', offset: 100});
		}		
	}, 250);
	// Match the height
	imagesLoaded('.col-full', function(){
		$('.col-full').children('section:not(.col-full)').matchHeight();
	});
	imagesLoaded('.tabs', function(){
		$('.match-height, .grid-picturesque').each(function(){
			$(this).children('li, div').matchHeight();
		});
	});
	// Download links
	$('.nav-download > li.disabled').each(function(){
		$(this).find('a').removeAttr('target').removeAttr('href');
	});
	if (WURFL.form_factor == 'Smartphone'){
		$('.nav-features').hide();
	}
	// Add class name for anchor links on Fellowship Groups section
	$('#nav-inside-mobile').next('a[id]').addClass('anchor-relative');
	//--- Pagination
	$('.pagination').jPages({
		containerID: 'listing', previous: 'Prev', next: 'Next', perPage: 20, minHeight: false,
		callback: function(pages, items){
			$('.pagination-legend').html(items.range.start +'-'+ items.range.end +' of '+ items.count);
			$('.pagination-count > strong').html(items.count);
		}
	});
	if (!$('.pagination > a').attr('href')){
		$('.pagination > a').click(function(){
			$('html, body').stop().animate({scrollTop: $('.col-full').offset().top});
		});
	}
	//--- Load next items
	var noItems = $('.load-next > li').size();
	var toBeShown = 20;
	$('#btn-load-next').click(function(){
		toBeShown = $('.load-next > li:visible').size() + 20;
		if (toBeShown < noItems){
			$('.load-next > li:lt('+ toBeShown +')').fadeIn();
		} else {
			$('.load-next > li:lt('+ noItems +')').fadeIn();
			$(this).parent().hide();
		}
		$('.col-full').children('section:not(.col-full)').css('height', 'auto');
		recountColHeight();
		return false;
	});
	function recountColHeight(){
		var hCol = $('.col-main').height();
		setTimeout(function(){
			$('.col-full').children('section:not(.col-full)').css('height', hCol);
		}, 1000);
	}
	//--- Back to top on mobile
	$(window).scroll(function(){
		if ($(this).scrollTop() > 50){
			$('#back-top').stop().animate({opacity: 1}, 500);
		} else {
			$('#back-top').stop().animate({opacity: 0}, 500);
		}
	});
	$('#back-top > a, .sticky-tabs-back-top').click(function(){
		$('html, body, #container').stop().animate({scrollTop: 0}, 500);
		return false;
	});
	//--- Responsive functions
	responsive();
	$(window).resize(function(){responsive();});
	$(window).on('orientationchange', function(){
		responsive();
	});
});

//--- HTML5 Audio Player
$(document).ready(function(){
	// Add in ID for each media player/bar
	$('.media-player').each(function(i){
		$(this).attr('id', 'media-player-'+ (i + 1));
	});
	$('.media-bar-wrp').each(function(i){
		$(this).attr('id', 'media-bar-'+ (i + 1));
	});
	// The real script
	// Audio #1
	var my_jPlayer_1 = $('#media-player-1'),
		my_media_1 = $('#media-bar-1 .media-bar'),
		my_playBtn_1 = $('#media-bar-1 .media-bar-icon'),
		my_playState_1 = $('#media-bar-1 .media-bar-title');
	var	opt_text_playing_1 = "Now playing...",
		opt_text_original_1 = $('#media-bar-1 .media-bar-title').text();
	my_playState_1.text(opt_text_original_1);
	my_jPlayer_1.jPlayer({
		ready: function(event){
			$(this).jPlayer('setMedia', {
				mp3: my_media_1.attr('href')
			});
		},
		play: function(event){
			my_playBtn_1.html('<i class="ion-ios-pause" />');
			my_playState_1.text(opt_text_playing_1);
		},
		pause: function(event){
			my_playBtn_1.html('<i class="ion-ios-play" />');
			my_playState_1.text(opt_text_original_1);
		},
		ended: function(event){
			my_playBtn_1.html('<i class="ion-ios-play" />');
			my_playState_1.text(opt_text_original_1);
		},
		swfPath: './assets/swf',
		cssSelectorAncestor: '#media-bar-1',
		supplied: 'mp3',
		wmode: 'window',
		autoBlur: false,
		seekBar: '.jp-seek-bar'
	});
	// Audio #2
	var my_jPlayer_2 = $('#media-player-2'),
		my_media_2 = $('#media-bar-2 .media-bar'),
		my_playBtn_2 = $('#media-bar-2 .media-bar-icon'),
		my_playState_2 = $('#media-bar-2 .media-bar-title');
	var	opt_text_playing_2 = "Now playing...",
		opt_text_original_2 = $('#media-bar-2 .media-bar-title').text();
	my_playState_2.text(opt_text_original_2);
	my_jPlayer_2.jPlayer({
		ready: function(event){
			$(this).jPlayer('setMedia', {
				mp3: my_media_2.attr('href')
			});
		},
		play: function(event){
			my_playBtn_2.html('<i class="ion-ios-pause" />');
			my_playState_2.text(opt_text_playing_2);
		},
		pause: function(event){
			my_playBtn_2.html('<i class="ion-music-note" />');
			my_playState_2.text(opt_text_original_2);
		},
		ended: function(event){
			my_playBtn_2.html('<i class="ion-music-note" />');
			my_playState_2.text(opt_text_original_2);
		},
		swfPath: './assets/swf',
		cssSelectorAncestor: '#media-bar-2',
		supplied: 'mp3',
		wmode: 'window',
		autoBlur: false,
		seekBar: '.jp-seek-bar'
	});
});

//--- Our Heritage
function ourHeritage(){
	if ($('#our-heritage').size() > 0){
		$('.oh-item').each(function(){
			$(this).append('<div class="oh-bull"/>');
		});
		//$('#our-heritage').append('<div class="our-heritage-section"/>');
		setTimeout(function(){
			// Script to track scroll ID
			$('.nav-our-heritage > li > a').click(function(e){
				$.scrollTo($(this).attr('href'), 700, {axis: 'y', offset: -160});
				$(this).parent().addClass('active');
				$(this).parent().siblings('.active').removeClass('active');
				e.preventDefault();
			});
			if (window.location.hash.substr(1) != '' && window.location.hash.substr(1) != undefined && window.location.hash.substr(1) != null ) {

				var current_tab = "#" +window.location.hash.substr(1);
				var $sections = $(current_tab).find('#our-heritage > .our-heritage-section');
				var $navs = $(current_tab).find('.nav-our-heritage > li');
			}
			else {
				var $sections = $('#our-heritage > .our-heritage-section');
				var $navs = $('.nav-our-heritage > li');
			}			

			var topsArray = $sections.map(function(){
				return $(this).position().top - 180; // make array of the tops of content sections, with some padding to change the class a little sooner
			}).get()
			var len = topsArray.length; // quantity of total sections
			var currentIndex = 0; // current section selected
			//topsArray[len - 1] = topsArray[len - 1] - 210;
			var getCurrent = function(top){ // take the current top position, and see which index should be displayed
				for (var i = 0; i <= len; i++){
					if(i == len & top > topsArray[i - 1]) {
						return i-1;
					}
					else {
						if (i == 0 && top < topsArray[i] ) {
							return -i;
						}
						else if (top > topsArray[i] && topsArray[i + 1] && top < topsArray[i + 1]){
							return i;
						}
					}
						
					
				}
			};
			// On scroll, call the getCurrent() function above, and see if we are in the current displayed section.
			// If not, add the "selected" class to the current nav, and remove it from the previous "selected" nav.
			$(window).scroll(function(e){
				var scrollTop = $(this).scrollTop();
				var checkIndex = getCurrent(scrollTop);
				if(checkIndex == 0) {
					$navs.eq(0).addClass('active').siblings('.active').removeClass('active');

				}
				else if (checkIndex !== currentIndex){
					currentIndex = checkIndex;
					$navs.eq(currentIndex).addClass('active').siblings('.active').removeClass('active');
				}
				else {
					$navs.eq(checkIndex).addClass('active').siblings('.active').removeClass('active');
				}
			});
		}, 50);
	}
}

//--- Responsive functions
responsive();
function responsive(){
	if ($(window).width() <= 1000 || screen.width <= 1000){
		$('html').addClass('resp-mobile');
		$('.carousel-memory-verse .slick-list, .slider-events .slick-list').css('height', 'auto');
		$('.nav-our-heritage').closest('.box-side').css('margin-top', '0');
	} else {
		$('html').removeClass('resp-mobile');
		$('html').removeClass('mm-opened mm-opening');
		$('#mm-nav-menu').removeClass('mm-current mm-opened');
		$('.nav-our-heritage').closest('.box-side').css('margin-top', '5rem');
	}
}
