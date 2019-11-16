<?php

/*
Plugin Name:       Lightpost
Plugin URI:        https://github.com/lightpost-app/wordpress-plugin
Description:       This plugin allows churches to display content from their Lightpost account on their Wordpress-based website.
Author:            Lightpost
Author URI:        https://lightpost.app
Version:           1.1.3
Requires PHP:      7.0
Requires at least: 5.0
*/

define('LIGHTPOST_API_DOMAIN', 'https://api.lightpost.app/v1');

include 'includes/Settings.php';
include 'includes/Util.php';
include 'includes/SermonArchive.php';
include 'includes/BibleClass.php';
include 'includes/Directory.php';

(new Lightpost\Settings(__FILE__));
(new Lightpost\Util());
(new Lightpost\SermonArchive());
(new Lightpost\BibleClass());
(new Lightpost\Directory());
