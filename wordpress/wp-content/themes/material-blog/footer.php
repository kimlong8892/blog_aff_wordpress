<?php
// Get ads fields from ACF Options page
$ads = get_field( 'ads', 'option' );
if ( $ads ) :
	$left_ad  = isset( $ads['left'] ) ? $ads['left'] : null;
	$right_ad = isset( $ads['right'] ) ? $ads['right'] : null;
	
	if ( ( $left_ad && ! empty( $left_ad['image'] ) ) || ( $right_ad && ! empty( $right_ad['image'] ) ) ) :
	?>
		<!-- Floating Gutters Ads -->
		<div class="md-gutters-ads">
			<?php if ( $left_ad && ! empty( $left_ad['image'] ) ) : 
				$left_img_url = is_array( $left_ad['image'] ) ? $left_ad['image']['url'] : $left_ad['image'];
				$left_target  = ! empty( $left_ad['url'] ) ? esc_url( $left_ad['url'] ) : '#';
			?>
				<div class="md-gutter-ad md-gutter-ad--left">
					<a href="<?php echo $left_target; ?>" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $left_img_url ); ?>" alt="Quảng cáo bên trái">
					</a>
				</div>
			<?php endif; ?>

			<?php if ( $right_ad && ! empty( $right_ad['image'] ) ) : 
				$right_img_url = is_array( $right_ad['image'] ) ? $right_ad['image']['url'] : $right_ad['image'];
				$right_target  = ! empty( $right_ad['url'] ) ? esc_url( $right_ad['url'] ) : '#';
			?>
				<div class="md-gutter-ad md-gutter-ad--right">
					<a href="<?php echo $right_target; ?>" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $right_img_url ); ?>" alt="Quảng cáo bên phải">
					</a>
				</div>
			<?php endif; ?>
		</div>
	<?php
	endif;
