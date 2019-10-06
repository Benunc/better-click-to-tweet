        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-lg font-bold mb-2"><?php _e( 'Your low quality content is probably the problem.', 'better-click-to-tweet' )?></h2>
            <div id="bctt-instructions">
                <p class="mb-4">
                    <?php _e( 'I didn\'t want to be the one to have to tell you, but you could likely triple the number of social shares on your content with a little effort.', 'better-click-to-tweet' )?>
                </p>
                
                <p class="mb-4">
                    <?php _e( 'Since launching Better Click To Tweet in 2014, I\'ve seen thousands of folks use the plugin, and some patterns started to emerge. Some would get one or two clicks-to-tweet while others got hundreds or even thousands of clicks. I started to isolate what made the difference.', 'better-click-to-tweet')  ?>
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
                            <?php _e( 'Here\'s a splash of cold water to the face regarding your content', 'better-click-to-tweet' )?>
                    </a>
                 </div>
            </div>     
        </div>
       
        <form id="bctt-set-handle" action="" class="text-center">
            <div id="bctt-wizard-nav" class="mt-8 flex justify-between">
                <a 
                    href="<?php echo bctt_get_step_url( 'bctt-usage' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                        <?php _e( 'Previous', 'better-click-to-tweet' )?>
                </a>
            
                <a href="<?php echo bctt_get_step_url( 'bctt-grow' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">
                    <?php _e( 'Next', 'better-click-to-tweet' )?>
                </a>
            </div>
        </form>