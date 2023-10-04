<form method="get" action="">
	<div class="d-flex">
	    <div class="form-group flex-grow-1">
	    	<div class="search-icon">
	    		<input class="form-control" name="keyword" value="<?php echo isset($_GET['keyword']) ? esc_attr($_GET['keyword']) : '';?>" placeholder="<?php echo esc_html__('Search', 'houzez'); ?>" type="text">
	    	</div>
	    </div>
	    <div class="form-group">
	    	<input class="form-control" name="property_id" value="<?php echo isset($_GET['property_id']) ? esc_attr($_GET['property_id']) : '';?>" placeholder="<?php echo esc_html__('Property ID', 'houzez'); ?>" type="text">
	    	
	    </div>
	    <input type="hidden" name="prop_status" value="<?php echo isset($_GET['prop_status']) ? esc_attr($_GET['prop_status']) : '';?>">
	    <button class="btn btn-search btn-secondary" type="submit"><?php esc_html_e('Search', 'houzez'); ?></button>
	</div>
</form>