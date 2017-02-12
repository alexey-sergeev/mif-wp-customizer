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
        add_options_page( __( 'Настройка плагина WP Customizer', 'mif-wp-customizer' ), __( 'WP Customizer', 'mif-wp-customizer' ), 'manage_options', 'mif-wp-customizer', array( $this, 'page' ) );
        wp_register_style( 'mif-wp-customizer-styles', plugins_url( '../mif-wp-customizer-styles.css', __FILE__ ) );
        wp_enqueue_style( 'mif-wp-customizer-styles' );
    }

    function page()
    {
        $out = '<h1>' . __( 'Настройка плагина WP Customizer', 'mif-wp-customizer' ) . '</h1>';
        $out .= '<p>' . __( 'Плагин MIF WP Customizer добавляет к вашему сайте несколько великолепных возможностей. Здесь вы можете указать, что именно надо применить на вашем сайте.', 'mif-wp-customizer' );
        $out .= '<p>&nbsp;';
      
        $out .= $this->update_mif_wpc_options();

        $args = get_mif_wpc_options();
        foreach ( $args as $key => $value ) {
            $chk[$key] = ( $value ) ? ' checked' : '';
        }

        $chk_jtm_mm[$args['join-to-multisite-mode']] = ' checked';

        $select_user_role = mif_wpc_wp_dropdown_roles( $args['join-to-multisite-default-role'] );

        $out .= '<form method="POST">';
        $out .= '<h2>' . __( 'Визуальные элементы', 'mif-wp-customizer' ) . '</h2>';
        $out .= '<table class="form-table">';
        $out .= '<tr>
                <th class="one">' . __( 'Меню «Войти/Выйти»', 'mif-wp-customizer' ) . '</th>
                <td class="two"><input type="checkbox"' . $chk['login-logout-menu'] . ' value = "yes" name="login-logout-menu" id="login-logout-menu"></td>
                <td class="three"><label for="login-logout-menu">' . __( 'Разрешить использовать элемент меню «Войти/Выйти». В меню отображается ссылка «Войти» или «Выйти» в зависимости от текущего статуса авторизации пользователя.', 'mif-wp-customizer' ) . '</label></td>
                </tr>';
        $out .= '<tr>
                <th>' . __( 'Виджет авторизации', 'mif-wp-customizer' ) . '</th>
                <td><input type="checkbox"' . $chk['login-logout-widget'] . ' value = "yes" name="login-logout-widget" id="login-logout-widget"></td>
                <td><label for="login-logout-widget">' . __( 'Разрешить использовать виджет авторизации. В зависимости от текущего статуса авторизации пользователя виджет отображает форму авторизации, либо аватар и имя пользователя.', 'mif-wp-customizer' ) . '</label></td>
                </tr>';
        $out .= '<tr>
                <th>' . __( 'Кнопка «Наверх»', 'mif-wp-customizer' ) . '</th>
                <td><input type="checkbox"' . $chk['button-to-top'] . ' value = "yes" name="button-to-top" id="button-to-top"></td>
                <td><label for="user_pass" for="button-to-top">' . __( 'Показывать кнопку «Наверх». Кнопка включается при пролистывании страницы вниз и позволяет быстро вернуться на начало.', 'mif-wp-customizer' ) . '</label></td>
                </tr>';
        $out .= '</table>';

        $out .= '<h2>' . __( 'Поведение сайта', 'mif-wp-customizer' ) . '</h2>';
        $out .= '<table class="form-table">';
        $out .= '<tr>
                <th>' . __( 'Шорткоды', 'mif-wp-customizer' ) . '</th>
                <td><input type="checkbox"' . $chk['mif-wpc-shortcodes'] . ' value = "yes" name="mif-wpc-shortcodes" id="mif-wpc-shortcodes"></td>
                <td><label for="user_pass" for="mif-wpc-shortcodes">' . __( 'Разрешить использовать шорткоды (redirect).', 'mif-wp-customizer' ) . '</label></td>
                </tr>';
        // $out .= '<tr>
        //         <th>' . __( 'MIME типы', 'mif-wp-customizer' ) . '</th>
        //         <td><input type="checkbox"' . $chk['mif-wpc-mime-types'] . ' value = "yes" name="mif-wpc-mime-types" id="mif-wpc-mime-types"></td>
        //         <td><label for="user_pass" for="mif-wpc-mime-types">' . __( 'Разрешить добавление пользовательских MIME типов.', 'mif-wp-customizer' ) . '</label></td>
        //         </tr>';
        $out .= '</table>';

        if ( is_multisite() ) {

            $out .= '<h2>' . __( 'Новые элементы для WordPress Multisite', 'mif-wp-customizer' ) . '</h2>';
            $out .= '<table class="form-table">';
            $out .= '<tr>
                    <th>' . __( 'Статус участника сайта', 'mif-wp-customizer' ) . '</th>
                    <td><input type="checkbox"' . $chk['join-to-multisite'] . ' value = "yes" name="join-to-multisite" id="join-to-multisite"></td>
                    <td><label for="login-logout-widget">' . __( 'Разрешить использовать виджет статуса участника. В зависимости от настроек этот виджет будет отображать текущий статус или позволять его изменять.', 'mif-wp-customizer' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['none'] . ' value="none"> ' . __( 'Не разрешать изменять статус (только просмотр)', 'mif-wp-customizer' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['manual'] . ' value="manual"> ' . __( 'Ручное изменение статуса (кнопки "Стать участником", "Покинуть сайт")', 'mif-wp-customizer' ) . '</label>
                    <p><label><input type="radio" name="join-to-multisite-mode"' . $chk_jtm_mm['automatic'] . ' value="automatic"> ' . __( 'Автоматическое и ручное изменение статуса (кнопки, а также автоматическая запись участником при размещении комментариев или записей)', 'mif-wp-customizer' ) . '</label>
                    <p>Статус пользователя по умолчанию <select name="join-to-multisite-default-role">' . $select_user_role . '</select>
                    </td>
                    </tr>';
            $out .= '</table>';

        }

        $out .= wp_nonce_field( "mif-wpc-admin-settings-page-nonce", "_wpnonce", true, false );
        $out .= '<p><input type="submit" class="button button-primary" name="update-mif-wpc-settings" value="' . __( 'Сохранить изменения', 'mif-wp-customizer' ) . '">';
        $out .= '</form>';

        echo $out;
    }

    function update_mif_wpc_options()
    {
        if ( ! $_POST['update-mif-wpc-settings'] ) return;
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], "mif-wpc-admin-settings-page-nonce" ) ) return '<div class="err">' . __( 'Ошибка авторизации', 'mif-wp-customizer' ) . '</div>';

        $args = get_mif_wpc_options();
        foreach ( $args as $key => $value ) {
            
            if ( isset($_POST[$key]) ) {
                $new_value = ( $_POST[$key] == 'yes' ) ? 1 : $_POST[$key];
            } else {
                $new_value = 0;    
            }
            
            update_option( $key, $new_value );
        }

        return '<div class="note">' . __( 'Изменения сохранены', 'mif-wp-customizer' ) . '</div>';
    }



}

new mif_wpc_console_settings_page();

?>
