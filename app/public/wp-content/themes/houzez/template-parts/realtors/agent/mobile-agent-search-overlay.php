<?php 
global $houzez_local; 

$city = $category = $agent_name = '';
$default_category = array();
$category = isset($_GET['category']) ? $_GET['category'] : $default_category;

$default_city = array();
$city = isset($_GET['city']) ? $_GET['city'] : $default_city;

$purl = houzez_get_template_link('template/template-agents.php');
?>
<section id="overlay-search-advanced-module" class="overlay-search-advanced-module overlay-agent-search-module">
	<div class="search-title">
		<?php esc_html_e('Search', 'houzez'); ?>
		<button type="button" class="btn overlay-search-module-close"><i class="houzez-icon icon-close"></i></button>
	</div>
	<form method="get" action="<?php echo esc_url($purl); ?>">
		<div class="row">
			<div class="col-12">
				<div class="form-group">
					<div class="search-icon">
						<input type="text" name="agent_name" class="form-control" placeholder="<?php echo $houzez_local['search_agent_name']?>" value="<?php echo isset ( $_GET['agent_name'] ) ? $_GET['agent_name'] : ''; ?>">
					</div><!-- search-icon -->
				</div><!-- form-group -->
			</div><!-- col-12 -->
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<select name="category[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cats']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
								<?php houzez_get_search_taxonomies('agent_category', $category ); ?>
							</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div><!-- col-md-4 col-sm-12 -->
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<select name="city[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cities']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
								<?php houzez_get_search_taxonomies('agent_city', $city ); ?>
		                 
							</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div><!-- col-md-4 col-sm-12 -->
			
			<div class="col-12">
				<button type="submit" class="btn btn-search btn-secondary btn-full-width"><?php echo $houzez_local['search_agent_btn']; ?></button>
			</div><!-- col-12 -->
		</div><!-- row -->
	</form>
</section><!-- overlay-agent-search-module -->