<?php
/*
Plugin Name: Blyen Profile
Description: Unlock the Power of Data with Blyen! Elevate your customer relationships and satisfaction, all in one place.
Version: 1.0
Author: Blyen
Author URI: https://blyen.com
*/

class Blyen_Profile_Plugin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_shortcode('blyen_profile', array($this, 'shortcode_handler'));
        add_filter('manage_users_columns', array($this, 'add_custom_column_header'));
        add_filter('manage_users_custom_column', array($this, 'populate_custom_column'), 10, 3);
    }

    public function add_admin_menu()
    {
        add_options_page(
            'Blyen Profile Settings',
            'Blyen Profile',
            'manage_options',
            'blyen-settings',
            [$this, 'settings_page']
        );
    }

    public function settings_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        $response = wp_remote_get('https://blyen.com/api/project?a=' . urlencode($_POST['blyen_project_key'] ? $_POST['blyen_project_key'] : get_option('blyen_project_key', '')));
        $projectName = '';

        if (isset($_POST['blyen_project_key'])) {
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                if (isset($data['name'])) {
                    $projectName = $data['name'];
                }
                update_option('blyen_project_key', sanitize_text_field($_POST['blyen_project_key']));
                echo '<div class="updated"><p>Project Key updated.</p></div>';
            } else {
                echo '<div class="error"><p>Project Key not found.</p></div>';
            }

        }

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['name'])) {
                $projectName = $data['name'];
            }
        }
        $projectKey = get_option('blyen_project_key', '');

        include(plugin_dir_path(__FILE__) . 'templates/settings-page.php');
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style('blyen-admin-styles', plugin_dir_url(__FILE__) . 'assets/admin-styles.css');
    }

    public function shortcode_handler($atts)
    {
        $atts = shortcode_atts(array(
            'title' => 'Verify with Blyen',
        ), $atts);

        ob_start();

        wp_enqueue_script('blygo-button');

        $users = get_users();

        include(plugin_dir_path(__FILE__) . 'templates/blyen-profile-template.php');

        return ob_get_clean();
    }

    public function add_custom_column_header($columns)
    {
        $columns['blyen_profile_column'] = 'Blyen Profile';
        return $columns;
    }

    public function populate_custom_column($value, $column_name, $user_id)
    {
        if ($column_name === 'blyen_profile_column') {
            // Retrieve user data
            $user = get_userdata($user_id);
            $email = $user->user_email;
            $atts = array('title' => 'Profile', 'blyen_project_key' => get_option('blyen_project_key', ''));

            ob_start();

            // Load the Blyen Profile template along with the SDK
            include(plugin_dir_path(__FILE__) . 'templates/blyen-profile-template.php');

            return ob_get_clean();
        }
        return $value;
    }
}

new Blyen_Profile_Plugin();
