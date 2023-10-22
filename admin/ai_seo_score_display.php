<?php

function ai_seo_display_score_details() {
    global $seo_title, $seo_description, $seo_keyword; // объявляем глобальные переменные
    global $seo_score_details;

    $post_id = get_the_ID();

    if (!isset($seo_score_details) || empty($seo_score_details)) {
        ai_seo_calculate_optimization_score($post_id, $seo_title, $seo_description, $seo_keyword);
    }

    $total_score = array_sum(array_column($seo_score_details, 'score'));

    $max_scores = [
        'Title Tag' => 18,
        'Meta Description' => 11,
        'URL' => 9,
        'Headings (H1-H6)' => 18,
        'Content' => 18,
        'Alt Text' => 9,
        'Links' => 9,
        'Additional Parameters' => 8
    ];

    $green_percentage = ($total_score / 100) * 97.5;
    $mid_percentage = $green_percentage - 2.5;

    echo '<div class="ai_seo_score_details_wrapper">';
    echo '<h2 style="font-size: 2em;">' . __("AI SEO Content Analyzer Plugin Score", "ai-seo-content-analyzer") . '</h2>';

    echo '<div class="total_score_container">';
    echo '<div class="total_score_bar" style="background: linear-gradient(90deg, #B0FFB0 0%, #B0FFB0 ' . $mid_percentage . '%, #FFA6A6 ' . $green_percentage . '%, #FFA6A6 100%);">';
    echo '<div class="current_score">' . __("Overall Score", "ai-seo-content-analyzer") . ': ' . $total_score . '/100</div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="ai_seo_score_details_accordion">';
    foreach ($max_scores as $section => $max_section_score) {
        $current_section_score = $seo_score_details[$section]['score'] ?? 0;
        $green_percentage_section = ($current_section_score / $max_section_score) * 97.5;
        $mid_percentage_section = $green_percentage_section - 2.5;

        echo '<div class="accordion_section">';
        echo '<h3 class="accordion_header" style="background: linear-gradient(90deg, #B0FFB0 0%, #B0FFB0 ' . $mid_percentage_section . '%, #FFA6A6 ' . $green_percentage_section . '%, #FFA6A6 100%);">';
        echo '<span class="header_title">' . __($section, 'ai-seo-content-analyzer') . '</span>';
        echo '<span class="header_score_wrapper">';
        echo '<span class="section_score">' . $current_section_score . '</span>/<span class="max_section_score">' . $max_section_score . '</span>';
        echo '</span>'; 
        echo '<span class="toggle_accordion">[+]</span>';
        echo '</h3>';

        echo '<div class="accordion_content" style="display:none;">';
        foreach ($seo_score_details[$section]['details']['hints'] as $index => $hint) {
            $detail_score = $seo_score_details[$section]['details']['scores'][$index] ?? 0;
            $backgroundColor = $detail_score > 0 ? "#E6FFE6" : "#FFE6E6";  // выбор цвета фона в зависимости от очков

            echo '<div style="border: 1px solid #cccccc; padding: 10px; margin-bottom: 10px; background-color: ' . $backgroundColor . ';">';  // блок для каждого подпункта

            echo '<div style="font-size: 1.3em; display: flex; justify-content: space-between;">';  // увеличиваем размер текста
            echo '<span class="detail_text">' . __($hint, 'ai-seo-content-analyzer') . '</span>';
            echo '<span class="detail_score">' . $detail_score . '</span>';
            echo '</div>';

            if (isset($seo_score_details[$section]['details']['comments'][$index])) {
                echo '<div style="padding-left: 20px; font-size: 1.2em;">' . $seo_score_details[$section]['details']['comments'][$index] . '</div>';  // увеличиваем размер текста комментария
            }

            echo '</div>';  // закрытие блока для каждого подпункта
        }
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';



    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.accordion_header').on('click', function() {
            $(this).next('.accordion_content').slideToggle();
            var toggle = $(this).find('.toggle_accordion');
            toggle.text(toggle.text() === '[+]' ? '[-]' : '[+]');
        });
    });
    </script>
    <?php
}


add_action('edit_form_after_title', 'ai_seo_display_score_details', 11);


function ai_seo_display_seo_score_metabox($post) {
    $post_id = $post->ID;
    $seo_score = ai_seo_calculate_optimization_score($post_id); // Вычисление оценки SEO для записи

    $red_value = 255 - (int)(1.5 * $seo_score);
    $green_value = (int)(1.5 * $seo_score);
    $background_gradient = "linear-gradient(90deg, rgba($red_value, $green_value, 0, 0.3), rgba($red_value, $green_value, 0, 0.5))";

    echo '<div class="ai_seo_log_summary">';
    echo '<p class="ai_seo_log_summary_heading"><strong>' . __('Current SEO score:', 'ai-seo-content-analyzer') . '</strong></p>';
    echo '<div class="seo_score_block" style="background-color: white; background-image: ' . $background_gradient . '; border-radius: 5px; padding: 10px 0; width: 100%; text-align: center;">';
    echo '<span style="font-size: 20px; font-weight: bold;">' . $seo_score . '/100</span>';
    echo '</div>';

    if ($seo_score < 50) {
        echo '<p><a target="_blank" href="https://aiseoautooptimize.pro/download-aiseoautooptimize/" class="ai-seo-optimize-button">' . __('SEO Optimize with artificial intelligence', 'ai-seo-content-analyzer') . '</a></p>';
    }

    
    echo '</div>';
}


function ai_seo_add_seo_score_metabox() {
    add_meta_box(
        'ai_seo_seo_score',
        __('AI SEO Score for Post', 'ai-seo-content-analyzer'),
        'ai_seo_display_seo_score_metabox',
        ['post', 'product'], // Добавляем meta-box для записей и товаров
        'side',
        'high'
    );
}

add_action('add_meta_boxes', 'ai_seo_add_seo_score_metabox');
