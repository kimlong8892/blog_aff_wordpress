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

		// --- Autocomplete Live Search Suggestions ---
		var searchInput = document.getElementById('md-search-input');
		var suggestionsContainer = document.getElementById('md-search-suggestions');
		
		if (searchInput && suggestionsContainer) {
			var searchDebounce = null;
			var currentQuery = '';

			// Helper to create skeleton loading html inside dropdown
			var getDropdownSkeletonHTML = function() {
				var items = '';
				for (var i = 0; i < 3; i++) {
					items += '<div class="md-search-suggestions__skeleton md-pulse">' +
						'<div class="md-search-suggestions__skeleton-image"></div>' +
						'<div class="md-search-suggestions__skeleton-info">' +
							'<div class="md-search-suggestions__skeleton-title"></div>' +
							'<div class="md-search-suggestions__skeleton-meta"></div>' +
						'</div>' +
					'</div>';
				}
				return items;
			};

			searchInput.addEventListener('input', function(e) {
				var query = e.target.value.trim();

				if (query === currentQuery) return;
				currentQuery = query;

				// Clear active debounce timer
				if (searchDebounce) {
					clearTimeout(searchDebounce);
				}

				if (query.length === 0) {
					suggestionsContainer.innerHTML = '';
					suggestionsContainer.classList.remove('is-visible');
					return;
				}

				// Show suggestions container and the skeleton loaders
				suggestionsContainer.classList.add('is-visible');
				suggestionsContainer.innerHTML = getDropdownSkeletonHTML();

				// Debounce request by 300ms
				searchDebounce = setTimeout(function() {
					var restBase = '<?php echo esc_url( get_rest_url( null, '/wp/v2/posts' ) ); ?>';
					var url = restBase + (restBase.indexOf('?') !== -1 ? '&' : '?') + 'search=' + encodeURIComponent(query) + '&_embed=wp:featuredmedia,wp:term&per_page=10';
					
					fetch(url)
						.then(function(res) {
							if (!res.ok) throw new Error('API Error');
							return res.json();
						})
						.then(function(posts) {
							// Check if user has cleared query during request
							if (currentQuery !== query) return;

							if (posts.length === 0) {
								suggestionsContainer.innerHTML = '<div class="md-search-suggestions__status">' +
									'<span class="material-symbols-outlined">search_off</span>' +
									'Không tìm thấy bài viết' +
								'</div>';
								return;
							}

							// Render suggestion items
							var html = '';
							posts.forEach(function(post) {
								var dateObj = new Date(post.date);
								var dateStr = dateObj.toLocaleDateString('vi-VN', { day: 'numeric', month: 'short', year: 'numeric' });
								var readingTime = post.reading_time || '1 phút đọc';

								// Thumbnail image or fallback icon
								var imageHTML = '<span class="material-symbols-outlined">article</span>';
								if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
									var media = post._embedded['wp:featuredmedia'][0];
									var thumbUrl = media.media_details && media.media_details.sizes && media.media_details.sizes.thumbnail 
										? media.media_details.sizes.thumbnail.source_url 
										: media.source_url;
									imageHTML = '<img src="' + thumbUrl + '" alt="' + post.title.rendered + '">';
								}

								html += '<a href="' + post.link + '" class="md-search-suggestion-item">' +
									'<div class="md-search-suggestion-item__image">' + imageHTML + '</div>' +
									'<div class="md-search-suggestion-item__info">' +
										'<div class="md-search-suggestion-item__title">' + post.title.rendered + '</div>' +
										'<div class="md-search-suggestion-item__meta">' +
											'<span>' + dateStr + '</span>' +
											'<span>&bull;</span>' +
											'<span>' + readingTime + '</span>' +
										'</div>' +
									'</div>' +
								'</a>';
							});

							suggestionsContainer.innerHTML = html;
						})
						.catch(function(err) {
							console.error(err);
							if (currentQuery !== query) return;
							suggestionsContainer.innerHTML = '<div class="md-search-suggestions__status">' +
								'<span class="material-symbols-outlined">error</span>' +
								'Có lỗi xảy ra' +
							'</div>';
						});
				}, 300);
			});

			// Show suggestions dropdown if user focuses input and there is text
			searchInput.addEventListener('focus', function() {
				if (searchInput.value.trim().length > 0) {
					suggestionsContainer.classList.add('is-visible');
				}
			});

			// Hide dropdown when clicking outside
			document.addEventListener('click', function(e) {
				if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
					suggestionsContainer.classList.remove('is-visible');
				}
			});

			// Hide dropdown when pressing Escape
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') {
					suggestionsContainer.classList.remove('is-visible');
				}
			});

			// Prevent form submission if query is empty
			var searchForm = searchInput.form;
			if (searchForm) {
				searchForm.addEventListener('submit', function(e) {
					if (searchInput.value.trim().length === 0) {
						e.preventDefault();
					}
				});
			}
		}
	})();
	</script>

	<?php wp_footer(); ?>
</body>
</html>
