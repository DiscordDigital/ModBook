@inject('headContent', 'BookStack\Theming\CustomHtmlHeadContentProvider')

@if(setting('app-custom-head') && !request()->routeIs('settings.category'))
<!-- Start: custom user content -->
{!! $headContent->forWeb() !!}
<!-- End: custom user content -->
@endif
<!-- ModBook -->
<?php
    # Directory of the ModBook folder
    $mbDir = realpath(getcwd()."/../themes/ModBook/");

    # User configuration file location
    $modsConfig = $mbDir."/config.php";

    # Default state for OpenGraph
    $OpenGraph = FALSE;

    # Checks if user configuration file exists
    if (file_exists($modsConfig)) {
        # Load user configuration to dynamically check which mods are enabled
        include($modsConfig);
    }
?>
@isset($book, $page)
@if($OpenGraph)
@if($book->cover)
    <meta property="og:description" content="{{ Str::limit($page->text, 100, '...') }}">
    <meta property="og:image" content="{{ $book->getBookCover() }}">
@else
    <meta property="og:description" content="{{ Str::limit($page->text, 100, '...') }}">
@endif
@endif
@endisset
<?php
    # Folder containing the mods
    $modsFolder = realpath($mbDir."/mods");

    # Mods repository file
    $modsSource = realpath($mbDir."/mods.php");

    # Check if user configuration file is missing
    if ($modsConfig === FALSE) {
        # Copy the defaults.php file to config.php
        if (copy($mbDir."/defaults.php", $mbDir."/config.php")) {
            # Reset $modsConfig, because it was set to FALSE previously
            $modsConfig = realpath($mbDir."/config.php");
        }
    }

    # Check if config.hash exists
    if (file_exists($mbDir."/config.hash")) {
        # Obtain sha256 hash
        $configHash = file_get_contents($mbDir."/config.hash");

        # Compare file hash with hash of the user configuration contents
        if ($configHash == hash("sha256", file_get_contents($modsConfig))) {
            # File is the same, don't regenerate cache
            $regenerateCache = FALSE;
        } else {
            # File has changed, regenerate cache
            $regenerateCache = TRUE;
        }
    } else {
        # config.hash doesn't exist, regenerate cache
        $regenerateCache = TRUE;
    }

    # Regenerate cache when required
    if ($regenerateCache === TRUE) {
        # Check if public/uploads/ModBook folder doesn't exist
        if (!file_exists(getcwd() . "/uploads/ModBook")) {
            # Create public/uploads/ModBook folder
            mkdir(getcwd() . "/uploads/ModBook");
        }

        # Path of public folder with modStyles.css, modScripts.js and modStylesGlobal.css files
        $modStylesPath = getcwd() . "/uploads/ModBook/modStyles.css";
        $modStylesGlobalPath = getcwd() . "/uploads/ModBook/modStylesGlobal.css";
        $modScriptsPath = getcwd() . "/uploads/ModBook/modScripts.js";

        # Delete config.hash if exist
        if (file_exists($mbDir."/config.hash")) {
            unlink($mbDir."/config.hash");
        }

        # Delete modStyles.css, modStylesGlobal.css and modScripts.js if exist
        if (file_exists($modStylesPath)) {
            unlink($modStylesPath);
        }
        if (file_exists($modStylesGlobalPath)) {
            unlink($modStylesGlobalPath);
        }
        if (file_exists($modScriptsPath)) {
            unlink($modScriptsPath);
        }

        # Write new config.hash with hash of user configuration file
        file_put_contents($mbDir."/config.hash", hash("sha256", file_get_contents($modsConfig)));

        # Load mods repository to access $modList and $coreMods
        include($modsSource);

        # Enable coreMods by default, and prepend them to the modList
        foreach ($coreMods as &$mod) {
            array_unshift($modList, $mod);
            $$mod = True;
        }

        # Create modStyles.css and modScripts.js file
        foreach ($modList as &$mod) {
            # Only handle mod if the dynamic variable is set
            if (isset($$mod)) {
                # Only load mod if it is set to TRUE
                if ($$mod === TRUE) {
                    # Possible paths of styles.css and scripts.js in mod directory
                    $cssPath = $modsFolder . "/" . $mod . "/styles.css";
                    $cssGlobalPath = $modsFolder . "/" . $mod . "/globalStyles.css";
                    $globalOverrideFile = $modsFolder . "/" . $mod . "/.global";
                    $jsPath = $modsFolder . "/" . $mod . "/scripts.js";

                    # If styles.css exists, create or append into modStyles.css
                    if (file_exists($cssPath)) {
                        # Obtain styles.css contents
                        $cssData = file_get_contents($cssPath);

                        # Load styles.css contents into modStyles.css
                        file_put_contents($modStylesPath, $cssData."\n\n", FILE_APPEND);
                    }

                    # If .global exists, use regular stylesheet for printStyles.css
                    if (file_exists($globalOverrideFile)) {
                        # Obtain styles.css contents
                        $cssData = file_get_contents($cssPath);

                        # Load styles.css contents into modStylesGlobal.css
                        file_put_contents($modStylesGlobalPath, $cssData."\n\n", FILE_APPEND);
                    }

                    # If printStyles.css exists, create or append into modStylesGlobal.css
                    if (file_exists($cssGlobalPath)) {
                        # Obtain styles.css contents
                        $cssData = file_get_contents($cssGlobalPath);

                        # Load styles.css contents into modStylesGlobal.css
                        file_put_contents($modStylesGlobalPath, $cssData."\n\n", FILE_APPEND);
                    }

                    # Checks if modStylesGlobal.css is missing
                    if (!file_exists($modStylesGlobalPath)) {
                        # Creates an empty modStylesGlobal.css file, because the coreMod TineMCEStyles requires it
                        file_put_contents($modStylesGlobalPath, '');
                    }

                    # If scripts.js exists, create or append into modScripts.js
                    if (file_exists($jsPath)) {
                        # Obtain scripts.js contents
                        $jsData = file_get_contents($jsPath);

                        # Load scripts.js contents into modScripts.js
                        file_put_contents($modScriptsPath, $jsData."\n\n", FILE_APPEND);
                    }
                }
            }
        }
    }

    # Only link modStyles.css if it exists in public folder
    if (file_exists(getcwd() . "/uploads/ModBook/modStyles.css")) {
        # Create link HTML pointing to modStyles.css
        echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"/uploads/ModBook/modStyles.css\"></link>"."\n";
    }

    # Only link modScripts.js if it exists in public folder
    if (file_exists(getcwd() . "/uploads/ModBook/modScripts.js")) {
        # Create script HTML pointing to modScripts.js
        echo "    <script nonce=\"$cspNonce\" src=\"/uploads/ModBook/modScripts.js\"></script>";
    }
?>
