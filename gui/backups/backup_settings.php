<?php 

    $BS_SETTINGS_INTERNAL = array(
        "GENERAL" => array(
            "DEBUG_MODE_PHP" => true, // show all php errors
            "DEBUG_MODE" => true, // if enabled on error/warnig create a log file
            "DEBUG" => true, // If true all actions are save in var: $DEBUG

            "PRE_DECLARED_CLASSES" => true,
            "PRE_DECLARED_CLASSES_NAMES" => array("tools"   => "class_tools",
                                                  "captcha" => "class_captcha",
                                                  "agent"   => "class_agent",
                                                  "sql"     => "class_sql",
                                                  "html"    => "class_html"
                                                )
        ),
        "PLUGINS" => array(
            "IS_ENABLED" => true,
            "FOLDER_NAME" => "plugins",
            "FILE-INCLUDED_FROM_BASECODE" => "index.php",
            "FILE-GUI_PLUGIN_MANAGER" => "config.php",
        ),
        "DB" => array(
            "IS_ENABLED" => true, // enable or disabled db
            "HOST" => array("localhost", "localhost"),
            "NAME" => array("basecode_test", "basecode_test2"),
            "USER" => array("capo","capo"),
            "PASS" => array("ciao","ciao"),
        ),
        "CAPTCHA" => array(
            "GOOGLE_CAPTCHA_PUBLIC-KEY" => "key_value",
            "GOOGLE_CAPTCHA_PRIVATE-KEY" => "key_value",
            "HCAPTCHA_PUBLIC-KEY" => "key_value",
            "HCAPTCHA_PRIVATE-KEY" => "key_value",
        ),
        // Errors
        "ERRORS" => array(
            "NEVER_DIE_PAGE" => true, // false
            "SHOW_HTML_ERROR_MESSAGE" => true,

            "DEFAULT_PUBLIC_ERROR" => "Action not allowed!",
            "DEFAULT_ERROR" => "Unable to complete this operation, check the logs for more details",
            "DEFAULT_DIE_ERROR" => "Critical Error Detected, please check the log file",
        ),
    );