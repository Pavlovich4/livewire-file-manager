<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize filemanager triggers
        document.querySelectorAll('[data-trigger="filemanager"]').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                const callback = this.dataset.callback;
                window.dispatchEvent(new CustomEvent('open-filemanager', {
                    detail: { callback }
                }));
            });
        });

        // Handle file selection
        window.addEventListener('filemanager:selected', function(e) {
            const { file, callback } = e.detail;
            if (callback && window[callback]) {
                window[callback](file);
            }
        });
    });
</script>
