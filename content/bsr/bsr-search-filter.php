<?php include get_template_directory() . '/includes/bible-books-chapters.php'; ?>

<form>
	<fieldset class="bsr-filter-top filter-top clearfix">
		<div class="field-wrp field-search">
			<span class="pagination-count"><strong></strong></span> <span>
			<strong>Results</strong></span>
		</div>
		<div>
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination-legend"></div>
			</div><!--#end of Pagination -->
			<a class="btn-primary btn-expand-filter" href="javascript:;">Filter &nbsp;<i class="ion-ios-arrow-down"></i></a>
		</div>
	</fieldset>
	
	<div class="filter-category bsr-filter-cat three">
		<a href="javascript:;" rel="bsr-filter-book">Bible Book</a>		
		<a href="javascript:;" rel="bsr-filter-series">Series</a>
		<a href="javascript:;" rel="bsr-filter-date">Date</a>
	</div>		
	
	<!-- Bible Study Filtering -->
	<div class="bsr-filter-wrp hidden">
		<hr/>
		<!-- Filter - Bible Book -->
		<div class="bsr-filter-book clearfix hidden">
			<div class="field-wrp">
				<select name="bible_book" id="bible_book" class="select-book">					
					<?php 
					foreach($old_testament_books as $key => $val_num ) {			
					?>						
						<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
					<?php
					}
					
					foreach($new_testament_books as $key => $val_num ) {					
					?>						
						<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
					<?php
					}
					
					?>
				</select>				
			</div>
		</div>
		
		<!-- Filter - Series -->
		<div class="bsr-filter-series clearfix hidden">
			<div class="filter-category search-series" >
				<a href="javascript:;" data-value="daily-devotions">Daily Devotions</a>
				<a href="javascript:;" data-value="grace-works">Grace Works</a>
				<a href="javascript:;" data-value="meditation">Meditation</a>
				<a href="javascript:;" data-value="parenting-by-the-book">Parenting by the Book</a>
				<a href="javascript:;" data-value="youth-walk">Youth Walk</a>
				<a href="javascript:;" data-value="bible-study">Bible Study</a>
				<a href="javascript:;" data-value="family-camp-messages">Family Camp Messages</a>
				<a href="javascript:;" data-value="morning-worship">Morning Worship</a>
				<a href="javascript:;" data-value="evening-worship">Evening Worship</a>
				<a href="javascript:;" data-value="prayer-meeting">Prayer Meeting</a>
				<a href="javascript:;" data-value="senior-sunday-school-4">Senior Sunday School 4</a>
				<a href="javascript:;" data-value="yag-retreat">YAG Retreat</a>
				<a href="javascript:;" data-value="young-peoples-group">Young People's Group</a>
				<a href="javascript:;" data-value="young-adults-group">Young Adult's Group</a>
			</div>			
		</div>
		
		<div class="bsr-filter-date clearfix hidden">
			<div>
				<h3>Date</h3>
			</div>
			<div>
				<fieldset>
					<p>From</p>
					<div class="field-wrp">
						<select  id="from_month" name="from_month" class="select-month">
							<?php for ($i = 1; $i <= 12; $i++) : ?>
							<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);  ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?></option>
							<?php endfor; ?>
						</select>
						
						<span class="slash">/</span>
						
						<select id="from_year" name="from_year" class="select-year">
							<?php for ($i = date('Y'); $i > date('Y')-15; $i--) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</fieldset>
			</div>
			<div>
				<fieldset>
					<p>To</p>
					<div class="field-wrp">
						<select id="to_month" name="to_month" class="select-month">
							<?php for ($i = 1; $i <= 12; $i++) : ?>
							<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);  ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?></option>
							<?php endfor; ?>
						</select>
						
						<span class="slash">/</span>
						
						<select id="to_year" name="to_year" class="select-year">
							<?php for ($i = date('Y'); $i > date('Y')-15; $i--) : ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</fieldset>
			</div>			
		</div>
		<a class="btn-primary btn-gray" href="javascript:;" id="bsr_apply">Apply &nbsp;<i class="ion-ios-arrow-right"></i></a>
	</div><!--#end of Bible Study Filtering -->						
</form>
