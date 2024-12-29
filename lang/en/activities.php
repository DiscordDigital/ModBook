<?php
    # Check if the function customTranslate is not defined
    if (!function_exists("customTranslate")) {
        # Include file that contains customTranslate
        include "custom.php";
    }

    # Run customTranslate and return the results
    return customTranslate("activities");
?>
