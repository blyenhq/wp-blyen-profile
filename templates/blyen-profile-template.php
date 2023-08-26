<?php
    wp_enqueue_script('blyen-sdk', 'https://blyen.com/sdk.min.js', array(), '0.1', true);
?>

<blyen-profile projectKey="<?php echo esc_attr($atts['blyen_project_key']); ?>" email="<?php echo esc_attr($user->user_email); ?>"></blyen-profile>