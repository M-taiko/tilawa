/**
 * TILAWA SIDEBAR MANAGER
 * Handles sidebar collapse/expand functionality with localStorage persistence
 */

class SidebarManager {
    constructor() {
        this.sidebar = document.getElementById('desktopSidebar');
        this.toggleBtn = document.getElementById('sidebarToggle');
        this.storageKey = 'tilawa_sidebar_collapsed';
        this.isCollapsed = localStorage.getItem(this.storageKey) === 'true';
        this.init();
    }

    init() {
        if (!this.sidebar) return;

        // Restore saved state
        if (this.isCollapsed) {
            this.collapse(false);
        }

        // Toggle button click
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }

        // Keyboard shortcut: Ctrl/Cmd + [
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === '[') {
                e.preventDefault();
                this.toggle();
            }
        });
    }

    toggle() {
        if (this.isCollapsed) {
            this.expand();
        } else {
            this.collapse();
        }
    }

    collapse(animate = true) {
        if (!this.sidebar) return;

        this.sidebar.classList.add('sidebar-collapsed');

        // Skip animation on initial load
        if (!animate) {
            this.sidebar.classList.add('no-transition');
        }

        this.isCollapsed = true;
        localStorage.setItem(this.storageKey, 'true');

        // Remove no-transition class after DOM update
        if (!animate) {
            setTimeout(() => {
                this.sidebar.classList.remove('no-transition');
            }, 50);
        }

        // Dispatch event for other components
        this.dispatchStateChange();
    }

    expand() {
        if (!this.sidebar) return;

        this.sidebar.classList.remove('sidebar-collapsed');
        this.isCollapsed = false;
        localStorage.setItem(this.storageKey, 'false');

        // Dispatch event for other components
        this.dispatchStateChange();
    }

    dispatchStateChange() {
        const event = new CustomEvent('sidebar-state-changed', {
            detail: { collapsed: this.isCollapsed }
        });
        window.dispatchEvent(event);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarManager = new SidebarManager();
});
