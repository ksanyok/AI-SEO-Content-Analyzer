<?php
// Preventing direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Mass Optimization page content
function ai_seo_content_analyzer_mass_optimization() {
    $paged = (isset($_GET['paged'])) ? intval($_GET['paged']) : 1;
    $posts_per_page = 20;

    $args = [
        'post_type' => ['post', 'product'],
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    ];

    $query = new WP_Query($args);
    $all_posts_products = $query->posts;
    $total_pages = $query->max_num_pages;

    // Получение сохраненных результатов анализа
    $analysis_results = get_transient('ai_seo_analysis_results');
	
    // Дополнительный запрос для получения всех записей и товаров
    $args_all = [
        'post_type' => ['post', 'product'],
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ];

    $query_all = new WP_Query($args_all);
    $all_posts_products_all = $query_all->posts;
    $total_posts = $query_all->found_posts;

    $total_score = 0;

    // Для всех товаров и записей
    $poor_score_count = 0;
    $normal_score_count = 0;
    $good_score_count = 0;
    $excellent_score_count = 0;

    foreach ($all_posts_products_all as $post_product) {
        $post_id = $post_product->ID;
        $score = get_post_meta($post_id, 'ai_seo_score', true) ?? 0; // Извлекаем оценку из мета-данных
        $score = (int) (get_post_meta($post_id, 'ai_seo_score', true) ?? 0);
		$total_score += $score;


        if ($score <= 40) {
            $poor_score_count++;
        } elseif ($score <= 65) {
            $normal_score_count++;
        } elseif ($score <= 80) {
            $good_score_count++;
        } else {
            $excellent_score_count++;
        }
    }

    $average_score = $total_posts ? round($total_score / $total_posts, 1) : 0;

    $total_products = wp_count_posts('product')->publish;

    // Далее идет основная часть HTML кода без изменений

    ?>
	<div class="wrap">
		<h1><?php _e('AI SEO Content Analyzer', 'ai-seo-content-analyzer'); ?></h1>
		<p><?php _e('Use this page to perform mass analyzer of your content. Select the posts or products you wish to analyze, and the tool will provide detailed insights and suggestions.', 'ai-seo-content-analyzer'); ?></p>
		<div class="ai-seo-analysis-summary" style="border: 1px solid #ddd; padding: 10px; display: flex; align-items: center;">

			<!-- Круговая диаграмма -->
			<div style="width: 33%; border-right: 1px solid #ddd;">
				<div class="chart-container">
					<div class="chart-box">
						<canvas id="scoreChart" width="120" height="120"></canvas>
					</div>
					<div class="legend-container">
						<strong><?php _e('Legend:', 'ai-seo-content-analyzer'); ?></strong>
						<ul>
							<li><span class="legend-color" style="background-color:red;"></span> 0-40: <?php _e('Poor', 'ai-seo-content-analyzer'); ?></li>
							<li><span class="legend-color" style="background-color:yellow;"></span> 41-65: <?php _e('Normal', 'ai-seo-content-analyzer'); ?></li>
							<li><span class="legend-color" style="background-color:green;"></span> 66-80: <?php _e('Good', 'ai-seo-content-analyzer'); ?></li>
							<li><span class="legend-color" style="background-color:blue;"></span> 81-100: <?php _e('Excellent', 'ai-seo-content-analyzer'); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Линейная диаграмма -->
			<div style="width: 33%; border-right: 1px solid #ddd; text-align: center;">
				<div class="chart-container" style="display: inline-block;">
					<div class="chart-box">
						<canvas id="averageScoreChart" width="200" height="200"></canvas> <!-- Увеличиваем ширину и высоту -->
					</div>
				</div>
			</div>

			<div style="width: 33%; text-align: center;">
				<strong style="font-size: 22px; color: #0073aa;"><?php _e('Average Score:', 'ai-seo-content-analyzer'); ?> <span style="font-size: 22px; color: #0073aa;"><?php echo $average_score; ?> / 100</span></strong><br><br>
				<strong style="font-size: 25px;"><?php _e('Total Posts:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 25px;"><?php echo $total_posts; ?></strong><br>
				<strong style="font-size: 22px; color: #d54e21;"><?php _e('Products:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 22px; color: #d54e21;"><?php echo $total_products ? $total_products : '0'; ?></strong><br>
				<strong style="font-size: 22px; color: red;"><?php _e('Poor Score:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 22px; color: red;"><?php echo $poor_score_count; ?></strong><br>
				<strong style="font-size: 22px; color: orange;"><?php _e('Normal Score:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 22px; color: orange;"><?php echo $normal_score_count; ?></strong><br>
				<strong style="font-size: 22px; color: green;"><?php _e('Good Score:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 22px; color: green;"><?php echo $good_score_count; ?></strong><br>
				<strong style="font-size: 22px; color: blue;"><?php _e('Excellent Score:', 'ai-seo-content-analyzer'); ?></strong> <strong style="font-size: 22px; color: blue;"><?php echo $excellent_score_count; ?></strong>
			</div>


			<div style="width: 33%; text-align: center;">
				<?php if ($analysis_results): ?>
					<button id="reanalyze" class="ai-seo-main-button"><?php _e('Reanalyze', 'ai-seo-content-analyzer'); ?></button>
				<?php else: ?>
					<button id="start-analysis" class="ai-seo-main-button"><?php _e('Start Analysis', 'ai-seo-content-analyzer'); ?></button>
				<?php endif; ?>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
		<script>
			var ctx = document.getElementById('scoreChart').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: ['<?php _e('Poor', 'ai-seo-content-analyzer'); ?>', '<?php _e('Normal', 'ai-seo-content-analyzer'); ?>', '<?php _e('Good', 'ai-seo-content-analyzer'); ?>', '<?php _e('Excellent', 'ai-seo-content-analyzer'); ?>'],
					datasets: [{
						data: [<?php echo $poor_score_count; ?>, <?php echo $normal_score_count; ?>, <?php echo $good_score_count; ?>, <?php echo $excellent_score_count; ?>],
						backgroundColor: ['red', 'yellow', 'green', 'blue'],
					}]
				}
			});

			// Линейная диаграмма для среднего балла
			var averageScoreCtx = document.getElementById('averageScoreChart').getContext('2d');
			var averageScoreChart = new Chart(averageScoreCtx, {
				type: 'bar', // Изменяем тип на горизонтальный столбец
				data: {
					labels: ['Average'],
					datasets: [{
						label: 'Score',
						data: [<?php echo $average_score; ?>],
						backgroundColor: 'rgba(75, 192, 192, 0.2)',
						borderColor: 'rgba(75, 192, 192, 1)',
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true,
							max: 100
						}
					}
				}
			});
		</script>
	</div>



    <div id="analysis-animation" style="display:none;"></div>
        <div id="analysis-table" style="display:<?php echo $analysis_results ? 'block' : 'none'; ?>;">
	<table class="ai-seo-product-table">
		<thead>
			<tr>
				<th>ID</th>
				<th><?php _e('Title', 'ai-seo-content-analyzer'); ?></th>
				<th><?php _e('Type', 'ai-seo-content-analyzer'); ?></th>
				<th><?php _e('Score', 'ai-seo-content-analyzer'); ?></th>
				<th><?php _e('Optimization', 'ai-seo-content-analyzer'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($all_posts_products as $post) {
				$post_id = $post->ID;
				$score = $analysis_results[$post_id] ?? 0;
				$red_value = 255 - (int)(1.5 * $score);
				$green_value = (int)(1.5 * $score);
				$background_color = "rgba($red_value, $green_value, 0, 0.3)";
				$post_title = get_the_title($post_id);
				$post_link = get_permalink($post_id);
				$edit_link = get_edit_post_link($post_id);
				$post_type = get_post_type($post_id) == 'product' ? __('Product', 'ai-seo-content-analyzer') : __('Post', 'ai-seo-content-analyzer');
				
				// Изменения здесь
				$seo_optimize_link = "https://aiseoautooptimize.pro/download-aiseoautooptimize/";
				echo "<tr style='background-color: $background_color;'>";
				echo "<td>$post_id</td>";
				echo "<td><a href='{$post_link}'>$post_title</a><br><a href='{$seo_optimize_link}' class='ai-seo-special-link'>" . __('SEO Optimize with artificial intelligence', 'ai-seo-content-analyzer') . "</a></td>";
				echo "<td>$post_type</td>";
				echo "<td style='text-align:center;'>" . (int)$score . "</td>";
				echo "<td><a href='{$edit_link}' class='ai-seo-audit-button'>" . __('SEO Content Analyzer', 'ai-seo-content-analyzer') . "</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>



    </div>
        <?php
        // Display Pagination
        $big = 999999999; // уникальное число для замены
        $pagination_args = [
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $total_pages,
            'prev_text' => __('&laquo; Prev'),
            'next_text' => __('Next &raquo;'),
        ];

        $pagination = paginate_links($pagination_args);

        if ($pagination) {
            echo '<div class="ai-seo-pagination">' . $pagination . '</div>';
        }
        ?>
   
   
   
   
   
    <?php
}

add_action('wp_ajax_ai_seo_start_mass_analysis', 'ai_seo_start_mass_analysis');
function ai_seo_start_mass_analysis() {
    // Получение всех записей и товаров
    $posts = get_posts([
        'numberposts' => -1,
        'post_type' => ['post', 'page', 'product'], // Выборка записей и товаров
        'post_status' => 'publish'
    ]);

    // Массив для сохранения результатов анализа
    $analysis_results = [];

    // Анализ каждого поста
    foreach ($posts as $post) {
        $score = ai_seo_calculate_optimization_score($post->ID); // Используем функцию из другого файла
        $analysis_results[$post->ID] = $score;
        update_post_meta($post->ID, 'ai_seo_score', $score); // Сохраняем оценку как мета-данные
    }

    // Сохранение результатов анализа во временную опцию (хранится 12 часов)
    set_transient('ai_seo_analysis_results', $analysis_results, 12 * HOUR_IN_SECONDS);

    // Возврат результатов в JSON-формате
    echo json_encode($analysis_results);
    wp_die();
}