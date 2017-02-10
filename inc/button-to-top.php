<?php

//
// Добавление пункта меню "Войти/Выйти"
//
//


defined( 'ABSPATH' ) || exit;


//
// Блок нового пункта меню в консоли WordPress
//
//

class evr_user_login_widget extends WP_Widget {

	/**
	 * bbPress Login Widget
	 *
	 * Registers the login widget
	 *
	 * @since bbPress (r2827)
	 *
	 * @uses apply_filters() Calls 'bbp_login_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'login_widget_options', array(
			'classname'   => 'evr_user_login_widget',
			'description' => __( 'Форма входа на сайт.', 'bbpress' )
		) );

		parent::__construct( false, __( 'EVR Login Widget', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public static function register_widget() {
		register_widget( 'EVR_Login_Widget' );
	}

	/**
	 * Displays the output, the login form
	 *
	 * @since bbPress (r2827)
	 *
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
	 * @uses get_template_part() To get the login/logged in form
	 */
	public function widget( $args, $instance ) {
    extract( $args );

		$title    = apply_filters( 'login_widget_title',    $instance['title']    );
		$register = apply_filters( 'login_widget_register', $instance['register'] );
		$lostpass = apply_filters( 'login_widget_lostpass', $instance['lostpass'] );

		echo $before_widget;

		if ( !empty( $title ) )
			echo $before_title . $title . $after_title;

		if ( !is_user_logged_in() ) : ?>

			<form method="post" action="http://lms.vspu.ru/wp-login.php" class="bbp-login-form">
				<fieldset>
					<legend><?php _e( 'Log In', 'bbpress' ); ?></legend>

					<div class="bbp-username">
						<label for="user_login">Имя пользователя:</label>
						<input type="text" name="log" value="" size="20" id="user_login" />
					</div>

					<div class="bbp-password">
						<label for="user_pass">Пароль:</label>
						<input type="password" name="pwd" value="" size="20" id="user_pass" />
					</div>

					<div class="bbp-remember-me">
						<input type="checkbox" name="rememberme" value="forever" id="rememberme" />
						<label for="rememberme">Запомнить меня</label>
					</div>

					<div class="bbp-submit-wrapper">

						<?php do_action( 'login_form' ); ?>

						<button type="submit" name="user-submit" id="user-submit" class="button submit user-submit">Войти</button>

						<input type="hidden" name="user-cookie" value="1">
        		<input type="hidden" id="bbp_redirect_to" name="redirect_to" value="<?php echo get_permalink(); ?>">

					</div>

					<?php if ( !empty( $register ) || !empty( $lostpass ) ) : ?>

						<div class="bbp-login-links">

							<?php if ( !empty( $register ) ) : ?>

								<a href="<?php echo esc_url( $register ); ?>" title="<?php esc_attr_e( 'Register', 'bbpress' ); ?>" class="bbp-register-link"><?php _e( 'Register', 'bbpress' ); ?></a>

							<?php endif; ?>

							<?php if ( !empty( $lostpass ) ) : ?>

								<a href="<?php echo esc_url( $lostpass ); ?>" title="<?php esc_attr_e( 'Lost Password', 'bbpress' ); ?>" class="bbp-lostpass-link"><?php _e( 'Lost Password', 'bbpress' ); ?></a>

							<?php endif; ?>

						</div>

					<?php endif; ?>

				</fieldset>
			</form>

		<?php else : ?>

			<div class="bbp-logged-in">
        <?php $current_user = wp_get_current_user(); ?>
        <a href="<?php echo bp_core_get_user_domain( $current_user->ID ); ?>" class="submit user-submit"><?php echo get_avatar( $current_user->ID, '40' ); ?></a>
				<h4><a href="<?php echo bp_core_get_user_domain( $current_user->ID ); ?>" class="submit user-submit"><?php if ($current_user->display_name) {echo $current_user->display_name;} else {echo $current_user->user_login;} ?></a></h4>

				<a href="<?php echo wp_logout_url( get_permalink() )?>" class="button logout-link">Выйти</a>
			</div>  

		<?php endif;

		echo $after_widget;
	}

	/**
	 * Update the login widget options
	 *
	 * @since bbPress (r2827)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['register'] = esc_url( $new_instance['register'] );
		$instance['lostpass'] = esc_url( $new_instance['lostpass'] );

		return $instance;
	}

	/**
	 * Output the login widget options form
	 *
	 * @since bbPress (r2827)
	 *
	 * @param $instance Instance
	 * @uses BBP_Login_Widget::get_field_id() To output the field id
	 * @uses BBP_Login_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {

		// Form values
		$title    = !empty( $instance['title'] )    ? esc_attr( $instance['title'] )    : '';
		$register = !empty( $instance['register'] ) ? esc_attr( $instance['register'] ) : '';
		$lostpass = !empty( $instance['lostpass'] ) ? esc_attr( $instance['lostpass'] ) : '';

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label>
		</p>

<!--		<p>
			<label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php _e( 'Register URI:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" type="text" value="<?php echo $register; ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'lostpass' ); ?>"><?php _e( 'Lost Password URI:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'lostpass' ); ?>" name="<?php echo $this->get_field_name( 'lostpass' ); ?>" type="text" value="<?php echo $lostpass; ?>" /></label>
		</p>-->

		<?php
	}
}

