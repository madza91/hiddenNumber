<?php
/**
 * Created by PhpStorm.
 * User: Nemanja
 * Date: 5.3.2017
 * Time: 14:11
 */


session_start();

require_once('core/config.php');
include('helpers/system_helper.php');


global $config;

/*
 * Check is program started from terminal
 */
$terminal = (isset($_SERVER['SERVER_NAME'])) ? false: true;

$site_template_dir = "{$config['base_url']}templates/{$config['templates']}/";

$actual_link = ($terminal) ? false: "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

/*
 * Define global variables
 */
define ('BASE_DIR', $config['base_dir']);
define ('BASE_URL', $config['base_url']);
define ('MAINTENANCE', $config['maintenance']);
define ('DEBUGGING', $config['debugging']);
define ('SITE_TEMPLATE', $config['templates']);
define ('SITE_TEMPLATE_DIR', $site_template_dir);
define ('ACTUAL_LINK', $actual_link);
define ('DEV_MODE', false);
define ('SITE_TITLE', $config['title']);


/*
 * Include helper functions
 */
require_once(BASE_DIR . 'helpers/system_helper.php');

if (!DEBUGGING){
    error_reporting(0);
}