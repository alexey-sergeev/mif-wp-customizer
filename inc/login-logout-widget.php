<?php

//
// Виджет "Войти/Выйти"
//
//


defined( 'ABSPATH' ) || exit;


if ( mif_wpc_options( 'mif_wpc_login_logout_menu' ) ) 
    add_action( 'widgets_init', 'mif_wpc_user_login_widget_init' );

function mif_wpc_user_login_widget_init() 
{
    register_widget( 'mif_wpc_user_login_widget' );
}




class mif_wpc_user_login_widget extends WP_Widget {

	public function __construct() 
    {
		$widget_options = apply_filters( 'mif_wpc_login_widget_options', array(
			'classname'   =>    'user_login_widget',
			'description' => __( 'Отображает форму входа на сайт', 'mif-wp-customizer' )
		) );

		parent::__construct( false, __( 'Форма входа на сайт', 'mif-wp-customizer' ), $widget_options );
	}



	public static function register_widget() 
    {
		register_widget( 'mif_wpc_user_login_widget' );
	}



	public function widget( $args, $data ) 
    {
        extract( $args );

        $out = '';
        
        $out .= $before_widget;

		$title = apply_filters( 'mif_wpc_user_login_widget_title',    $data['title']    );
		if ( ! empty( $title ) ) $out .= $before_title . $title . $after_title;

        if ( is_user_logged_in() ) {

            $current_user = wp_get_current_user();

            $avatar = get_avatar( $current_user->ID );
            $user_name = ( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
            $user_link = ( function_exists( 'bp_core_get_user_domain' ) ) ? bp_core_get_user_domain( $current_user->ID ) : $current_user->user_url;
            if ( empty( $user_link ) ) $user_link = get_option('siteurl') . '/wp-admin/profile.php';

            $out .= '<div class="mif_wpc_user_login_widget widget logged-in">
                    <a href="' . $user_link . '">' . $avatar . '</a>
                    <div>
                    <h4><a href="' . $user_link . '" class="username">' . $user_name . '</a></h4>
                    <a href="' . wp_logout_url( get_permalink() ) . '" class="logout">' . __( 'Выйти', 'mif-wp-customizer' ) . ' &rarr;</a></h4>
                    </div> 
                    </div>';

        } else {

            $out .= '<form method="post" action="' . wp_login_url() . '" class="mif_wpc_user_login_widget">
                        <div class="username">
                            <label for="user_login">Имя пользователя:</label>
                            <p><input type="text" name="log" value="" size="20" id="user_login" />
                        </div>

                        <div class="password">
                            <label for="user_pass">Пароль:</label>
                            <p><input type="password" name="pwd" value="" size="20" id="user_pass" />
                        </div>
                
                        <div class="rememberme">
                            <input type="checkbox" name="rememberme" value="forever" id="rememberme" />
                            <label for="rememberme">Запомнить меня</label>
                        </div>
                
                        <div class="submit-wrapper">
                            <p><button type="submit" name="user-submit" id="user-submit" class="button submit user-submit">' . __( 'Войти', 'mif-wp-customizer' ) . '</button>
                            <input type="hidden" name="user-cookie" value="1">
                            <input type="hidden" id="redirect_to" name="redirect_to" value="' . get_permalink() . '">
                        </div>

                        <p><a href="' . wp_registration_url() . '">' . __( 'Создать новую учётную запись', 'mif-wp-customizer' ) . '</a><br />
                        <a href="' . wp_lostpassword_url() . '">' . __( 'Забыли пароль?', 'mif-wp-customizer' ) . '</a>


                    </form>';

        }


		$out .= $after_widget;

        echo $out;

	}



	public function update( $new_instance, $old_instance ) 
    {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['register'] = esc_url( $new_instance['register'] );
		$instance['lostpass'] = esc_url( $new_instance['lostpass'] );

		return $instance;
	}



	public function form( $data ) 
    {
		$title = ( ! empty( $data['title'] ) ) ? esc_attr( $data['title'] ) : '';
		$register = ( ! empty( $data['register'] ) ) ? esc_attr( $data['register'] ) : '';
		$lostpass = ( ! empty( $data['lostpass'] ) ) ? esc_attr( $data['lostpass'] ) : '';

        $out = '';

        $out .= '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Заголовок', 'mif-wp-customizer' ) . '
                <input class="widefat" id="' . $this->get_field_id( 'title' ) . ' " name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /></label>';

        echo $out;    
    }
}

