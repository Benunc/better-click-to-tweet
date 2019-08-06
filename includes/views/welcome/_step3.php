        <div id="bctt-steps">
            <ul class="list-outside flex justify-around pb-10 my-8">
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm font-bold">Step 1</span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">Step 2</span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-blue-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">Step 3</span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-blue-500 h-1 w-full m-0 -mt-12"/>
                </li>
                <li class="flex flex-col flex-no-wrap text-gray-500 text-center w-1/4">
                    <span class="-mb-8 text-sm">Step 4</span>
                    <span class="text-6xl -mb-px">•</span>
                    <hr class="bg-gray-500 h-1 w-full m-0 -mt-12"/>
                </li>
            </ul>
        </div>
      
        <div id="bctt-copy" class="text-gray-600">
                Link to documentation on power user features and an article on how to get users to click on content       
        </div>
       
        <form id="bctt-set-handle" action="" class="text-center">
            <div id="bctt-wizard-nav" class="mt-8 flex justify-between">
                <a 
                    href="<?php echo bctt_get_step_url( 'step2' ); ?>"
                    class="rounded py-1 px-2 border-2 border-solid border-blue-500 text-blue-600">
                    Previous
                </a>
            
                <a href="<?php echo bctt_get_step_url( 'step4' ); ?>"
                    class="rounded py-1 px-2 bg-blue-500 border-2 border-solid border-blue-500 text-white cursor-pointer">
                    Next
                </a>
            </div>
        </form>