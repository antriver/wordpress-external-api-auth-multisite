<?php

/**
 * Plugin Name: Wordpress Multisite External API Authentication
 * Plugin URI: https://github.com/antriver/wordpress-external-api-auth-multisite
 * Description: Used to authenticate Wordpress logins via an external REST API.
 * Version: 2015042900
 * Author: Anthony Kuske
 * Author URI: http://www.anthonykuske.com
 */

function wp_api_auth_get_options()
{
    // Return an array of options and their default value
    return array(
        'wp_api_auth_url' => '',
        'wp_api_auth_method' => 'POST',
        'wp_api_auth_username_field' => 'username',
        'wp_api_auth_password_field' => 'password',

        'wp_api_auth_first_name_field' => '',
        'wp_api_auth_last_name_field' => '',
        'wp_api_auth_user_url_field' => '',
        'wp_api_auth_user_email_field' => '',
        'wp_api_auth_description_field' => '',
        'wp_api_auth_aim_field' => '',
        'wp_api_auth_yim_field' => '',
        'wp_api_auth_jabber_field' => '',

        'wp_api_auth_login_message' => '',
    );
}

function wp_api_auth_init()
{
    $options = wp_api_auth_get_options();
    foreach ($options as $key => $defaultValue) {
        add_site_option($key, $defaultValue);
    }
}

/**
 * Add link to the settings page to the nework admin menu
 */
function wp_api_auth_add_menu()
{
    add_submenu_page(
        'settings.php', // parent_slug
        'API Authentication Settings', // page_title
        'API Authentication', // menu_title
        'manage_options', // capability
        'wp_api_auth_settings', // menu_slug
        'wp_api_auth_display_options' // function
    );
}

 /**
  * Display the settings page
  */
