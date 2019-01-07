<?php
class qa_html_theme_layer extends qa_html_theme_base {	
	
	var $theme_directory;
	var $theme_url;
	function html_theme_layer($template, $content, $rooturl, $request)
	{
		global $qa_layers;
		$this->theme_directory = $qa_layers['Smatika Theme']['directory'];
		$this->theme_url = $qa_layers['Smatika Theme']['urltoroot'];
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
	}
	
	function doctype(){
		global $p_path, $s_path,$p_url, $s_url;
		global $qa_request;
		require_once QA_INCLUDE_DIR . '/app/admin.php';
		
		$categories = qa_db_select_with_pending( qa_db_category_nav_selectspec( null, true ) );

		//	For non-text options, lists of option types, minima and maxima

		$optiontype = array(
			'smatika_activate_prod_mode'           => 'checkbox',
			'smatika_use_local_font'               => 'checkbox',
			'smatika_enable_top_bar'               => 'checkbox',
			'smatika_show_top_social_icons'        => 'checkbox',
			'smatika_enable_sticky_header'         => 'checkbox',
			'smatika_enable_back_to_top_btn'       => 'checkbox',
			'smatika_show_home_page_banner'        => 'checkbox',
			'smatika_banner_closable'              => 'checkbox',
			'smatika_banner_show_ask_box'          => 'checkbox',
			'smatika_show_collapsible_btns'        => 'checkbox',
			'smatika_show_breadcrumbs'             => 'checkbox',
			'smatika_show_site_stats_above_footer' => 'checkbox',
			'smatika_show_social_links_at_footer'  => 'checkbox',
			'smatika_show_copyright_at_footer'     => 'checkbox',
			'smatika_show_custom_404_page'         => 'checkbox',
			'smatika_copyright_text'               => 'text',
			'smatika_banner_head_text'             => 'text',
			'smatika_banner_div1_text'             => 'text',
			'smatika_banner_div1_icon'             => 'text',
			'smatika_banner_div2_text'             => 'text',
			'smatika_banner_div2_icon'             => 'text',
			'smatika_banner_div3_text'             => 'text',
			'smatika_banner_div3_icon'             => 'text',
			'smatika_top_bar_left_text'            => 'text',
			'smatika_top_bar_right_text'           => 'text',
			'smatika_facebook_url'                 => 'text',
			'smatika_twitter_url'                  => 'text',
			'smatika_pinterest_url'                => 'text',
			'smatika_google-plus_url'              => 'text',
			'smatika_vk_url'                       => 'text',
			'smatika_email_address'                => 'text',
			'smatika_custom_404_text'              => 'text',
			'smatika_general_settings_notice'      => 'custom',
			'smatika_homepage_settings_notice'     => 'custom',
			'smatika_footer_settings_notice'       => 'custom',
			'smatika_social_settings_notice'       => 'custom',
		);

		$optionmaximum = array();

		$optionminimum = array();

		//	Define the options to show (and some other visual stuff) based on request

		$formstyle = 'tall';
		$checkboxtodisplay = null;
		
		if ( ($qa_request == 'admin/smatika') and (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) ) {
		
			$showoptions = array( 'smatika_general_settings_notice', 'smatika_activate_prod_mode', 'smatika_use_local_font','smatika_enable_top_bar', 'smatika_top_bar_left_text', 'smatika_top_bar_right_text', 'smatika_show_top_social_icons', 'smatika_enable_sticky_header', 'smatika_enable_back_to_top_btn' );
            array_push( $showoptions, 'smatika_show_collapsible_btns' );
            array_push( $showoptions, 'smatika_show_custom_404_page', 'smatika_custom_404_text' );

            array_push( $showoptions, 'smatika_homepage_settings_notice', 'smatika_show_home_page_banner', 'smatika_banner_head_text', 'smatika_banner_div1_text', 'smatika_banner_div1_icon', 'smatika_banner_div2_text', 'smatika_banner_div2_icon', 'smatika_banner_div3_text', 'smatika_banner_div3_icon', 'smatika_banner_show_ask_box', 'smatika_banner_closable' );

            if ( class_exists( 'Ami_Breadcrumb' ) ) {
                array_push( $showoptions, '', 'smatika_show_breadcrumbs' );
            }

            array_push( $showoptions, 'smatika_footer_settings_notice', 'smatika_show_site_stats_above_footer', 'smatika_show_social_links_at_footer', 'smatika_show_copyright_at_footer', 'smatika_copyright_text' );

            array_push( $showoptions, 'smatika_social_settings_notice', 'smatika_facebook_url', 'smatika_twitter_url', 'smatika_pinterest_url', 'smatika_google-plus_url', 'smatika_vk_url', 'smatika_email_address' );
			
			$getoptions = array();
			foreach ( $showoptions as $optionname )
				if ( strlen( $optionname ) && ( strpos( $optionname, '/' ) === false ) ) // empties represent spacers in forms
					$getoptions[] = $optionname;


		//	Process user actions

			$errors = array();
			$securityexpired = false;

			$formokhtml = null;

			if ( qa_clicked( 'doresetoptions' ) ) {
				if ( !qa_check_form_security_code( 'admin/smatika', qa_post_text( 'code' ) ) )
					$securityexpired = true;

				else {
					smatika_reset_options( $getoptions );
					$formokhtml = qa_lang_html('admin/options_reset');
				}
			} elseif ( qa_clicked( 'dosaveoptions' ) ) {
				if ( !qa_check_form_security_code( 'admin/smatika', qa_post_text( 'code' ) ) )
					$securityexpired = true;

				else {
					foreach ( $getoptions as $optionname ) {
						$optionvalue = qa_post_text( 'option_' . $optionname );

						if (
							( @$optiontype[$optionname] == 'number' ) ||
							( @$optiontype[$optionname] == 'checkbox' ) ||
							( ( @$optiontype[$optionname] == 'number-blank' ) && strlen( $optionvalue ) )
						)
							$optionvalue = (int) $optionvalue;

						if ( isset( $optionmaximum[$optionname] ) )
							$optionvalue = min( $optionmaximum[$optionname], $optionvalue );

						if ( isset( $optionminimum[$optionname] ) )
							$optionvalue = max( $optionminimum[$optionname], $optionvalue );

						qa_set_option( $optionname, $optionvalue );
					}

					$formokhtml = qa_lang_html( 'admin/options_saved' );
				}
			}

			//	Get the actual options

			$options = qa_get_options( $getoptions );
	
			$p_path = $this->theme_directory . 'patterns';
			$s_path = $this->theme_directory . 'styles';
			$p_url = $this->theme_url . 'patterns';
			$s_url = $this->theme_url . 'styles';

            $formstyle = 'wide';

            $checkboxtodisplay = array(
                'smatika_top_bar_left_text'     => 'option_smatika_enable_top_bar',
                'smatika_top_bar_right_text'    => 'option_smatika_enable_top_bar',
                'smatika_show_top_social_icons' => 'option_smatika_enable_top_bar',
                'smatika_banner_head_text'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div1_text'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div1_icon'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div2_text'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div2_icon'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div3_text'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_div3_icon'      => 'option_smatika_show_home_page_banner',
                'smatika_banner_show_ask_box'   => 'option_smatika_show_home_page_banner',
                'smatika_banner_closable'       => 'option_smatika_show_home_page_banner',
                'smatika_copyright_text'        => 'option_smatika_show_copyright_at_footer',
                'smatika_custom_404_text'       => 'option_smatika_show_custom_404_page',
            );
			//$this->content['form']=$options;
			
			$this->template = "admin";
			$this->content['navigation']['sub'] = qa_admin_sub_navigation();
			$this->content['suggest_next']="";
			$this->content['title']= qa_lang_html('admin/admin_title') . ' - ' . qa_lang('smatika/smatika_theme');
			$this->content['error'] = $securityexpired ? qa_lang_html( 'admin/form_security_expired' ) : qa_admin_page_error();

			$this->content['script_rel'][] = 'qa-content/qa-admin.js?' . QA_VERSION;

			$this->content['form'] = array(
				'ok'      => $formokhtml,

				'tags'    => 'method="post" action="' . qa_self_html() . '" name="admin_form" onsubmit="document.forms.admin_form.has_js.value=1; return true;"',

				'style'   => $formstyle,

				'fields'  => array(),

				'buttons' => array(
					'save'  => array(
						'tags'  => 'id="dosaveoptions"',
						'label' => qa_lang_html( 'admin/save_options_button' ),
					),

					'reset' => array(
						'tags'  => 'name="doresetoptions"',
						'label' => qa_lang_html( 'admin/reset_options_button' ),
					),
				),

				'hidden'  => array(
					'dosaveoptions' => '1',
					'has_js'        => '0',
					'code'          => qa_get_form_security_code( 'admin/smatika' ),
				),
			);
			
			$indented = false;

			foreach ( $showoptions as $optionname )
				if ( empty( $optionname ) ) {
					$indented = false;

					$qa_content['form']['fields'][] = array(
						'type' => 'blank',
					);

				} elseif ( strpos( $optionname, '/' ) !== false ) {
					$qa_content['form']['fields'][] = array(
						'type'  => 'static',
						'label' => qa_lang_html( $optionname ),
					);

					$indented = true;

				} else {
					$type = @$optiontype[$optionname];
					if ( $type == 'number-blank' )
						$type = 'number';

					$value = $options[$optionname];

					$optionfield = array(
						'id'    => $optionname,
						'label' => ( $indented ? '&ndash; ' : '' ) . qa_lang( 'smatika/'.$optionname ),
						'tags'  => 'name="option_' . $optionname . '" id="option_' . $optionname . '"',
						'value' => qa_html( $value ),
						'type'  => $type,
						'error' => qa_html( @$errors[$optionname] ),
					);

					if ( isset( $optionmaximum[$optionname] ) )
						$optionfield['note'] = qa_lang_html_sub( 'admin/maximum_x', $optionmaximum[$optionname] );

					$feedrequest = null;
					$feedisexample = false;

					switch ( $optionname ) { // special treatment for certain options

						case 'special_opt': //not using for now
							$optionfield['note'] = smatika_options_lang_html( $optionname . '_note' );
							break;

					}

					switch ( $optionname ) {
						case 'smatika_activate_prod_mode':
						case 'smatika_use_local_font':
						case 'smatika_top_bar_left_text':
						case 'smatika_top_bar_right_text':
						case 'smatika_enable_top_bar':
						case 'smatika_enable_sticky_header':
						case 'smatika_enable_back_to_top_btn':
						case 'smatika_show_home_page_banner':
						case 'smatika_show_collapsible_btns':
						case 'smatika_show_breadcrumbs':
						case 'smatika_show_site_stats_above_footer':
						case 'smatika_show_social_links_at_footer':
						case 'smatika_show_copyright_at_footer':
						case 'smatika_show_custom_404_page':
							$optionfield['style'] = 'tall';
							break;
					}

					$this->content['form']['fields'][$optionname] = $optionfield;
				}


			if ( isset( $checkboxtodisplay ) )
				qa_set_display_rules( $this->content, $checkboxtodisplay );
		}
		qa_html_theme_base::doctype();
	}
}
/*
	Omit PHP closing tag to help avoid accidental output
*/