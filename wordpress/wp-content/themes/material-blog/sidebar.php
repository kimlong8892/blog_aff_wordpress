<aside class="md-sidebar" role="complementary">
	<?php
	// Get pinned posts from ACF Options page
	$pinned_posts = get_field( 'list_post_pin', 'option' );
	if ( $pinned_posts ) :
	?>
		<div class="widget widget_pinned_posts">
			<h3 class="widget-title">Bài viết nổi bật</h3>
			<div class="md-pinned-posts">
				<?php foreach ( $pinned_posts as $post_obj ) : ?>
					<div class="md-pinned-item">
						<div class="md-pinned-item__media">
							<?php if ( has_post_thumbnail( $post_obj->ID ) ) : ?>
								<a href="<?php echo esc_url( get_permalink( $post_obj->ID ) ); ?>">
									<?php echo get_the_post_thumbnail( $post_obj->ID, array( 80, 50 ) ); ?>
								</a>
							<?php else : ?>
								<?php
								$site_logo = material_blog_get_site_logo_url();
								if ( $site_logo ) :
								?>
									<a href="<?php echo esc_url( get_permalink( $post_obj->ID ) ); ?>">
										<img src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo esc_attr( get_the_title( $post_obj->ID ) ); ?>" class="md-fallback-logo">
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( get_permalink( $post_obj->ID ) ); ?>" class="md-pinned-item__media-placeholder">
										<span class="material-symbols-outlined">article</span>
									</a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<div class="md-pinned-item__body">
							<h4 class="md-pinned-item__title">
								<a href="<?php echo esc_url( get_permalink( $post_obj->ID ) ); ?>">
									<?php echo esc_html( get_the_title( $post_obj->ID ) ); ?>
								</a>
							</h4>
							<div class="md-pinned-item__views">
								<span class="material-symbols-outlined">visibility</span>
								<span><?php echo material_blog_get_post_views( $post_obj->ID ); ?></span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<!-- Categories -->
	<div class="widget widget_categories">
		<h3 class="widget-title">Chuyên mục</h3>
		<ul>
			<?php
			wp_list_categories( array(
				'title_li' => '',
				'orderby'  => 'count',
				'order'    => 'DESC',
			) );
			?>
		</ul>
	</div>

	<!-- Tags -->
	<div class="widget widget_tag_cloud">
		<h3 class="widget-title">Tags</h3>
		<div class="tagcloud">
			<?php
			wp_tag_cloud( array(
				'smallest' => 12,
				'largest'  => 12,
				'unit'     => 'px',
				'number'   => 20,
				'orderby'  => 'count',
				'order'    => 'DESC',
			) );
			?>
		</div>
	</div>
</aside>
