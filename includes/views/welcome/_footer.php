            </div>

            <?php if ( 'bctt-done' !== bctt_get_step() ): ?>
                <a 
                    href="<?php echo admin_url( 'options-general.php?page=better-click-to-tweet' ); ?>"
                    class="text-gray-600 underline w-full text-center block mb-10 text-xs"
                    title="<?php _e( 'Go to dashboard', 'better-click-to-tweet' )?>">
                <?php _e( 'Not right now', 'better-click-to-tweet' )?>
                </a>
            <?php endif; ?>
            

    </body>
</html>