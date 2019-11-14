<?php

namespace Lightpost;

class Settings
{
    protected $plugin_file;

    public function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;

        if (is_admin()) {
            $this->registerOptions();
            $this->addPage();
            $this->addPluginLink();
        }
    }

    protected function registerOptions()
    {
        register_activation_hook($this->plugin_file, function () {
            add_option('lightpost_api_key', '');
            add_option('lightpost_theme', '');
            add_option('lightpost_sermon_archive_page_id', '');
            add_option('lightpost_bible_class_registration_page_id', '');
            add_option('lightpost_directory_page_id', '');
            add_option('lightpost_directory_disclaimer', '');
        });

        register_deactivation_hook($this->plugin_file, function () {
            delete_option('lightpost_api_key');
            delete_option('lightpost_theme');
            delete_option('lightpost_sermon_archive_page_id');
            delete_option('lightpost_bible_class_registration_page_id');
            delete_option('lightpost_directory_page_id');
            delete_option('lightpost_directory_disclaimer');
        });

        add_action('admin_init', function () {
            register_setting('lightpost', 'lightpost_api_key');
            register_setting('lightpost', 'lightpost_theme');
            register_setting('lightpost', 'lightpost_sermon_archive_page_id');
            register_setting('lightpost', 'lightpost_bible_class_registration_page_id');
            register_setting('lightpost', 'lightpost_directory_page_id');
            register_setting('lightpost', 'lightpost_directory_disclaimer');
        });
    }

    protected function addPage()
    {
        add_action('admin_menu', function () {
            add_submenu_page(
                'options-general.php',
                'Lightpost',
                'Lightpost',
                'manage_options',
                'lightpost',
                function () {
                    include dirname($this->plugin_file).'/views/admin.php';
                }
            );
        });
    }

    protected function addPluginLink()
    {
        add_filter('plugin_action_links_'.plugin_basename($this->plugin_file), function ($links) {
            return array_merge($links, [
                '<a href="'.admin_url('options-general.php?page=lightpost').'">Settings</a>',
            ]);
        });
    }
}
