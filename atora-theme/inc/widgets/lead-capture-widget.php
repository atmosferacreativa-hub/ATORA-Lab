<?php
/**
 * Lead Capture Widget
 * 
 * Registers a widget for capturing lead information
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) exit;

class Atora_Lead_Capture_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'atora_lead_capture',
            __('Atora Lead Capture', 'atora-learning'),
            [
                'description' => __('Capture user information and create CRM leads', 'atora-learning'),
                'classname' => 'widget-lead-capture',
            ]
        );
    }

    public function widget($args, $instance) {
        echo wp_kses_post($args['before_widget']);
        echo wp_kses_post($args['before_title']);
        echo isset($instance['title']) ? wp_kses_post($instance['title']) : __('Get Started Today', 'atora-learning');
        echo wp_kses_post($args['after_title']);

        $heading = isset($instance['heading']) ? wp_kses_post($instance['heading']) : '';
        $subheading = isset($instance['subheading']) ? wp_kses_post($instance['subheading']) : '';

        if ($heading) echo '<p style="margin-bottom: 1rem; font-weight: 600;">' . $heading . '</p>';
        if ($subheading) echo '<p style="margin-bottom: 1.5rem; color: var(--color-gray-600);">' . $subheading . '</p>';

        ?>
        <form id="atora-lead-form" class="atora-lead-capture-form" style="display: flex; flex-direction: column; gap: 1rem;">
            <input type="hidden" name="action" value="atora_lead_capture_submit">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('atora-theme-nonce'); ?>">
            <div>
                <input type="text" name="first_name" placeholder="<?php esc_attr_e('First Name', 'atora-learning'); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-gray-300); border-radius: var(--radius-md); font-size: 1rem;">
            </div>
            <div>
                <input type="text" name="last_name" placeholder="<?php esc_attr_e('Last Name', 'atora-learning'); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-gray-300); border-radius: var(--radius-md); font-size: 1rem;">
            </div>
            <div>
                <input type="email" name="email" placeholder="<?php esc_attr_e('Email Address', 'atora-learning'); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-gray-300); border-radius: var(--radius-md); font-size: 1rem;">
            </div>
            <div>
                <input type="tel" name="phone" placeholder="<?php esc_attr_e('Phone (Optional)', 'atora-learning'); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-gray-300); border-radius: var(--radius-md); font-size: 1rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; border: none; cursor: pointer;">
                <?php echo isset($instance['button_text']) ? esc_html($instance['button_text']) : esc_html__('Submit', 'atora-learning'); ?>
            </button>
        </form>

        <p id="atora-lead-message" style="display: none; margin-top: 1rem; padding: 0.75rem; border-radius: var(--radius-md);"></p>

        <script>
        document.getElementById('atora-lead-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const messageDiv = document.getElementById('atora-lead-message');

            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'var(--color-success-light)';
                    messageDiv.style.color = 'var(--color-success)';
                    messageDiv.textContent = res.data.message || '<?php esc_attr_e('Thank you! We\'ll be in touch soon.', 'atora-learning'); ?>';
                    form.reset();
                } else {
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'var(--color-error-light)';
                    messageDiv.style.color = 'var(--color-error)';
                    messageDiv.textContent = res.data.message || '<?php esc_attr_e('An error occurred. Please try again.', 'atora-learning'); ?>';
                }
            });
        });
        </script>

        <?php

        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $heading = isset($instance['heading']) ? $instance['heading'] : '';
        $subheading = isset($instance['subheading']) ? $instance['subheading'] : '';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : __('Submit', 'atora-learning');

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget Title:', 'atora-learning'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('heading')); ?>"><?php esc_html_e('Form Heading:', 'atora-learning'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('heading')); ?>" name="<?php echo esc_attr($this->get_field_name('heading')); ?>" type="text" value="<?php echo esc_attr($heading); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('subheading')); ?>"><?php esc_html_e('Form Subheading:', 'atora-learning'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('subheading')); ?>" name="<?php echo esc_attr($this->get_field_name('subheading')); ?>" rows="3"><?php echo esc_attr($subheading); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'atora-learning'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['heading'] = wp_kses_post($new_instance['heading'] ?? '');
        $instance['subheading'] = wp_kses_post($new_instance['subheading'] ?? '');
        $instance['button_text'] = sanitize_text_field($new_instance['button_text'] ?? '');
        return $instance;
    }
}

add_action('widgets_init', function() {
    register_widget('Atora_Lead_Capture_Widget');
});

// AJAX handler for lead form submission
add_action('wp_ajax_atora_lead_capture_submit', 'atora_lead_capture_ajax');
add_action('wp_ajax_nopriv_atora_lead_capture_submit', 'atora_lead_capture_ajax');

function atora_lead_capture_ajax() {
    check_ajax_referer('atora-theme-nonce', 'nonce', false);

    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

    if (!$email) {
        wp_send_json_error(['message' => __('Email is required.', 'atora-learning')]);
    }

    if (function_exists('atora_crm_contact_form_submit')) {
        $result = atora_crm_contact_form_submit([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
        ]);

        wp_send_json_success(['message' => __('Thank you! We\'ll be in touch soon.', 'atora-learning'), 'lead_id' => $result]);
    }

    wp_send_json_error(['message' => __('An error occurred. Please try again.', 'atora-learning')]);
}
