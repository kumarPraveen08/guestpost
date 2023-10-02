<?php
// LOAD CONFIG
require_once 'config/config.php';

// LOAD HELPERS
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

// LOAD LIBRARIES
// require_once 'libraries/Core.php';
// require_once 'libraries/Controller.php';
// require_once 'libraries/Database.php';

spl_autoload_register(function ($className) {
    require_once 'libraries/'. $className . '.php';
});
