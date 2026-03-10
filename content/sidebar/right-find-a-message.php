<div class="box-side sticky-box">
	<div class="box-side-top clearfix">
		<h3>Find a message</h3>
	</div>
	<div class="box-side-content">
		<form action="<?php echo get_permalink(get_page_by_path('messages-search-result')->ID); ?>" method="GET">
			<fieldset>
				<div class="field-wrp field-search">
					<input type="text" name="msg_search" placeholder="Search" class="full-width"/>
					<!--<a class="i-search ion-ios-search-strong" href="javascript:;"></a>-->
				</div>
				<h4>Date</h4>
				<div class="field-wrp field-half clearfix">
					<select name="msg_month" class="select-month">
						<?php for ($i = 1; $i <= 12; $i++) : ?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT);  ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?></option>
						<?php endfor; ?>
					</select>
					<select name="msg_year" class="select-year">
						<?php for ($i = date('Y'); $i > date('Y')-10; $i--) : ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="field-wrp">
					<p><button type="submit" class="btn-primary btn-gray">Search messages</button></p>
				</div>
			</fieldset>			
		</form>
	</div>
</div>