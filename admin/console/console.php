<?php
if (!defined('ABSPATH')) {
    exit;
}

function ai_seo_content_analyzer_console() {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">';

    echo '
	<div class="ai-seo-main-plugin-wrapper">
			<div class="ai-seo-main-plugin-info">
				<img src="https://buyreadysite.com/wp-content/uploads/2023/03/logo-buyreadysite.com_.svg" alt="BuyReadySite Logo" class="ai-seo-main-logo">
				<h2>AI SEO Content Analyzer (Version 1.0.1)</h2>
				<p class="ai-seo-main-plugin-description">' . __("Your go-to tool for comprehensive SEO content analysis. Brought to you by BuyReadySite, the AI SEO Content Analyzer helps you scrutinize and optimize your content like never before. Enhance your content's SEO score with detailed audits and intelligent recommendations.", 'ai-seo-content-analyzer') . '</p>
				<h3 class="ai-h3-centered">' . __('Plugin Advantages', 'ai-seo-content-analyzer') . '</h3>
				<div class="ai-seo-main-plugin-advantages-wrapper">
					<!-- Content Analysis Advantages -->
					<div class="advantages-row">
						<div class="ai-seo-main-plugin-advantages">
							<h4>' . __('Content Analysis', 'ai-seo-content-analyzer') . '</h4>
							<ul>
								<li>' . __('Keyword density analysis', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('Content readability score', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('Meta tags evaluation', 'ai-seo-content-analyzer') . '</li>
							</ul>
						</div>
						<div class="ai-seo-main-plugin-advantages">
							<h4>' . __('SEO Auditing', 'ai-seo-content-analyzer') . '</h4>
							<ul>
								<li>' . __('On-page SEO audit', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('Backlink analysis', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('Technical SEO evaluation', 'ai-seo-content-analyzer') . '</li>
							</ul>
						</div>
						<div class="ai-seo-main-plugin-advantages">
							<h4>' . __('Performance', 'ai-seo-content-analyzer') . '</h4>
							<ul>
								<li>' . __('Fast content analysis', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('Easy integration', 'ai-seo-content-analyzer') . '</li>
								<li>' . __('User-friendly dashboard', 'ai-seo-content-analyzer') . '</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		
        <div class="ai-seo-main-purchase-pro">
           <a href="admin.php?page=ai-seo-content-analyzer-mass-optimization" class="ai-seo-main-button-pro" style="width: 100%; background-color: #6633cc; text-align: center; padding: 10px 0; color: #ffffff; font-weight: bold;">' . __('Mass content Analyzer', 'ai-seo-content-analyzer') . '</a>
        </div>
';

}