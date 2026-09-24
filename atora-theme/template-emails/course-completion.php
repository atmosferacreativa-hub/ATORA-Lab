<?php
/**
 * Email Template: Course Completion Certificate
 * 
 * Variables available:
 * - $user_name (string)
 * - $course_title (string)
 * - $course_url (string)
 * - $certificate_url (string)
 * - $completion_date (string)
 * - $site_name (string)
 * - $site_url (string)
 * 
 * @package Atora_Learning
 */

$user_name = isset($user_name) ? $user_name : '';
$course_title = isset($course_title) ? $course_title : '';
$course_url = isset($course_url) ? $course_url : '';
$certificate_url = isset($certificate_url) ? $certificate_url : '';
$completion_date = isset($completion_date) ? $completion_date : current_time('F d, Y');
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
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .brand-logo { margin-bottom: 14px; }
        .brand-logo img { max-width: 220px; width: 100%; height: auto; }
        .content { background: #f5f5f5; padding: 30px; border-radius: 0 0 8px 8px; }
        .badge { display: inline-block; background: #fbbf24; color: #78350f; padding: 8px 12px; border-radius: 4px; font-weight: bold; margin: 10px 0; }
        .certificate { background: white; padding: 30px; text-align: center; border: 3px solid #fbbf24; border-radius: 8px; margin: 20px 0; }
        .certificate h2 { color: #10b981; font-size: 2em; margin: 10px 0; }
        .btn { display: inline-block; background: #10b981; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; margin: 20px 0; }
        .meta { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #10b981; border-radius: 4px; }
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
            <h1>🎉 <?php esc_html_e('Congratulations!', 'atora-learning'); ?></h1>
            <p><?php esc_html_e('You\'ve Successfully Completed a Course', 'atora-learning'); ?></p>
        </div>

        <div class="content">
            <p><?php printf(esc_html__('Hi %s,', 'atora-learning'), esc_html($user_name)); ?></p>

            <p><?php printf(esc_html__('We\'re thrilled to inform you that you have successfully completed "%s"!', 'atora-learning'), esc_html($course_title)); ?></p>

            <div class="certificate">
                <h2><?php esc_html_e('Certificate of Completion', 'atora-learning'); ?></h2>
                <p style="font-size: 1.1em; margin: 15px 0;">
                    <?php printf(esc_html__('Completed on %s', 'atora-learning'), esc_html($completion_date)); ?>
                </p>
                <div class="badge">✓ <?php esc_html_e('Verified', 'atora-learning'); ?></div>
            </div>

            <div class="meta">
                <strong><?php esc_html_e('Achievement Details:', 'atora-learning'); ?></strong>
                <p>
                    <strong><?php esc_html_e('Course:', 'atora-learning'); ?></strong> <?php echo esc_html($course_title); ?><br>
                    <strong><?php esc_html_e('Completion Date:', 'atora-learning'); ?></strong> <?php echo esc_html($completion_date); ?><br>
                </p>
            </div>

            <p><?php esc_html_e('Your certificate is now available to download and share. This achievement represents your commitment and hard work.', 'atora-learning'); ?></p>

            <?php if ($certificate_url) : ?>
                <center>
                    <a href="<?php echo esc_url($certificate_url); ?>" class="btn"><?php esc_html_e('Download Certificate', 'atora-learning'); ?></a>
                </center>
            <?php endif; ?>

            <p><?php esc_html_e('Don\'t stop here! Explore more courses and continue your learning journey.', 'atora-learning'); ?></p>

            <center>
                <a href="<?php echo esc_url($course_url); ?>" class="btn" style="background: #6366f1;"><?php esc_html_e('View Course', 'atora-learning'); ?></a>
            </center>

            <p>
                <?php esc_html_e('Congratulations again!', 'atora-learning'); ?><br>
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
