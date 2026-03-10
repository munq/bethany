<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * The default index page
 *
 * Displays either the default or home page
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>
	
	
	<!-- Home Banner -->
	<section id="banner-home" style="background-image:url(<?php echo get_template_directory_uri(); ?>/assets/img/main-banners/home.jpg);">
		<div class="wrapper">
			<div class="title">
				God’s kind of church,<br/>
				<small>where God is touching lives, and people care for people.</small>
			</div>
			<div class="box-banner">
				<div class="box-banner-top clearfix">
					<span>Daily Devotions</span>
					<span><span class="numeric">10</span> July <span class="numeric">2014</span></span>
				</div>
				<div class="box-banner-content">
					<blockquote>
						<p>Yet I considered it necessary to send to you Epaphroditus, my brother, fellow worker, and fellow soldier, but your messenger and the one who ministered to my need.</p>
						<em>- Philippians 2:25</em>
					</blockquote>
					<p class="txt-right"><a href="#">Read Devotion</a></p>
				</div>
			</div>
		</div>
	</section><!--#end of Home Banner -->
	
	<!-- Carousel - Announcement -->
	<section id="carousel-announcement" class="add-separator">
		<div class="carousel-announcement">
			<div>
				<div class="cal-date-event">
					<div class="date">
						<span>07</span>
						<span>Aug<br/>2014</span>
					</div>
					<div class="event">No AM Bethany this Saturday</div>
				</div>
			</div>
			<div>
				<div class="cal-date-event">
					<div class="date">
						<span>12</span>
						<span>Aug<br/>2014</span>
					</div>
					<div class="event">No Prayer Meeting this Thursday</div>
				</div>
			</div>
			<div>
				<div class="cal-date-event">
					<div class="date">
						<span>14</span>
						<span>Aug<br/>2014</span>
					</div>
					<div class="event">No AM Bethany this Saturday</div>
				</div>
			</div>
		</div>
		<div class="carousel-view-all">
			<a href="#">View all announcements</a>
		</div>
	</section><!--#end of Carousel - Announcement -->
	
	<!-- Content -->
	<section id="content" class="home-page clearfix">
		<!-- Highlight #1 -->
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix desktop-only">
			<h2>This Week at Bethany</h2>
			
			<!-- Full-width Column -->
			<section class="col-full clearfix">
				<!-- Homepage Tabs -->
				<div class="tabs-home clearfix">
					<ul class="nav-tabs-home clearfix">
						<li><a href="#tab-1">Morning Worship</a></li>
						<li><a href="#tab-2">Evening Worship</a></li>
						<li><a href="#tab-3">Senior Sunday School 4</a></li>
						<li><a href="#tab-4">Bible Study</a></li>
						<li><a href="#tab-5">Prayer Meeting</a></li>
						<li><a href="#tab-6">Young People’s Group</a></li>
						<li><a href="#tab-7">Young Adults’ Group</a></li>
					</ul>
					
					<!-- Tab #1 -->
					<div id="tab-1" class="clearfix">
						<!-- Video -->
						<div class="video-embed box-img">
							<img src="http://placehold.it/980x594/369/333&text=video" />
							<div class="video-overlay">
								<span class="video-duration">15:25</span>
							</div>
							<div class="video-iframe">
								<iframe type="text/html" width="674" height="408" frameborder="0" src="http://www.youtube.com/embed/M7lc1UVf-VE?enablejsapi=1&rel=0" allowfullscreen></iframe>
							</div>
						</div><!--#end of Video -->
						
						<!-- Resource Download -->
						<div class="download-resource clearfix">
							<div class="resource">
								<h3 class="font-normal">“Now therefore, Arise” - Joshua 1</h3>
								<p>6 July 2014</p>
								<a href="#">View past messages</a>
							</div>
							<ul class="nav-download clearfix">
								<li class="bulletin"><a target="_blank" href="#">Bulletin</a></li>
								<li class="notes"><a target="_blank" href="#">Notes</a></li>
								<li class="video"><a target="_blank" href="#">Download Video</a></li>
								<li class="audio"><a target="_blank" href="#">Download Audio</a></li>
							</ul>
						</div><!--#end of Resource Download -->
					</div><!--#end of Tab #1 -->
					
					<!-- Tab #2 -->
					<div id="tab-2" class="clearfix">
						<p>Evening Worship</p>
					</div><!--#end of Tab #2 -->
					
					<!-- Tab #3 -->
					<div id="tab-3" class="clearfix">
						<p>Senior Sunday School 4</p>
					</div><!--#end of Tab #3 -->
					
					<!-- Tab #4 -->
					<div id="tab-4" class="clearfix">
						<p>Bible Study</p>
					</div><!--#end of Tab #4 -->
					
					<!-- Tab #5 -->
					<div id="tab-5" class="clearfix">
						<p>Prayer Meeting</p>
					</div><!--#end of Tab #5 -->
					
					<!-- Tab #6 -->
					<div id="tab-6" class="clearfix">
						<p>Young People’s Group</p>
					</div><!--#end of Tab #6 -->
					
					<!-- Tab #7 -->
					<div id="tab-7" class="clearfix">
						<p>Young Adults’ Group</p>
					</div><!--#end of Tab #7 -->
				</div><!--#end of Homepage Tabs -->
			</section><!--#end of Full-width Column -->
			
			<hr/>
			
		</section><!--#nd of Full-width Column -->
		
		<!-- Highlight #2 -->
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
			<h2 class="h2-lead">Pulpit Theme for July and August 2014</h2>
			<h2>Be Strong &amp; of Good Courage</h2>
			<p>Join us for Sunday Worship to learn to be strong and courageous from the book of Joshua.</p>
			
			<!-- Full-width Column -->
			<section class="col-full clearfix">
				<!-- Overlapping Image Column -->
				<section class="col-overlap-img float-left">
					<div class="box-img">
						<img src="http://placehold.it/800x414/bbb/fff&text=image" />
					</div>
					<p>Caption explaning the wall here lorem ipsum dolor</p>
					<p><a href="#">Read more</a></p>
				</section><!--#end of Overlapping Image Column -->
				
				<!-- Overlapping Banner Column -->
				<section class="col-overlap-banner float-right">
					<!-- Carousel - Memory Verse -->
					<section id="carousel-memory-verse">
						<div class="carousel-memory-verse">
							<div>
								<div class="box-banner">
									<div class="box-banner-top clearfix">
										<span>Memory Verse</span>
										<span><span class="numeric">1</span> July <span class="numeric">2014</span></span>
									</div>
									<div class="box-banner-content">
										<blockquote>
											<p>Yet I considered it necessary to send to you Epaphroditus, my brother, fellow worker, and fellow soldier, but your messenger and the one who ministered to my need. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
											<em>- Philippians 2:25</em>
										</blockquote>
										<p class="txt-right"><a href="#">Read more</a></p>
									</div>
								</div>
							</div>
							<div>
								<div class="box-banner">
									<div class="box-banner-top clearfix">
										<span>Memory Verse</span>
										<span><span class="numeric">12</span> July <span class="numeric">2014</span></span>
									</div>
									<div class="box-banner-content">
										<blockquote>
											<p>Yet I considered it necessary to send to you Epaphroditus, my brother, fellow worker, and fellow soldier, but your messenger and the one who ministered to my need.</p>
											<em>- Philippians 2:25</em>
										</blockquote>
										<p class="txt-right"><a href="#">Read more</a></p>
									</div>
								</div>
							</div>
							<div>
								<div class="box-banner">
									<div class="box-banner-top clearfix">
										<span>Memory Verse</span>
										<span><span class="numeric">18</span> July <span class="numeric">2014</span></span>
									</div>
									<div class="box-banner-content">
										<blockquote>
											<p>Yet I considered it necessary to send to you Epaphroditus, my brother, fellow worker, and fellow soldier, but your messenger and the one who ministered to my need.</p>
											<em>- Philippians 2:25</em>
										</blockquote>
										<p class="txt-right"><a href="#">Read more</a></p>
									</div>
								</div>
							</div>
						</div>
					</section><!--#end of Carousel - Memory Verse -->
				</section><!--#end of Overlapping Banner Column -->
			</section><!--#end of Full-width Column -->
		</section><!--#nd of Full-width Column -->
	</section><!--#end of Content -->

<!-- End Content -->

<?php get_footer(); ?>