jQuery(document).ready(function($) {
    $('#start-analysis').on('click', function() {
        $(this).hide();
        $('#reanalyze').hide(); // Скрываем кнопку "Reanalyze" при начале анализа
        $('#analysis-animation').show();
        startAnalysis();
    });

    // Добавляем обработчик для кнопки "Reanalyze"
    $('#reanalyze').on('click', function() {
        $(this).hide();
        $('#start-analysis').hide(); // Скрываем кнопку "Start Analysis" при повторном анализе
        $('#analysis-animation').show();
        startAnalysis();
    });
});

function startAnalysis() {
    jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
            action: 'ai_seo_start_mass_analysis'
        },
        success: function(response) {
            // Перезагрузка страницы после успешного анализа
            location.reload();
        },
        complete: function() {
            jQuery('#analysis-animation').hide();
        }
    });
}



/* jQuery(document).ready(function($) {
    $('#start-analysis').on('click', function() {
        $(this).hide();
        $('#analysis-animation').show();
        // Здесь начинается анализ и анимация
        // Как только анализ закончен, можно скрыть анимацию и показать результаты
    });

    $('#reanalyze').on('click', function() {
        // Здесь можно запустить анализ заново
    });
});


function startAnalysis() {
    jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
            action: 'ai_seo_start_mass_analysis'
        },
        success: function(response) {
            var results = JSON.parse(response);

            // Здесь можно добавить логику обработки результатов и заполнения таблицы
            
            // Пример заполнения таблицы:
            var tableHTML = "<table><tr><th>ID</th><th>Score</th></tr>";
            for (var id in results) {
                tableHTML += `<tr><td>${id}</td><td>${results[id]}</td></tr>`;
            }
            tableHTML += "</table>";
            jQuery('#analysis-table').html(tableHTML).show();

            // Здесь можно добавить логику отображения средней оценки и других данных в колонках
        },
        complete: function() {
            jQuery('#analysis-animation').hide();
            jQuery('#reanalyze').show(); // Показываем кнопку повторного анализа
        }
    });
}


jQuery(document).ready(function($) {
    $('#start-analysis').on('click', function() {
        $(this).hide();
        $('#analysis-animation').show();
        startAnalysis(); // Запуск анализа
    });
});
 */