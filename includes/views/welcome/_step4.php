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
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">
                        <?php _e( 'Step 3', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">
                        <?php _e( 'Step 4', 'better-click-to-tweet' )?>
                    </span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
            </ul>
        </div>
      
        
        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-center text-lg font-bold">About the Author</h2>
            <div class=" flex justify-center my-4">
                <?php echo get_avatar( 'ben.meredith@gmail.com', 128 , '' ,'Ben Meredith', array( 'class' => 'rounded-full shadow-2xl border-4 border-solid border-gray-200' ) ); ?>
            </div>
            <p class="text-center"> 
                This plugin is developed by <a class="text-blue-500" href="https://www.wpsteward.com">Ben Meredith</a>. I am a freelance developer specializing in <a class="text-blue-500" href="https://www.wpsteward.com/service-plans">outrunning and outsmarting hackers</a>.
            </p>
        </div>
       
        <form 
            id="mc-embedded-subscribe-form" 
            name="mc-embedded-subscribe-form"
            action="//benandjacq.us1.list-manage.com/subscribe/post?u=8f88921110b81f81744101f4d&id=bd909b5f89&SUBSET=http%3A%2F%2Fbctt.test" 
            method="post"
            target="_blank"           
            class="text-center mt-12">
            
            <label for="bctt-email" class="text-gray-600 font-bold w-2/3 inline-block mb-2">
                <?php _e( 'Sign up here for a free 7-part email series on how to manage your website like a pro.', 'better-click-to-tweet' )?>
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
                    placeholder="jhon@gmail.com"
                    class="border-2 border-solid border-gray-300 text-gray-600 px-2 pl-2 py-1 rounded text-center w-1/2"
                />
            </div>

            <div class="mt-2 text-gray-500 text-sm">No Spam. One-click unsubscribe in every message</div>

            <div id="bctt-wizard-nav" class="mt-12 flex justify-between">
                <a 
                    href="<?php echo bctt_get_step_url( 'step3' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>
                <input 
                    type="submit" 
                    value="<?php _e( 'Finish', 'better-click-to-tweet' )?>
"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">            
            </div>
        </form>

        <a 
        href="<?php echo bctt_get_step_url( 'finish' ); ?>"
        class="text-blue-500 underline w-full text-center block"
        title="Go to dashboard">
            <?php _e( 'Skip', 'better-click-to-tweet' )?>
        </a>


        <script>
            var bctt_mailing_form = document.getElementById("mc-embedded-subscribe-form");

            bctt_mailing_form.addEventListener("submit", function(e) {
                window.location = "<?php echo bctt_get_step_url( 'finish' );  ?>"
            });
        </script>