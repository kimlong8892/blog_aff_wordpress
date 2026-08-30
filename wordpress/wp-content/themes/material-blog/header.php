<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo( 'description' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top App Bar -->
<header class="md-appbar" role="banner">
	<!-- Hamburger (mobile) -->
	<button class="md-icon-btn md-appbar__hamburger" id="md-menu-toggle" aria-label="Menu">
		<span class="material-symbols-outlined">menu</span>
	</button>

	<!-- Brand -->
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="md-appbar__brand">
		<?php if ( has_custom_logo() ) : ?>
			<?php
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$logo_url       = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			?>
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		<?php endif; ?>
		<span class="md-appbar__title"><?php bloginfo( 'name' ); ?></span>
	</a>

	<!-- Desktop Navigation -->
	<nav class="md-appbar__nav" role="navigation" aria-label="Primary Menu">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'depth'          => 2,
			'fallback_cb'    => 'material_blog_fallback_menu',
		) );
		?>
	</nav>

	<!-- Search Bar -->
	<div class="md-appbar__search">
		<span class="material-symbols-outlined md-appbar__search-icon">search</span>
		<input type="search" id="md-search-input" placeholder="Tìm kiếm bài viết..." aria-label="Tìm kiếm" autocomplete="off">
	</div>

	<!-- Actions -->
	<div class="md-appbar__actions">
		<!-- Dark Mode Toggle -->
		<button class="md-icon-btn" id="md-theme-toggle" aria-label="Toggle dark mode">
			<span class="material-symbols-outlined" id="md-theme-icon">dark_mode</span>
		</button>
	</div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="md-mobile-nav" id="md-mobile-nav">
	<div class="md-mobile-nav__scrim" id="md-mobile-scrim"></div>
	<div class="md-mobile-nav__drawer">
		<div class="md-mobile-nav__header">
			<span class="md-appbar__title"><?php bloginfo( 'name' ); ?></span>
		</div>
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'depth'          => 2,
			'fallback_cb'    => 'material_blog_fallback_menu',
		) );
		?>
	</div>
</div>

<?php
/**
 * Fallback menu when no menu is assigned
 */
function material_blog_fallback_menu() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a></li>';
	wp_list_pages( array(
		'title_li' => '',
		'depth'    => 1,
	) );
	echo '</ul>';
}
?>
