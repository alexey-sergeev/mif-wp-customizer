<?php

//
// Страница настроек плагина
//
//


defined( 'ABSPATH' ) || exit;


class mif_wpc_console_settings_page {
    
    function __construct() 
    {
        add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
    }

    function register_menu_page()
    {
        add_options_page( __( 'WP Customizer Plugin configuration', 'mif-wpc' ), __( 'WP Customizer', 'mif-wpc' ), 'manage_options', 'mif-wpc', array( $this, 'page' ) );
        wp_register_style( 'mif-wpc-styles', plugins_url( '../mif-wpc-styles.css', __FILE__ ) );
        wp_enqueue_style( 'mif-wpc-styles' );

        wp_register_style( 'font-awesome', plugins_url( '../css/font-awesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'font-awesome' );
    }

    function page()
    {
        $out = '';
        
        $out .= '<h1>' . __( 'WP Customizer Plugin configuration', 'mif-wpc' ) . '</h1>';
        
        $out .= '<p>' . __( 'MIF WP Customizer plugin adds new features to your site. Here you can specify what exactly should be applied on your site.', 'mif-wpc' );

        $out .= $this->update_mif_wpc_options();

        $args = get_mif_wpc_options();
        foreach ( $args as $key => $value ) {
            $chk[$key] = ( $value ) ? ' checked' : '';
        }

        $chk_jtm_mm[$args['join-to-multisite-mode']] = ' checked';

        $select_user_role = mif_wpc_wp_dropdown_roles( $args['join-to-multisite-default-role'] );

        $out .= '<form method="POST">';
        $out .= '<table class="form-table">';
        $out .= '<tr><td colspan="3">';
        $out .= '<h2>' . __( 'Visual elements', 'mif-wpc' ) . '</h2>';
        $out .= '</td></tr>';
        $out .= '<tr>
                <th class="one">' . __( 'Menu "Login/Logout"', 'mif-wpc' ) . '</th>
                <td class="two"><input type="checkbox"' . $chk['login-logout-menu'] . ' value = "yes" name="login-logout-menu" id="login-logout-menu"></td>
                <td class="three"><label for="login-logout-menu">' . __( 'Allow to use menu item "Login/Logout". The "Login" or "Logout" link is displayed in the menu depending on the current status of user authorization.', 'mif-wpc' ) . '</label></td>
                </tr>';
        $out .= '<tr>
                <th>' . __( 'Authorization widget', 'mif-wpc' ) . '</th>
                <td><input type="checkbox"' . $chk['login-logout-widget'] . ' value = "yes" name="login-logout-widget" id="login-logout-widget"></td>
                <td><label for="login-logout-widget">' . __( 'Allow to use authorization widget. Widget displays either authorization form or avatar and username, depending on the current status of user authorization.', 'mif-wpc' ) . '</label></td>
                </tr>';
        $out .= '<tr>
                <th>' . __( '"Up" button', 'mif-wpc' ) . '</th>
                <td><input type="checkbox"' . $chk['button-to-top'] . ' value = "yes" name="button-to-top" id="button-to-top"></td>
                <td><label for="button-to-top">' . __( 'Show the "Up" button. The button turns on when the page is scrolled down and allows you to quickly return to top.', 'mif-wpc' ) . '</label></td>
                </tr>';
        $out .= '<tr>
                <th>' . __( 'Toolbar', 'mif-wpc' ) . '</th>
                <td><input type="checkbox"' . $chk['disable-admin-bar'] . ' value = "yes" name="disable-admin-bar" id="disable-admin-bar"></td>
                <td><label for="disable-admin-bar">' . __( 'Hide the toolbar (admin-bar) for all site users.', 'mif-wpc' ) . '</label></td>
                </tr>';
        $out .= '<tr><td colspan="3">';
        $out .= '<h2>' . __( 'Site behavior', 'mif-wpc' ) . '</h2>';
        $out .= '</td></tr>';
        $out .= '<tr>
                <th>' . __( 'Shortcodes', 'mif-wpc' ) . '</th>
                <td><input type="checkbox"' . $chk['mif-wpc-shortcodes'] . ' value = "yes" name="mif-wpc-shortcodes" id="mif-wpc-shortcodes"></td>
                <td><label for="mif-wpc-shortcodes">' . __( 'Allow to use shortcodes (redirect).', 'mif-wpc' ) . '</label></td>
                </tr>';

        if ( is_multisite() ) {

            $out .= '<tr><td colspan="3">';
            $out .= '<h2>' . __( 'New elements for WordPress Multisite', 'mif-wpc' ) . '</h2>';
            $out .= '</td></tr>';
            $out .= '<tr>
                    <th>' . __( 'Site member’s status', 'mif-wpc' ) . '</th>
                    <td><input type="checkbox"' . $chk['join-to-multisite'] . ' value = "yes" name="join-to-multisite" id="join-to-multisite"></td>
                    <td><label for="login-logout-widget">' . __( 'Allow to use member status widget. Depending on configuration, this widget will display the current status or allow to change it.', 'mif-wpc' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['none'] . ' value="none"> ' . __( 'Don’t allow to change status (view only)', 'mif-wpc' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['manual'] . ' value="manual"> ' . __( 'Manual status change ("Become a member", "Leave site" buttons)', 'mif-wpc' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['automatic'] . ' value="automatic"> ' . __( 'Automatic and manual status change (buttons and automatic registration as member when posting comments or posts)', 'mif-wpc' ) . '</label>
                    <p>' . __( 'Default member’s status', 'mif-wpc' ) . ': <select name="join-to-multisite-default-role">' . $select_user_role . '</select>
                    </td>
                    </tr>';

        }
        

        $out .= '<tr><td colspan="3">';
        $out .= wp_nonce_field( "mif-wpc-admin-settings-page-nonce", "_wpnonce", true, false );
        $out .= '<p><input type="submit" class="button button-primary" name="update-mif-wpc-settings" value="' . __( 'Save changes', 'mif-wpc' ) . '">';
        $out .= '</td></tr>';
        
        $out .= '</table>';
        $out .= '</form>';
        
        $out .= $this->donate();
          
        echo $out;
    }
    
    
    function donate()
    {
        $out = '';
        
        $out .= '<p>&nbsp;<p><strong>' . __( 'You can help make this plugin better', 'mif-wpc' ) . '</strong></p>';
        $out .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="94EGBV7KSTBHE">
            <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/ru_RU/i/scr/pixel.gif" width="1" height="1">
            </form>';

        return $out;
    }


    function update_mif_wpc_options()
    {
        if ( empty( $_POST['update-mif-wpc-settings'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], "mif-wpc-admin-settings-page-nonce" ) ) return '<div class="err">' . __( 'Authorization error', 'mif-wpc' ) . '</div>';

        $args = get_mif_wpc_options();
        foreach ( $args as $key => $value ) {
            
            if ( isset($_POST[$key]) ) {

                $new_value = ( $_POST[$key] == 'yes' ) ? 1 : sanitize_text_field( $_POST[$key] );

            } else {

                $new_value = 0;    

            }
            
            update_option( $key, $new_value );
        }

        return '<div class="note">' . __( 'Changes saved', 'mif-wpc' ) . '</div>';
    }



}

new mif_wpc_console_settings_page();

?>