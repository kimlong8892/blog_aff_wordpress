<?php
/**
 * Standalone script to delete existing mock posts and re-import them with featured images.
 */

// Load WordPress
require_once( __DIR__ . '/wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );
require_once( ABSPATH . 'wp-admin/includes/taxonomy.php' );

// Disable SSL verification for media download
add_filter('https_ssl_verify', '__return_false');
add_filter('https_local_ssl_verify', '__return_false');

if ( php_sapi_name() !== 'cli' && !current_user_can('manage_options') ) {
    die('Unauthorized access.');
}

// 10 Mock posts data
$posts_data = [
    [
        'title' => 'Lịch sử và Tiến trình Phát triển của Material Design',
        'content' => 'Material Design là ngôn ngữ thiết kế được phát triển bởi Google vào năm 2014. Bắt đầu từ phiên bản đầu tiên với các mảng màu phẳng và đổ bóng đổ bóng nhẹ, Material Design đã trải qua nhiều phiên bản nâng cấp lớn. 

Đến nay, Material Design 3 (Material You) đã mang lại khả năng cá nhân hóa giao diện mạnh mẽ dựa trên hình nền của người dùng. Ngôn ngữ thiết kế này nhấn mạnh vào sự linh hoạt, dễ tiếp cận và các hiệu ứng chuyển động mượt mà. Trong bài viết này, chúng ta sẽ cùng điểm lại các cột mốc quan trọng của Material Design.',
        'category' => 'Thiết kế',
        'tags' => ['Material Design', 'UI UX', 'Google'],
        'image_url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => '5 Thói quen tốt của Lập trình viên chuyên nghiệp',
        'content' => 'Trở thành một lập trình viên chuyên nghiệp không chỉ đòi hỏi kiến thức chuyên môn vững vàng mà còn cần những thói quen làm việc khoa học. 

Dưới đây là 5 thói quen giúp bạn nâng cao năng suất và chất lượng code:
1. Viết code sạch (Clean Code) và dễ đọc.
2. Viết tài liệu (Documentation) rõ ràng cho code của mình.
3. Thường xuyên Refactor code cũ.
4. Tự động hóa các tác vụ lặp đi lặp lại.
5. Luôn cập nhật công nghệ mới mỗi ngày.

Hãy áp dụng chúng vào công việc hàng ngày của bạn ngay từ hôm nay!',
        'category' => 'Lập trình',
        'tags' => ['Coding', 'Developer', 'Tips'],
        'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Tại sao bạn nên học Javascript vào năm 2026?',
        'content' => 'Javascript vẫn đang là một trong những ngôn ngữ lập trình phổ biến nhất thế giới và không hề có dấu hiệu hạ nhiệt. Với sự phát triển mạnh mẽ của Node.js, React, Next.js và nhiều framework hiện đại khác, Javascript có thể giúp bạn phát triển cả Front-end lẫn Back-end.

Ngoài ra, hệ sinh thái của Javascript vô cùng phong phú và có cộng đồng hỗ trợ lớn nhất thế giới. Học Javascript mở ra rất nhiều cơ hội việc làm hấp dẫn cho các lập trình viên trẻ.',
        'category' => 'Lập trình',
        'tags' => ['Javascript', 'Web Development', 'NextJS'],
        'image_url' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Tìm hiểu về CSS Grid và Flexbox: Khi nào nên dùng loại nào?',
        'content' => 'CSS Grid và Flexbox là hai công cụ bố cục cực kỳ mạnh mẽ trong CSS hiện đại. Tuy nhiên, nhiều người vẫn nhầm lẫn và không biết khi nào nên chọn công cụ nào.

Về cơ bản:
- Flexbox được thiết kế cho bố cục một chiều (1D) - theo hàng dọc hoặc hàng ngang. Rất thích hợp cho các thanh điều hướng (navbar), căn chỉnh các thẻ bài viết nhỏ.
- CSS Grid được thiết kế cho bố cục hai chiều (2D) - cả hàng và cột đồng thời. Thích hợp để xây dựng cấu trúc layout lớn cho toàn bộ trang web.

Kết hợp cả hai sẽ giúp giao diện của bạn hoạt động linh hoạt và đáp ứng tốt trên mọi thiết bị.',
        'category' => 'Thiết kế',
        'tags' => ['CSS', 'Frontend', 'Web Design'],
        'image_url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Hướng dẫn tối ưu hóa tốc độ tải trang WordPress',
        'content' => 'Tốc độ trang web là một trong những yếu tố quan trọng hàng đầu ảnh hưởng đến trải nghiệm người dùng và điểm số SEO trên Google. Một trang web tải chậm sẽ khiến tỷ lệ thoát trang tăng vọt.

Các bước cơ bản để tối ưu tốc độ WordPress bao gồm:
1. Sử dụng nhà cung cấp hosting chất lượng cao.
2. Tối ưu và nén dung lượng hình ảnh trước khi đăng.
3. Cài đặt các plugin tạo bộ nhớ đệm (Caching).
4. Sử dụng mạng phân phối nội dung (CDN).
5. Giảm thiểu các file CSS và Javascript (Minification).',
        'category' => 'WordPress',
        'tags' => ['WordPress', 'SEO', 'Optimization'],
        'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Xu hướng thiết kế UI/UX nổi bật hiện nay',
        'content' => 'Thế giới thiết kế UI/UX luôn thay đổi không ngừng để mang đến trải nghiệm trực quan tốt hơn cho người dùng. 

Một số xu hướng thiết kế đang dẫn đầu xu thế bao gồm:
- **Glassmorphism**: Hiệu ứng kính mờ nghệ thuật.
- **Dark Mode**: Chế độ tối bảo vệ mắt đã trở thành tiêu chuẩn bắt buộc.
- **Micro-interactions**: Các hiệu ứng chuyển động nhỏ, tinh tế khi người dùng tương tác.
- **Minimalism**: Thiết kế tối giản, loại bỏ các chi tiết thừa thãi để tập trung vào nội dung.',
        'category' => 'Thiết kế',
        'tags' => ['UI UX', 'Design Trend', 'Minimalism'],
        'image_url' => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Cách thiết lập môi trường phát triển với Docker',
        'content' => 'Docker đã cách mạng hóa cách chúng ta xây dựng và triển khai các ứng dụng. Bằng cách đóng gói ứng dụng và toàn bộ môi trường chạy của nó vào trong một Container, Docker đảm bảo ứng dụng sẽ chạy giống hệt nhau trên máy tính của bạn cũng như trên máy chủ production.

Trong bài viết này, chúng ta sẽ hướng dẫn cách viết file `docker-compose.yml` để thiết lập nhanh một môi trường LAMP stack (Linux, Apache, MySQL, PHP) chỉ với một dòng lệnh duy nhất.',
        'category' => 'Lập trình',
        'tags' => ['Docker', 'DevOps', 'Backend'],
        'image_url' => 'https://images.unsplash.com/photo-1605379399642-870262d3d051?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Nghệ thuật chụp ảnh phong cảnh bằng điện thoại',
        'content' => 'Bạn không cần một chiếc máy ảnh DSLR đắt tiền để có được những bức ảnh phong cảnh đẹp mắt. Camera trên các thiết bị di động ngày nay đã đủ thông minh để làm điều đó.

Một vài mẹo nhỏ giúp bức ảnh của bạn trở nên chuyên nghiệp hơn:
- Luôn kích hoạt lưới Grid để căn chỉnh bố cục 1/3.
- Chụp vào giờ vàng (Golden Hour) - ngay sau khi bình minh hoặc trước hoàng hôn.
- Sử dụng tính năng HDR để cân bằng vùng sáng và tối.
- Giữ vững tay hoặc dùng tripod để tránh nhòe ảnh.',
        'category' => 'Đời sống',
        'tags' => ['Photography', 'Mobile', 'Life style'],
        'image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Tại sao làm việc từ xa (Remote Work) là xu hướng tất yếu?',
        'content' => 'Hình thức làm việc từ xa đang thay đổi cách các doanh nghiệp vận hành. Nó mang lại sự tự do và linh hoạt chưa từng có cho nhân viên, đồng thời giúp công ty tiết kiệm chi phí vận hành văn phòng đáng kể.

Tuy nhiên, làm việc từ xa cũng đòi hỏi tính tự giác cực kỳ cao và khả năng quản lý thời gian hiệu quả để tránh bị kiệt sức hoặc mất tập trung. Đây chắc chắn vẫn sẽ là xu hướng làm việc chủ đạo trong tương lai.',
        'category' => 'Đời sống',
        'tags' => ['Remote Work', 'Productivity', 'Career'],
        'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80'
    ],
    [
        'title' => 'Lập trình hướng đối tượng (OOP) cơ bản cho người mới',
        'content' => 'Lập trình hướng đối tượng (OOP) là một mô hình lập trình phổ biến được sử dụng rộng rãi trong các ngôn ngữ như Java, C++, Python, PHP. 

OOP xoay quanh 4 tính chất cốt lõi:
1. **Tính đóng gói (Encapsulation)**: Che giấu thông tin chi tiết bên trong đối tượng.
2. **Tính kế thừa (Inheritance)**: Cho phép một lớp thừa hưởng các thuộc tính và phương thức của lớp khác.
3. **Tính đa hình (Polymorphism)**: Cho phép các đối tượng khác nhau phản hồi cùng một thông điệp theo các cách khác nhau.
4. **Tính trừu tượng (Abstraction)**: Tập trung vào các thuộc tính cốt lõi của đối tượng.',
        'category' => 'Lập trình',
        'tags' => ['OOP', 'Computer Science', 'Programming'],
        'image_url' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=800&q=80'
    ]
];

echo "Bắt đầu dọn dẹp các bài cũ...\n";

foreach ($posts_data as $data) {
    $existing = get_page_by_title($data['title'], OBJECT, 'post');
    if ($existing) {
        wp_delete_post($existing->ID, true);
        echo "Đã xóa bài: '" . $data['title'] . "'\n";
    }
}

echo "\nBắt đầu import bài viết mẫu với hình ảnh...\n";

foreach ($posts_data as $index => $data) {
    $cat_id = get_cat_ID($data['category']);
    if ($cat_id === 0) {
        $cat_id = wp_create_category($data['category']);
    }

    $post_id = wp_insert_post([
        'post_title'    => $data['title'],
        'post_content'  => $data['content'],
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_category' => [$cat_id]
    ]);

    if (is_wp_error($post_id)) {
        echo "Lỗi khi tạo bài viết '" . $data['title'] . "': " . $post_id->get_error_message() . "\n";
        continue;
    }

    wp_set_post_tags($post_id, $data['tags']);

    echo "Đang tải ảnh đại diện cho bài: '" . $data['title'] . "'...\n";
    $image_id = media_sideload_image($data['image_url'], $post_id, $data['title'], 'id');
    
    if (!is_wp_error($image_id)) {
        set_post_thumbnail($post_id, $image_id);
        echo "Thành công: Đã tạo bài viết ID $post_id với ảnh đại diện ID $image_id\n";
    } else {
        echo "Cảnh báo: Không tải được ảnh đại diện. Lỗi: " . $image_id->get_error_message() . "\n";
    }
}

echo "Hoàn thành import bài viết mẫu!\n";
unlink(__FILE__);
?>
