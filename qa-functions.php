<?php
    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        header( 'Location: ../../' );
        exit;
    }
/**
     * Reset the options in $names to their defaults
     *
     * @param $names
     */
    function smatika_reset_options( $names )
    {
        foreach ( $names as $name )
            qa_set_option( $name, smatika_default_option( $name ) );
    }

    /**
     * Return the default value for option $name
     *
     * @param $name
     *
     * @return bool|mixed|string
     */
    function smatika_default_option( $name )
    {
        $fixed_defaults = array(
            'smatika_activate_prod_mode'           => 0,
            'smatika_use_local_font'               => 1,
            'smatika_enable_top_bar'               => 1,
            'smatika_show_top_social_icons'        => 1,
            'smatika_enable_sticky_header'         => 1,
            'smatika_enable_back_to_top_btn'       => 1,
            'smatika_show_home_page_banner'        => 1,
            'smatika_banner_closable'              => 1,
            'smatika_banner_show_ask_box'          => 1,
            'smatika_show_collapsible_btns'        => 1,
            'smatika_show_breadcrumbs'             => 1,
            'smatika_show_site_stats_above_footer' => 1,
            'smatika_show_social_links_at_footer'  => 1,
            'smatika_show_copyright_at_footer'     => 1,
            'smatika_show_custom_404_page'         => 1,
            'smatika_copyright_text'               => qa_lang( 'smatika/smatika_theme' ),
            'smatika_banner_head_text'             => qa_lang( 'smatika/smatika_discussion_forum' ),
            'smatika_banner_div1_text'             => qa_lang( 'smatika/search_answers' ),
            'smatika_banner_div1_icon'             => 'fa fa-search-plus',
            'smatika_banner_div2_text'             => qa_lang( 'smatika/one_destination' ),
            'smatika_banner_div2_icon'             => 'fa fa-question-circle',
            'smatika_banner_div3_text'             => qa_lang( 'smatika/get_expert_answers' ),
            'smatika_banner_div3_icon'             => 'fa fa-check-square-o',
            'smatika_top_bar_left_text'            => qa_lang( 'smatika/responsive_q2a_theme' ),
            'smatika_top_bar_right_text'           => qa_lang( 'smatika/ask_us_anything' ),
            'smatika_custom_404_text'              => qa_lang( 'smatika/page_not_found_default_text' ),
        );

        if ( isset( $fixed_defaults[$name] ) ) {
            $value = $fixed_defaults[$name];
        } else {
            switch ( $name ) {

                default: // call option_default method in any registered modules
                    $modules = qa_load_all_modules_with( 'option_default' );  // Loads all modules with the 'option_default' method

                    foreach ( $modules as $module ) {
                        $value = $module->option_default( $name );
                        if ( strlen( $value ) )
                            return $value;
                    }

                    $value = '';
                    break;
            }
        }

        return $value;
    }

    /**
     * Returns an array of all options used in Blog Tool
     *
     * @return array
     */
    function smatika_get_all_options()
    {
        return array(
            'smatika_activate_prod_mode',
            'smatika_use_local_font',
            'smatika_enable_top_bar',
            'smatika_show_top_social_icons',
            'smatika_enable_sticky_header',
            'smatika_enable_back_to_top_btn',
            'smatika_show_home_page_banner',
            'smatika_banner_closable',
            'smatika_banner_show_ask_box',
            'smatika_show_collapsible_btns',
            'smatika_show_breadcrumbs',
            'smatika_show_site_stats_above_footer',
            'smatika_show_social_links_at_footer',
            'smatika_show_copyright_at_footer',
            'smatika_copyright_text',
            'smatika_banner_head_text',
            'smatika_banner_div1_text',
            'smatika_banner_div1_icon',
            'smatika_banner_div2_text',
            'smatika_banner_div2_icon',
            'smatika_banner_div3_text',
            'smatika_banner_div3_icon',
            'smatika_top_bar_left_text',
            'smatika_top_bar_right_text',
        );
    }

    /**
     * reset all blog options
     *
     * @return bool
     */
    function smatika_reset_all_options()
    {
        smatika_reset_options( smatika_get_all_options() );

        return true;
    }
	
    function smatika_get_glyph_icon( $icon )
    {
        if ( !empty( $icon ) ) {
            return '<span class="glyphicon glyphicon-' . $icon . '"></span> ';
        } else {
            return '';
        }
    }

    function smatika_get_fa_icon( $icon )
    {
        if ( !empty( $icon ) ) {
            return '<span class="fa fa-' . $icon . '"></span> ';
        } else {
            return '';
        }
    }

    function smatika_get_voting_icon( $tags )
    {
        $icon = '';
        switch ( $tags ) {
            case 'vote_up_tags':
                $icon = 'chevron-up';
                break;
            case 'vote_down_tags':
                $icon = 'chevron-down';
                break;
            case 'unselect_tags':
            case 'select_tags':
                $icon = 'check';
                break;
            default:
                break;
        }

        return smatika_get_fa_icon( $icon );
    }

    if ( !function_exists( 'starts_with' ) ) {
        function starts_with( $haystack, $needle )
        {
            return $needle === "" || strpos( $haystack, $needle ) === 0;
        }
    }

    if ( !function_exists( 'ends_with' ) ) {
        function ends_with( $haystack, $needle )
        {
            return $needle === "" || substr( $haystack, -strlen( $needle ) ) === $needle;
        }
    }

    function smatika_remove_brackets( &$nav_cat )
    {
        if ( is_array( $nav_cat ) && count( $nav_cat ) ) {
            foreach ( $nav_cat as $key => &$nav_cat_item ) {
                if ( !empty( $nav_cat_item['note'] ) ) {
                    $nav_cat_item['note'] = str_replace( array( '(', ')' ), '', $nav_cat_item['note'] );
                }
                if ( !empty( $nav_cat_item['subnav'] ) ) {
                    smatika_remove_brackets( $nav_cat_item['subnav'] );
                }
            }
        }
    }

    function smatika_get_user_data( $handle )
    {
        $userid = qa_handle_to_userid( $handle );
        $identifier = QA_FINAL_EXTERNAL_USERS ? $userid : $handle;
        $user = array();
        if ( defined( 'QA_WORDPRESS_INTEGRATE_PATH' ) ) {
            $u_rank = qa_db_select_with_pending( qa_db_user_rank_selectspec( $userid, true ) );
            $u_points = qa_db_select_with_pending( qa_db_user_points_selectspec( $userid, true ) );

            $userinfo = array();
            $user_info = get_userdata( $userid );
            $userinfo['userid'] = $userid;
            $userinfo['handle'] = $handle;
            $userinfo['email'] = $user_info->user_email;

            $user[0] = $userinfo;
            $user[1]['rank'] = $u_rank;
            $user[2] = $u_points;
            $user = ( $user[0] + $user[1] + $user[2] );
        } else {
            $user['account'] = qa_db_select_with_pending( qa_db_user_account_selectspec( $userid, true ) );
            $user['rank'] = qa_db_select_with_pending( qa_db_user_rank_selectspec( $handle ) );
            $user['points'] = qa_db_select_with_pending( qa_db_user_points_selectspec( $identifier ) );

            $user['followers'] = qa_db_read_one_value( qa_db_query_sub( 'SELECT count(*) FROM ^userfavorites WHERE ^userfavorites.entityid = # and ^userfavorites.entitytype = "U" ', $userid ), true );

            $user['following'] = qa_db_read_one_value( qa_db_query_sub( 'SELECT count(*) FROM ^userfavorites WHERE ^userfavorites.userid = # and ^userfavorites.entitytype = "U" ', $userid ), true );
        }

        return $user;
    }

    function smatika_user_profile( $handle, $field = null )
    {
        $userid = qa_handle_to_userid( $handle );
        if ( defined( 'QA_WORDPRESS_INTEGRATE_PATH' ) ) {
            return get_user_meta( $userid );
        } else {
            $query = qa_db_select_with_pending( qa_db_user_profile_selectspec( $userid, true ) );

            if ( !$field ) return $query;
            if ( isset( $query[$field] ) )
                return $query[$field];
        }

        return false;
    }

    function smatika_user_badge( $handle )
    {
        if ( qa_opt( 'badge_active' ) ) {
            $userids = qa_handles_to_userids( array( $handle ) );
            $userid = $userids[$handle];


            // displays small badge widget, suitable for meta

            $result = qa_db_read_all_values(
                qa_db_query_sub(
                    'SELECT badge_slug FROM ^userbadges WHERE user_id=#',
                    $userid
                )
            );

            if ( count( $result ) == 0 ) return;

            $badges = qa_get_badge_list();
            foreach ( $result as $slug ) {
                $bcount[$badges[$slug]['type']] = isset( $bcount[$badges[$slug]['type']] ) ? $bcount[$badges[$slug]['type']] + 1 : 1;
            }
            $output = '<ul class="user-badge clearfix">';
            for ( $x = 2 ; $x >= 0 ; $x-- ) {
                if ( !isset( $bcount[$x] ) ) continue;
                $count = $bcount[$x];
                if ( $count == 0 ) continue;

                $type = qa_get_badge_type( $x );
                $types = $type['slug'];
                $typed = $type['name'];

                $output .= '<li class="badge-medal ' . $types . '"><i class="icon-badge" title="' . $count . ' ' . $typed . '"></i><span class="badge-pointer badge-' . $types . '-count" title="' . $count . ' ' . $typed . '"> ' . $count . '</span></li>';
            }
            $output = substr( $output, 0, -1 );  // lazy remove space
            $output .= '</ul>';

            return ( $output );
        }
    }

    function smatika_get_user_level( $userid )
    {
        global $smatika_userid_and_levels;
        if ( empty( $smatika_userid_and_levels ) ) {
            $smatika_userid_and_levels = qa_db_read_all_assoc( qa_db_query_sub( "SELECT userid , level from ^users" ), 'userid' );
        }

        if ( isset( $smatika_userid_and_levels[$userid] ) ) {
            return $smatika_userid_and_levels[$userid]['level'];
        } else {
            return 0;
        }
    }

    function smatika_get_user_avatar( $userid, $size = 40 )
    {
        if ( !defined( 'QA_WORDPRESS_INTEGRATE_PATH' ) ) {
            $useraccount = qa_db_select_with_pending( qa_db_user_account_selectspec( $userid, true ) );

            $user_avatar = qa_get_user_avatar_html( $useraccount['flags'], $useraccount['email'], null,
                $useraccount['avatarblobid'], $useraccount['avatarwidth'], $useraccount['avatarheight'], $size );
        } else {
            $user_avatar = qa_get_external_avatar_html( $userid, qa_opt( 'avatar_users_size' ), true );
        }

        if ( empty( $user_avatar ) ) {
            // if the default avatar is not set by the admin , then take the default
            $user_avatar = smatika_get_default_avatar( $size );
        }

        return $user_avatar;
    }

    function smatika_get_post_avatar( $post, $size = 40, $html = false )
    {
        if ( !isset( $post['raw'] ) ) {
            $post['raw']['userid'] = $post['userid'];
            $post['raw']['flags'] = $post['flags'];
            $post['raw']['email'] = $post['email'];
            $post['raw']['handle'] = $post['handle'];
            $post['raw']['avatarblobid'] = $post['avatarblobid'];
            $post['raw']['avatarwidth'] = $post['avatarwidth'];
            $post['raw']['avatarheight'] = $post['avatarheight'];
        }

        if ( defined( 'QA_WORDPRESS_INTEGRATE_PATH' ) ) {
            $avatar = get_avatar( qa_get_user_email( $post['raw']['userid'] ), $size );
        }
        if ( QA_FINAL_EXTERNAL_USERS )
            $avatar = qa_get_external_avatar_html( $post['raw']['userid'], $size, false );
        else
            $avatar = qa_get_user_avatar_html( $post['raw']['flags'], $post['raw']['email'], $post['raw']['handle'],
                $post['raw']['avatarblobid'], $post['raw']['avatarwidth'], $post['raw']['avatarheight'], $size );

        if ( empty( $avatar ) ) {
            // if the default avatar is not set by the admin , then take the default
            $avatar = smatika_get_default_avatar( $size );
        }

        if ( $html )
            return '<div class="avatar" data-id="' . $post['raw']['userid'] . '" data-handle="' . $post['raw']['handle'] . '">' . $avatar . '</div>';

        return $avatar;
    }

    function smatika_get_default_avatar( $size = 40 )
    {
        return '<img src="' . THEME_URL . '/images/default-profile-pic.png" width="' . $size . '" height="' . $size . '" class="qa-avatar-image" alt="">';
    }

    function smatika_include_template( $template_file, $echo = true )
    {
        ob_start();
        require( THEME_TEMPLATES . $template_file );
        $op = ob_get_clean();
        if ( $echo ) echo $op;

        return $op;
    }

    function smatika_get_link( $params )
    {
        if ( !empty( $params['icon'] ) ) {
            $icon = '<span class="fa fa-' . $params['icon'] . '"></span> ';
        }

        if ( @$params['tooltips'] ) {
            $tooltips_data = 'data-toggle="tooltip" data-placement="' . @$params['hover-position'] . '" title="' . $params['hover-text'] . '"';
        }

        return sprintf( '<a href="%s" %s>%s %s</a>', @$params['link'], @$tooltips_data, @$icon, @$params['text'] );
    }

    function smatika_get_social_link( $params, $icon_only = false )
    {
        if ( $icon_only ) $params['text'] = '';

        $params['tooltips'] = true;
        $params['hover-position'] = 'bottom';

        return smatika_get_link( $params );
    }

    function smatika_stats_output( $value, $langsingular, $langplural )
    {
        echo '<div class="count-item">';

        if ( $value == 1 )
            echo qa_lang_html_sub( $langsingular, '<span class="count-data">1</span>', '1' );
        else
            echo qa_lang_html_sub( $langplural, '<span class="count-data">' . number_format( (int) $value ) . '</span>' );

        echo '</div>';
    }

    function smatika_generate_social_links()
    {

        $social_links = array(
            'facebook'    => array(
                'icon'       => 'facebook',
                'text'       => qa_lang( 'smatika/facebook' ),
                'hover-text' => qa_lang( 'smatika/follow_us_on_x', qa_lang( 'smatika/facebook' ) ),
            ),
            'twitter'     => array(
                'icon'       => 'twitter',
                'text'       => qa_lang( 'smatika/twitter' ),
                'hover-text' => qa_lang( 'smatika/follow_us_on_x', qa_lang( 'smatika/twitter' ) ),
            ),
            'email'       => array(
                'icon'       => 'envelope',
                'text'       => qa_lang( 'smatika/email' ),
                'hover-text' => qa_lang( 'smatika/send_us_an_email' ),
            ),
            'pinterest'   => array(
                'icon'       => 'pinterest',
                'text'       => qa_lang( 'smatika/pinterest' ),
                'hover-text' => qa_lang( 'smatika/follow_us_on_x', qa_lang( 'smatika/pinterest' ) ),
            ),
            'google-plus' => array(
                'icon'       => 'google-plus',
                'text'       => qa_lang( 'smatika/google-plus' ),
                'hover-text' => qa_lang( 'smatika/follow_us_on_x', qa_lang( 'smatika/google-plus' ) ),
            ),
            'vk'          => array(
                'icon'       => 'vk',
                'text'       => qa_lang( 'smatika/vk' ),
                'hover-text' => qa_lang( 'smatika/follow_us_on_x', qa_lang( 'smatika/vk' ) ),
            ),
        );

        foreach ( $social_links as $key => $s ) {

            if ( $key == 'email' ) {

                $address = qa_opt( 'smatika_email_address' );
                
                if ( empty( $address ) ) {
                    unset( $social_links[$key] );
                    continue;
                }

                $social_links[$key]['link'] = 'mailto:' . $address ;
                continue;
            }

            $url = qa_opt( 'smatika_' . $key . '_url' );

            if ( empty( $url ) ) {
                unset( $social_links[$key] );
                continue;
            }

            $social_links[$key]['link'] = $url;
        }

        return $social_links;
    }