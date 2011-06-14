<?php
function vhl_page_admin() { 

    $vhl_config = get_option('vhl_config');

    ?>
    <div class="wrap">
            <form method="post" action="options.php">

                <?php settings_fields('vhl-settings-group'); ?>
                
                <h2><?php _e('BVS Site Options', 'vhl'); ?></h2>
                
                <h3><?php _e('Google Analytics Integration', 'vhl'); ?></h3>
                
                    Google Analytics Code: <input type="text" name="vhl_config[google_analytics_code]" value="<?php echo $vhl_config[google_analytics_code] ?>" class="regular-text code">
                
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            
            </form>
        </div>

        <?php
}
?>
