<div class="wrap blyen-settings-page">
    <h1>Blyen Profile Settings</h1>
    <p>Unlock the Power of Data with Blyen! Elevate your customer relationships and satisfaction, all in one place.</p>

    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="blyen_project_key">Project Key:</label></th>
                <td>
                    <input type="text" id="blyen_project_key" name="blyen_project_key" value="<?php echo esc_attr($projectKey); ?>" class="regular-text" />
                    <p class="description">Enter your Blyen project key here.</p>
                </td>
            </tr>
        </table>
        <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
    </form>

    <?php if ($projectName): ?>
        <div class="blyen-project-name">
            <p><strong>Project Name:</strong> <?php echo esc_html($projectName); ?></p>
        </div>
    <?php endif; ?>

    <hr>

    <p>For more information about the Blyen, visit <a href="https://blyen.com/dashboard" target="_blank">https://blyen.com/dashboard</a>.</p>
</div>
