<?php
/**
 * Single Post Template
 *
 * @package Material_Blog
 */

get_header();
?>

<main class="md-page" role="main">
	<div class="md-layout">
		<div class="md-content">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'md-article' ); ?>>
					<!-- Article Header -->
					<header class="md-article__header">
						<!-- Categories -->
						<div class="md-article__categories">
							<?php
							$categories = get_the_category();
							if ( $categories ) :
								foreach ( $categories as $cat ) :
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
						<h1 class="md-article__title"><?php the_title(); ?></h1>

						<!-- Meta -->
						<div class="md-article__meta">
							<span class="md-article__meta-item">
								<span class="material-symbols-outlined">person</span>
								<?php the_author(); ?>
							</span>
							<span class="md-article__meta-item">
								<span class="material-symbols-outlined">calendar_today</span>
								<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
							</span>
							<span class="md-article__meta-item">
								<span class="material-symbols-outlined">schedule</span>
								<?php echo material_blog_reading_time(); ?>
							</span>
						</div>
					</header>

					<!-- Featured Image -->
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="md-article__featured-image">
							<?php the_post_thumbnail( 'full' ); ?>
						</div>
					<?php endif; ?>

					<!-- Content -->
					<div class="md-article__content">
						<?php the_content(); ?>
					</div>

					<!-- Tags -->
					<?php
					$tags = get_the_tags();
					if ( $tags ) :
					?>
						<div class="md-article__tags">
							<span class="material-symbols-outlined" style="font-size: 18px; color: var(--md-on-surface-variant);">sell</span>
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="md-chip">
									<?php echo esc_html( $tag->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<!-- Post Navigation -->
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();
					if ( $prev_post || $next_post ) :
					?>
						<nav class="md-post-nav" aria-label="Post navigation">
							<?php if ( $prev_post ) : ?>
								<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="md-post-nav__link">
									<span class="md-post-nav__label">← Bài trước</span>
									<span class="md-post-nav__title"><?php echo esc_html( $prev_post->post_title ); ?></span>
								</a>
							<?php else : ?>
								<div></div>
							<?php endif; ?>

							<?php if ( $next_post ) : ?>
								<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="md-post-nav__link md-post-nav__link--next">
									<span class="md-post-nav__label">Bài tiếp →</span>
									<span class="md-post-nav__title"><?php echo esc_html( $next_post->post_title ); ?></span>
								</a>
							<?php else : ?>
								<div></div>
							<?php endif; ?>
						</nav>
					<?php endif; ?>

				</article>
			<?php endwhile; ?>
		</div> <!-- .md-content -->

		<?php get_sidebar(); ?>

	</div> <!-- .md-layout -->
</main>

<?php get_footer(); ?>
