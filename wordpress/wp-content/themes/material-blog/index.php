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

	<div class="md-layout md-layout--full">
		<div class="md-content">
			<?php if ( have_posts() ) : ?>
				<div class="md-post-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'md-card' . ( is_sticky() ? ' md-card--sticky' : '' ) ); ?>>
							<!-- Card Media -->
							<div class="md-card__media">
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'large' ); ?>
									</a>
								<?php else : ?>
									<a href="<?php the_permalink(); ?>" class="md-card__media-placeholder">
										<span class="material-symbols-outlined">article</span>
									</a>
								<?php endif; ?>
							</div>

							<!-- Card Body -->
							<div class="md-card__body">
								<!-- Meta: Categories -->
								<div class="md-card__meta">
									<?php
									$categories = get_the_category();
									if ( $categories ) :
										foreach ( array_slice( $categories, 0, 2 ) as $cat ) :
									?>
										<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="md-chip">
											<?php echo esc_html( $cat->name ); ?>
										</a>
									<?php
										endforeach;
									endif;
									?>
								</div>

								<!-- Title -->
								<h2 class="md-card__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>

								<!-- Excerpt -->
								<div class="md-card__excerpt">
									<?php the_excerpt(); ?>
								</div>

								<!-- Footer: Author + Date + Read More -->
								<div class="md-card__footer">
									<div class="md-card__author-date">
										<span class="material-symbols-outlined">calendar_today</span>
										<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
										<span>&bull;</span>
										<span><?php echo material_blog_reading_time(); ?></span>
									</div>
									<a href="<?php the_permalink(); ?>" class="md-btn md-btn--tonal">
										Đọc tiếp
									</a>
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
		</div>

	</div>
</main>

<?php get_footer(); ?>
