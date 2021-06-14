

<div class="exhibitions" id="exhibitions">
	<?php
	if ( ! empty( $summary ) ) : ?>
		<div class="exhibitions__summary">
			<p><?php echo wp_kses_post( $summary ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $posts ) ) : ?>
		<div class="exhibitions__results">

			<?php
			$index = 0;
			foreach ( $posts as $item ) {
				$link         = esc_url( get_permalink( $item->ID ) );
				$content      = apply_filters( 'the_content', $item->post_content );
				$image        = get_the_post_thumbnail( $item->ID, 'medium' );
				$e_fields     = get_fields( $item->ID );
				$date_display =  Cc_Exhibitions_Public::show_date_range( $e_fields );

				if ( empty( $image ) ) {
					// $image = sprintf('<img src="%s" alt="No Image" />', get_stylesheet_directory_uri() . '/images/no-image.jpg' );
					$image = sprintf('<img src="%s" alt="No Image" />', 'https://via.placeholder.com/300' );
				}
				?>
				<div class="exhibitions__item">
					<div class="exhibitions__item__image">
						<a href="<?php echo $link; ?>">
							<?php echo $image; ?>
						</a>
					</div>
					<div class="exhibitions__item__details">
						<h3><a href="<?php echo $link; ?>"><?php echo $item->post_title; ?></a></h3>
						<p class="info">
							<?php echo $date_display; ?>
						</p>
						<p>
							<?php echo wp_kses_post( wp_trim_words( $item->post_content, 50 ) ); ?>
						</p>
						<p>
							<a href="<?php echo $link; ?>" class="more-link">SEE MORE</a>
						</p>
					</div>
				</div>
				<?php
			}
			?>
		</div>

		<div class="resources__pagination pagination">
			<?php echo wp_kses_post( $pagination ); ?>
		</div>
	<?php else: ?>

		<div class="resources_no-results not-found">
			No Items Found with your search criteria.
		</div>
	<?php endif; ?>

</div>
