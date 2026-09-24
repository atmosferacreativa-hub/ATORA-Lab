<?php
/**
 * Template Name: Student Dashboard
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! is_user_logged_in() ) :
	?>
	<section class="meridian-dashboard-hero">
		<div class="meridian-dashboard-hero__grid">
			<div class="meridian-dashboard-hero__copy" data-reveal>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Student area', 'atora-learning' ); ?></p>
				<h1><?php esc_html_e( 'Your academic dashboard now feels as premium as the storefront.', 'atora-learning' ); ?></h1>
				<p><?php esc_html_e( 'Log in to continue your courses, review progress and access your account in one cleaner workspace.', 'atora-learning' ); ?></p>
				<div class="meridian-hero-actions">
					<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Log in', 'atora-learning' ); ?></a>
					<a class="meridian-button meridian-button--ghost" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Browse courses', 'atora-learning' ); ?></a>
				</div>
			</div>
		</div>
	</section>
	<?php
	get_footer();
	return;
endif;

$user_id          = get_current_user_id();
$user             = get_userdata( $user_id );
$enrolled_courses = atora_get_user_enrolled_courses( $user_id );
$programs_count   = class_exists( 'CLMS_Helper' ) && method_exists( 'CLMS_Helper', 'get_user_enrolled_programs' )
	? count( (array) CLMS_Helper::get_user_enrolled_programs( $user_id ) )
	: 0;
$course_count     = count( $enrolled_courses );
$avg_progress     = 0;
$completed_count  = 0;

if ( $course_count > 0 ) {
	$total_progress = 0;
	foreach ( $enrolled_courses as $course_id ) {
		$progress = atora_get_course_progress( $user_id, $course_id );
		$total_progress += $progress;
		if ( $progress >= 100 ) {
			++$completed_count;
		}
	}
	$avg_progress = (int) round( $total_progress / $course_count );
}
?>

<section class="meridian-dashboard-hero">
	<div class="meridian-dashboard-hero__grid">
		<div class="meridian-dashboard-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Student dashboard', 'atora-learning' ); ?></p>
			<h1><?php echo esc_html( sprintf( __( 'Welcome back, %s.', 'atora-learning' ), $user->display_name ) ); ?></h1>
			<p><?php esc_html_e( 'This version of the dashboard is calmer, cleaner and more aligned with the premium learning experience ATORA should project.', 'atora-learning' ); ?></p>
		</div>

		<div class="meridian-dashboard-panel" data-reveal>
			<h3><?php esc_html_e( 'Snapshot', 'atora-learning' ); ?></h3>
			<div class="meridian-dashboard-grid">
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( number_format_i18n( $course_count ) ); ?></strong>
					<span><?php esc_html_e( 'Courses', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( number_format_i18n( $programs_count ) ); ?></strong>
					<span><?php esc_html_e( 'Programs', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( $avg_progress ); ?>%</strong>
					<span><?php esc_html_e( 'Average progress', 'atora-learning' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="meridian-dashboard-shell">
	<div class="meridian-dashboard-layout">
		<div class="meridian-dashboard-panel" data-reveal>
			<h2><?php esc_html_e( 'Your active courses', 'atora-learning' ); ?></h2>

			<?php if ( ! empty( $enrolled_courses ) ) : ?>
				<div class="meridian-grid meridian-grid--2">
					<?php foreach ( $enrolled_courses as $course_id ) : ?>
						<?php $progress = atora_get_course_progress( $user_id, $course_id ); ?>
						<article class="post-card">
							<div class="post-thumbnail">
								<a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">
									<?php echo get_the_post_thumbnail( $course_id, 'atora-meridian-card' ); ?>
								</a>
							</div>
							<div class="post-card__body">
								<div class="meridian-entry-meta">
									<span><?php echo esc_html( atora_get_primary_label( $course_id ) ); ?></span>
									<span><?php echo esc_html( $progress ); ?>%</span>
								</div>
								<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a></h3>
								<p><?php echo esc_html( atora_get_plain_excerpt( $course_id, 18 ) ); ?></p>
								<div class="meridian-progress" style="margin-bottom:1rem;"><span style="width:<?php echo esc_attr( $progress ); ?>%;"></span></div>
								<div class="entry-footer">
									<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">
										<?php echo esc_html( $progress >= 100 ? __( 'Review', 'atora-learning' ) : __( 'Continue', 'atora-learning' ) ); ?>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="meridian-empty-state">
					<h2><?php esc_html_e( 'No enrolled courses yet', 'atora-learning' ); ?></h2>
					<p><?php esc_html_e( 'Once enrollment starts, your dashboard will highlight progress here.', 'atora-learning' ); ?></p>
					<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Browse courses', 'atora-learning' ); ?></a>
				</div>
			<?php endif; ?>
		</div>

		<aside class="meridian-sidebar">
			<div class="meridian-sidebar-card" data-reveal>
				<h3><?php esc_html_e( 'Account', 'atora-learning' ); ?></h3>
				<ul class="meridian-sidebar-list">
					<li><?php echo esc_html( $user->user_email ); ?></li>
					<li><?php echo esc_html( date_i18n( 'F Y', strtotime( (string) $user->user_registered ) ) ); ?></li>
					<li><?php echo esc_html( sprintf( __( '%d completed courses', 'atora-learning' ), $completed_count ) ); ?></li>
				</ul>
				<div class="meridian-hero-actions">
					<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php esc_html_e( 'Manage account', 'atora-learning' ); ?></a>
					<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log out', 'atora-learning' ); ?></a>
				</div>
			</div>

			<div class="meridian-sidebar-card" data-reveal>
				<h3><?php esc_html_e( 'Useful next steps', 'atora-learning' ); ?></h3>
				<ul class="meridian-sidebar-list">
					<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Explore programs', 'atora-learning' ); ?></a></li>
					<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Visit the store', 'atora-learning' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Support', 'atora-learning' ); ?></a></li>
				</ul>
			</div>
		</aside>
	</div>
</div>

<?php
get_footer();
