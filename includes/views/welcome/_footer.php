            </div>

            <?php if ( 'bctt-twitter-setup' === bctt_get_step() ): ?>
                <a 
                    href="<?php echo admin_url( ); ?>"
                    class="text-gray-600 underline w-full text-center block mb-10 text-xs"
                    title="<?php _e( 'Go to dashboard', 'better-click-to-tweet' )?>">
                <?php _e( 'Not right now', 'better-click-to-tweet' )?>
                </a>
            <?php endif; ?>
            
            <?php if ( 'bctt-mailing-list' === bctt_get_step()  ): ?>
                <a 
                    href="<?php echo bctt_get_step_url( 'bctt-ready' ); ?>"
                    class="text-gray-600 underline w-full text-center block mb-10 text-xs"
                    title="<?php _e( 'Go to dashboard', 'better-click-to-tweet' )?>">
                <?php _e( 'Not right now', 'better-click-to-tweet' )?>
                </a>
            <?php endif; ?>

    </body>
</html>