        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-center text-lg font-bold"><?php _e( 'We\'re planning big things to help you ace the quality content game.', 'better-click-to-tweet' )?></h2>
            <div id="bctt-instructions">
                <p class="my-4"><?php _e( 'If you want to be the first to know about our plans to help you level up your content, so that readers share it, sign up below.', 'better-click-to-tweet' ) ?>
            </div>

        </div>
       
        <form 
            id="mc-embedded-subscribe-form" 
            name="mc-embedded-subscribe-form"
            action="//benandjacq.us1.list-manage.com/subscribe/post?u=8f88921110b81f81744101f4d&id=bd909b5f89&SUBSET=splash_<?php echo urlencode( site_url() ) ?> " 
            method="post"
            target="_blank" 
            rel="noopener noreferrer"          
            class="text-center mt-12">
            
            <label for="bctt-email" class="text-gray-600 font-bold w-2/3 inline-block mb-2">
                <?php _e( 'Better content starts here.', 'better-click-to-tweet' )?>
            </label>
            <div class="mt-2 flex justify-center">
                <span class="flex justify-center bg-gray-300 rounded px-2 -mr-2">
                    <svg
                        class="text-gray-500 fill-current w-4 mr-2"
                        viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                            <g id="icon-shape">
                                <path d="M14.8780488,10.097561 L20,14 L20,16 L13.627451,11.0980392 L10,14 L6.37254902,11.0980392 L0,16 L0,14 L5.12195122,10.097561 L0,6 L0,4 L10,12 L20,4 L20,6 L14.8780488,10.097561 Z M18.0092049,2 C19.1086907,2 20,2.89451376 20,3.99406028 L20,16.0059397 C20,17.1072288 19.1017876,18 18.0092049,18 L1.99079514,18 C0.891309342,18 0,17.1054862 0,16.0059397 L0,3.99406028 C0,2.8927712 0.898212381,2 1.99079514,2 L18.0092049,2 Z" id="Combined-Shape"></path>
                            </g>
                        </g>
                    </svg>
                </span>

                <input 
                    type="text" 
                    name="EMAIL"
                    type="email" 
                    id="bctt-emails" 
                    placeholder="you@example.com"
                    class="border-2 border-solid border-gray-300 text-gray-600 px-2 pl-2 py-1 rounded-l text-center w-1/2"
                />
                <input 
                    type="submit" 
                    value="<?php _e( 'Subscribe', 'better-click-to-tweet' )?>"
                    class="rounded-r py-1 px-2 bg-blue-400 border-2 border-solid border-blue-400 text-white cursor-pointer -ml-1 text-sm"/>  
            </div>

            <div class="mt-2 text-gray-500 text-sm"><?php _e( 'No Spam. One-click unsubscribe in every message', 'better-click-to-tweet' ) ?></div>
        </form>

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