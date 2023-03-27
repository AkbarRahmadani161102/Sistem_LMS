<?php

function generate_breadcrumb(array $list_breadcrumbs)
{
    $role = $_SESSION['role'];

    echo "<div class='flex items-center gap-2 mt-5'>";
    echo "<a class='text-xl text-gray-400 hover:text-amber-500 transition' href='../$role/index.php'>Home</a>";

    foreach ($list_breadcrumbs as $key => $file) {
        $filename = $file['filename'];
        $title = $file['title'];

        $is_last_index = fn() => count($list_breadcrumbs) - 1 === $key;
        echo "<i class='ri-arrow-right-s-line text-gray-400 text-xl'></i>";
        if ($is_last_index())
            echo "<a class='text-xl text-slate-800 dark:text-white hover:text-amber-500 transition' href='$filename'>$title</a>";
        else
            echo "<a class='text-xl text-gray-400 hover:text-amber-500 transition' href='$filename'>$title</a>";
    }
    echo '</div>';
}
