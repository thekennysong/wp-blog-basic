<?php
	// =========================================================================
	// REMOVE JUNK FROM HEAD
	// =========================================================================
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action('wp_head', 'rel_canonical');



	// =========================================================================
	// THEME SETUP
	// =========================================================================
	function multipleinc_setup() {
		// Theme Settings
		add_theme_support('automatic-feed-links');
		register_nav_menus();
		add_theme_support('post-thumbnails');
		add_theme_support('html5');

		// Image Resizing
		add_image_size('feed_size', '700', '257', true);
		add_image_size('single_size', '700', '317', true);
	}
	add_action('after_setup_theme', 'multipleinc_setup');



	// =========================================================================
	// DYNAMIC ASSETS
	// =========================================================================
	function dynamic_assets() {

		$current_url = $_SERVER['REQUEST_URI'];
		$current_url = explode('?', $current_url);
		$current_url = $current_url[0];
		$current_url = trim($current_url, '/');

		if (!is_admin()) {

			wp_deregister_script('jquery');

			// Use latest jQuery
			wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', FALSE, '1.9.1', FALSE); // Don't put jQuery in footer because of plugins
			wp_enqueue_script('jquery');

			wp_register_script('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array('jquery'), '3.3.4', TRUE);
			wp_enqueue_script('bootstrap');

			// Home Only
			if ($current_url == '') {

				wp_enqueue_style('magnific-css', get_template_directory_uri() . '/css/magnific.css', false, '1.0', 'all');
				wp_register_script('magnific-js', get_template_directory_uri().'/js/magnific.js', array('jquery'), '1.0', TRUE);
				wp_enqueue_script('magnific-js');

			}

			// About Only
			if ($current_url == 'about') {

			}

			wp_register_script('power', get_template_directory_uri() . '/js/power.js', array('jquery'), '1', TRUE);
			wp_enqueue_script('power');
		}
	}
	add_action('wp_enqueue_scripts', 'dynamic_assets');


	// =========================================================================
	// HTML5 STYLE TAGS
	// =========================================================================
	function html5_style_tag($tag) {
		$tag = str_replace("type='text/css'", '', $tag);
		$tag = str_replace("'", '"', $tag);
		$tag = str_replace(" />", '>', $tag);

		return $tag;
	}
	add_filter('style_loader_tag', 'html5_style_tag');


	// =========================================================================
	// REMOVE UNWANTED MENU ITEMS
	// =========================================================================
	function remove_menus () {
		// Remove the Editor
		remove_action('admin_menu', '_add_themes_utility_last', 101);

		// Remove Comments
		remove_menu_page('edit-comments.php');

		// Remove Theme Options
		global $submenu;
        unset($submenu['themes.php'][5]); // Switch Themes
        unset($submenu['themes.php'][6]); // Customize
	}
	add_action('admin_menu', 'remove_menus');


	function get_feeds() {
		// Don't call this function twice or move the require
		require_once('includes/grabfeeds/feeds.php');
		return $feeds;
	}

	function grab_slice($slice) {
		include(locate_template('slices/'.$slice.'.php'));
	}

	function grab_title() {
		if (is_page() || is_single())
		{
			return get_the_title();
		}
		elseif (is_archive() && !is_category() && !is_tag() && !is_author() && !is_tax())
		{
			if (is_year()) return get_the_time('Y');
			if (is_month()) return get_the_time('F,  Y');
			if (is_day()) return get_the_time('F j, Y');
		}
		elseif (is_tag())
		{
			return single_tag_title('', FALSE);
		}
		elseif (is_search() && $_GET['s'] != '')
		{
			$search = get_search_query();
			if (strlen($search) > 40) $search = substr($search, 0, 35).'...';
			return $search;
		}
		elseif (is_search() && $_GET['s'] == '')
		{
			return 'All Posts';
		}
		elseif (is_404())
		{
			return '404';
		}
		elseif (is_author())
		{
			$author = get_user_by('id', $post->post_author)->data;
			return get_the_author_meta('first_name', $author->ID).' '.get_the_author_meta('last_name', $author->ID);
		}
		elseif (is_category())
		{
			$category = get_category(get_query_var('cat'));
			return $category->name;
		}
		elseif (is_tax())
		{
			return $wp_query->queried_object->name;
		}
		else
		{
			return FALSE;
		}
	}

	function grab_subheading() {

		if (is_page()) :
			return 'Page';
		endif;

		if (is_single()) :
			return 'Single';
		endif;

		if (is_archive() && !is_category() && !is_tag() && !is_author() && !is_tax()) :
			return 'Archive';
		endif;

		if (is_tag()) :
			return 'Tag';
		endif;

		if (is_search() && $_GET['s'] != '') :
			return 'Search Results';
		endif;

		if (is_search() && $_GET['s'] == '') :
			return 'Archive';
		endif;

		if (is_404()) :
			return 'Page Not Found';
		endif;

		if (is_author()) :
			return 'Author';
		endif;

		if (is_category()) :
			return 'Category';
		endif;
	}


	if(function_exists('register_field_group'))
	{
		register_field_group(array (
			'id' => 'acf_splash-popup',
			'title' => 'Splash Popup',
			'fields' => array (
				array (
					'key' => 'field_5345c4b236133',
					'label' => 'Turn popup on?',
					'name' => 'turn_popup_on',
					'type' => 'true_false',
					'message' => '',
					'default_value' => 0,
				),
				array (
					'key' => 'field_5345bf6e4280f',
					'label' => 'Is cookied?',
					'name' => 'is_cookied',
					'type' => 'true_false',
					'instructions' => 'If it\'s "cookied", a user will only see the popup once versus every single page load.',
					'message' => '',
					'default_value' => 0,
				),
				array (
					'key' => 'field_5345bfab42810',
					'label' => 'Cookie Name',
					'name' => 'cookie_name',
					'type' => 'text',
					'instructions' => 'It will reference to see if this cookie is set. Must all be one word with or without underscores. For example: cookie1, cookie2, issue_cookie_3.',
					'required' => 1,
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf6e4280f',
								'operator' => '==',
								'value' => '1',
							),
						),
						'allorany' => 'all',
					),
					'default_value' => 'has_seen_homepage',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5345bf284280e',
					'label' => 'Lightbox Type',
					'name' => 'lightbox_type',
					'type' => 'radio',
					'choices' => array (
						'image' => 'Image',
						'video' => 'Video',
						'html' => 'HTML',
					),
					'other_choice' => 0,
					'save_other_choice' => 0,
					'default_value' => '',
					'layout' => 'vertical',
				),
				array (
					'key' => 'field_5345c0f00c94b',
					'label' => 'Popup Image',
					'name' => 'popup_image',
					'type' => 'image',
					'instructions' => 'Images should be 600px wide.',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'image',
							),
						),
						'allorany' => 'all',
					),
					'save_format' => 'url',
					'preview_size' => 'full',
					'library' => 'all',
				),
				array (
					'key' => 'field_5345c1070c94c',
					'label' => 'Is image linkable?',
					'name' => 'is_image_linkable',
					'type' => 'true_false',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'image',
							),
						),
						'allorany' => 'all',
					),
					'message' => '',
					'default_value' => 0,
				),
				array (
					'key' => 'field_5345c15b0c94d',
					'label' => 'Image URL',
					'name' => 'image_url',
					'type' => 'text',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'image',
							),
							array (
								'field' => 'field_5345c1070c94c',
								'operator' => '==',
								'value' => '1',
							),
						),
						'allorany' => 'all',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5345c1948289a',
					'label' => 'Popup Video ID',
					'name' => 'popup_video_id',
					'type' => 'text',
					'instructions' => 'If the URL is: "http://www.youtube.com/watch?v=0O2aH4XLbto", you would input "0O2aH4XLbto". Only supports youtube.',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'video',
							),
						),
						'allorany' => 'all',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'html',
					'maxlength' => '',
				),
				array (
					'key' => 'field_5345c2448289b',
					'label' => 'Autoplay Video?',
					'name' => 'autoplay_video',
					'type' => 'true_false',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'video',
							),
						),
						'allorany' => 'all',
					),
					'message' => '',
					'default_value' => 0,
				),
				array (
					'key' => 'field_5345c2738289c',
					'label' => 'Inline Popup',
					'name' => 'inline_popup',
					'type' => 'wysiwyg',
					'instructions' => 'Use this if you want full control over the lightbox. Requires custom code and styles. Mostly for advanced users only.',
					'conditional_logic' => array (
						'status' => 1,
						'rules' => array (
							array (
								'field' => 'field_5345bf284280e',
								'operator' => '==',
								'value' => 'html',
							),
						),
						'allorany' => 'all',
					),
					'default_value' => '',
					'toolbar' => 'full',
					'media_upload' => 'yes',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'page',
						'operator' => '==',
						'value' => '7',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'acf_after_title',
				'layout' => 'default',
				'hide_on_screen' => array (),
			),
			'menu_order' => 0,
		));
	}


	// Plain Shortcode
	function example_static_shortcode_function($atts) {

		extract(shortcode_atts(array(
			'title' => '',
		), $atts));


		$text = 'The title is: '.$title;

		return $text;
	}
	add_shortcode('example_static_shortcode_tag', 'example_static_shortcode_function');


	//  Content Shortcode
	function example_wrap_shortcode_function($atts, $content = NULL) {
		return '<div class="content-wrap">'.$content.'</div>';
	}
	add_shortcode('example_wrap_shortcode_tag', 'example_wrap_shortcode_function');



	/*========================================
	=            CUSTOM ENDPOINTS            =
	========================================*/
	function add_multipleinc_controller($controllers) {
		$controllers[] = 'multipleinc';
		return $controllers;
	}
	add_filter('json_api_controllers','add_multipleinc_controller');

	function set_multipleinc_controller_path() {
		return get_stylesheet_directory().'/includes/api/api_multipleinc.php';
	}
	add_filter('json_api_multipleinc_controller_path','set_multipleinc_controller_path');



	/*========================================
	=            ACF OPTION PAGES            =
	========================================*/
	if( function_exists('acf_add_options_page') ) {

		acf_add_options_page(array(
			'page_title' 	=> 'Options',
			'menu_title'	=> 'Options',
			'menu_slug' 	=> 'options',
			'capability'	=> 'edit_posts',
			'redirect'	=> false
		));

		// acf_add_options_sub_page(array(
		// 	'page_title' 	=> 'Page 1',
		// 	'menu_title'	=> 'Page 1',
		// 	'parent_slug'	=> 'options',
		// ));

	}



	function splash_page() {
		$current_url = $_SERVER['REQUEST_URI'];
		$current_url = explode('?', $current_url);
		$current_url = $current_url[0];
		$current_url = trim($current_url, '/');

		$okay_to_proceed = TRUE;
		if (get_option('splash_only_home') && $current_url != '')
		{
			$okay_to_proceed = FALSE;
		}

		if (get_option('splash_cookie_name') && isset($_COOKIE[get_option('splash_cookie_name')]) && $_COOKIE[get_option('splash_cookie_name')] == 1)
		{
			$okay_to_proceed = FALSE;
		}

		if (get_option('enable_splash_page') && !is_admin() && $okay_to_proceed) :

			if (get_option('splash_cookie_name') && get_option('splash_server_cookie'))
			{
				$days = 30;
				if (get_option('splash_cookie_days'))
					$days = get_option('splash_cookie_days');

				setcookie(get_option('splash_cookie_name'), 1, time()+60*60*24*$days, '/', '.'.$_SERVER['HTTP_HOST']);
			}



			if (get_option('enable_geo'))
			{
				/*=====================================
				=            GEO REDIRECTS            =
				=====================================*/
		 		$states = array(
					'Alabama'=>'AL',
					'Alaska'=>'AK',
					'Arizona'=>'AZ',
					'Arkansas'=>'AR',
					'California'=>'CA',
					'Colorado'=>'CO',
					'Connecticut'=>'CT',
					'Washington, DC' => 'DC',
					'Delaware'=>'DE',
					'Florida'=>'FL',
					'Georgia'=>'GA',
					'Hawaii'=>'HI',
					'Idaho'=>'ID',
					'Illinois'=>'IL',
					'Indiana'=>'IN',
					'Iowa'=>'IA',
					'Kansas'=>'KS',
					'Kentucky'=>'KY',
					'Louisiana'=>'LA',
					'Maine'=>'ME',
					'Maryland'=>'MD',
					'Massachusetts'=>'MA',
					'Michigan'=>'MI',
					'Minnesota'=>'MN',
					'Mississippi'=>'MS',
					'Missouri'=>'MO',
					'Montana'=>'MT',
					'Nebraska'=>'NE',
					'Nevada'=>'NV',
					'New Hampshire'=>'NH',
					'New Jersey'=>'NJ',
					'New Mexico'=>'NM',
					'New York'=>'NY',
					'North Carolina'=>'NC',
					'North Dakota'=>'ND',
					'Ohio'=>'OH',
					'Oklahoma'=>'OK',
					'Oregon'=>'OR',
					'Pennsylvania'=>'PA',
					'Rhode Island'=>'RI',
					'South Carolina'=>'SC',
					'South Dakota'=>'SD',
					'Tennessee'=>'TN',
					'Texas'=>'TX',
					'Utah'=>'UT',
					'Vermont'=>'VT',
					'Virginia'=>'VA',
					'Washington'=>'WA',
					'West Virginia'=>'WV',
					'Wisconsin'=>'WI',
					'Wyoming'=>'WY'
				);



				// Get Users Location
				$state = FALSE;
				if (isset($_GET['state']) && !empty($_GET['state']) && in_array($_GET['state'], $states)) :
					$state = $_GET['state'];

					if (isset($_GET['set_cookie']) && !empty($_GET['set_cookie']) && $_GET['set_cookie'] == '1') :
						setcookie('ip_state', $state, time()+60*60*24*30, '/', '.'.$_SERVER['HTTP_HOST']);
					endif;
				else :
					if (isset($_COOKIE['ip_state']) && !empty($_COOKIE['ip_state']))
					{
						$state = $_COOKIE['ip_state'];
					}
					else
					{
						// Get it from IP
						$ip_address = $_SERVER['REMOTE_ADDR'];

						// $ip_address = '128.177.116.146';
						// $ip_address = '216.189.101.121';

						$api_key = 'a11e79a9a3347b73f9e84f957ce15d4873a04815e83b264bb0a8a44e86a87db6';
						$url = 'http://api.ipinfodb.com/v3/ip-city/?key='.$api_key.'&ip='.$ip_address.'&format=json';

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
						curl_setopt($ch, CURLOPT_TIMEOUT, 4);
						$output = curl_exec($ch);
						curl_close($ch);
						$result = json_decode($output);

						if ($result && isset($result->regionName) && !empty($result->regionName) && $result->regionName != '-')
					 	{

							$state = ucwords(strtolower($result->regionName));

							if ($state == 'District Of Columbia')
							{
								$state = 'Washington, DC';
							}

							if (isset($states[$state]))
							{
								$state = $states[$state];
								setcookie('ip_state', $state, time()+60*60*24*30, '/', '.'.$_SERVER['HTTP_HOST']);
							}
						}
						else
						{
							$state = FALSE;
						}
				 	}
				endif;


				// State will be ABRV from COOKIE or from Lookup or from GET override
				// State will be FALSE if can't determine state
			 	if ($state)
			 	{
			 		if (get_option($state))
			 		{
			 			header('Location: '.get_option($state));
			 			exit;
			 		}
			 	}
			 	else
			 	{
			 		if (get_option('unknown_location')) {
			 			header('Location: '.get_option('unknown_location'));
			 			exit;
			 		}
			 	}
			}
			else
			{
				/*=========================================
				=            DEFAULT REDIRECTS            =
				=========================================*/
				header('Location: '.get_option('splash_page_url'));
				exit;
			}



		endif;
	}
	add_action('init', 'splash_page');