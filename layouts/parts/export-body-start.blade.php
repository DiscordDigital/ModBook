@if ($format === 'pdf' or $format === 'html')
<?php
    # Location of modStylesGlobal.css
    $modStylesGlobal = getcwd() . "/uploads/ModBook/modStylesGlobal.css";
    $engine = isset($engine) ? $engine : "html";

    # Don't modify css if dompdf is used
    if ($engine !== 'dompdf') {
        # Check if modStylesGlobal.css exists
        if (file_exists($modStylesGlobal)) {
            # Obtain conents of modStylesGlobal.css
            $modStylesGlobalContent = file_get_contents($modStylesGlobal);

            # Check if $engine is html, if it is, don't use the media attribute
            if ($engine === 'html') {
                $attribute = "";
            } else {
                $attribute = 'media="print"';
            }

            # Creating a style tag with the $attribute variable, including the contents of $modStylesGlobalContent
            echo "<style ". $attribute . "> " . $modStylesGlobalContent . "</style>"."\n";
        }
    }
?>
@endif
