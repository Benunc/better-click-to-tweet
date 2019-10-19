        <div id="bctt-copy" class="text-gray-600">
            <h1 class="text-center text-lg font-bold px-2 text-gray-700">
                <?php _e( 'Getting Started Using Better Click To Tweet', 'better-click-to-tweet') ?>
            </h1>
        </div>
        <div id="bctt-video" class="rounded-lg shadow-xl overflow-hidden mx-auto my-8 bg-gray-200">
            <iframe 
                width="560" 
                height="315" 
                src="https://www.youtube.com/embed/G-N9uZPq6o8?controls=0?modestbranding=1&rel=0" 
                frameborder="0" 
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>

        <div id="bctt-copy" class="text-gray-600">
            <h2 class="text-center text-lg font-bold px-2 text-gray-700">
                <?php _e( 'Good content is shared when you make it shareable!', 'better-click-to-tweet') ?>
            </h2>
        </div>

        <form 
            id="bctt-set-handle" 
            action="<?php echo $_SERVER['REQUEST_URI']; ?>" 
            method="post"
            class="text-center flex flex-col flex-no-wrap mt-8">

            <label for="bctt-twitter" class="text-blue-500 font-bold">
                <?php _e( 'Want a site-wide default for the "via" on the Tweet?', 'better-click-to-tweet' )?>
            </label>
            <div class="mt-4 flex justify-center">
                <span class="flex justify-center bg-gray-300 rounded px-2 -mr-2">
                    <svg
                        class="text-gray-500 fill-current w-4 mr-2"
                        viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                            <g id="icon-shape">
                                <path d="M13.6038462,13.465884 C14.2335946,14.3918325 15.2957747,15 16.5,15 C18.4329966,15 20,13.4329966 20,11.4999996 L20,10 L19.8315922,10 L20,10 C20,4.4771525 15.5228475,0 10,0 C4.4771525,0 0,4.4771525 0,10 C0,15.5228475 4.4771525,20 10,20 C11.6078966,20 13.1271602,19.6205173 14.4731143,18.9462285 L14.4731143,18.9462285 L13.5784914,17.1569828 C12.5017282,17.6964138 11.2863173,18 10,18 C5.581722,18 2,14.418278 2,10 C2,5.581722 5.581722,2 10,2 C14.418278,2 18,5.581722 18,10 L18,10.7499176 L18,11.5 C17.9999117,12.3284424 17.3341474,13 16.5,13 C15.6715729,13 15,12.3256778 15,11.4998351 L15,9.16840779 L15,5 L13,5 L13,5.99963381 C12.1643395,5.37194482 11.1256059,5 10,5 C7.23857625,5 5,7.23857625 5,10 C5,12.7614237 7.23857625,15 10,15 C11.4158008,15 12.6941617,14.4115493 13.6038462,13.465884 L13.6038462,13.465884 Z M10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 Z" id="Combined-Shape"></path>							
                            </g>
                        </g>
                    </svg>
                </span>

                <input 
                    type="text" 
                    name="bctt-twitter" 
                    id="bctt-twitter" 
                    value="<?php echo esc_attr( get_option( 'bctt-twitter-handle' ) ); ?>"
                    placeholder="<?php _e( 'Enter your Twitter handle ', 'better-click-to-tweet' )?>"
                    class="border-2 border-solid border-gray-300 text-gray-600 px-2 pl-2 py-1 rounded text-center w-1/2"
                />
            </div>
            <span class="mt-2 text-gray-500 text-sm"><?php _e( 'You can also do this on a per-box basis in the block settings or the shortcode generator.', 'better-click-to-tweet') ?></span>

            <div id="bctt-wizard-nav" class="mt-12 flex justify-end">
                <input 
                    type="submit" 
                    value="<?php _e( 'Next', 'better-click-to-tweet' )?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer self-end">
            </div>
        </form>