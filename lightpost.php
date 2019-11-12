<?php

/*
Plugin Name:       Lightpost
Plugin URI:        https://github.com/lightpost-app/wordpress-plugin
Description:       This plugin allows churches to display content from their Lightpost account on their Wordpress-based website.
Author:            Lightpost
Author URI:        https://lightpost.app
Version:           1.0.1
Requires PHP:      7.0
Requires at least: 5.0
*/

define('LIGHTPOST_API_DOMAIN', 'https://api.lightpost.app/v1');

include 'includes/Styles.php';
include 'includes/Settings.php';
include 'includes/SermonArchive.php';
include 'includes/BibleClass.php';
include 'includes/Util.php';

(new Lightpost\Styles(__FILE__));
(new Lightpost\Settings(__FILE__));
(new Lightpost\SermonArchive());
(new Lightpost\BibleClass());
(new Lightpost\Util());