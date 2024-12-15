<style>
    [x-cloak] {
        display: none !important;
    }

    .aspect-w-10 {
        position: relative;
        padding-bottom: 70%;
    }

    .aspect-w-10 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    /* Dark theme custom styles */
    .dark-theme {
        --bg-primary: #1a1a1a;
        --bg-secondary: #2d2d2d;
        --bg-hover: #333333;
        --text-primary: #ffffff;
        --text-secondary: #a0aec0;
        --border-color: #404040;
        --accent-color: #6366f1;
        --accent-hover: #4f46e5;
        --danger-color: #ef4444;
        --danger-hover: #dc2626;
    }

    .dark-mode-transition {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* Input styles for dark theme */
    .dark-theme input:not([type="file"]) {
        @apply bg-[var(--bg-secondary)] border-[var(--border-color)] text-[var(--bg-primary)] placeholder-[var(--text-secondary)];
    }

    .dark-theme input:not([type="file"]):focus {
        @apply ring-[var(--accent-color)] border-[var(--accent-color)] outline-none;
    }

</style>
