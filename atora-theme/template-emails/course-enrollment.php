<?php
/**
 * Email Template: Course Enrollment Confirmation
 * 
 * Variables available:
 * - $user_name (string)
 * - $course_title (string)
 * - $course_url (string)
 * - $course_instructor (string)
 * - $site_name (string)
 * - $site_url (string)
 * 
 * @package Atora_Learning
 */

$user_name = isset($user_name) ? $user_name : '';
$course_title = isset($course_title) ? $course_title : '';
$course_url = isset($course_url) ? $course_url : '';
$course_instructor = isset($course_instructor) ? $course_instructor : '';
$site_name = isset($site_name) ? $site_name : get_bloginfo('name');
$site_url = isset($site_url) ? $site_url : home_url();
$logo_url = isset($logo_url) ? $logo_url : '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .brand-logo { margin-bottom: 14px; }
        .brand-logo img { max-width: 220px; width: 100%; height: auto; }
        .content { background: #f5f5f5; padding: 30px; border-radius: 0 0 8px 8px; }
        .btn { display: inline-block; background: #6366f1; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; margin: 20px 0; }
        .meta { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #6366f1; border-radius: 4px; }
        .footer { text-align: center; color: #666; font-size: 0.9em; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php if (!empty($logo_url)) : ?>
                <div class="brand-logo">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
                </div>
            <?php endif; ?>
            <h1><?php esc_html_e('Welcome to Your Course!', 'atora-learning'); ?></h1>
        </div>

        <div class="content">
            <p><?php printf(esc_html__('Hi %s,', 'atora-learning'), esc_html($user_name)); ?></p>

            <p><?php printf(esc_html__('Congratulations! You\'ve successfully enrolled in "%s".', 'atora-learning'), esc_html($course_title)); ?></p>

            <div class="meta">
                <strong><?php esc_html_e('Course Details:', 'atora-learning'); ?></strong>
                <p>
                    <strong><?php esc_html_e('Course:', 'atora-learning'); ?></strong> <?php echo esc_html($course_title); ?><br>
                    <?php if ($course_instructor) : ?>
                        <strong><?php esc_html_e('Instructor:', 'atora-learning'); ?></strong> <?php echo esc_html($course_instructor); ?><br>
                    <?php endif; ?>
                </p>
            </div>

            <p><?php esc_html_e('You can now access all the course materials, lessons, and resources. Get started at any time.', 'atora-learning'); ?></p>

            <center>
                <a href="<?php echo esc_url($course_url); ?>" class="btn"><?php esc_html_e('Start Learning', 'atora-learning'); ?></a>
            </center>

            <p><?php esc_html_e('If you have any questions or need support, please don\'t hesitate to reach out.', 'atora-learning'); ?></p>

            <p>
                <?php esc_html_e('Best regards,', 'atora-learning'); ?><br>
                <?php echo esc_html($site_name); ?>
            </p>
        </div>

        <div class="footer">
            <p><?php printf(esc_html__('&copy; %s %s. All rights reserved.', 'atora-learning'), date('Y'), esc_html($site_name)); ?></p>
            <p><?php printf(esc_html__('Visit us at %s', 'atora-learning'), '<a href="' . esc_url($site_url) . '">' . esc_html($site_name) . '</a>'); ?></p>
        </div>
    </div>
</body>
</html>
