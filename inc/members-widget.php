<?php

//
// Виджет "Пользователи"
//
//


defined( 'ABSPATH' ) || exit;


if ( mif_wpc_options( 'members-widget' ) ) 
    add_action( 'widgets_init', 'mif_wpc_members_widget_init' );

function mif_wpc_members_widget_init() 
{
    register_widget( 'mif_wpc_members_widget' );
}


class mif_wpc_members_widget extends WP_Widget {

	public function __construct() 
    {
		$widget_options = apply_filters( 'mif_wpc_members_widget_options', array(
			'classname'   =>    'members_widget',
			'description' => __( 'Отображает участников сайта', 'mif-wp-customizer' )
		) );

		parent::__construct( false, __( 'Участники сайта', 'mif-wp-customizer' ), $widget_options );
	}



	public static function register_widget() 
    {
		register_widget( 'mif_wpc_members_widget' );
	}



	public function widget( $args, $data ) 
    {
        extract( $args );

        $out = '';
        
        $out .= $before_widget;

		$title = apply_filters( 'mif_wpc_members_widget_title', $data['title'] );

		if ( ! empty( $title ) ) $out .= $before_title . $title . $after_title;

		$avatars = $this->get_avatars( $data );

        $out .= $avatars;

		$out .= $after_widget;

        echo $out;

	}


	private function get_avatars( $data )
	{
		$out = '';

        $number = apply_filters( 'mif_wpc_members_widget_number', $data['number'] );
        $members_type = apply_filters( 'mif_wpc_members_widget_members_type', $data['members_type'] );
		$cache_expires = apply_filters( 'mif_wpc_members_widget_cache_expires', $data['cache_expires'] );
		$avatar_size = apply_filters( 'mif_wpc_members_widget_avatar_size', 50 );

		$user_data = array();

	    global $wpdb, $blog_id;

		$cache_widget_avatars = get_option( 'cache_widget_avatars' );
		$timestamp = absint( $cache_widget_avatars['timestamp'] );
		$now = time();


		if ( ! $cache_widget_avatars || $now - $timestamp > $cache_expires ) {
		
			if ( is_active_buddypress() ) {
				// Если есть buddypress

				$limit = $number * 4;


				$args = array(
						'type' => $members_type,
						'max' => $limit,
						'per_page' => $limit,
						// 'meta_key' => $wpdb->base_prefix . $blog_id . "_capabilities"
				);
				
                if ( ! is_main_site( $blog_id ) ) $args['meta_key'] = $wpdb->base_prefix . $blog_id . "_capabilities";

				if ( bp_has_members( $args ) ) {

					while ( bp_members() ) {

						bp_the_member(); 

						$user_data[] = array(
										'ID' => bp_get_member_user_id(),
										'url' => bp_get_member_link(),
										'name' => bp_get_member_name(),
									);

					}; 

				}

				$avatar_dir = trailingslashit( bp_core_avatar_upload_path() ) . trailingslashit(  'avatars' ); 

				foreach ( (array) $user_data as $key => $item ) {
					if ( count( $user_data ) <= $number ) break;
					if ( ! file_exists( $avatar_dir . $item['ID'] ) ) unset( $user_data[$key] );
				}


			} else {

				// Если buddypress нет

				$limit = $number * 2;

				add_action( 'pre_user_query', array( $this, 'random_user_query' ) );

				$users = get_users( array(
									'blog_id' => $blog_id,
									'number' => $limit,
									'orderby' => 'rand',
								));

				foreach ( (array) $users as $user ) 
					$user_data[] = array(
									'ID' => $user->ID,
									'url' => $user->user_url,
									'name' => $user->user_nicename,
								);
				
			}

			$user_avatars = array();

			foreach ( (array) $user_data as $item ) {
				
				$before = ( $item['url'] ) ? '<a href="' . $item['url'] . '">' : '';
				$after = ( $item['url'] ) ? '</a>' : '';

				$user_avatars[] = '<span class="avatar" title="' . $item['name'] . '">' . $before . get_avatar( $item['ID'], $avatar_size ) . $after . '</span>';

			}

			update_option( 'cache_widget_avatars', array( 'timestamp' => time(), 'user_avatars' => $user_avatars ), false );

		} else {

			$user_avatars = $cache_widget_avatars['user_avatars'];

		}


		shuffle($user_avatars);
		$out_arr = array_splice( $user_avatars, 0, $number );
		$out .= implode( '', $out_arr );

		return $out;
	}

