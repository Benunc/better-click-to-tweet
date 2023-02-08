        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-center text-lg font-bold"><?php _e( 'We\'re planning big things to help you ace the quality content game.', 'better-click-to-tweet' )?></h2>
            <div id="bctt-instructions">
                <p class="my-4"><?php _e( 'If you want to be the first to know about our plans to help you level up your content (so that readers share it!) sign up below.', 'better-click-to-tweet' ) ?>
            </div>

        </div>
        <div class="border border-solid border-blue-200 bg-blue-100 p-4 rounded flex justify-center my-12">
        <svg class="fill-current text-blue-500 w-6 mr-2" viewBox="0 0 20 20">
							<path d="M17.388,4.751H2.613c-0.213,0-0.389,0.175-0.389,0.389v9.72c0,0.216,0.175,0.389,0.389,0.389h14.775c0.214,0,0.389-0.173,0.389-0.389v-9.72C17.776,4.926,17.602,4.751,17.388,4.751 M16.448,5.53L10,11.984L3.552,5.53H16.448zM3.002,6.081l3.921,3.925l-3.921,3.925V6.081z M3.56,14.471l3.914-3.916l2.253,2.253c0.153,0.153,0.395,0.153,0.548,0l2.253-2.253l3.913,3.916H3.56z M16.999,13.931l-3.921-3.925l3.921-3.925V13.931z"></path>
						</svg>
                    <a class="text-sm font-bold text-blue-500" href="https://benlikes.us/bcttsubscribe" target="_blank" rel="nofollow noopener noreferrer"> <?php _e( 'Subscribe Today', 'better-click-to-tweet' ); ?> </a></strong></p>
        </div>
        <div class="text-center text-gray-500 text-sm"><?php _e( 'No Spam. One-click unsubscribe in every message', 'better-click-to-tweet' ) ?></div>

        <div id="bctt-wizard-nav" class="mt-12 flex justify-between items-center">
                <a 
                    href="<?php echo bctt_get_step_url( 'bctt-content' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>

                <a 
                    href="<?php echo bctt_get_step_url( 'bctt-done' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white">
                        <?php _e( 'Finish', 'better-click-to-tweet' )?>
                </a>         
        </div>