<?php
/**
 * Widget Name: Advanced Search
 * Version: 1.0
 * Author: Waqas Riaz
 * Author URI: http://favethemes.com/
 *
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 20/01/16
 * Time: 10:51 PM
 */

class HOUZEZ_agent_search extends WP_Widget {

    /**
     * Register widget
     **/
    public function __construct() {

        parent::__construct(
            'houzez_agent_search', // Base ID
            esc_html__( 'HOUZEZ: Agent Search', 'houzez' ), // Name
            array( 'description' => esc_html__( 'Agents Search', 'houzez' ), 'classname' => 'widget-agency-search' ) // Args
        );

    }


    /**
     * Front-end display of widget
     **/
    public function widget( $args, $instance ) {

        global $before_widget, $after_widget, $before_title, $after_title, $post;
        extract( $args );

        $allowed_html_array = array(
            'div' => array(
                'id' => array(),
                'class' => array()
            ),
            'h3' => array(
                'class' => array()
            )
        );

        $title = apply_filters('widget_title', $instance['title'] );

        echo wp_kses( $before_widget, $allowed_html_array );

        if ( $title ) echo wp_kses( $before_title, $allowed_html_array ) . $title . wp_kses( $after_title, $allowed_html_array );

        houzez_agent_search_widget();

        echo wp_kses( $after_widget, $allowed_html_array );

    }


    /**
     * Sanitize widget form values as they are saved
     **/
    public function update( $new_instance, $old_instance ) {

        $instance = array();

        /* Strip tags to remove HTML. For text inputs and textarea. */
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;

    }


    /**
     * Back-end widget form
     **/
    public function form( $instance ) {

        /* Default widget settings. */
        $defaults = array(
            'title' => 'Find Agent'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>

        <?php
    }

}

if ( ! function_exists( 'HOUZEZ_agent_search_loader' ) ) {
    function HOUZEZ_agent_search_loader (){
        register_widget( 'HOUZEZ_agent_search' );
    }
    add_action( 'widgets_init', 'HOUZEZ_agent_search_loader' );
}

function houzez_agent_search_widget() {

    $houzez_local = houzez_get_localization();


    $city = $category = $agent_name = '';

    $default_category = array();
    $category = isset($_GET['category']) ? $_GET['category'] : $default_category;

    $default_city = array();
    $city = isset($_GET['city']) ? $_GET['city'] : $default_city;
    $purl = houzez_get_template_link('template/template-agents.php');

 ?>
    <div class="widget-body">
        <div class="widget-content">
            
            <form method="get" action="<?php echo esc_url($purl); ?>">
                <input type="hidden" name="agent-search" value="yes">
                <div class="form-group">
                    <div class="search-icon">
                        <input type="text" class="form-control" value="<?php echo isset ( $_GET['agent_name'] ) ? $_GET['agent_name'] : ''; ?>" name="agent_name" placeholder="<?php echo $houzez_local['search_agent_name']?>">
                    </div><!-- search-icon -->
                </div>
                
                <div class="form-group">
                    <select name="category[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cats']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
                                <?php houzez_get_search_taxonomies('agent_category', $category ); ?>
                            </select><!-- selectpicker -->
                </div><!-- form-group -->

                <div class="form-group">
                    <select name="city[]" class="selectpicker form-control bs-select-hidden" title="<?php echo $houzez_local['all_agent_cities']; ?>" data-live-search="true" data-selected-text-format="count" multiple data-actions-box="true" data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}">
                                <?php houzez_get_search_taxonomies('agent_city', $city ); ?>
                         
                            </select><!-- selectpicker -->
                </div><!-- form-group -->

                <button type="submit" class="btn btn-search btn-secondary btn-full-width"><?php echo $houzez_local['search_agent_btn']; ?></button>
            </form>
        </div><!-- widget-content -->
    </div><!-- widget-body -->
<?php
}