	public function random_user_query( $class ) 
	{
		if( 'rand' == $class->query_vars['orderby'] )
			$class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );

		return $class;
	}


	public function update( $new_data, $old_data ) 
    {
		$data = $old_data;
		$data['title'] = strip_tags( $new_data['title'] );
		$data['number'] = strip_tags( $new_data['number'] );
		$data['members_type'] = strip_tags( $new_data['members_type'] );
		$data['cache_expires'] = strip_tags( $new_data['cache_expires'] );

		return $data;
	}



	public function form( $data ) 
    {
		// $title = ( ! empty( $data['title'] ) ) ? esc_attr( $data['title'] ) : __( 'Участники сайта', 'mif-wp-customizer' );
		$title = isset( $data['title'] ) ? $data['title'] : '';
		$number = isset( $data['number'] ) ? absint( $data['number'] ) : 16;
        $members_type = isset( $data['members_type'] ) ? $data['members_type'] : 'active';
		$cache_expires = isset( $data['cache_expires'] ) ? absint( $data['cache_expires'] ) : 300;

        $out = '';

        $out .= '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Заголовок:', 'mif-wp-customizer' ) . '
                <input class="widefat" id="' . $this->get_field_id( 'title' ) . ' " name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /></label>';
        $out .= '<p><label for="' . $this->get_field_id( 'number' ) . '">' . __( 'Количество аватарок:', 'mif-wp-customizer' ) . '
                <input class="tiny-text" id="' . $this->get_field_id( 'number' ) . ' " name="' . $this->get_field_name( 'number' ) . '" type="number" value="' . $number . '" /></label>';
        // $out .= '<p><label for="' . $this->get_field_id( 'members_type' ) . '">' . __( 'Параметры выбора:', 'mif-wp-customizer' ) . '
        //         <input class="widefat" id="' . $this->get_field_id( 'members_type' ) . ' " name="' . $this->get_field_name( 'members_type' ) . '" type="text" value="' . $members_type . '" /></label>';
		$out .= '<p><label for="' . $this->get_field_id( 'members_type' ) . '">' . __( 'Параметры выбора:', 'mif-wp-customizer' ) . '</label>
			    <select name="' . $this->get_field_name( 'members_type' ) . '" id="' . $this->get_field_id( 'members_type' ) . '" class="widefat">
				<option value="active"' . selected( $members_type, 'active', false ) . '>' . __( 'Активные', 'mif-wp-customizer' ) . '</option>
				<option value="popular"' . selected( $members_type, 'popular', false ) . '>' . __( 'Популярные', 'mif-wp-customizer' ) . '</option>
				<option value="random"' . selected( $members_type, 'random', false ) . '>' . __( 'Случайные', 'mif-wp-customizer' ) . '</option></select>';
        $out .= '<p><label for="' . $this->get_field_id( 'cache_expires' ) . '">' . __( 'Срок хранения в кэше:', 'mif-wp-customizer' ) . '
                <input class="tiny-text" id="' . $this->get_field_id( 'cache_expires' ) . ' " name="' . $this->get_field_name( 'cache_expires' ) . '" type="text" value="' . $cache_expires . '" /> ' . __( 'сек.', 'mif-wp-customizer' ) . '</label>';

        echo $out;    
    }
}


// add_action( 'pre_user_query', 'my_random_user_query' );
// function my_random_user_query( $class ) 
// 	{
// 		p('sss');

// 		if( 'rand' == $class->query_vars['orderby'] )
// 			$class->query_orderby = str_replace( 'user_login', 'RAND()', $class->query_orderby );

// 		return $class;
// 	}


?>
