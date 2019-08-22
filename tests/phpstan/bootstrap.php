<?php

define('WEGLOT_NAME', 'Weglot');
define('WEGLOT_SLUG', 'weglot-translate');
define('WEGLOT_OPTION_GROUP', 'group-weglot-translate');
define('WEGLOT_VERSION', '3.0.6');
define('WEGLOT_PHP_MIN', '5.4');
define('WEGLOT_BNAME', plugin_basename(__FILE__));
define('WEGLOT_DIR', __DIR__ );
define('WEGLOT_DIR_LANGUAGES', WEGLOT_DIR . '/languages');
define('WEGLOT_DIR_DIST', WEGLOT_DIR . '/dist');

define('WEGLOT_DIRURL', plugin_dir_url(__FILE__));
define('WEGLOT_URL_DIST', WEGLOT_DIRURL . 'dist');
define('WEGLOT_LATEST_VERSION', '2.7.0');
define('WEGLOT_LIB_PARSER', '1');
define('WEGLOT_DEBUG', false);
define('WEGLOT_DEV', false);

define('WEGLOT_TEMPLATES', WEGLOT_DIR . '/templates');
define('WEGLOT_TEMPLATES_ADMIN', WEGLOT_TEMPLATES . '/admin');
define('WEGLOT_TEMPLATES_ADMIN_METABOXES', WEGLOT_TEMPLATES_ADMIN . '/metaboxes');
define('WEGLOT_TEMPLATES_ADMIN_NOTICES', WEGLOT_TEMPLATES_ADMIN . '/notices');
define('WEGLOT_TEMPLATES_ADMIN_PAGES', WEGLOT_TEMPLATES_ADMIN . '/pages');
