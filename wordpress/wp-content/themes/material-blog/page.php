<?php
/**
 * Page Template
 *
 * @package Material_Blog
 */

get_header();
?>

<main class="md-page" role="main">
	<div class="md-layout md-layout--full">
		<div class="md-content">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="page-<?php the_ID(); ?>" <?php post_class( 'md-article' ); ?>>
					<!-- Page Header -->
					<header class="md-page-header">
						<h1 class="md-page-header__title"><?php the_title(); ?></h1>
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
				</article>
			<?php endwhile; ?>
		</div>

	</div>
</main>

<?php get_footer(); ?>
