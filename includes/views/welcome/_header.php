<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php esc_html_e( 'Better Click To Tweet &rsaquo; Welcome', 'better-click-to-tweet' ); ?></title>
        <?php do_action( 'admin_enqueue_scripts' ); ?>
        <?php do_action( 'admin_print_styles' ); ?>
        <?php do_action( 'admin_head' ); ?>
    </head>

    <body class="bg-gray-200">

        <div class="bg-white w-1/3 m-auto p-8 my-4 shadow rounded">
            <img 
                src="<?php echo plugins_url( '../../assets/img/bctt-logo.png', dirname(__FILE__) ); ?>" 
                alt="<?php _e( 'Better Click To Tweet', 'better-click-to-tweet' )?>" 
                class="mx-auto" />

