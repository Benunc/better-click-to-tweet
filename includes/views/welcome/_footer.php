            </div>

            <?php if ( 'step1' === bctt_get_step()  ): ?>
                <a 
                    href="<?php echo admin_url( ); ?>"
                    class="text-gray-600 underline w-full text-center block mb-10 text-xs"
                    title="Go to dashboard">
                <?php _e( 'Not Right Now', 'better-click-to-tweet' )?>
                </a>
            <?php endif; ?>

    </body>
</html>