endif;
?>

	<!-- Footer -->
	<footer class="md-footer" role="contentinfo">
		<div class="md-footer__inner">
			<div class="md-footer__grid">
				<!-- About Column -->
				<div class="md-footer__about">
					<h4 class="md-footer__col-title"><?php bloginfo( 'name' ); ?></h4>
					<p><?php bloginfo( 'description' ); ?></p>
				</div>

				<!-- Footer Menu -->
				<div class="md-footer__links">
					<h4 class="md-footer__col-title">Liên kết</h4>
					<nav>
						<?php
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						) );
						?>
					</nav>
				</div>

				<!-- Info Column -->
				<div class="md-footer__info">
					<h4 class="md-footer__col-title">Thông tin</h4>
					<nav>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a></li>
							<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Chính sách bảo mật</a></li>
						</ul>
					</nav>
				</div>
			</div>

			<!-- Bottom bar -->
			<div class="md-footer__bottom">
				<span>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
				<span>Powered by <a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a> &bull; Material Blog Theme</span>
			</div>
		</div>
	</footer>

	<!-- Inline JavaScript: Mobile Menu + Dark Mode -->
	<script>
	(function() {
		'use strict';

		// --- Dark Mode Toggle ---
		var html       = document.documentElement;
		var toggleBtn  = document.getElementById('md-theme-toggle');
		var themeIcon  = document.getElementById('md-theme-icon');
		var storageKey = 'material-blog-theme';

		function setTheme(mode) {
			html.setAttribute('data-theme', mode);
			themeIcon.textContent = mode === 'dark' ? 'light_mode' : 'dark_mode';
			try { localStorage.setItem(storageKey, mode); } catch(e) {}
		}

		// Load saved theme
		var saved = null;
		try { saved = localStorage.getItem(storageKey); } catch(e) {}
		if (saved === 'dark' || saved === 'light') {
			setTheme(saved);
		} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			setTheme('dark');
		}

		if (toggleBtn) {
			toggleBtn.addEventListener('click', function() {
				var current = html.getAttribute('data-theme') || 'light';
				setTheme(current === 'dark' ? 'light' : 'dark');
			});
		}

		// --- Mobile Menu Toggle ---
		var menuToggle = document.getElementById('md-menu-toggle');
		var mobileNav  = document.getElementById('md-mobile-nav');
		var scrim      = document.getElementById('md-mobile-scrim');

		function openMenu() {
			mobileNav.classList.add('is-open');
			document.body.style.overflow = 'hidden';
		}

		function closeMenu() {
			mobileNav.classList.remove('is-open');
			document.body.style.overflow = '';
		}

		if (menuToggle) {
			menuToggle.addEventListener('click', function() {
				if (mobileNav.classList.contains('is-open')) {
					closeMenu();
				} else {
					openMenu();
				}
			});
		}

		if (scrim) {
			scrim.addEventListener('click', closeMenu);
		}

		// Close mobile menu on escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && mobileNav && mobileNav.classList.contains('is-open')) {
				closeMenu();
			}
		});

		// --- Live Search ---
		var searchInput = document.getElementById('md-search-input');
		var mdContent   = document.querySelector('.md-content');
		var pagination  = document.querySelector('.md-pagination');
		var archiveHead = document.querySelector('.md-archive-header');
		
		if (searchInput && mdContent) {
			var originalContent = mdContent.innerHTML;
			var originalPaginationDisplay = pagination ? pagination.style.display : '';
			var originalArchiveHeadDisplay = archiveHead ? archiveHead.style.display : '';
			var searchDebounce = null;
			var currentQuery = '';

			// Helper to create skeleton loading html
			var getSkeletonHTML = function() {
				var cards = '';
				for (var i = 0; i < 4; i++) {
					cards += '<article class="md-card md-card--skeleton md-pulse">' +
						'<div class="md-card__media" style="aspect-ratio: 16/9;"></div>' +
						'<div class="md-card__body">' +
							'<div class="md-card--skeleton-text" style="height: 16px; width: 40%; margin-bottom: 16px;"></div>' +
							'<div class="md-card--skeleton-text" style="height: 24px; width: 85%; margin-bottom: 12px;"></div>' +
							'<div class="md-card--skeleton-text" style="height: 14px; width: 100%; margin-bottom: 8px;"></div>' +
							'<div class="md-card--skeleton-text" style="height: 14px; width: 70%; margin-bottom: 24px;"></div>' +
							'<div class="md-card__footer" style="border: none; padding: 0;">' +
								'<div class="md-card--skeleton-text" style="height: 16px; width: 50%; margin: 0;"></div>' +
								'<div class="md-card--skeleton-text" style="height: 36px; width: 90px; border-radius: 20px; margin: 0;"></div>' +
							'</div>' +
						'</div>' +
					'</article>';
				}
				return '<div class="md-post-grid">' + cards + '</div>';
			};

			searchInput.addEventListener('input', function(e) {
				var query = e.target.value.trim();

				if (query === currentQuery) return;
				currentQuery = query;

				// Update browser URL query parameter '?q='
				var params = new URLSearchParams(window.location.search);
				if (query.length > 0) {
					params.set('q', query);
				} else {
					params.delete('q');
				}
				var newSearch = params.toString();
				var newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '');
				window.history.replaceState({ path: newUrl }, '', newUrl);

				// Clear active debounce timer
				if (searchDebounce) {
					clearTimeout(searchDebounce);
				}

				if (query.length === 0) {
					// Restore original content
					mdContent.innerHTML = originalContent;
					if (pagination) pagination.style.display = originalPaginationDisplay;
					if (archiveHead) archiveHead.style.display = originalArchiveHeadDisplay;
					return;
				}

				// Hide standard pagination and archive title
				if (pagination) pagination.style.display = 'none';
				if (archiveHead) archiveHead.style.display = 'none';

				// Show skeleton loading indicator
				mdContent.innerHTML = getSkeletonHTML();

				// Debounce request by 300ms
				searchDebounce = setTimeout(function() {
					var restBase = '<?php echo esc_url( get_rest_url( null, '/wp/v2/posts' ) ); ?>';
					var url = restBase + (restBase.indexOf('?') !== -1 ? '&' : '?') + 'search=' + encodeURIComponent(query) + '&_embed=wp:featuredmedia,wp:term';
					
					fetch(url)
						.then(function(res) {
							if (!res.ok) throw new Error('API Error');
							return res.json();
						})
						.then(function(posts) {
							// Check if user has cleared query during request
							if (currentQuery.length === 0) return;

							if (posts.length === 0) {
								mdContent.innerHTML = '<div class="md-no-posts">' +
									'<span class="material-symbols-outlined">search_off</span>' +
									'<h2>Không tìm thấy bài viết</h2>' +
									'<p>Hãy thử tìm kiếm với từ khóa khác.</p>' +
								'</div>';
								return;
							}

							// Render post cards
							var html = '<div class="md-post-grid">';
							posts.forEach(function(post) {
								var dateObj = new Date(post.date);
								var dateStr = dateObj.toLocaleDateString('vi-VN', { day: 'numeric', month: 'short', year: 'numeric' });
								var readingTime = post.reading_time || '1 phút đọc';

								// Media image
								var mediaHTML = '';
								if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
									var media = post._embedded['wp:featuredmedia'][0];
									mediaHTML = '<a href="' + post.link + '"><img src="' + media.source_url + '" alt="' + post.title.rendered + '"></a>';
								} else {
									mediaHTML = '<a href="' + post.link + '" class="md-card__media-placeholder"><span class="material-symbols-outlined">article</span></a>';
								}

								// Categories
								var catsHTML = '';
								if (post._embedded && post._embedded['wp:term'] && post._embedded['wp:term'][0]) {
									var terms = post._embedded['wp:term'][0];
									terms.slice(0, 2).forEach(function(term) {
										catsHTML += '<a href="' + term.link + '" class="md-chip">' + term.name + '</a> ';
									});
								}

								html += '<article class="md-card">' +
									'<div class="md-card__media">' + mediaHTML + '</div>' +
									'<div class="md-card__body">' +
										'<div class="md-card__meta">' + catsHTML + '</div>' +
										'<h2 class="md-card__title"><a href="' + post.link + '">' + post.title.rendered + '</a></h2>' +
										'<div class="md-card__excerpt">' + post.excerpt.rendered + '</div>' +
										'<div class="md-card__footer">' +
											'<div class="md-card__author-date">' +
												'<span class="material-symbols-outlined">calendar_today</span>' +
												'<time>' + dateStr + '</time>' +
												'<span>&bull;</span>' +
												'<span>' + readingTime + '</span>' +
											'</div>' +
											'<a href="' + post.link + '" class="md-btn md-btn--tonal">Đọc tiếp</a>' +
										'</div>' +
									'</div>' +
								'</article>';
							});
							html += '</div>';

							mdContent.innerHTML = html;
						})
						.catch(function(err) {
							console.error(err);
							mdContent.innerHTML = '<div class="md-no-posts">' +
								'<span class="material-symbols-outlined">error</span>' +
								'<h2>Có lỗi xảy ra</h2>' +
								'<p>Không thể kết nối đến máy chủ. Vui lòng thử lại sau.</p>' +
							'</div>';
						});
				}, 300);
			});

			// Check if URL already contains ?q= on load
			var urlParams = new URLSearchParams(window.location.search);
			var initialQuery = urlParams.get('q');
			if (initialQuery) {
				searchInput.value = initialQuery;
				var event = new Event('input', { bubbles: true });
				searchInput.dispatchEvent(event);
			}
		}
	})();
	</script>

	<?php wp_footer(); ?>
</body>
</html>
