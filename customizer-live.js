(function($){
    const farben = [
        'body_bg', 'body_text', 'link', 'link_visited', 'link_hover',
        'input_bg', 'input_text', 'input_border',
        'table_bg', 'table_text', 'table_border', 'hr'
    ];
    function updateStyleTag() {
        let css = ':root {';
        farben.forEach(function(key) {
            const val = getComputedStyle(document.documentElement).getPropertyValue('--' + key);
            if(val) css += `--${key}: ${val.trim()};`;
        });
        css += '}';
        let styleTag = document.getElementById('dbp-theme-custom-colors');
        if(styleTag) styleTag.textContent = css;
    }
    farben.forEach(function(key) {
        wp.customize(key, function(value) {
            value.bind(function(newval) {
                document.documentElement.style.setProperty('--' + key, newval);
                updateStyleTag();
            });
        });
    });
})(jQuery);
