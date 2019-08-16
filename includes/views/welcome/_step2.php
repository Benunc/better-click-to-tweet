        <div id="bctt-steps">
            <ul class="list-outside flex justify-around pb-10 my-8">
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm font-bold">
                        <?php _e( 'Step 1', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">
                        <?php _e( 'Step 2', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-gray-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">
                        <?php _e( 'Step 3', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-gray-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-gray-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">
                        <?php _e( 'Step 4', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-gray-500 h-1 w-full m-0 -mt-12"/>
                </li>
            </ul>
        </div>
      
        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-lg font-bold mb-2">Usage</h2>
            <div id="bctt-instructions">
                <p class="mb-4">To add styled click-to-tweet quote boxes include the Better Click To Tweet shortcode in your post.</p>
               
               <p class="mb-2">Here's how you format the shortcode:</p>

               <p class="bg-gray-200 p-4 rounded shadow-md font-mono">[bctt tweet="Meaningful, tweetable quote."]</p>

               <p class="my-4">
                   If you are using the visual editor, click the BCTT birdie in the toolbar to add a pre-formatted shortcode to your post.

               </p>
             
               <p class="mb-4">
                   In the WordPress 5.0 editor (codenamed Gutenberg), there is a Better Click To Tweet block.
               </p>
            </div>
        </div>
       
        <form id="bctt-set-handle" action="" class="text-center">
            <div id="bctt-wizard-nav" class="mt-12 flex justify-between">
                <a href="<?php echo bctt_get_step_url( 'step1' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>
               
                <a href="<?php echo bctt_get_step_url( 'step3' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">
                        <?php _e( 'Next', 'better-click-to-tweet' )?>
                </a>
            </div>
        </form>