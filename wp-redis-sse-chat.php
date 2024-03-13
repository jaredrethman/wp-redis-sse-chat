<?php
/**
 * Plugin Name:         WP Redis SSE Chat Experiment
 * Requires Plugins:    wp-redis, wp-redis-sse
 * Plugin URI:          https://github.com/jaredrethman/wp-redis-sse-chat
 * Description:         Chat experiment using WP Redis SSE.
 * Author:              Jared Rethman
 * Author URI:          https://jaredrethman.com
 * Text Domain:         wp-redis-sse-chat
 * Domain Path:         /languages
 * Version:             0.0.1
 * Required:            6.5.0
 * Required PHP:        8.0.0
 * Requires Plugins:    wp-redis
 * Network:             true
 * GitHub Plugin URI:   https://github.com/jaredrethman/wp-redis-sse-chat
 *
 * @package             WpRedisSseChat
 */

 const WRS_CHAT_DIR = __DIR__;
 const WRS_CHAT_VER = '0.0.1';
 define('WRS_CHAT_URL', plugins_url('', __FILE__));
 
 /**
  * When installing/using wp-redis, setting `$redis_server` in wp-config.php
  * if required. If it doesn't exist, wp-redis plugin isn't active and/or installed.
  */
 if (empty($redis_server)) {
     return;
 }
 
 require_once WRS_CHAT_DIR . '/includes/includes.php';
