<div class="flex w-full mt-2 space-x-3 max-w-xs">
    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300"></div>
    <div>
        <div class="bg-gray-300 p-3 rounded-r-lg rounded-bl-lg">
            <p class="text-sm"><?php the_content(); ?></p>
        </div>
        <span class="text-xs text-gray-500 leading-none"><?php echo get_the_date(); ?></span>
    </div>
</div>