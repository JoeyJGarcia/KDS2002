<?php
// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)

define('HTTP_SERVER', 'http://www.kerussods.com');
define('DIR_WS_HTTP_HOMEDIR', '/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_CLASSES', 'includes/classes/');
define('DIR_WS_IMAGES', 'images/');
define('DIR_FS_*',  '/home/kerussod/public_html/');
define('SESSION_WRITE_DIRECTORY', '/tmp');
define('SESSION_FORCE_COOKIE_USE', 'False');

// define our database connection

define('DB_SERVER', 'localhost');

// eg, localhost - should not be empty for productive servers

define('DB_SERVER_USERNAME', 'kerussod_chillie');
define('DB_SERVER_PASSWORD', '123456q');
define('DB_DATABASE', 'kerussod_kdsdb');
define('USE_PCONNECT', 'false');

// use persistent connections?

define('STORE_SESSIONS', '');

// leave empty '' for default handler or set to 'mysql'


?>