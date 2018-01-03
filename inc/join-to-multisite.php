<?php

//
// Become a member сайта (мультисайт)
//
//


defined( 'ABSPATH' ) || exit;


if ( mif_wpc_options( 'join-to-multisite' ) )
    add_action( 'widgets_init', 'mif_wpc_join_to_multisite_widget_init' );

function mif_wpc_join_to_multisite_widget_init() 
{
    register_widget( 'mif_wpc_join_to_multisite_widget' );
}




class mif_wpc_join_to_multisite_widget extends WP_Widget {

	public function __construct() 
    {
		$widget_options = apply_filters( 'mif_wpc_join_to_multisite_widget_options', array(
			'classname' => 'join_to_multisite_widget',
			'description' => __( 'Displays the site member status form', 'mif-wpc' )
		) );

		parent::__construct( false, __( 'Site member’s status', 'mif-wpc' ), $widget_options );
	}



	public static function register_widget() 
    {
		register_widget( 'mif_wpc_join_to_multisite_widget' );
	}



	public function widget( $args, $data ) 
    {
        extract( $args );

        $out = '';
        
        $out .= $before_widget;

		$title = apply_filters( 'mif_wpc_join_to_multisite_widget_title',    $data['title']    );
		if ( ! empty( $title ) ) $out .= $before_title . $title . $after_title;

        if ( is_user_logged_in() ) {

            mif_wpc_join_to_multisite_action();

            $current_user = wp_get_current_user();

            $avatar = get_avatar( $current_user->ID );
            $user_name = ( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
            $user_link = ( function_exists( 'bp_core_get_user_domain' ) ) ? bp_core_get_user_domain( $current_user->ID ) : $current_user->user_url;
            if ( empty( $user_link ) ) $user_link = get_option('siteurl') . '/wp-admin/profile.php';

            $action_value = ( is_user_member_of_blog() ) ? 'unjoin' : 'join';
            $submit_value = ( is_user_member_of_blog() ) ? __( 'Leave site', 'mif-wpc' ) : __( 'Become a member', 'mif-wpc' );
            $comment = ( is_user_member_of_blog() ) ? __( 'You are a site member', 'mif-wpc' ) : __( 'You are not a site member', 'mif-wpc' );

            $form = '<p><form method="POST">
                    <input type="hidden" name="action" value="' . $action_value . '">
                    <input type="submit" name="join-to-multisite" value="' . $submit_value . '" class="' . $action_value . '">
                    ' . wp_nonce_field( "mif-wpc-join-to-multisite-nonce", "_wpnonce", true, false ) . '
                    </form>';

            $args = get_mif_wpc_options();
            if ( $args['join-to-multisite-mode'] == 'none' ) $form = '';

            $out .= '<div class="mif_wpc_join_to_multisite_widget widget">
                    <a href="' . $user_link . '">' . $avatar . '</a>
                    <div>
                    <strong><a href="' . $user_link . '" class="username">' . $user_name . '</a></strong><br />
                    ' . $comment . '
                    ' . $form . '
                    </div> 
                    </div>';

        } else {

            // $url = get_site_url() . $_SERVER['REQUEST_URI'];
            $url = ( is_page() || is_single() ) ? get_permalink() : home_url();

            $out .= '<a href="' . wp_login_url( $url ) . '">' . __( 'Please login', 'mif-wpc' ) . '</a>';
            $out .= ' ' . __( 'or', 'mif-wpc' ) . ' ';
            $out .= '<a href="' . wp_registration_url() . '">' . __( 'Register again', 'mif-wpc' ) . '</a>.';

        }


		$out .= $after_widget;

        echo $out;

	}



	public function update( $new_data, $old_data ) 
    {
		$data = $old_data;
		$data['title'] = strip_tags( $new_data['title'] );

		return $data;
	}



	public function form( $data ) 
    {
		$title = ( ! empty( $data['title'] ) ) ? esc_attr( $data['title'] ) : '';
		$register = ( ! empty( $data['register'] ) ) ? esc_attr( $data['register'] ) : '';
		$lostpass = ( ! empty( $data['lostpass'] ) ) ? esc_attr( $data['lostpass'] ) : '';

        $out = '';

        $out .= '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title', 'mif-wpc' ) . '
                <input class="widefat" id="' . $this->get_field_id( 'title' ) . ' " name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /></label>';

        $out .= '<p>' . __( 'The behavior of this element depends on WP Customizer Plugin settings. Check these settings to get the correct result.', 'mif-wpc' );
        $out .= '<p><a href="' . get_admin_url() . 'options-general.php?page=mif-wp-customizer">' . __( 'Settings', 'mif-wpc' ) . '</a>';

        echo $out;    
    }
}



//
// Обработка нажатия кнопки "Become a member", "Leave site"
//
//

// if ( mif_wpc_options( 'join-to-multisite' ) )
//     add_action( 'init', 'mif_wpc_join_to_multisite_action' );

function mif_wpc_join_to_multisite_action()
{
    global $current_user, $blog_id;

    if ( ! $_POST['join-to-multisite'] ) return;
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], "mif-wpc-join-to-multisite-nonce" ) ) return;
    if ( ! $current_user ) return;

    $args = get_mif_wpc_options();

    if ( $args['join-to-multisite-mode'] == 'none' ) return;

    $role = $args['join-to-multisite-default-role'];

    if ( $_POST['action'] == 'join' ) add_user_to_blog( $blog_id, $current_user->ID, $role );
    if ( $_POST['action'] == 'unjoin' ) remove_user_from_blog( $current_user->ID, $blog_id );
   
}


//
// Автоматическое добавление пользователя на сайт
//
//

if ( mif_wpc_options( 'join-to-multisite' ) ) {
    add_action( 'save_post', 'mif_wpc_join_to_multisite_automatic', 10, 3 );
    // add_action( 'wp_insert_comment', 'mif_wpc_join_to_multisite_automatic', 10, 2 );
}

function mif_wpc_join_to_multisite_automatic()
{
    global $current_user, $blog_id;
    if ( ! $current_user ) return;

    $args = get_mif_wpc_options();

    if ( $args['join-to-multisite-mode'] != 'automatic' ) return;

    $role = $args['join-to-multisite-default-role'];
    add_user_to_blog( $blog_id, $current_user->ID, $role );

    // p($current_user);
    // p($blog_id);
}



//
// Выпадающий список с ролями пользователей. 
// Аналог стандартно функции wp_dropdown_roles, но не выводит список,
// а возвращает в переменно
//
//

function mif_wpc_wp_dropdown_roles( $selected = '' ) 
{
	$p = '';
	$r = '';

	$editable_roles = array_reverse( get_editable_roles() );

	foreach ( $editable_roles as $role => $details ) {
		$name = translate_user_role($details['name'] );
		if ( $selected == $role )
			$p = '\n\t<option selected="selected" value="' . esc_attr( $role ) . '">' . $name . '</option>';
		else
			$r .= '\n\t<option value="' . esc_attr( $role ) . '">' . $name . '</option>';
	}
	
    return $p . $r;
}

?>