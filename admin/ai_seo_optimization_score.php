<?php

/**
 * SEO Optimization Score Calculator for AI SEO AutoOptimize Pro (Initial Implementation)
 * 
 * This file contains the function that calculates the SEO optimization score
 * for a given product or post based on a set of predefined criteria.
 * 
 * Maximum Score: 100 points
 * 
 * 1- Title Tag (18 points):
 *   - Presence and match of keyword: 6 points
 *   - Optimal length (50-60 characters): 4 points
 *   - Keyword at the beginning: 4 points
 *   - Presence of numbers, special characters, or emojis: 4 points
 * 
 * 2- Meta Description (11 points):
 *   - Presence and match of keyword: 6 points
 *   - Optimal length (150-160 characters): 5 points
 * 
 * 3- URL (9 points):
 *   - Presence of keyword: 5 points
 *   - Clean and short URL: 4 points
 * 
 * 4- Headings (H1-H6) (18 points):
 *   - Only one H1: 5 points
 *   - Keyword presence in H1: 5 points
 *   - Keyword presence in subheadings (H2-H6): 5 points
 *   - Correct nesting of subheadings: 3 points
 * 
 * 5- Alt Text (9 points):
 *   - Presence of alt text for images: 2 points
 *   - Keyword presence in alt text of main image: 3 points
 *   - Keyword presence in alt text of other images: 2 points
 *   - Ratio of images/media to text volume (at least 1 image/media per 1500 characters): 2 points
 * 
 * 6- Links (9 points):
 *   - Presence of external links: 3 points
 *   - Presence of internal links: 2 points
 *   - Presence of nofollow attributes: 4 points
 * 
 * 7- Content (18 points):
 *   - Keyword presence: 5 points
 *   - Optimal content length (minimum 300 words): 4 points
 *   - Presence of media content (images, videos): 4 points
 *   - Check for long sentences: 2 points
 *   - Check for large paragraphs: 3 points
 * 
 * 8- Additional Parameters (8 points):
 *   - Presence of lists: 4 points (2 points for unordered lists and 2 points for ordered lists)
 *   - Use of italic or bold for keyword emphasis: 2 points
 *   - Presence of tables: 2 points
 * 
 * This criteria is based on the latest on-page SEO trends and practices.
 */


/* 
include_once(__DIR__ . '/analysis-parameters/title-tag.php');

function ai_seo_calculate_optimization_score($post_id, $seo_title = null, $seo_description = null, $seo_keyword = null) {
    global $seo_score_details;

    $score = 0;
    $seo_score_details = [];

	$post_type = get_post_type($post_id);
	if ($post_type == 'product' && function_exists('wc_get_product')) {
		$product = wc_get_product($post_id);
		$seo_title_value = $product->get_meta($seo_title);
		$seo_description_value = $product->get_meta($seo_description);
		$seo_keyword_value = $product->get_meta($seo_keyword);
	} else {
		$seo_title_value = get_post_meta($post_id, $seo_title, true);
		$seo_description_value = get_post_meta($post_id, $seo_description, true);
		$seo_keyword_value = get_post_meta($post_id, $seo_keyword, true);
	}
 */
	/*********************************/
	// 1. Title Tag: 18 points
	/*********************************/

/*     // Вызываем функцию аудита тайтла
    $title_audit = ai_seo_title_tag_audit($seo_title_value, $seo_keyword_value);
    $seo_score_details['Title Tag'] = $title_audit['details']; */





