/**
 * TILAWA SIDEBAR SEARCH
 * Real-time search filtering of navigation items with debouncing
 */

class SidebarSearch {
    constructor() {
        this.searchInput = document.getElementById('sidebarSearch');
        this.navItems = document.querySelectorAll('.sidebar-nav .nav-item');
        this.sections = document.querySelectorAll('.sidebar-section');
        this.resultsCount = document.getElementById('searchResultsCount');
        this.debounceTimer = null;
        this.debounceDelay = 150;
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        // Keyboard shortcut: Cmd/Ctrl + K
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.searchInput.focus();
                this.searchInput.select();
            }
        });

        // Input event with debouncing
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.performSearch(e.target.value);
            }, this.debounceDelay);
        });

        // Escape key clears search
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.clearSearch();
            }
        });
    }

    performSearch(query) {
        const trimmedQuery = query.trim().toLowerCase();

        // Clear search if empty
        if (!trimmedQuery) {
            this.clearSearch();
            return;
        }

        let matchCount = 0;
        const terms = trimmedQuery.split(/\s+/);

        // Filter nav items
        this.navItems.forEach(item => {
            const text = item.textContent.trim().toLowerCase();
            const matches = terms.every(term => text.includes(term));

            if (matches) {
                item.classList.remove('search-hidden');
                matchCount++;
            } else {
                item.classList.add('search-hidden');
            }
        });

        // Hide empty sections
        this.sections.forEach(section => {
            const visibleItems = section.querySelectorAll('.nav-item:not(.search-hidden)');
            if (visibleItems.length === 0) {
                section.classList.add('search-hidden');
            } else {
                section.classList.remove('search-hidden');
            }
        });

        // Update results count
        this.updateResultsCount(matchCount);
    }

    clearSearch() {
        // Clear input
        if (this.searchInput) {
            this.searchInput.value = '';
        }

        // Show all items
        this.navItems.forEach(item => {
            item.classList.remove('search-hidden');
        });

        // Show all sections
        this.sections.forEach(section => {
            section.classList.remove('search-hidden');
        });

        // Hide results count
        if (this.resultsCount) {
            this.resultsCount.classList.add('hidden');
        }
    }

    updateResultsCount(count) {
        if (!this.resultsCount) return;

        this.resultsCount.classList.remove('hidden');

        if (count === 0) {
            this.resultsCount.innerHTML = '<span class="text-amber-600">لا توجد نتائج</span>';
        } else {
            this.resultsCount.innerHTML = `<span class="text-slate-600">${count} نتيجة</span>`;
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarSearch = new SidebarSearch();
});
