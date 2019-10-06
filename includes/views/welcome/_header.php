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
        <img
            src="<?php echo plugins_url( '../../assets/img/bctt-logo-alt.png', dirname(__FILE__) ); ?>" 
            alt="<?php _e( 'Better Click To Tweet', 'better-click-to-tweet' )?>" 
            class="mx-auto w-9/12 sm:max-w-xs mt-12 mb-10" />

            <div id="bctt-steps-nav" class="hidden md:block max-w-2xl mx-6 md:mx-auto">
                <ol class="bctt-steps">
                    <li 
                        <?php echo bctt_get_step_class( 'bctt-setup' ); ?>>
                        <a 
                            href=" <?php echo admin_url( "admin.php?page=bctt-welcome&step=1")?>"
                            title="<?php _e( 'Back to that Awe-inspiring video', 'better-click-to-tweet' )?>">
                            <?php _e( 'Welcome', 'better-click-to-tweet' )?>
                        </a>
                    </li>
                    <li <?php echo bctt_get_step_class( 'bctt-usage' ); ?>>
                        <a 
                            href=" <?php echo admin_url( "admin.php?page=bctt-welcome&step=bctt-usage")?>" 
                            title="<?php _e( 'Learn how to use Better Click To Tweet', 'better-click-to-tweet' )?>">
                            <?php _e( 'Usage', 'better-click-to-tweet' )?>
                        </a>
                    </li>
                    <li <?php echo bctt_get_step_class( 'bctt-content' ); ?>>
                        <a 
                            href=" <?php echo admin_url( "admin.php?page=bctt-welcome&step=bctt-content")?>" 
                            title="<?php _e( 'Learn why it\'s probably not us, it\'s your content', 'better-click-to-tweet' )?>">
                            <?php _e( 'Content', 'better-click-to-tweet' )?>
                        </a>
                    </li>
                    <li <?php echo bctt_get_step_class( 'bctt-grow' ); ?>>
                        <a 
                            href=" <?php echo admin_url( "admin.php?page=bctt-welcome&step=bctt-grow")?>" 
                            title="<?php _e( 'Sign up to be a part of the (hashtag) NextBigThing', 'better-click-to-tweet' )?>">
                            <?php _e( 'Grow', 'better-click-to-tweet' )?>
                        </a>
                    </li>
                    <li <?php echo bctt_get_step_class( 'bctt-done' ); ?>>
                        <a 
                            href=" <?php echo admin_url( "admin.php?page=bctt-welcome&step=bctt-done")?>"
                            title="<?php _e( 'Why you tryna skip steps?', 'better-click-to-tweet' )?>">                        
                            <?php _e( 'Done!', 'better-click-to-tweet' )?>
                        </a>
                    </li>
                </ol>
            </div>
        <div class="bg-white max-w-2xl mx-6 md:mx-auto p-8 mb-4 shadow rounded">

