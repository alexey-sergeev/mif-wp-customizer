<?php

//
// Убрать админ-бар
//
//


defined( 'ABSPATH' ) || exit;


if ( mif_wpc_options( 'disable-admin-bar' ) ) {

    define('BP_DISABLE_ADMIN_BAR', true);
    
}


?>
