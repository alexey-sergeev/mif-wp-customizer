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

if ( mif_wpc_options( 'login-logout-menu' ) ) 
    add_action( 'admin_head-nav-menus.php', 'mif_wpc_login_logout_menu_metabox_register' );

function mif_wpc_login_logout_menu_metabox_register() 
{
	add_meta_box( 'mif_wpc_llm', __( 'Войти/Выйти' ), 'mif_wpc_login_logout_menu_metabox_render', 'nav-menus', 'side', 'default' );
}


function mif_wpc_login_logout_menu_metabox_render( $object )
{

	global $nav_menu_selected_id;

    $items = array( (object) array(
                                    'db_id' => 0,
                                    'object' => 'login-logout',
                                    'object_id' => 1,
                                    'menu_item_parent' => 0,
                                    'type' => 'login-logout-menu-type',
                                    'title' => __( 'Войти/Выйти', 'mif-wp-customizer' ),
                                    'url' => '',
                                    'target' => '',
                                    'attr_title' => '', 
                                    'classes' => array(),
                                    'xfn' => '',
                                ));

	$walker = new Walker_Nav_Menu_Checklist( array() );
	?>
	<div id="login-logout">

		<div id="tabs-panel-login-logout-menu-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
			<ul id="login-logout-menu-checklist" class="categorychecklist form-no-clear">
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $items ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>
		</div>

		<p class="button-controls">
			<span class="list-controls">
                <p><?php echo __( 'Ссылка «Войти» или «Выйти» в зависимости от текущего статуса авторизации пользователя', 'mif-wp-customizer' ) ?>
                <p>
				</span>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-login-logout-menu-item" id="submit-login-logout" />
				<span class="spinner"></span>
			</span>
		</p>

	</div>
	<?php

}



//
// Ссылки на вход и выход
//
//

if ( mif_wpc_options( 'login-logout-menu' ) ) 
    add_filter( 'wp_get_nav_menu_items', 'mif_wpc_login_logout_menu_metabox_links', 10, 3 );
 
function mif_wpc_login_logout_menu_metabox_links( $items, $menu, $args ) 
{

    foreach ( $items as &$item ) {
    
        if ( $item->object != 'login-logout' ) continue;
        
        if ( is_user_logged_in() ) {

            $item->url = wp_logout_url();
            $item->title = __( 'Выйти', 'mif-wp-customizer' );

        } else {

            $item->url = wp_login_url();
            $item->title = __( 'Войти', 'mif-wp-customizer' );

        }
    
    }

    return $items;

}





