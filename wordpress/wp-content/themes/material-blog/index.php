<?php
/**
 * Main Template – Post listing
 *
 * @package Material_Blog
 */

get_header();
?>

<main class="md-page" role="main">
	<?php if ( is_home() && ! is_paged() && get_option( 'page_for_posts' ) == 0 ) : ?>
		<!-- Archive header for blog home -->
	<?php elseif ( is_category() ) : ?>
		<div class="md-archive-header">
			<span class="md-archive-header__label">Chuyên mục</span>
			<h1 class="md-archive-header__title"><?php single_cat_title(); ?></h1>
		</div>
	<?php elseif ( is_tag() ) : ?>
		<div class="md-archive-header">
			<span class="md-archive-header__label">Tag</span>
			<h1 class="md-archive-header__title"><?php single_tag_title(); ?></h1>
		</div>
	<?php elseif ( is_author() ) : ?>
		<div class="md-archive-header">
			<span class="md-archive-header__label">Tác giả</span>
			<h1 class="md-archive-header__title"><?php the_author(); ?></h1>
		</div>
	<?php elseif ( is_search() ) : ?>
		<div class="md-archive-header">
			<span class="md-archive-header__label">Kết quả tìm kiếm</span>
			<h1 class="md-archive-header__title">&ldquo;<?php echo get_search_query(); ?>&rdquo;</h1>
		</div>
	<?php elseif ( is_archive() ) : ?>
		<div class="md-archive-header">
			<span class="md-archive-header__label">Lưu trữ</span>
			<h1 class="md-archive-header__title"><?php the_archive_title(); ?></h1>
		</div>
	<?php endif; ?>

	<div class="md-layout">
		<div class="md-content">
			<?php if ( have_posts() ) : ?>
				<div class="md-post-list">
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'md-horizontal-card' . ( is_sticky() ? ' md-horizontal-card--sticky' : '' ) ); ?>>
							<!-- Card Media -->
							<div class="md-horizontal-card__media">
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium_large' ); ?>
									</a>
								<?php else : ?>
									<?php
									$site_logo = material_blog_get_site_logo_url();
									if ( $site_logo ) :
									?>
										<a href="<?php the_permalink(); ?>">
											<img src="<?php echo esc_url( $site_logo ); ?>" alt="<?php the_title_attribute(); ?>" class="md-fallback-logo">
										</a>
									<?php else : ?>
										<a href="<?php the_permalink(); ?>" class="md-horizontal-card__media-placeholder">
											<span class="material-symbols-outlined">article</span>
										</a>
									<?php endif; ?>
								<?php endif; ?>
							</div>

							<!-- Card Body -->
							<div class="md-horizontal-card__body">
								<!-- Title -->
								<h2 class="md-horizontal-card__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>

								<!-- Meta: Comments + Date -->
								<div class="md-horizontal-card__meta">
									<span class="material-symbols-outlined">chat_bubble</span>
									<span class="md-horizontal-card__meta-comments">
										<?php
										$comments_num = get_comments_number();
										if ( $comments_num == 0 ) {
											echo '0 Bình luận';
										} elseif ( $comments_num == 1 ) {
											echo '1 Bình luận';
										} else {
											echo $comments_num . ' Bình luận';
										}
										?>
									</span>
									<span class="md-horizontal-card__meta-date">
										<?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' trước'; ?>
									</span>
									<span class="md-horizontal-card__meta-views">
										<span class="material-symbols-outlined">visibility</span>
										<span><?php echo material_blog_get_post_views( get_the_ID() ); ?></span>
									</span>
								</div>

								<!-- Excerpt -->
								<div class="md-horizontal-card__excerpt">
									<?php the_excerpt(); ?>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<?php material_blog_pagination(); ?>

			<?php else : ?>
				<div class="md-no-posts">
					<span class="material-symbols-outlined">search_off</span>
					<h2>Không tìm thấy bài viết</h2>
					<p>Hãy thử tìm kiếm với từ khóa khác.</p>
				</div>
			<?php endif; ?>
		</div> <!-- .md-content -->

		<?php get_sidebar(); ?>

	</div> <!-- .md-layout -->
</main>

<?php get_footer(); ?>
