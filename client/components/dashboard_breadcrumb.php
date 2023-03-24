<?php

function generate_breadcrumb(array $list_breadcrumbs)
{

    echo "<div class='flex items-center gap-2 mt-5'>";
    echo "<a class='text-xl text-gray-400 hover:text-amber-500 transition' href='./index.php'>Home</a>";

    foreach ($list_breadcrumbs as $key => $file) {
        $filename = $file['filename'];
        $title = $file['title'];
        echo "<i class='ri-arrow-right-s-line text-gray-400 text-xl'></i>";
        echo "<a class='text-xl text-slate-800 dark:text-white hover:text-amber-500 transition' href='#'>$title</a>";
    }
    echo '</div>';
}
