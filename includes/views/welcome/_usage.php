        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-lg font-bold mb-2"><?php _e( 'Usage', 'better-click-to-tweet' )?></h2>
            <div id="bctt-instructions">
                <p class="mb-4"><?php _e( 'To add styled click-to-tweet quote boxes include the Better Click To Tweet block or shortcode in your post.', 'better-click-to-tweet') ?></p>
               
                <p class="mb-4">
                   <?php _e('In the WordPress 5.x+ editor (also known as Gutenberg), there is a Better Click To Tweet block. Use the forward slash command <span class="bg-gray-200 font-mono">/tweet</span> to quickly add a Better Click To Tweet block.', 'better-click-to-tweet') ?> 
               </p>
               
               <p class="mb-2"><?php _e('If you\'re using the Classic Editor, here\'s how you format the shortcode:', 'better-click-to-tweet') ?></p>

               <p class="bg-gray-200 p-4 rounded shadow-md font-mono">[bctt tweet="<?php _e('Meaningful, tweetable quote.', 'better-click-to-tweet') ?>"]</p>

               <p class="my-4">
                   <?php _e('If you are using the visual editor within the classic WordPress editor, click the BCTT birdie in the toolbar to access a shortcode generator that adds a pre-formatted shortcode to your post.', 'better-click-to-tweet') ?>

               </p>
            </div>
            <h2 class="text-lg font-bold mb-2"><?php _e( 'Power Users Rejoice!', 'better-click-to-tweet' )?></h2>
            <div id="bctt-instructions">
                <p class="mb-4">
                    <?php _e( 'This plugin has a ton under the hood to maximize the sharing of your posts on Twitter. If you\'re using the classic editor, many of those features are more hidden. ', 'better-click-to-tweet' )?>
                </p>
                
                <p class="mb-4">
                    <?php echo sprintf( __( 'That\'s why we have a <a class="text-blue-500" target="_blank" rel="noopener noreferrer" href="%s">Power User Guide</a>.', 'better-click-to-tweet'), esc_url( 'http://benlikes.us/7r') ); ?>
                </p>   
            </div>
        </div>
       
        <form id="bctt-set-handle" action="" class="text-center">
            <div id="bctt-wizard-nav" class="mt-12 flex justify-between">
                <a href="<?php echo bctt_get_step_url( 'bctt-setup' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>
               
                <a href="<?php echo bctt_get_step_url( 'bctt-content' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">
                        <?php _e( 'Next', 'better-click-to-tweet' )?>
                </a>
            </div>
        </form>