function ai_seo_calculate_optimization_score($post_id) {
    global $seo_score_details, $seo_title, $seo_description, $seo_keyword;

    $score = 0;
    $seo_score_details = [];

    $post_type = get_post_type($post_id);
    if ($post_type == 'product' && function_exists('wc_get_product')) {
        $product = wc_get_product($post_id);
        $seo_title_value = $product->get_meta($seo_title);
        $seo_description_value = $product->get_meta($seo_description);
        $seo_keyword_value = $product->get_meta($seo_keyword);
    } else {
        $seo_title_value = get_post_meta($post_id, $seo_title, true);
        $seo_description_value = get_post_meta($post_id, $seo_description, true);
        $seo_keyword_value = get_post_meta($post_id, $seo_keyword, true);
    }

	/*********************************/
	// 1. Title Tag: 18 points
	/*********************************/

	$title_score = 0;
	$title_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []  // добавляем массив комментариев
	];

	$highlighted_title = $seo_title_value;

	// Добавляем проверку на наличие ключевого слова
	if (!empty(trim($seo_keyword_value))) {
		
		// 1.1. Title Tag - Presence and match of keyword: 6 points
		$highlighted_title = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $seo_title_value);
		
		$keyword_comment = sprintf(__("Keyword used: %s", "ai-seo-content-analyzer"), $seo_keyword_value);
		$enhance_ranking_comment = __("According to analytical data, including the targeted keyword in the title enhances search engine ranking. It emphasizes the relevance of the content.", "ai-seo-content-analyzer");
		$missing_keyword_comment = __("Missing keyword in the title can affect search engine visibility. Implementing the keyword can enhance ranking.", "ai-seo-content-analyzer");
		$title_comment = sprintf(__("Title: %s", "ai-seo-content-analyzer"), $highlighted_title);
		
		if (stripos($seo_title_value, $seo_keyword_value) !== false) {
			$score += 6;
			$title_score += 6;
			$title_details['hints'][] = __("Keyword presence in title", "ai-seo-content-analyzer");
			$title_details['scores'][] = 6;
			$title_details['comments'][] = "<strong>{$keyword_comment}</strong><br>{$enhance_ranking_comment}<br>{$title_comment}";
		} else {
			$title_details['hints'][] = __("Keyword absence in title", "ai-seo-content-analyzer");
			$title_details['scores'][] = 0;
			$title_details['comments'][] = "<strong>{$keyword_comment}</strong><br>{$missing_keyword_comment}<br>{$title_comment}";
		}
		
		// 1.2. Title Tag - Optimal length (50-60 characters): 4 points
		$title_length = mb_strlen($seo_title_value);
		if ($title_length >= 50 && $title_length <= 60) {
			$score += 4;
			$title_score += 4;
			$title_details['hints'][] = __("Optimal title length", "ai-seo-content-analyzer");
			$title_details['scores'][] = 4;
			$title_details['comments'][] = __("Title length is optimal (50-60 characters).<br>Current title length: {$title_length}", "ai-seo-content-analyzer");
		} else {
			$title_details['hints'][] = __("Non-optimal title length", "ai-seo-content-analyzer");
			$title_details['scores'][] = 0;
			$title_details['comments'][] = __("Title length is not optimal. Recommended length: 50-60 characters.<br>Current title length: {$title_length}", "ai-seo-content-analyzer");
		}

		// 1.3. Title Tag - Keyword at the beginning: 4 points
		$beginning_of_title = mb_substr($seo_title_value, 0, 40);
		$highlighted_beginning = '<span style="background-color: #b0ffb0;">' . str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $beginning_of_title) . '</span>';

		if (stripos($beginning_of_title, $seo_keyword_value) !== false) {
			$score += 4;
			$title_score += 4;
			$title_details['hints'][] = __("Keyword at the beginning of the title", "ai-seo-content-analyzer");
			$title_details['scores'][] = 4;
			$title_details['comments'][] = __("Keyword used:", "ai-seo-content-analyzer") . " <strong>" . $seo_keyword_value . "</strong><br>" . __("Title:", "ai-seo-content-analyzer") . " " . $highlighted_beginning . mb_substr($seo_title_value, 40);
		} else {
			$title_details['hints'][] = __("Keyword not at the beginning of the title", "ai-seo-content-analyzer");
			$title_details['scores'][] = 0;
			$title_details['comments'][] = __("Keyword used:", "ai-seo-content-analyzer") . " <strong>" . $seo_keyword_value . "</strong><br>" . __("Title:", "ai-seo-content-analyzer") . " " . $highlighted_beginning . mb_substr($seo_title_value, 40);
		}

	} else {
		// Можно добавить сообщение, что ключевое слово не задано
		$title_details['comments'][] = __("Keyword is not set. Please provide a keyword for SEO analysis.", "ai-seo-content-analyzer");
	}

	// 1.4. Title Tag - Presence of numbers, special characters, or emojis: 4 points
	$pattern = "/[0-9]|[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2190}-\x{21AA}\x{25A0}-\x{25FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1400}-\x{167F}]/u";
	$highlighted_title_with_specials = preg_replace($pattern, "<span style='background-color: #b0ffb0;'>$0</span>", $seo_title_value);
	if (preg_match($pattern, $seo_title_value, $matches)) {
		$score += 4;
		$title_score += 4;
		$title_details['hints'][] = __("Presence of numbers, special Unicode characters, or emojis in title", "ai-seo-content-analyzer");
		$title_details['scores'][] = 4;
		$title_details['comments'][] = __("The SEO title includes numbers, special Unicode characters, or emojis. These elements can attract attention and enhance user engagement.<br>Title with special characters highlighted: {$highlighted_title_with_specials}", "ai-seo-content-analyzer");
	} else {
		$title_details['hints'][] = __("Absence of numbers, special Unicode characters, or emojis in title", "ai-seo-content-analyzer");
		$title_details['scores'][] = 0;
		$title_details['comments'][] = __("The SEO title does not contain numbers, special Unicode characters, or emojis. Incorporating these elements may make the title more appealing.<br>Title: {$highlighted_title}", "ai-seo-content-analyzer");
	}

	$seo_score_details['Title Tag'] = [
		'score' => $title_score,
		'details' => $title_details
	];




	/*********************************/
	// 2. Meta Description: 11 points
	/*********************************/

	$meta_description_score = 0;
	$meta_description_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// Добавляем проверку на наличие ключевого слова
	if (!empty(trim($seo_keyword_value))) {
		// 2.1. Meta Description - Presence and match of keyword: 6 points
		if (stripos($seo_description_value, $seo_keyword_value) !== false) {
			$highlighted_description = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $seo_description_value);
			$score += 6;
			$meta_description_score += 6;
			$meta_description_details['hints'][] = __("Keyword presence in meta description", "ai-seo-content-analyzer");
			$meta_description_details['scores'][] = 6;
			$meta_description_details['comments'][] = __("Using the keyword in the meta description can improve SEO ranking.<br>Keyword used:", "ai-seo-content-analyzer") . " <strong>" . $seo_keyword_value . "</strong><br>Meta Description: " . $highlighted_description;
		} else {
			$meta_description_details['hints'][] = __("Keyword absence in meta description", "ai-seo-content-analyzer");
			$meta_description_details['scores'][] = 0;
			$meta_description_details['comments'][] = __("The keyword was not found in the meta description. It's recommended to include the keyword for better SEO performance.<br>Keyword used:", "ai-seo-content-analyzer") . " <strong>" . $seo_keyword_value . "</strong><br>Meta Description: " . $seo_description_value;
		}


	} else {
		// Можно добавить сообщение, что ключевое слово не задано
		$meta_description_details['comments'][] = __("Keyword is not set. Please provide a keyword for SEO analysis.", "ai-seo-content-analyzer");
	}

	// 2.2. Meta Description - Optimal length (150-160 characters): 5 points
	$description_length = mb_strlen($seo_description_value);
	if ($description_length >= 130 && $description_length <= 160) {
		$score += 5;
		$meta_description_score += 5;
		$meta_description_details['hints'][] = __("Optimal meta description length", "ai-seo-content-analyzer");
		$meta_description_details['scores'][] = 5;
		$meta_description_details['comments'][] = sprintf(__("The length of the meta description is optimal (130-160 characters).<br>Current Length: %d", "ai-seo-content-analyzer"), $description_length);
	} else {
		$meta_description_details['hints'][] = __("Non-optimal meta description length", "ai-seo-content-analyzer");
		$meta_description_details['scores'][] = 0;
		$meta_description_details['comments'][] = sprintf(__("The length of the meta description is not optimal. It's recommended to keep the meta description between 130-160 characters.<br>Current Length: %d", "ai-seo-content-analyzer"), $description_length);
	}


	$seo_score_details['Meta Description'] = [
		'score' => $meta_description_score,
		'details' => $meta_description_details
	];


	/*********************************/
	// 3. URL: 9 points
	/*********************************/
	$url_score = 0;
	$url_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	$post_url = get_permalink($post_id);
	$decoded_post_url = urldecode($post_url);
	$url_length = mb_strlen($decoded_post_url);
	$slug = basename($decoded_post_url);
	$transliterated_slug = transliterate($slug);
	$transliterated_keyword = transliterate(str_replace(' ', '-', $seo_keyword_value));
	$keywords_for_search = [$seo_keyword_value, $transliterated_keyword];

	// Добавляем проверку на наличие ключевого слова
	if (!empty(trim($seo_keyword_value))) {
		// 3.1. URL - Presence of keyword: 5 points
		$keyword_in_url = false;
		$found_keyword = "";

		foreach ($keywords_for_search as $keyword_for_search) {
			if (stripos($slug, $keyword_for_search) !== false || stripos($transliterated_slug, $keyword_for_search) !== false) {
				$keyword_in_url = true;
				$found_keyword = $keyword_for_search;
				break;
			}
		}

		if ($keyword_in_url) {
			$score += 5;
			$url_score += 5;
			$url_details['hints'][] = __("Keyword presence in URL", "ai-seo-content-analyzer");
			$url_details['scores'][] = 5;
			/* translators: 1: Keyword used, 2: URL with highlighted keyword */
			$highlighted_url = str_replace($found_keyword, "<strong>{$found_keyword}</strong>", $decoded_post_url);
			$localized_string = __("The keyword is found in the URL, which is good for SEO performance.<br>Keyword used: <strong>{keyword}</strong><br>URL: {url}", "ai-seo-content-analyzer");
			$localized_string = str_replace("{keyword}", $seo_keyword_value, $localized_string);
			$localized_string = str_replace("{url}", $highlighted_url, $localized_string);
			$url_details['comments'][] = $localized_string;


			/* $url_details['comments'][] = sprintf(__("The keyword is found in the URL, which is good for SEO performance.<br>Keyword used: <strong>%1$s</strong><br>URL: %2$s", "ai-seo-content-analyzer"), $seo_keyword_value, $highlighted_url); */
		} else {
			$url_details['hints'][] = __("Keyword absence in URL", "ai-seo-content-analyzer");
			$url_details['scores'][] = 0;
			/* translators: 1: URL */
			$localized_string_320 = __("The keyword is not found in the URL. It's recommended to include the keyword in the URL for better SEO performance.<br>URL: %s", "ai-seo-content-analyzer");
			$url_details['comments'][] = sprintf($localized_string_320, $decoded_post_url);

			/* $url_details['comments'][] = sprintf(__("The keyword is not found in the URL. It's recommended to include the keyword in the URL for better SEO performance.<br>URL: %1$s", "ai-seo-content-analyzer"), $decoded_post_url); */
		}

	} else {
		// Можно добавить сообщение, что ключевое слово не задано
		$url_details['comments'][] = __("Keyword is not set. Please provide a keyword for SEO analysis.", "ai-seo-content-analyzer");
	}

	// 3.2. URL - Clean and short URL: 4 points
	if ($url_length < 80 && !strpos($decoded_post_url, "?") && !strpos($decoded_post_url, "&") && !strpos($decoded_post_url, "_")) {
		$score += 4;
		$url_score += 4;
		$url_details['hints'][] = __("Clean and short URL", "ai-seo-content-analyzer");
		$url_details['scores'][] = 4;
		/* translators: 1: URL length, 2: URL */
		$localized_string_338 = __("The URL is clean and short (length: %1\$s characters). Recommended URL length is less than 80 characters. You can modify the URL structure in <a href='options-permalink.php'>Permalink Settings</a>.<br>Current URL: %2\$s", "ai-seo-content-analyzer");
		$url_details['comments'][] = sprintf($localized_string_338, "<strong>" . $url_length . "</strong>", $decoded_post_url);

		/* $url_details['comments'][] = sprintf(__("The URL is clean and short (length: <strong>%1$d</strong> characters). Recommended URL length is less than 80 characters. You can modify the URL structure in <a href='options-permalink.php'>Permalink Settings</a>.<br>Current URL: %2$s", "ai-seo-content-analyzer"), $url_length, $decoded_post_url); */
	} else {
		$url_details['hints'][] = __("URL not clean or too long", "ai-seo-content-analyzer");
		$url_details['scores'][] = 0;
		/* translators: 1: URL length, 2: URL */
		$localized_string = __("The URL is not clean or is too long (length: <strong>{length}</strong> characters). Consider shortening the URL and avoiding special characters for better SEO. You can modify the URL structure in <a href='options-permalink.php'>Permalink Settings</a>.<br>Current URL: {url}", "ai-seo-content-analyzer");
		$localized_string = str_replace("{length}", $url_length, $localized_string);
		$localized_string = str_replace("{url}", $decoded_post_url, $localized_string);
		$url_details['comments'][] = $localized_string;
		
		/* 		$url_details['comments'][] = sprintf(__("The URL is not clean or is too long (length: <strong>%1$d</strong> characters). Consider shortening the URL and avoiding special characters for better SEO. You can modify the URL structure in <a href='options-permalink.php'>Permalink Settings</a>.<br>Current URL: %2$s", "ai-seo-content-analyzer"), $url_length, $decoded_post_url); */
	}

	$seo_score_details['URL'] = [
		'score' => $url_score,
		'details' => $url_details
	];


	/*********************************/
	// 4. Headings (H1-H6): 18 points
	/*********************************/

	$headings_score = 0;
	$headings_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// Получаем содержимое поста
	$post_content = get_post_field('post_content', $post_id);

	// Получаем заголовок поста
	$post_title = get_the_title($post_id);
	$h1_content = $post_title; // Мы не будем стилизовать его как H1

	// 4.1. Only one H1: 5 points
	$h1_count = preg_match_all('/<h1[^>]*>.*?<\/h1>/i', "<h1>{$h1_content}</h1>" . $post_content, $matches);
	if ($h1_count == 1) {
		$score += 5;
		$headings_score += 5;
		$headings_details['hints'][] = __("Only one H1", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 5;
		$localized_string_381 = __("The content has only one H1 tag: {h1_content}.", "ai-seo-content-analyzer");
		$headings_details['comments'][] = str_replace("{h1_content}", "<strong>" . $h1_content . "</strong>", $localized_string_381);
	} else {
		$headings_details['hints'][] = __("Multiple or no H1 tags", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 0;
		$headings_details['comments'][] = sprintf(__("The content has %d H1 tags, which is not recommended. There should be exactly one H1 tag.", "ai-seo-content-analyzer"), $h1_count);
	}


	// 4.2. Keyword presence in H1
	$keyword_in_h1 = !empty($seo_keyword_value) && stripos(mb_strtolower($post_title), mb_strtolower($seo_keyword_value)) !== false;
	$highlighted_h1 = $keyword_in_h1 ? str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $post_title) : $post_title;
	if ($keyword_in_h1) {
		$score += 5;
		$headings_score += 5;
		$headings_details['hints'][] = __("Keyword presence in H1", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 5;
		$localized_string_413 = __("The keyword is present in the H1 tag: {highlighted_title}. Keyword used: {highlighted_keyword}.", "ai-seo-content-analyzer");
		$highlighted_keyword = "<span style='background-color:#b0e57c;'><strong>{$seo_keyword_value}</strong></span>";
		$highlighted_title = "<span style='background-color:#b0e57c;'>{$highlighted_h1}</span>";
		$headings_details['comments'][] = str_replace(["{highlighted_title}", "{highlighted_keyword}"], [$highlighted_title, $highlighted_keyword], $localized_string_413);
	} else {
		$headings_details['hints'][] = __("Keyword absence in H1", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 0;
		$headings_details['comments'][] = __("The keyword is not present in the H1 tag.", "ai-seo-content-analyzer");
	}

	// 4.3. Keyword presence in subheadings (H2-H6)
	$subheadings_count = preg_match_all('/<h[2-6][^>]*>.*?<\/h[2-6]>/i', $post_content, $subheadings_matches);
	$subheadings_with_keyword = [];
	if (!empty($seo_keyword_value)) {
		foreach ($subheadings_matches[0] as $subheading) {
			if (stripos($subheading, $seo_keyword_value) !== false) {
				$highlighted_subheading = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", strip_tags($subheading));
				$subheadings_with_keyword[] = $highlighted_subheading;
			}
		}
	}

	if (!empty($subheadings_with_keyword)) {
		$score += 5;
		$headings_score += 5;
		$headings_details['hints'][] = __("Keyword presence in subheadings", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 5;
		$subheadings_list = '<ul>' . implode('', array_map(function($subheading) {
			return '<li style="background-color: #b0e57c;"><em>' . $subheading . '</em></li>';
		}, $subheadings_with_keyword)) . '</ul>';
		$localized_string_434 = __("The keyword is found in {count} subheadings. The keyword is present in the following subheadings: {subheadings_list}", "ai-seo-content-analyzer");
		$headings_details['comments'][] = str_replace(["{count}", "{subheadings_list}"], [count($subheadings_with_keyword), $subheadings_list], $localized_string_434);
	} else {
		$headings_details['hints'][] = __("Keyword absence in subheadings", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 0;
		$headings_details['comments'][] = __("The keyword is not present in any of the subheadings.", "ai-seo-content-analyzer");
	}

	// 4.4. Correct nesting of subheadings
	$heading_structure = '';
	$prev_level = 1;
	$correct_nesting = true;
	$current_section_level = 0; // Отслеживаем текущий раздел

	foreach ($subheadings_matches[0] as $subheading) {
		preg_match('/<h([2-6])>/', $subheading, $level_matches);
		if (isset($level_matches[1])) {
			$level = (int)$level_matches[1];
			if ($level == 2) {
				$current_section_level = 2;
			}
			if ($level < $current_section_level) {
				$correct_nesting = false;
			}
			$indent = $level > 2 ? str_repeat('-', $level - 2) : '';
			$heading_structure .= sprintf("<strong>%sH%d:</strong> %s<br>", $indent, $level, strip_tags($subheading));

			if ($level >= $current_section_level) {
				$current_section_level = $level;
			}
		}
	}

	if ($correct_nesting) {
		$score += 3;
		$headings_score += 3;
		$headings_details['hints'][] = __("Correct nesting of subheadings", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 3;
		$headings_details['comments'][] = __("The subheadings are correctly nested. Correctly nested subheadings help in organizing the content and improving readability.", "ai-seo-content-analyzer") . "<br>" . $heading_structure;
	} else {
		$headings_details['hints'][] = __("Incorrect nesting of subheadings", "ai-seo-content-analyzer");
		$headings_details['scores'][] = 0;
		$localized_string_450 = __("The subheadings are not correctly nested. Incorrect nesting may lead to poor content structure and affect readability. Correct structure should follow the pattern: H2 > H3 > H4, etc. Incorrect structure: {heading_structure}", "ai-seo-content-analyzer");
		$headings_details['comments'][] = str_replace("{heading_structure}", $heading_structure, $localized_string_450);
	}

	$seo_score_details['Headings (H1-H6)'] = [
		'score' => $headings_score,
		'details' => $headings_details
	];



	/************************/
	// 5. Alt Text: 9 points
	/************************/

	$alt_text_score = 0;
	$alt_text_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// 5.1. Presence of alt text for images: 2 points
	$all_img_count = preg_match_all('/<img[^>]+>/i', $post_content, $all_img_matches);
	$alt_count = preg_match_all('/<img[^>]+alt="([^"]*)"[^>]*>/i', $post_content, $alt_matches);

	if ($all_img_count == $alt_count) {
		$score += 2;
		$alt_text_score += 2;
		$alt_text_details['hints'][] = __("Presence of alt text for all images", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 2;
		$alt_text_details['comments'][] = sprintf(__("All %d images have alt text. This is good for SEO and accessibility.", "ai-seo-content-analyzer"), $all_img_count);
	} else {
		$missing_alt_count = $all_img_count - $alt_count;
		$alt_text_details['hints'][] = __("Missing alt text for some images", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 0;
		$alt_text_details['comments'][] = sprintf(__("<strong>%d</strong> out of <strong>%d</strong> images are missing alt text. Consider adding descriptive alt text to all images for better SEO and accessibility.", "ai-seo-content-analyzer"), $missing_alt_count, $all_img_count);
	}

	// 5.2. Keyword presence in alt text of main image: 3 points
	$thumbnail_id = get_post_thumbnail_id($post_id);
	$alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

	if (!empty($seo_keyword_value) && stripos($alt_text, $seo_keyword_value) !== false) {
		$highlighted_main_img_alt = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $alt_text);
		$score += 3;
		$alt_text_score += 3;
		$alt_text_details['hints'][] = __("Keyword presence in alt text of main image", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 3;
		$alt_text_details['comments'][] = sprintf(__("The keyword is present in the alt text of the main image: \"<strong>%s</strong>\".", "ai-seo-content-analyzer"), $highlighted_main_img_alt);
	} else {
		$alt_text_details['hints'][] = __("Keyword absence in alt text of main image", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 0;
		$alt_text_details['comments'][] = __("The keyword is not present in the alt text of the main image. Consider including the keyword.", "ai-seo-content-analyzer");
	}

	// 5.3. Keyword presence in alt text of other images: 2 points
	$all_imgs_alt_texts = $alt_matches[1] ?? [];
	$keyword_in_others_list = "";
	if (!empty($seo_keyword_value)) {
		foreach ($all_imgs_alt_texts as $index => $alt_text) {
			if (stripos($alt_text, $seo_keyword_value) !== false) {
				$highlighted_alt_text = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $alt_text);
				$keyword_in_others_list .= "<li style='background-color: #b0e57c; display: block;'>{$highlighted_alt_text}</li>";
			}
		}
	}

	if (!empty($keyword_in_others_list)) {
		$score += 2;
		$alt_text_score += 2;
		$alt_text_details['hints'][] = __("Keyword presence in alt text of other images", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 2;
		$alt_text_details['comments'][] = sprintf(__("The keyword is present in the alt text of other images:<ul>%s</ul>", "ai-seo-content-analyzer"), $keyword_in_others_list);
	} else {
		$alt_text_details['hints'][] = __("Keyword absence in alt text of other images", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 0;
		$alt_text_details['comments'][] = __("The keyword is not present in the alt text of other images. Consider including the keyword.", "ai-seo-content-analyzer");
	}

	// 5.4. Ratio of images/media to text volume (at least 1 image/media per 1500 characters): 2 points
	$post_content_length = mb_strlen(strip_tags($post_content));
	$expected_imgs_count = ceil($post_content_length / 3000);
	if ($all_img_count >= $expected_imgs_count) {
		$score += 2;
		$alt_text_score += 2;
		$alt_text_details['hints'][] = __("Good ratio of images/media to text volume", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 2;
		$alt_text_details['comments'][] = sprintf(__("The content has %d images and %d characters, providing a good ratio of images to text volume.", "ai-seo-content-analyzer"), $all_img_count, $post_content_length);
	} else {
		$alt_text_details['hints'][] = __("Poor ratio of images/media to text volume", "ai-seo-content-analyzer");
		$alt_text_details['scores'][] = 0;
		$alt_text_details['comments'][] = sprintf(__("The content has %d images and %d characters. Consider adding more images to achieve the recommended ratio of at least 1 image per 3000 characters.", "ai-seo-content-analyzer"), $all_img_count, $post_content_length);
	}

	$seo_score_details['Alt Text'] = [
		'score' => $alt_text_score,
		'details' => $alt_text_details
	];



	/*********************/
	// 6. Links: 9 points
	/*********************/

	$links_score = 0;
	$link_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// Получаем все ссылки из контента и их якорный текст
	preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i', $post_content, $link_matches);

	$external_links = 0;
	$internal_links = 0;
	$nofollow_links = 0;

	$site_url = get_site_url();

	$external_links_details = [];
	$internal_links_details = [];

	foreach ($link_matches[1] as $key => $link_url) {
		$anchor_text = '<strong>' . strip_tags($link_matches[2][$key]) . '</strong>';
		if (strpos($link_url, $site_url) === false && !preg_match('/^#/', $link_url)) {
			$external_links++;
			$external_links_details[] = $link_url . ' (' . $anchor_text . ')';
		} else {
			$internal_links++;
			$internal_links_details[] = $link_url . ' (' . $anchor_text . ')';
		}

		if (strpos($link_matches[0][$key], 'rel="nofollow"') !== false) {
			$nofollow_links++;
		}
	}

	// Formatting the external and internal links for output
	$external_links_list = implode('<li style="background-color:#b0e57c; padding:5px; display: block;">', $external_links_details);
	$internal_links_list = implode('<li style="background-color:#b0e57c; padding:5px; display: block;">', $internal_links_details);

	// 6.1. Presence of external links: 3 points    
	if ($external_links > 0) {
		$score += 3;
		$links_score += 3;
		$link_details['hints'][] = __("Presence of external links", "ai-seo-content-analyzer");
		$link_details['scores'][] = 3;
		/* translators: 1: Number of external links, 2: List of external links with their anchor text */
		$link_details['comments'][] = sprintf(__("Found %1\$s external links. External links can add value and credibility to your content. Links:%2\$s", "ai-seo-content-analyzer"), "<strong>" . $external_links . "</strong>", "<br><ul>" . $external_links_list . "</ul>");
		
		/* $link_details['comments'][] = sprintf(__("Found <strong>%1$d</strong> external links. External links can add value and credibility to your content. Links:<br><ul>%2$s</ul>", "ai-seo-content-analyzer"), $external_links, $external_links_list); */
	} else {
		$link_details['hints'][] = __("No external links", "ai-seo-content-analyzer");
		$link_details['scores'][] = 0;
		$link_details['comments'][] = __("No external links found.", "ai-seo-content-analyzer");
	}

	// 6.2. Presence of internal links: 2 points
	if ($internal_links > 0) {
		$score += 2;
		$links_score += 2;
		$link_details['hints'][] = __("Presence of internal links", "ai-seo-content-analyzer");
		$link_details['scores'][] = 2;
		/* translators: 1: Number of internal links, 2: List of internal links with their anchor text */
		$link_details['comments'][] = sprintf(__("Found <strong>%d</strong> internal links. Internal links help in improving navigation and spreading link equity. Links:<br><ul>%s</ul>", "ai-seo-content-analyzer"), $internal_links, $internal_links_list);
	} else {
		$link_details['hints'][] = __("No internal links", "ai-seo-content-analyzer");
		$link_details['scores'][] = 0;
		$link_details['comments'][] = __("No internal links found.", "ai-seo-content-analyzer");
	}


	// 6.3. Presence of nofollow attributes: 4 points
	$nofollow_links_list = '';
	if ($nofollow_links > 0) {
		foreach ($link_matches[0] as $key => $link) {
			if (strpos($link, 'rel="nofollow"') !== false) {
				$anchor_text = strip_tags($link_matches[2][$key]);
				$nofollow_links_list .= '<li style="background-color:#b0e57c; padding:5px; display: block;">' . htmlspecialchars($link) . ' (<strong>' . htmlspecialchars($anchor_text) . '</strong>)</li>';
			}
		}
	}

	if ($nofollow_links == 0) {
		$score += 4;
		$links_score += 4;
		$link_details['hints'][] = __("No links with nofollow attributes", "ai-seo-content-analyzer");
		$link_details['scores'][] = 4;
		$link_details['comments'][] = __("No links with nofollow attribute found. This is positive as it allows search engines to crawl and pass link equity to the linked pages. Consider linking to authoritative and trusted websites without the nofollow attribute, as it aligns with the best SEO practices.", "ai-seo-content-analyzer");
	} else {
		$link_details['hints'][] = __("Presence of nofollow attributes", "ai-seo-content-analyzer");
		$link_details['scores'][] = 0;
		/* translators: 1: Number of links with nofollow attribute, 2: List of nofollow links with their anchor text */
		$link_details['comments'][] = sprintf(__("Found %d links with nofollow attribute. Review the usage and consider allowing followed links to trustworthy and authoritative websites. Here are the nofollow links found:<br><ul>%s</ul>", "ai-seo-content-analyzer"), $nofollow_links, $nofollow_links_list);

	}

	$seo_score_details['Links'] = [
		'score' => $links_score,
		'details' => $link_details
	];



	/***********************/
	// 7. Content: 18 points
	/***********************/

	global $post;

	$content_score = 0;
	$content_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// Если это товар WooCommerce, добавляем краткое описание к основному содержимому
	if (is_a($post, 'WC_Product')) {
		$product = wc_get_product($post->ID);
		$full_description = $post->post_content;
		$short_description = $product->get_short_description();
		$post_content = $full_description . ' ' . $short_description;
	}

	$post_content_without_tags = strip_tags($post_content);

	// Convert content and keyword to lowercase
	$lower_content = mb_strtolower($post_content_without_tags);
	$lower_keyword = mb_strtolower(trim($seo_keyword_value));

	// Count occurrences of the keyword
	$keyword_occurrences = 0;
	if (!empty($lower_keyword)) {
		$keyword_occurrences = mb_substr_count($lower_content, $lower_keyword);
	}

	// Calculate keyword density
	$keyword_density = 0;
	if (mb_strlen($lower_content) > 0 && $keyword_occurrences > 0) {
		$keyword_density = (mb_strlen($lower_keyword) * $keyword_occurrences / mb_strlen($lower_content)) * 100;
	}

	$min_density_low = 0.8; // Minimum density for low score
	$min_density_high = 1.5; // Minimum recommended keyword density for high score
	$max_density_high = 3; // Maximum recommended keyword density for high score

	if ($keyword_density >= $min_density_high && $keyword_density <= $max_density_high) {
		$score += 5;
		$content_score += 5;
		$content_details['hints'][] = __("Keyword presence in content", "ai-seo-content-analyzer");
		$content_details['scores'][] = 5;
		$content_details['comments'][] = sprintf(__("Keyword density is %1$.2f%%. Recommended optimal density is between %2$.2f%% and %3$.2f%%.", "ai-seo-content-analyzer"), $keyword_density, $min_density_high, $max_density_high);
	} elseif ($keyword_density > $min_density_low && $keyword_density < $min_density_high) {
		$score += 2;
		$content_score += 2;
		$content_details['hints'][] = __("Keyword presence in content (suboptimal density)", "ai-seo-content-analyzer");
		$content_details['scores'][] = 2;
		$content_details['comments'][] = sprintf(__("Keyword density is %1$.2f%%. Recommended optimal density is between %2$.2f%% and %3$.2f%%.", "ai-seo-content-analyzer"), $keyword_density, $min_density_high, $max_density_high);
	} else {
		$content_details['hints'][] = __("Keyword presence in content (suboptimal density)", "ai-seo-content-analyzer");
		$content_details['scores'][] = 0;
		$content_details['comments'][] = sprintf(__("Keyword density is %1$.2f%%. Recommended optimal density is between %2$.2f%% and %3$.2f%%. No points awarded as density is out of range.", "ai-seo-content-analyzer"), $keyword_density, $min_density_high, $max_density_high);
	}

	// 7.2. Optimal content length (minimum 300 words for posts, 200 for products): 4 points
	$word_count = str_word_count($post_content_without_tags, 0, "а-яА-ЯёЁ");

	$recommended_length = (is_a($post, 'WC_Product') ? 200 : 300); 
	if ($word_count >= $recommended_length) {
		$score += 4;
		$content_score += 4;
		$content_details['hints'][] = __("Optimal content length", "ai-seo-content-analyzer");
		$content_details['scores'][] = 4;
		$content_details['comments'][] = sprintf("Content has %d words. A minimum of %d words is recommended for optimal SEO.", $word_count, $recommended_length);
	} else {
		$content_details['hints'][] = __("Content length is not optimal", "ai-seo-content-analyzer");
		$content_details['scores'][] = 0;
		$content_details['comments'][] = sprintf("Content is too short with %d words. Consider adding more content for better SEO.", $word_count);
	}


	// 7.3. Presence of media content (images, videos): 4 points
	$media_count = preg_match_all('/(<img|<video)/i', $post_content, $media_matches);
	if ($media_count > 0) {
		$score += 4;
		$content_score += 4;
		$content_details['hints'][] = __("Presence of media content", "ai-seo-content-analyzer");
		$content_details['scores'][] = 4;
		$content_details['comments'][] = sprintf(__("Found %d media items in content. Media items like images and videos can enhance user engagement.", "ai-seo-content-analyzer"), $media_count);
	} else {
		$content_details['hints'][] = __("No media content found", "ai-seo-content-analyzer");
		$content_details['scores'][] = 0;
		$content_details['comments'][] = __("No media items found in content. Consider adding images or videos to enhance user engagement.", "ai-seo-content-analyzer");
	}


	// 7.4. Check for long sentences: 2 points
	$sentences = preg_split('/(?<=[.!?])\s+/', strip_tags($post_content), -1, PREG_SPLIT_NO_EMPTY);
	$long_sentences_details = [];
	$recommended_words_limit = 20;
	foreach ($sentences as $sentence) {
		if (str_word_count($sentence) > $recommended_words_limit && strpos($sentence, ':') === false && strpos($sentence, ';') === false) {
			$long_sentences_details[] = '<span style="font-size: 0.7em;">' . htmlspecialchars($sentence) . '</span> <span style="color: green;">(' . str_word_count($sentence) . ' words, recommended below ' . $recommended_words_limit . ' words)</span>';
		}
	}

	if (empty($long_sentences_details)) {
		$score += 2;
		$content_score += 2;
		$content_details['hints'][] = __("No long sentences", "ai-seo-content-analyzer");
		$content_details['scores'][] = 2;
		$content_details['comments'][] = __("No long sentences found in content.", "ai-seo-content-analyzer");
	} else {
		$content_details['hints'][] = __("Presence of long sentences", "ai-seo-content-analyzer");
		$content_details['scores'][] = 0;
		$formatted_comment = sprintf(__("Found %d long sentences. Consider breaking them down for better readability. Sentences:<br><ul style='background-color: #e0f2e9;'><li>%s</li></ul>", "ai-seo-content-analyzer"), count($long_sentences_details), implode('</li><li>', $long_sentences_details));
		$content_details['comments'][] = $formatted_comment;
	}


	// 7.5. Check for large paragraphs: 3 points
	$paragraphs = explode("\n", strip_tags($post_content));
	$large_paragraphs = array_filter($paragraphs, function ($paragraph) {
		return str_word_count($paragraph) > 120;
	});
	if (count($large_paragraphs) == 0) {
		$score += 3;
		$content_score += 3;
		$content_details['hints'][] = __("No large paragraphs", "ai-seo-content-analyzer");
		$content_details['scores'][] = 3;
		$content_details['comments'][] = __("No large paragraphs found in content.", "ai-seo-content-analyzer");
	} else {
		$content_details['hints'][] = __("Presence of large paragraphs", "ai-seo-content-analyzer");
		$content_details['scores'][] = 0;
		$content_details['comments'][] = sprintf(__("Found %d large paragraphs. Consider breaking them down for better readability. Paragraphs: <br>%s", "ai-seo-content-analyzer"), count($large_paragraphs), implode('<br>', array_map('htmlspecialchars', $large_paragraphs)));
	}


	$seo_score_details['Content'] = [
		'score' => $content_score,
		'details' => $content_details
	];

	/*************************************/
	// 8. Additional Parameters: 8 points
	/*************************************/

	$additional_score = 0;
	$additional_details = [
		'hints' => [],
		'scores' => [],
		'comments' => []
	];

	// 8.1. Presence of lists: 3 points
	$unordered_lists = preg_match_all('/<ul>.*?<\/ul>/is', $post_content, $unordered_matches);
	$ordered_lists = preg_match_all('/<ol>.*?<\/ol>/is', $post_content, $ordered_matches);

	$unordered_lists_details = '';
	if ($unordered_lists > 0) {
		foreach ($unordered_matches[0] as $index => $list) {
			$items = explode('<li>', $list);
			array_shift($items); // remove empty element before the first <li>
			$unordered_lists_details .= __("List", "ai-seo-content-analyzer") . " " . ($index + 1) . ':<br><li style="background-color: lightgreen;">- ' . implode('<br>--- ', array_map('strip_tags', $items)) . '</li>';
		}
	}

	$ordered_lists_details = '';
	if ($ordered_lists > 0) {
		foreach ($ordered_matches[0] as $index => $list) {
			$items = explode('<li>', $list);
			array_shift($items); // remove empty element before the first <li>
			$ordered_lists_details .= __("List", "ai-seo-content-analyzer") . " " . ($index + 1) . ':<br><li style="background-color: lightgreen;">1. ' . implode('<br>2. ', array_map('strip_tags', $items)) . '</li>';
		}
	}

	if ($unordered_lists > 0 || $ordered_lists > 0) {
		$additional_details['hints'][] = __("Presence of lists", "ai-seo-content-analyzer");
		$additional_details['scores'][] = ($unordered_lists > 0 ? 3 : 0) + ($ordered_lists > 0 ? 3 : 0);
		/* translators: 1: Number of unordered lists, 2: Number of ordered lists, 3: Details of unordered lists, 4: Details of ordered lists */
		$additional_details['comments'][] = sprintf(
			__("Found %1\$s unordered lists and %2\$s ordered lists.<br>%3\$s%4\$s", "ai-seo-content-analyzer"),
			"<strong>" . $unordered_lists . "</strong>",
			"<strong>" . $ordered_lists . "</strong>",
			$unordered_lists_details,
			$ordered_lists_details
		);

		/* 		$additional_details['comments'][] = sprintf(
			__("<strong>Found %1$d unordered lists</strong> and <strong>%2$d ordered lists</strong>.<br>%3$s%s", "ai-seo-content-analyzer"),
			$unordered_lists, $ordered_lists, $unordered_lists_details, $ordered_lists_details
		); */
		$score += ($unordered_lists > 0 ? 3 : 0) + ($ordered_lists > 0 ? 3 : 0);
		$additional_score += ($unordered_lists > 0 ? 3 : 0) + ($ordered_lists > 0 ? 3 : 0);
	}

	// 8.2. Use of italic or bold for keyword emphasis: 3 points
	$bold_or_italic_keyword = preg_match_all('/<b>.*' . preg_quote($seo_keyword_value, '/') . '.*<\/b>|<strong>.*' . preg_quote($seo_keyword_value, '/') . '.*<\/strong>|<i>.*' . preg_quote($seo_keyword_value, '/') . '.*<\/i>|<em>.*' . preg_quote($seo_keyword_value, '/') . '.*<\/em>/', $post_content, $emphasis_matches);

	$emphasis_details = $bold_or_italic_keyword > 0 ? '<ul>' . implode('', array_map(function ($match) {
		return '<li style="background-color: lightgreen;">' . strip_tags($match) . '</li>';
	}, $emphasis_matches[0])) . '</ul>' : '';

	$additional_details['hints'][] = __("Use of italic or bold for keyword emphasis", "ai-seo-content-analyzer");
	$additional_details['scores'][] = $bold_or_italic_keyword ? 3 : 0;

	if ($bold_or_italic_keyword) {
		$comment = sprintf(__("Found <strong>%d emphasis</strong> on keyword. Emphasis can enhance readability. Details:%s", "ai-seo-content-analyzer"), $bold_or_italic_keyword, $emphasis_details);
		$additional_details['comments'][] = $comment;
		$score += 3;
		$additional_score += 3;
	} else {
		$additional_details['comments'][] = __("Keyword is not emphasized with bold or italic. Emphasizing important keywords can improve readability.", "ai-seo-content-analyzer");
	}

	// 8.3. Presence of tables: 2 points
	$tables = preg_match_all('/<table>/i', $post_content);
	$additional_details['hints'][] = __("Presence of tables", "ai-seo-content-analyzer");
	$additional_details['scores'][] = $tables > 0 ? 2 : 0;
	/* translators: 1: Number of tables found in content */
	$additional_details['comments'][] = $tables > 0
		? sprintf(__("Found %d tables. Tables help to organize and present data effectively.", "ai-seo-content-analyzer"), $tables)
		: __("No tables found in content. Consider using tables to present data when applicable.", "ai-seo-content-analyzer");
	$score += $tables > 0 ? 2 : 0;
	$additional_score += $tables > 0 ? 2 : 0;

	$seo_score_details['Additional Parameters'] = [
		'score' => $additional_score,
		'details' => $additional_details
	];




	
    // Логирование и возврат оценки
    ai_seo_score_log($post_id, $score, $seo_score_details);
    return $score;
}


 function ai_seo_score_log($post_id, $score, $details) {
    // Store the score and details in a file for future use
    $upload_dir = wp_upload_dir();
    $logs_dir = $upload_dir['basedir'] . '/aiseologs/seo-score';
    $filename = get_post_type($post_id) . "_" . $post_id . ".txt";

    if (!file_exists($logs_dir)) {
        mkdir($logs_dir, 0755, true);
    }

    $log_content = "SEO Score: " . $score . "\n\n";
    if (is_array($details)) {
        foreach ($details as $section => $section_data) {
            $log_content .= $section . ":\n";

            // Проверяем, является ли $section_data массивом и содержит ли ключ 'hints'
            if (is_array($section_data) && isset($section_data['hints'])) {
                // Теперь у нас есть два подмассива: scores и hints
                foreach ($section_data['hints'] as $index => $hint) {
                    $log_content .= "- " . $hint;
                    if (isset($section_data['scores'][$index])) {
                        $log_content .= " (" . $section_data['scores'][$index] . " points)";
                    }
                    $log_content .= "\n";
                }
            }
            $log_content .= "\n";
        }
    }

    file_put_contents($logs_dir . '/' . $filename, $log_content);
}
