<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\WeglotContext;

?>

<h2><?php esc_html_e( 'Custom URLs', 'weglot' ); ?></h2>
<?php $options = weglot_get_options(); ?>


<div class="wrap">

	<?php foreach ($options['custom_urls'] as $lang => $urls): ?>

	<h3><?php esc_html_e( 'Lang : ', 'weglot' ); ?><?php echo $lang; ?></h3>

	<div style="display:flex;">
			<div style="flex:5; margin-right:10px;">
				<?php esc_html_e( 'Base URL :', 'weglot' ); ?>
			</div>
			<div style="flex:5;">
				<?php esc_html_e( 'Custom URL :', 'weglot' ); ?>
			</div>
			<div style="flex:1;"></div>
		</div>
	<?php
	foreach ( $urls as $key => $value ) {
		$keyGenerate = sprintf('%s-%s-%s', $lang, $key, $value); ?>
		<div style="display:flex;" id="<?php echo $keyGenerate; ?>">
			<div style="margin-right:10px; flex:5;">
				<input style="max-width:100%;" type="text" value="<?php echo $value ?>" class="base-url base-url-<?php echo $keyGenerate; ?>" data-key="<?php echo $keyGenerate; ?>" name="<?php echo sprintf( '%s[%s][%s][%s]', WEGLOT_SLUG, 'custom_urls', $lang, $key ); ?>" data-lang="<?php echo $lang; ?>" />
			</div>
			<div style="flex:5;">
				<input style="max-width:100%;"  type="text" value="<?php echo $key; ?>" data-key="<?php echo $keyGenerate; ?>" class="custom-url custom-<?php echo $keyGenerate; ?>" data-lang="<?php echo $lang; ?>" />
			</div>
			<div style="align-self:flex-end; flex:1; text-align: center; height: 32px;">
				<button class="js-btn-remove" data-key="<?php echo $keyGenerate; ?>">
					<span class="dashicons dashicons-minus"></span>
				</button>
			</div>
		</div>
		<?php
	} ?>

	<script>
		document.addEventListener('DOMContentLoaded', function(){
			const $ = jQuery

			$('.custom-url').on('keyup', function(e){
				const key = $(this).data('key')
				const lang = $(this).data('lang')
				$('.base-url-' + key).attr('name', 'weglot-translate[custom_urls][' + lang + '][' + e.target.value + ']')
			})

			$('.js-btn-remove').on('click', function(e){
				e.preventDefault();

				$('#' + $(this).data('key')).remove()
			})
		})
	</script>

	<?php endforeach; ?>
</div>
