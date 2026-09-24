<?php
/**
 * Title Settings Page
 * Global configuration for hiding titles
 */

if (!defined('ABSPATH')) exit;

// Save settings
if (isset($_POST['atora_save_title_settings'])) {
    check_admin_referer('atora_title_settings');
    
    $post_types = ['post', 'page'];
    foreach ($post_types as $type) {
        $value = isset($_POST['hide_title_' . $type]) ? '1' : '0';
        update_option('atora_hide_title_' . $type, $value);
    }
    
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved!', 'atora-learning') . '</p></div>';
}

?>

<div class="wrap">
    <h1><?php _e('Title Display Settings', 'atora-learning'); ?></h1>
    
    <div class="atora-admin-content" style="max-width: 800px;">
        
        <div class="card" style="padding: 20px; margin-top: 20px;">
            <h2><?php _e('How Title Hiding Works', 'atora-learning'); ?></h2>
            <p><?php _e('You can hide titles in two ways:', 'atora-learning'); ?></p>
            <ol>
                <li><strong><?php _e('Per Page/Post:', 'atora-learning'); ?></strong> <?php _e('Use the "Title Display" meta box in the editor sidebar', 'atora-learning'); ?></li>
                <li><strong><?php _e('Globally:', 'atora-learning'); ?></strong> <?php _e('Use the settings below to hide titles for all posts/pages by default', 'atora-learning'); ?></li>
            </ol>
            <p class="description">
                <?php _e('Individual page settings always override global settings.', 'atora-learning'); ?>
            </p>
        </div>
        
        <form method="post" action="">
            <?php wp_nonce_field('atora_title_settings'); ?>
            
            <div class="card" style="padding: 20px; margin-top: 20px;">
                <h2><?php _e('Global Settings', 'atora-learning'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Hide Post Titles', 'atora-learning'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="hide_title_post" value="1" <?php checked(get_option('atora_hide_title_post'), '1'); ?>>
                                <?php _e('Hide titles on all blog posts by default', 'atora-learning'); ?>
                            </label>
                            <p class="description">
                                <?php _e('You can still show individual post titles using the meta box', 'atora-learning'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Hide Page Titles', 'atora-learning'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="hide_title_page" value="1" <?php checked(get_option('atora_hide_title_page'), '1'); ?>>
                                <?php _e('Hide titles on all pages by default', 'atora-learning'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Useful for landing pages and custom page builders', 'atora-learning'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Settings', 'atora-learning'), 'primary', 'atora_save_title_settings'); ?>
            </div>
        </form>
        
        <div class="card" style="padding: 20px; margin-top: 20px; background: #f0f9ff; border-left: 4px solid #0ea5e9;">
            <h3 style="margin-top: 0;">💡 <?php _e('Pro Tips', 'atora-learning'); ?></h3>
            <ul style="margin-bottom: 0;">
                <li><?php _e('For landing pages, hide the title and use a custom hero section', 'atora-learning'); ?></li>
                <li><?php _e('Course pages look better without titles when using ATORA-LMS course headers', 'atora-learning'); ?></li>
                <li><?php _e('The title is still present in the HTML (for SEO) but hidden visually', 'atora-learning'); ?></li>
                <li><?php _e('Use the body class "title-hidden" for custom CSS styling', 'atora-learning'); ?></li>
            </ul>
        </div>
        
        <div class="card" style="padding: 20px; margin-top: 20px;">
            <h3><?php _e('CSS Class Available', 'atora-learning'); ?></h3>
            <p><?php _e('When a title is hidden, the body gets the class:', 'atora-learning'); ?> <code>title-hidden</code></p>
            <p><?php _e('You can use this for custom styling:', 'atora-learning'); ?></p>
            <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto;"><code>/* Your Custom CSS */
.title-hidden .entry-content {
    padding-top: 0;
}

.title-hidden .featured-image {
    margin-top: -40px;
}</code></pre>
        </div>
        
    </div>
</div>

<style>
.atora-admin-content .card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.atora-admin-content code {
    background: #f0f0f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 13px;
}

.atora-admin-content pre {
    font-size: 13px;
    line-height: 1.6;
}
</style>
