<?php
    # CLI utility to manage ModBook

    # Load user configuration to obtain status of mods
    if (file_exists("config.php")) {
        include('config.php');
    } else {
        # Copy the defaults.php file to config.php
        if (copy("defaults.php", "config.php")) {
            # Load newly generated user configuration file
            include('config.php');
        }
    }

    # Load mods file to access $modList
    include('mods.php');

    # Prints text, makes it green if supported terminal is detected.
    function _print($str) {
        # Sets the default value of $hasColorSupport to False
        $hasColorSupport = False;

        # Obtains the used terminal from the environment variable TERM
        $env = getenv('TERM');
    
        # Only check if the terminal supports color, when $env is set
        if (isset($env)) {
            # A list of terminals that support color
            $supportedTerms = ['xterm', 'xterm-256color', 'screen', 'screen-256color', 'linux', 'cygwin'];

            # Sets $hasColorSupport to True, if $env is in $supportedTerms
            $hasColorSupport = in_array($env, $supportedTerms);
        }

        # Checks if $hasColorSupport is True
        if ($hasColorSupport) {
            # Prints the text with color
            echo "\033[1;32m".$str."\033[0m\n";
        } else {
            # Prints the text without color
            echo $str."\n";
        }
    }

    # Attempts to remove $file, reports back the status with $altName
    function _clear_file($file, $altName) {
        # Check if $file exists
        if (file_exists($file)) {
            # Delete $file and check if it was deleted successfully
            if (unlink($file)) {
                # Print success for $altName deletion
                _print($altName . " cleared.");
            } else {
                # Print failed for $altName deletion
                _print("Failed to clear " . $altName . ".");
            }
        } else {
            # Print that $altName was not found
            _print($altName . " was not found.");
        }
    }

    # Removes $folder when it is empty, reports back the status with $altName
    function _clear_folder($folder, $altName) {
        # Check if $folder folder exists
        if (file_exists(realpath($folder))) {
            # Read files in $folder
            $files = scandir(realpath($folder));
            # Check if $folder is empty
            if (count($files) == 0 || count($files) == 2) {
                # $folder is empty, attempt to remove it
                if(rmdir(realpath($folder))) {
                    # Print that the $folder got removed
                    _print("Empty " . $altName . " folder got removed.");
                } else {
                    # Print that the removal of the empty $folder failed
                    _print("Failed to remove empty " . $altName . " folder.");
                }
            } else {
                # Print that the $folder is not empty, and is therefore not removed
                _print($altName . " folder is not empty. Not removing it.");
            }
        } else {
            # Print that $folder is not found
            _print($altName . " folder not found.");
        }
    }

    # Prints theme name
    _print("ModBook - A modular BookStack theme");

    if ($argc == 1) { # No arguments passed, show available commands
        # Show empty line
        echo "\n";

        # Information for lsmod argument
        _print("lsmod - Shows all available mods in this version.");

        # Information for clear argument
        _print("clear - Clears cache if available.");
    } elseif ($argv[1] == "lsmod") { # Run if lsmod is passed
        # Show header of the following content
        _print("Mods:");

        # Parse through all available mods in $modList
        foreach ($modList as &$mod) {
            # Check if mod is enabled
            $modEnabled = $$mod ? "[Enabled]" : "[Disabled]";

            # Print the mod name and status
            _print(" - ".$mod . " " . $modEnabled);

            # Create path of readme.txt file, which contains a short description of the mod
            $readmePath = __DIR__."/mods/".$mod."/readme.txt";

            # Only show readme.txt contents, if the file exists
            if (file_exists($readmePath)) {
                # Show readme.txt contents, indented for visual reasons
                _print("   - " . file_get_contents($readmePath));
            }
        }
    } elseif ($argv[1] == "clear") { # Run if clear is passed
        # Clear files
        _clear_file(__DIR__."/config.hash", "config.hash");
        _clear_file(__DIR__."/../../public/uploads/ModBook/modStyles.css", "public/uploads/ModBook/modStyles.css");
        _clear_file(__DIR__."/../../public/uploads/ModBook/modScripts.js", "public/uploads/ModBook/modScripts.js");

        # Clear folder
        _clear_folder(__DIR__."/../../public/uploads/ModBook", "public/uploads/ModBook");
    } else {
        # Print that the passed argument is invalid
        _print("Invalid command.");
    }
?>
