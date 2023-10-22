<?php

function ai_seo_title_tag_audit($seo_title_value, $seo_keyword_value) {
    // Если это массивы, их нужно обработать соответствующим образом
    if (is_array($seo_title_value)) {
        $seo_title_value = implode(" ", $seo_title_value); // или другая логика
    }
    if (is_array($seo_keyword_value)) {
        $seo_keyword_value = implode(" ", $seo_keyword_value); // или другая логика
    }


    $title_score = 0;
    $score = 0; // добавляем эту строку
    $title_details = [
        'hints' => [],
        'scores' => [],
        'comments' => []  // добавляем массив комментариев
    ];

    $highlighted_title = $seo_title_value;
	
	
	// Добавляем проверку на наличие ключевого слова
	if (is_array($seo_keyword_value)) {
		// здесь можешь обработать массив как хочешь, например, соединить элементы в строку
		$seo_keyword_value = implode(" ", $seo_keyword_value);
	}

	if (!empty(trim($seo_keyword_value))) {
	// 1.1. Title Tag - Presence and match of keyword: 6 points
	$highlighted_title = str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $seo_title_value);
	if (stripos($seo_title_value, $seo_keyword_value) !== false) {
		$score += 6;
		$title_score += 6;
		$title_details['hints'][] = __("Keyword presence in title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 6;
		$title_details['comments'][] = sprintf(__("Keyword used: <strong>%s</strong><br>According to analytical data, including the targeted keyword in the title enhances search engine ranking. It emphasizes the relevance of the content.<br>Title: %s", "ai-seo-autooptimize-pro"), $seo_keyword_value, $highlighted_title);
	} else {
		$title_details['hints'][] = __("Keyword absence in title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 0;
		$title_details['comments'][] = sprintf(__("Keyword used: <strong>%s</strong><br>Missing keyword in the title can affect search engine visibility. Implementing the keyword can enhance ranking.<br>Title: %s", "ai-seo-autooptimize-pro"), $seo_keyword_value, $highlighted_title);
	}

	// 1.2. Title Tag - Optimal length (50-60 characters): 4 points
	$title_length = mb_strlen($seo_title_value);
	if ($title_length >= 50 && $title_length <= 60) {
		$score += 4;
		$title_score += 4;
		$title_details['hints'][] = __("Optimal title length", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 4;
		$title_details['comments'][] = __("Title length is optimal (50-60 characters).<br>Current title length: {$title_length}", "ai-seo-autooptimize-pro");
	} else {
		$title_details['hints'][] = __("Non-optimal title length", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 0;
		$title_details['comments'][] = __("Title length is not optimal. Recommended length: 50-60 characters.<br>Current title length: {$title_length}", "ai-seo-autooptimize-pro");
	}

	// 1.3. Title Tag - Keyword at the beginning: 4 points
	$beginning_of_title = mb_substr($seo_title_value, 0, 40);
	$highlighted_beginning = "<span style='background-color: #b0ffb0;'>" . str_replace($seo_keyword_value, "<strong>{$seo_keyword_value}</strong>", $beginning_of_title) . "</span>";
	if (stripos($beginning_of_title, $seo_keyword_value) !== false) {
		$score += 4;
		$title_score += 4;
		$title_details['hints'][] = __("Keyword at the beginning of the title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 4;
		$title_details['comments'][] = sprintf(__("Keyword used: <strong>%s</strong><br>Title: %s%s", "ai-seo-autooptimize-pro"), $seo_keyword_value, $highlighted_beginning, mb_substr($seo_title_value, 40));
	} else {
		$title_details['hints'][] = __("Keyword not at the beginning of the title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 0;
		$title_details['comments'][] = sprintf(__("Keyword used: <strong>%s</strong><br>Title: %s%s", "ai-seo-autooptimize-pro"), $seo_keyword_value, $highlighted_beginning, mb_substr($seo_title_value, 40));
	}

} else {
    // Можно добавить сообщение, что ключевое слово не задано
    $title_details['comments'][] = __("Keyword is not set. Please provide a keyword for SEO analysis.", "ai-seo-autooptimize-pro");
}

	// 1.4. Title Tag - Presence of numbers, special characters, or emojis: 4 points
	// Обновлённый шаблон, чтобы учитывать числа и определённые спецсимволы/эмодзи, включая символ "ᐉ"
	$pattern = "/[0-9]|[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2190}-\x{21AA}\x{25A0}-\x{25FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1400}-\x{167F}]/u";
	$highlighted_title_with_specials = preg_replace($pattern, "<span style='background-color: #b0ffb0;'>$0</span>", $seo_title_value);
	if (preg_match($pattern, $seo_title_value, $matches)) {
		$score += 4;
		$title_score += 4;
		$title_details['hints'][] = __("Presence of numbers, special Unicode characters, or emojis in title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 4;
		$title_details['comments'][] = __("The SEO title includes numbers, special Unicode characters, or emojis. These elements can attract attention and enhance user engagement.<br>Title with special characters highlighted: {$highlighted_title_with_specials}", "ai-seo-autooptimize-pro");
	} else {
		$title_details['hints'][] = __("Absence of numbers, special Unicode characters, or emojis in title", "ai-seo-autooptimize-pro");
		$title_details['scores'][] = 0;
		$title_details['comments'][] = __("The SEO title does not contain numbers, special Unicode characters, or emojis. Incorporating these elements may make the title more appealing.<br>Title: {$highlighted_title}", "ai-seo-autooptimize-pro");
	}

    $seo_score_details['Title Tag'] = [
        'score' => $title_score,
        'details' => $title_details
    ];
    
    return [
        'total_score' => $score,
        'score' => $title_score,
        'details' => $title_details
    ];
}