<?php

class leadee_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @access 		protected
	 * @var 		array 		$actions 		The actions registered with WordPress to fire when the plugin loads.
	 */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @access        protected
     * @var        array $filters The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    protected $virtual_pages;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     */
    public function __construct()
    {

        $this->actions = array();
        $this->filters = array();
	} // __construct()

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @param 		string 		$hook 				The name of the WordPress action that is being registered.
	 * @param 		object 		$component 			A reference to the instance of the object on which the action is defined.
	 * @param 		string 		$callback 			The name of the function definition on the $component.
	 * @param 		int 		$priority 			Optional. he priority at which the function should be fired. Default is 10.
	 * @param 		int 		$accepted_args 		Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );

	} // add_action()

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @param 		string 		$hook 				The name of the WordPress filter that is being registered.
	 * @param 		object 		$component 			A reference to the instance of the object on which the filter is defined.
	 * @param 		string 		$callback 			The name of the function definition on the $component.
	 * @param 		int 		$priority 			Optional. he priority at which the function should be fired. Default is 10.
	 * @param 		int 		$accepted_args 		Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );

	} // add_filter()

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @access 		private
	 * @param 		array 		$hooks 				The collection of hooks that is being registered (that is, actions or filters).
	 * @param 		string 		$hook 				The name of the WordPress filter that is being registered.
	 * @param 		object 		$component 			A reference to the instance of the object on which the filter is defined.
	 * @param 		string 		$callback 			The name of the function definition on the $component.
	 * @param 		int 		$priority 			The priority at which the function should be fired.
	 * @param 		int 		$accepted_args 		The number of arguments that should be passed to the $callback.
	 * @return 		array 							The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

        return $hooks;

    } // add()

    /**
     * Register the filters and actions with WordPress.
     *
     */
    public function run()
    {
        $leadee_pages = array('dashboard', 'goals', 'goals-settings', 'leads', 'leads-table-settings');

        if (is_user_logged_in() && current_user_can('activate_plugins')) {

            //Run api
            require_once LEADEE_PLUGIN_DIR . '/core/api/class-leadee-api.php';

            function leadee_api_callback()
            {
                $leadeeApi = new LeadeeApi();
                $leadeeApi->leadee_api();
            }

            add_action('wp_ajax_leadee_api', 'leadee_api_callback');

            //Load pages
            if (isset($_GET['leadee-page']) && in_array($_GET['leadee-page'], $leadee_pages)) {

                //remove styles of templates so that there is not conflicts of styles for the plugin
                function remove_theme_styles($src)
                {
                    if (strpos($src, '/wp-content/themes') !== false) {
                        return false;
                    }
                    return $src;
                }

                add_filter('style_loader_src', 'remove_theme_styles');

                //add wp top bar
                function setting_admin_bar()
                {
                    global $wp_admin_bar;

                    $nodes = $wp_admin_bar->get_nodes();
                    $native_menu_items = array(
                        'user-actions',
                        'user-info',
                        'edit-profile',
                        'logout',
                        'search',
                        'my-account',
                        'wp-logo',
                        'about',
                        'wporg',
                        'documentation',
                        'support-forums',
                        'feedback',
                        'site-name',
                        'dashboard',
                        'appearance',
                        'themes',
                        'updates',
                        'comments',
                        'new-content',
                        'new-post',
                        'new-media',
                        'new-page',
                        'new-user',
                        'top-secondary',
                        'wp-logo-external',
                    );
                    foreach ($nodes as $node) {
                        if (!in_array($node->id, $native_menu_items) && strpos($node->id, LEADEE_PLUGIN_NAME) === false) {
                            $wp_admin_bar->remove_node($node->id);
                        }
                    }
                }

                add_action('wp_before_admin_bar_render', 'setting_admin_bar');

                require_once LEADEE_PLUGIN_DIR . '/core/class-virtual-pages.php';
                $virtual_pages = new VirtualPages();
                $page = sanitize_text_field($_GET['leadee-page']);
                $virtual_pages->init_pages(LEADEE_PLUGIN_NAME, $page);

                //For export
            } elseif (isset($_GET['leadee-export'])) {
                require_once LEADEE_PLUGIN_DIR . '/core/api/leadee-export.php';
                exit;
            }
        }
        if (!isset($_GET['leadee-page'])) {
            //load tracker for site
            function leadee_enqueue_scripts($version)
            {
                wp_enqueue_script('leadee-trk', LEADEE_PLUGIN_URL . '/core/assets/js/leadee-trk.js', $version, false);
            }

            add_action('wp_enqueue_scripts', 'leadee_enqueue_scripts', LEADEE_VERSION);
        }

    } // run()


} // class
