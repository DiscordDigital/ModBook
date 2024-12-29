<?php
    function customTranslate($file) {
        # Directory of the ModBook folder
        $mbDir = realpath(getcwd()."/../themes/ModBook/");

        # User configuration file location
        $modsConfig = realpath($mbDir."/config.php");

        # Check if config file exists
        if (file_exists($modsConfig)) {
            # Loads user configuration file
            include $modsConfig;

            # Checks if the variable is set
            if (isset($CategoriesAndTopics)) {
                # Checks if the mod is disabled
                if ($CategoriesAndTopics === FALSE) {
                    # Don't translate if mod is disabled
                    return [];
                }
            } else {
                # Don't translate if variable was not set
                return [];
            }
        } else {
            # Don't translate if config file was not found
            return [];
        }

        # Data to replace in translation files
        $replaceData = [
            "Book" => "Topic",
            "book" => "topic",
            "Books" => "Topics",
            "books" => "topics",
            "Shelf" => "Category",
            "shelf" => "category",
            "Shelves" => "Categories",
            "shelves" => "categories",
            "Chapters" => "Sections",
            "chapters" => "sections",
            "Chapter" => "Section",
            "chapter" => "section",
        ];

        # Create path of original translation file
        $fileData = getcwd() . "/../lang/en/" . $file . ".php";

        # Checks if the translation exists
        if (file_exists(realpath($fileData))) {
            # Initialize empty array
            $replacedTranslation = [];

            # Load translation data
            $translation = include $fileData;

            # Iterate over all translations in that file
            foreach ($translation as $key => $value) {
                # Iterate over all data in $replaceData
                foreach ($replaceData as $find => $replace) {
                    # Don't replace anything, if the value is an array
                    if (!is_array($value)) {
                        # Check if there's text that needs to be translated
                        if (strpos($value, $find) !== FALSE || $value == $find) {
                            # Check if the key has already been translated
                            if (isset($replacedTranslation[$key])) {
                                # Apply modification using existing data
                                $replacedTranslation[$key] = str_replace($find, $replace, $replacedTranslation[$key]);
                            } else {
                                # Apply modification using original data
                                $replacedTranslation[$key] = str_replace($find, $replace, $value);
                            }
                        }
                    }
                }
            }

            # Return the modified translations
            return $replacedTranslation;
        } else {
            # Don't return any modifications if original file is missing
            return [];
        }
    }
?>
