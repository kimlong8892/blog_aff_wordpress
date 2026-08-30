<aside class="md-sidebar" role="complementary">
	<?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'blog-sidebar' ); ?>
	<?php else : ?>
		<!-- Fallback widgets khi chưa có widget nào được thêm -->

		<!-- Search -->
		<div class="widget widget_search">
			<h3 class="widget-title">Tìm kiếm</h3>
			<?php get_search_form(); ?>
		</div>

		<!-- Recent Posts -->
		<div class="widget widget_recent_entries">
			<h3 class="widget-title">Bài viết mới</h3>
			<ul>
				<?php
				$recent_posts = wp_get_recent_posts( array(
					'numberposts' => 5,
					'post_status' => 'publish',
				) );
				foreach ( $recent_posts as $post ) :
				?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
							<?php echo esc_html( $post['post_title'] ); ?>
						</a>
					</li>
				<?php endforeach; wp_reset_postdata(); ?>
			</ul>
		</div>

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
	<?php endif; ?>
</aside>
