<?php
//uninstall script

//protection
if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();
//remove options
delete_option('secondary_html_options');

//note: we could remove secondary html content blocks, but seems like a bad idea... dont want to accidentally delete content!!
?>