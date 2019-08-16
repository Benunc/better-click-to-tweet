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
            <h2 class="text-lg font-bold mb-2">Power User</h2>
            <div id="bctt-instructions">
                <p class="mb-4">
                    If you include a link back to the post using the URL parameter (or leaving it out, the default behavior), the tweet length is automatically shortened to 253 characters minus the length of your twitter handle, to leave room for the handle and link back to the post.
                </p>
                
                <p class="mb-4">
                    Learn more about the URL parameter as well as the other power user features in the <a class="text-blue-500" target="_blank" href="http://benlikes.us/7r">Power User Guide</a>.
                </p>

                <div class="border border-solid border-blue-200 bg-blue-100 p-4 rounded flex justify-center my-12">
                    <svg class="fill-current text-blue-500 w-4 mr-2" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                            <g id="icon-shape">
                                <path d="M11,11.8999819 C13.2822403,11.4367116 15,9.41895791 15,7 C15,4.23857625 12.7614237,2 10,2 C7.23857625,2 5,4.23857625 5,7 C5,9.41895791 6.71775968,11.4367116 9,11.8999819 L9,14 L11,14 L11,11.8999819 Z M13,13.3263688 C15.3649473,12.2029049 17,9.79239596 17,7 C17,3.13400675 13.8659932,0 10,0 C6.13400675,0 3,3.13400675 3,7 C3,9.79239596 4.63505267,12.2029049 7,13.3263688 L7,16 L13,16 L13,13.3263688 Z M7,17 L13,17 L13,18.5 C13,19.3284271 12.3349702,20 11.501424,20 L8.49857602,20 C7.67093534,20 7,19.3342028 7,18.5 L7,17 Z" id="Combined-Shape"></path>
                            </g>
                        </g>
                    </svg>
                    <a 
                        class="text-sm font-bold text-blue-500" 
                        href="https://www.wpsteward.com/2019/06/why-arent-my-posts-being-shared-on-social-media/">
                        Learn how to get users to click on content
                    </a>
                 </div>
            </div>     
        </div>
       
        <form id="bctt-set-handle" action="" class="text-center">
            <div id="bctt-wizard-nav" class="mt-8 flex justify-between">
                <a 
                    href="<?php echo bctt_get_step_url( 'step2' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>
            
                <a href="<?php echo bctt_get_step_url( 'step4' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">
                    <?php _e( 'Next', 'better-click-to-tweet' )?>
                </a>
            </div>
        </form>