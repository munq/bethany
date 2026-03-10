(function($) {
	$(document).ready(function() {
		// by default, prepened <div id="preloader"></div> to the <body> manually
		// remove preloader added in the main.js
		$('body').find('#preloader:last-child').remove();
		
			$('select[name=chapter_dropdown_intro]').change(function() {
				window.location = $(this).val();
			});
		
		$('select[name=chapter_dropdown]').change(function() {
			//window.location = $(this).val();
			
			// hide all day dropdown
			$('.chapter-day-dropdown').addClass('hidden');
			
			// show specific or selected chapter day dropdown
			$('.chapter-' + $(this).val() + '-day-dropdown').removeClass('hidden');
		});
		
		$('select[name=day_dropdown]').change(function() {
			window.location = $(this).val();
		});
		
		// show day dropdown for selected chapter dropdown
		if ($('select[name=chapter_dropdown]').children().size() > 0) {
			// hide all day dropdown
			$('.chapter-day-dropdown').addClass('hidden');
			
			// show specific or selected chapter day dropdown
			$('.chapter-' + $('select[name=chapter_dropdown] option').filter(':selected').val() + '-day-dropdown').removeClass('hidden');
		}
		
		
		// BSR Books of the Bible
		if ($('body').hasClass('page-template-bsr-books') || 
		$('body').hasClass('page-template-bsr-search-result')) {
			$('#bsr_book_chapter_dropdown').change(function(e) {
				window.location = $(this).val();
			});
			
			// Hide Labels
			if ( $('.pagination').length > 0 ) {
				$('.pagination').jPages("destroy");
			}
			$('.pagination').jPages({
				containerID: 'listing', previous: 'Prev', next: 'Next', perPage: 20, minHeight: false,
				callback: function(pages, items){
					$('.pagination-legend').html(items.range.start +'-'+ items.range.end +' of '+ items.count);
					$('.pagination-count > strong').html(items.count);
					var i;
					var daily_found = 0;
					var others_found = 0;
					for (i = 0; i < items.showing.length; ++i) {
					  if($(items.showing[i]).hasClass("item-daily")) {
						  daily_found++;
					  }
					  if($(items.showing[i]).hasClass("item-others")) {
						 others_found++;
					  }
					}

					if(daily_found == 0){
						 $(".label-daily").hide();
					}
					else{
						 $(".label-daily").show();
					}
					if(others_found == 0){
						 $(".label-others").hide();
					}
					else{
						$(".label-others").show();
					}
				}
			});
		}
		// END of BSR Books of the Bible
		
		

		// Pagination hacks
		// $listing = $('.pagination-wrp').parent().find('#listing');
		// if ($listing.children().size() < 20) {
			// $('.pagination-wrp').css('visibility', 'hidden');
		// }
		// END of Pagination hacks
		
		setTimeout(function(){
		  $('.mejs-mediaelement source[type="video/youtube"]').closest('.mejs-container').addClass('video-youtube');
		}, 100);

		//remove style inside registration lightbox
		$('a.lightbox-styled').click(function(){
			//get the id of the lightbox
			var id = $(this).attr("href");
			setTimeout(function(){
				$( id + " .wrapper .col-full .col-side" ).removeAttr("style");
				$( id + " .wrapper .col-full .col-main" ).removeAttr("style");
			},100);
		});


		if ( WURFL.form_factor == "Smartphone" ) {
			if ($('.nav-features').length > 0) {
				$('.nav-features').show();
			}
		}

		if ( $("#breadcrumb").length > 0 ) {
			var lis = $(".nav-breadcrumb").children().size();
			var li_last_active = $(".nav-breadcrumb li").eq(lis-2).innerWidth();
			var li_last_active_a = $(".nav-breadcrumb li").eq(lis-2).find("a").innerWidth();

			if (li_last_active >= 300) {
				$(".nav-breadcrumb li").eq(lis-1).css({"padding-left": li_last_active_a-li_last_active+15+"px"});
			}
		}

		/* New Script for height of listing  box http://beta.bethanyipc.sg/user-sst/
		Added: 16 March 2017
		==========================================================*/
		$('ul.listing.listing-sitemap').each(function() {
			var max_height = 0;
			$this = $(this);
			$this.find('li.has-figure').each(function(){
				$item = $(this);
				if(max_height < $item.find('figcaption > h3').height()) {
					max_height = $item.find('figcaption > h3').height();
				}
			});

			$(this).find('li.has-figure figcaption > h3').height(max_height + "px");

		});

	});
})(jQuery);


/* New Script
 Added: 18 January 2017
 ==========================================================*/
$(document).ready(function() {
	$('.loggedOn').on('click', function() {
		$(this).toggleClass('active');
		$('.postlogSubs').slideToggle(500);
		return false;
	});

	$('.postlogSubs').on('click', function(e) {
		e.stopPropagation();
	});
});

$(document).on('click', function() {
	$('.postlogSubs').hide();
	$('.loggedOn').removeClass('active');
});