function wp_api_auth_display_options()
{
    // Kill magic quotes if necessary
    if (get_magic_quotes_gpc()) {
        foreach ($_POST as $key => &$value) {
            $value = stripslashes($value);
        }
    }

    // Save changes
    if (!empty($_POST)) {
        $options = wp_api_auth_get_options();
        foreach ($options as $key => $defaultValue) {
            if (isset($_POST[$key])) {
                update_site_option($key, $_POST[$key]);
            }
        }
    }

    ?>

    <div class="wrap">
        <h2><?php _e('External API Authentication Settings'); ?></h2>
        <form method="post" action="settings.php?page=wp_api_auth_settings">

            <?php settings_fields('wp_api_auth'); ?>

            <h3><?php _e('API Settings'); ?></h3>
            <p><?php _e('Make sure your Wordpress admin account exists on the external API prior to saving these settings or you will be unable to login!'); ?></p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label><?php _e('API URL'); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" name="wp_api_auth_url" value="<?php echo get_site_option('wp_api_auth_url'); ?>" />
                        <p class="description"><?php _e('Required'); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Request Method'); ?></label></th>
                    <td>
                        <fieldset>
                            <label><input type="radio" name="wp_api_auth_method" value="GET" <?php echo (get_site_option('wp_api_auth_method') === 'GET' ? 'checked="checked"' : ''); ?> /> GET</label>
                            <br/>
                            <label><input type="radio" name="wp_api_auth_method" value="POST" <?php echo (get_site_option('wp_api_auth_method') === 'POST' ? 'checked="checked"' : ''); ?> /> POST</label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Username Field'); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" name="wp_api_auth_username_field" value="<?php echo get_site_option('wp_api_auth_username_field'); ?>" />
                        <p class="description"><?php _e('Required'); ?></p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Password Field'); ?></label></th>
                    <td>
                        <input type="text" class="regular-text" name="wp_api_auth_password_field" value="<?php echo get_site_option('wp_api_auth_password_field'); ?>" />
                        <p class="description"><?php _e('Required'); ?></p>
                    </td>
                </tr>

            </table>

            <h3><?php _e('Response Fields'); ?></h3>
            <p><?php _e('Enter these optional field names returned in the response that will be mapped to the user\'s Wordpress account.'); ?></p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label><?php _e('First name'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_first_name_field" value="<?php echo get_site_option('wp_api_auth_first_name_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Last name'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_last_name_field" value="<?php echo get_site_option('wp_api_auth_last_name_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Homepage'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_user_url_field" value="<?php echo get_site_option('wp_api_auth_user_url_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Email'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_user_email_field" value="<?php echo get_site_option('wp_api_auth_user_email_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Bio/Description'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_description_field" value="<?php echo get_site_option('wp_api_auth_description_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('AIM screen name'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_aim_field" value="<?php echo get_site_option('wp_api_auth_aim_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('YIM screen name'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_yim_field" value="<?php echo get_site_option('wp_api_auth_yim_field'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('JABBER screen name'); ?></label></th>
                    <td><input type="text" class="regular-text" name="wp_api_auth_jabber_field" value="<?php echo get_site_option('wp_api_auth_jabber_field'); ?>" /></td>
                </tr>
            </table>

            <h3><?php _e('Other'); ?></h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Custom Login Form Message'); ?></th>
                    <td>
                        <textarea class="large-text" rows="5" name="wp_api_auth_login_message"><?php echo htmlspecialchars(get_site_option('wp_api_auth_login_message'));?></textarea>
                        <p class="description"><?php _e('Shows on the login form. e.g. To tell users where to create an account. You can use HTML in this text.'); ?></p>
                    </tr>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="Submit" value="Save changes" />
            </p>

        </form>
    </div>
    <?php
}

// actual meat of plugin - essentially, you're setting $username and $password to pass on to the system.
// You check from your external system and insert/update users into the WP system just before WP actually
// authenticates with its own database.
function wp_api_auth_check_login($username, $password)
{
    require_once './wp-includes/user.php';
    require_once './wp-includes/pluggable.php';

    $response = wp_api_auth_get_response($username, $password);

    if ($response->success) {

        $externalUser = $response->user;

    } else {

        if ($response->error) {
            global $wp_api_auth_error;
            $wp_api_auth_error = $response->error;
            return false;
        }

    }

    // Set the mapping of fields from the external db to the wordpress db
    $fieldMappings = array(
        'first_name' => get_site_option('wp_api_auth_first_name_field'),
        'last_name' => get_site_option('wp_api_auth_last_name_field'),
        'user_url' => get_site_option('wp_api_auth_user_url_field'),
        'user_email' => get_site_option('wp_api_auth_user_email_field'),
        'description' => get_site_option('wp_api_auth_description_field'),
        'aim' => get_site_option('wp_api_auth_aim_field'),
        'yim' => get_site_option('wp_api_auth_yim_field'),
        'jabber' => get_site_option('wp_api_auth_jabber_field'),
    );

    // Insert or update the user in wordpress
    $wordpressUser = array(
        'user_login' => $username,
        'user_pass' => $password,
        'first_name' => !empty($fieldMappings['first_name']) ? $externalUser->{$fieldMappings['first_name']} : '',
        'last_name' => !empty($fieldMappings['last_name']) ? $externalUser->{$fieldMappings['last_name']} : '',
        'user_url' => !empty($fieldMappings['user_url']) ? $externalUser->{$fieldMappings['user_url']} : '',
        'user_email' => !empty($fieldMappings['user_email']) ? $externalUser->{$fieldMappings['user_email']} : '',
        'description' => !empty($fieldMappings['user_email']) ? $externalUser->{$fieldMappings['description']} : '',
        'aim' => !empty($fieldMappings['aim']) ? $externalUser->{$fieldMappings['aim']} : '',
        'yim' => !empty($fieldMappings['yim']) ? $externalUser->{$fieldMappings['yim']} : '',
        'jabber' => !empty($fieldMappings['jabber']) ? $externalUser->{$fieldMappings['jabber']} : '',
    );

    if (!empty($fieldMappings['first_name']) || !empty($fieldMappings['last_name'])) {
        $wordpressUser['display_name'] = $externalUser->{$fieldMappings['first_name']} . ' ' . $externalUser->{$fieldMappings['last_name']};
    }

    if (empty($wordpressUser['display_name'])) {
        $wordpressUser['display_name'] = $username;
    }

    if ($id = username_exists($username)) {
        // If user is already in wordpress, update
        $wordpressUser['ID'] = $id;
        wp_update_user($wordpressUser);
    } else {
        // Otherwise create a new user
        wp_insert_user($wordpressUser);
    }
}

function wp_api_auth_get_response($username, $password)
{
    $url = get_site_option('wp_api_auth_url');
    $method = get_site_option('wp_api_auth_method');
    $post = $method === 'POST'; // otherwise GET

    $params = array();
    $params[get_site_option('wp_api_auth_username_field')] = $username;
    $params[get_site_option('wp_api_auth_password_field')] = $password;

    $params = http_build_query($params);

    if ($additionalParams = get_site_option('wp_api_auth_additional_params')) {
        $params .= $additionalParam;
    }

    $ch = curl_init();

    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    } else {
        $url .= '?' . $params;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $jsonResponse = @json_decode($response);

    $return = (object)array(
        'success' => false,
        'error' => null,
        'user' => null
    );

    if ($httpcode === 200) {
        $return->success = true;
        if (!empty($jsonResponse)) {
            if (isset($jsonResponse->user)) {
                $return->user = $jsonResponse->user;
            }
        }
    } else {
        if (!empty($jsonResponse)) {
            if (isset($jsonResponse->error)) {
                $return->error = $jsonResponse->error;
            }
        }
    }

    return $return;
}

/**
 * Displays informational message on login form
 */
function wp_api_auth_show_login_message()
{
    echo '<p class="message">' . get_site_option('wp_api_auth_login_message') . '</p>';
}

/**
 * Display errors on the login page
 */
function wp_api_auth_errors()
{
    global $wp_api_auth_error;
    if ($wp_api_auth_error) {
        return $wp_api_auth_error;
    }
}

/**
 * Disables the (now useless) password reset option in WP when this plugin is enabled.
 */
function wp_api_auth_show_password_fields()
{
    return false;
}

/*
 * Disable functions.  Idea taken from http auth plugin.
 */
function wp_auth_api_disable_function_register()
{
    $errors = new WP_Error();
    $errors->add('registerdisabled', __('User registration is not available from this site, so you can\'t create an account or retrieve your password from here. See the message above.'));
    ?></form><br /><div id="login_error"><?php _e('User registration is not available from this site, so you can\'t create an account or retrieve your password from here. See the message above.'); ?></div>
        <p id="backtoblog"><a href="<?php bloginfo('url'); ?>/" title="<?php _e('Are you lost?') ?>"><?php printf(__('&larr; Back to %s'), get_bloginfo('title', 'display')); ?></a></p>
    <?php
    exit();
}

function wp_auth_api_disable_function()
{
    $errors = new WP_Error();
    $errors->add('registerdisabled', __('User registration is not available from this site, so you can\'t create an account or retrieve your password from here. See the message above.'));
    login_header(__('Log In'), '', $errors);
    ?>
    <p id="backtoblog"><a href="<?php bloginfo('url'); ?>/" title="<?php _e('Are you lost?') ?>"><?php printf(__('&larr; Back to %s'), get_bloginfo('title', 'display')); ?></a></p>
    <?php
    exit();
}


add_action('admin_init', 'wp_api_auth_init');
add_action('network_admin_menu', 'wp_api_auth_add_menu');

// On login
add_action('wp_authenticate', 'wp_api_auth_check_login', 1, 2);
add_filter('login_message', 'wp_api_auth_show_login_message');
add_filter('login_errors', 'wp_api_auth_errors');


// Disable default registration / forgotten password functions
add_action('lost_password', 'wp_auth_api_disable_function');
add_action('retrieve_password', 'wp_auth_api_disable_function');
add_action('password_reset', 'wp_auth_api_disable_function');
add_action('user_register', 'wp_auth_api_disable_function');
add_action('register_form', 'wp_auth_api_disable_function_register');

add_action('profile_personal_options', 'wp_api_auth_show_login_message');
add_filter('show_password_fields', 'wp_api_auth_show_password_fields');


register_activation_hook(__FILE__, 'wp_api_auth_activate');
