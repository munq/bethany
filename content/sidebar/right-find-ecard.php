<div class="box-side sticky-box">
	<div class="box-side-top clearfix">
		<h3>Find an eCard</h3>
	</div>
	<div class="box-side-content">
		<form id="ecards_cat" action="<?php echo get_permalink();?>" method="GET">
			<fieldset>
				<div class="field-wrp field-search">
					<input type="text" placeholder="Search" name="search" class="full-width" />
				</div>
				<div class="field-wrp">
					<select class="select-category" name="ecard_category">									
					<?php
					$id_obj = get_category_by_slug('ecards');
					$parent_cat_id  = $id_obj->term_id;
					
					$args_cat = array(				
						'child_of'                 => $parent_cat_id,
						'orderby'                  => 'name',
						'hide_empty'               => 1
					);
					
					$sub_categories = get_categories( $args_cat );
					
					foreach($sub_categories as $sub_category) {
					?>
						<option value="<?php echo $sub_category->slug; ?>"><?php echo $sub_category->name; ?></option>
					<?php
					}
					?>								
					</select>
				</div>
				<div class="field-wrp">
					<a class="btn-primary btn-gray" onclick="document.getElementById('ecards_cat').submit();" href="javascript:;">Apply &nbsp;<i class="ion-ios-arrow-right"></i></a>
				</div>
			</fieldset>
		</form>
	</div>
</div>