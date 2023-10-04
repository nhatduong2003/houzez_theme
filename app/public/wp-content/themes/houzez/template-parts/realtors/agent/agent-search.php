<?php 
global $houzez_local; 

$city = $category = $agent_name = '';
$default_category = array();
$category = isset($_GET['category']) ? $_GET['category'] : $default_category;

$default_city = array();
$city = isset($_GET['city']) ? $_GET['city'] : $default_city;

$purl = houzez_get_template_link('template/template-agents.php');
?>
<section class="advanced-search advanced-search-nav">
	<div class="container">
		<div class="advanced-search-v1">
			<form method="get" action="<?php echo esc_url($purl); ?>">
				<input type="hidden" name="agent-search" value="yes">
				<div class="d-flex">
					<div class="flex-search flex-grow-1">
						<div class="form-group">
							<div class="search-icon">
								<input type="text" name="agent_name" class="form-control" placeholder="<?php echo $houzez_local['search_agent_name']?>" value="<?php echo isset ( $_GET['agent_name'] ) ? $_GET['agent_name'] : ''; ?>">
							</div><!-- search-icon -->
						</div><!-- form-group -->
					</div><!-- flex-search -->
					<div class="flex-search">
						<div class="form-group">
							<select name="category[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cats']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
								<?php houzez_get_search_taxonomies('agent_category', $category ); ?>
							</select><!-- selectpicker -->
						</div><!-- form-group -->
					</div><!-- flex-search -->
					<div class="flex-search">
						<div class="form-group">
							<select name="city[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cities']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
								<?php houzez_get_search_taxonomies('agent_city', $city ); ?>
		                 
							</select><!-- selectpicker -->
						</div><!-- form-group -->
					</div><!-- flex-search -->
			
					<div class="flex-search">
						<button type="submit" class="btn btn-search btn-secondary btn-full-width"><?php echo $houzez_local['search_agent_btn']; ?></button>
					</div><!-- flex-search -->
				</div><!-- d-flex -->
			</form>
		</div><!-- advanced-search-v1 -->
	</div><!-- container -->
</section><!-- advanced-search -->
<section class="advanced-search advanced-search-nav mobile-search-nav mobile-agent-search-trigger">
	<div class="container">
		<div class="advanced-search-v1">
			<div class="d-flex">
				<div class="flex-search flex-grow-1">
					<div class="form-group">
						<div class="search-icon">
							<input type="text" fsdfs class="form-control" placeholder="<?php echo houzez_option('srh_mobile_title', 'Search'); ?>" onfocus="blur();">
						</div><!-- search-icon -->
					</div><!-- form-group -->
				</div><!-- flex-search -->
			</div><!-- d-flex -->
		</div><!-- advanced-search-v1 -->
	</div><!-- container -->
</section><!-- advanced-search -->

