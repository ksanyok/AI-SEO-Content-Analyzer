<?php


add_action('plugins_loaded', function() {
    global $seo_title, $seo_description, $seo_keyword;

    // Variables for plugin checks
    $is_seo_plugin_active = false;
    $is_classic_editor_active = is_plugin_active('classic-editor/classic-editor.php');

    // Check if Yoast SEO plugin is active
    if (defined('WPSEO_FILE')) {
        $seo_title = '_yoast_wpseo_title';
        $seo_description = '_yoast_wpseo_metadesc';
        $seo_keyword = '_yoast_wpseo_focuskw';
        $is_seo_plugin_active = true;
    }
    // Check if Rank Math plugin is active
    elseif (defined('RANK_MATH_FILE')) {
        $seo_title = 'rank_math_title';
        $seo_description = 'rank_math_description';
        $seo_keyword = 'rank_math_focus_keyword';
        $is_seo_plugin_active = true;
    }
    // Check if All in One SEO Pack plugin is active
    elseif (is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || defined('AIOSEO_VERSION')) {
        $seo_title = '_aioseo_title';
        $seo_description = '_aioseo_description';
        $seo_keyword = '_aioseo_keywords';
        $is_seo_plugin_active = true;
    }

    // If none of the SEO plugins is active or Classic Editor is not active, notify the admin
    if (!$is_seo_plugin_active || !$is_classic_editor_active) {
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e('AI SEO AutoOptimize Pro requires an active SEO plugin (Yoast, Rank Math, or All in One SEO Pack) and Classic Editor. Please install and activate them.', 'ai-seo-content-analyzer'); ?></p>
                <p>
                    <a href="<?php echo admin_url('plugin-install.php?s=wordpress-seo&tab=search&type=term') ?>"><?php _e('Install Yoast SEO', 'ai-seo-content-analyzer'); ?></a> |
                    <a href="<?php echo admin_url('plugin-install.php?s=seo-by-rank-math&tab=search&type=term') ?>"><?php _e('Install Rank Math', 'ai-seo-content-analyzer'); ?></a> |
                    <a href="<?php echo admin_url('plugin-install.php?s=all-in-one-seo-pack&tab=search&type=term') ?>"><?php _e('Install All in One SEO Pack', 'ai-seo-content-analyzer'); ?></a> |
                    <a href="<?php echo admin_url('plugin-install.php?s=Classic-Editor&tab=search&type=term') ?>"><?php _e('Classic Editor', 'ai-seo-content-analyzer'); ?></a>
                </p>
            </div>
            <?php
        });

        // Deactivate the plugin
        add_action('admin_init', function() {
            deactivate_plugins(plugin_basename(__FILE__));
        });
    }
});




function transliterate($text) {
    $transliterationTable = [
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ж' => 'Zh',  'З' => 'Z',   'И' => 'I',
        'Й' => 'Y',   'К' => 'K',   'Л' => 'L',
        'М' => 'M',   'Н' => 'N',   'О' => 'O',
        'П' => 'P',   'Р' => 'R',   'С' => 'S',
        'Т' => 'T',   'У' => 'U',   'Ф' => 'F',
        'Х' => 'H',   'Ц' => 'Ts',  'Ч' => 'Ch',
        'Ш' => 'Sh',  'Щ' => 'Sch', 'Ь' => '',
        'Ю' => 'Yu',  'Я' => 'Ya',
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ж' => 'zh',  'з' => 'z',   'и' => 'i',
        'й' => 'y',   'к' => 'k',   'л' => 'l',
        'м' => 'm',   'н' => 'n',   'о' => 'o',
        'п' => 'p',   'р' => 'r',   'с' => 's',
        'т' => 't',   'у' => 'u',   'ф' => 'f',
        'х' => 'h',   'ц' => 'ts',  'ч' => 'ch',
        'ш' => 'sh',  'щ' => 'sch', 'ь' => '',
        'ю' => 'yu',  'я' => 'ya',
        'І' => 'I',   'Ї' => 'Yi',  'Є' => 'Ye',
        'і' => 'i',   'ї' => 'yi',  'є' => 'ye',
        'Ґ' => 'G',   'ґ' => 'g',
        'Ä' => 'Ae',  'Ö' => 'Oe',  'Ü' => 'Ue',
        'ä' => 'ae',  'ö' => 'oe',  'ü' => 'ue',
        'ß' => 'ss',  'ẞ' => 'SS',
        'À' => 'A',   'Á' => 'A',   'Â' => 'A',
        'Ã' => 'A',   'Å' => 'A',   'Æ' => 'AE',
        'Ç' => 'C',   'È' => 'E',   'É' => 'E',
        'Ê' => 'E',   'Ë' => 'E',   'Ì' => 'I',
        'Í' => 'I',   'Î' => 'I',   'Ï' => 'I',
        'Ð' => 'D',   'Ñ' => 'N',   'Ò' => 'O',
        'Ó' => 'O',   'Ô' => 'O',   'Õ' => 'O',
        'Ø' => 'O',   'Ù' => 'U',   'Ú' => 'U',
        'Û' => 'U',   'Ý' => 'Y',   'Þ' => 'TH',
        'à' => 'a',   'á' => 'a',   'â' => 'a',
        'ã' => 'a',   'å' => 'a',   'æ' => 'ae',
        'ç' => 'c',   'è' => 'e',   'é' => 'e',
        'ê' => 'e',   'ë' => 'e',   'ì' => 'i',
        'í' => 'i',   'î' => 'i',   'ï' => 'i',
        'ð' => 'd',   'ñ' => 'n',   'ò' => 'o',
        'ó' => 'o',   'ô' => 'o',   'õ' => 'o',
        'ø' => 'o',   'ù' => 'u',   'ú' => 'u',
        'û' => 'u',   'ý' => 'y',   'þ' => 'th',
        'ÿ' => 'y'
    ];

    return strtr($text, $transliterationTable);
}
