<?php


class AnnouncementPostType {

    private $slug;
    private $labels;

    protected static $instance;

    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->slug = "announcement";

        $this->labels = array(
            'name'          => __('Ankündigungen'),
            'singular_name' => __('Ankündigung'),
            'edit_item' 	=> __('Ankündigung bearbeiten'),
        );
    }

    public function register_posttype () {

        $args = array(
            'labels'      => $this->labels,
            'public'      => true,
            'has_archive' => true,
            'menu_icon'		  => plugin_dir_url( __FILE__ ) . '../../menu-icon.png',
            'supports'    => array( 'title', 'editor', 'author','excerpt' ),
            'taxonomies'  => array(),
            'capabilities' => array(
                'edit_post'          => 'makerspace_announcement_edit_announcement', 
                'read_post'          => 'makerspace_announcement_read_announcement', 
                'delete_post'        => 'makerspace_announcement_delete_announcement', 
                'edit_posts'         => 'makerspace_announcement_edit_announcement', 
                'edit_others_posts'  => 'makerspace_announcement_edit_announcement', 
                'publish_posts'      => 'makerspace_announcement_publish_announcement',       
                'read_private_posts' => 'makerspace_announcement_read_announcement', 
                'create_posts'       => 'makerspace_announcement_edit_announcement', 
              ),
        );

        register_post_type( $this->slug, $args );
    }

    public function add_caps() {
        $role = get_role( 'editor' );
        $role->add_cap( 'makerspace_announcement_read_announcement', true ); 
        $role->add_cap( 'makerspace_announcement_edit_announcement', true ); 
        $role->add_cap( 'makerspace_announcement_delete_announcement', true ); 
        $role->add_cap( 'makerspace_announcement_publish_announcement', true ); 

        // $role = add_role('foobar', 'Foo Bar', array());
        // $role->add_cap('foo_bar_cap');
    }

    public function save_custom_meta_box ()
    {
        $pid = $_POST["post_ID"];

        if ( $pid == NULL )
            return;


        if(!current_user_can("makerspace_announcement_edit_announcement", $pid)){
            return $pid;
        }

        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){
            return $pid;
        }

        if(isset($_POST["announcement_option_show_global"])) {
            $announcement_option_show_global = $_POST["announcement_option_show_global"];

            update_post_meta($pid, "announcement_option_show_global", $announcement_option_show_global);
        }
    }

    public function metabox_options_template () {
        require( plugin_dir_path( __FILE__ ) . 'partials/announcements-options.php' );
    }

    public function add_metaboxes() {

        add_meta_box(
            'metabox_announcement_options',
            'Optionen',
            array( $this, 'metabox_options_template' ),
            $this->slug,
            'side',
            'default'
        );
    }

    public function register () {
        add_action( 'init', array( $this, 'register_posttype') );
        add_action( 'init', array( $this, 'add_caps') );

        add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
        add_action( 'init', array( $this, 'save_custom_meta_box') );

        // add_filter('manage_workshop_columns', array($this, 'list_columns_head'));
        // add_action('manage_posts_custom_column',  array($this, 'list_columns_content'), 10, 2);


        // subpages
        // add_action( 'admin_menu', array($this, 'add_menu') );


    }

}
