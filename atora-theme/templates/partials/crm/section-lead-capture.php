<?php
/**
 * Meridian CRM lead capture.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$crm_lead_form_id        = isset( $crm_lead_form_id ) ? absint( $crm_lead_form_id ) : 0;
$crm_lead_form_shortcode = isset( $crm_lead_form_shortcode ) ? (string) $crm_lead_form_shortcode : '';

if ( ! $crm_lead_form_id || '' === trim( $crm_lead_form_shortcode ) ) {
	return;
}
?>

<section class="meridian-crm-lead" data-clms-crm-lead-context="<?php echo esc_attr( sanitize_key( (string) $crm_lead_context ) ); ?>">
	<div class="meridian-crm-lead__card">
		<div>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Lead capture', 'atora-learning' ); ?></p>
			<?php if ( ! empty( $crm_lead_title ) ) : ?>
				<h2><?php echo esc_html( (string) $crm_lead_title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $crm_lead_copy ) ) : ?>
				<p><?php echo esc_html( (string) $crm_lead_copy ); ?></p>
			<?php endif; ?>
		</div>

		<div class="clms-crm-lead__form">
			<?php echo do_shortcode( $crm_lead_form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
