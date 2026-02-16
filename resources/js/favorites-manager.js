/**
 * TILAWA FAVORITES MANAGER
 * Allows users to pin favorite navigation items with localStorage persistence
 */

class FavoritesManager {
    constructor() {
        this.storageKey = 'tilawa_favorites';
        this.maxFavorites = 10;
        this.favorites = this.loadFavorites();
        this.favoritesContainer = document.getElementById('favoritesSection');
        this.favoritesCount = document.getElementById('favoritesCount');
        this.init();
    }

    init() {
        this.renderFavorites();
        this.attachEventListeners();
        this.updateCount();
    }

    loadFavorites() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            return stored ? JSON.parse(stored) : [];
        } catch (e) {
            console.error('Failed to load favorites:', e);
            return [];
        }
    }

    saveFavorites() {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(this.favorites));
        } catch (e) {
            console.error('Failed to save favorites:', e);
            this.showNotification('فشل حفظ المفضلة', 'error');
        }
    }

    addFavorite(navItem) {
        const id = navItem.getAttribute('href');
        const label = navItem.querySelector('.tracking-tight')?.textContent.trim();
        const iconElement = navItem.querySelector('svg');
        const icon = iconElement ? iconElement.outerHTML : '';

        // Check if already exists
        if (this.favorites.some(fav => fav.id === id)) {
            this.showNotification('هذا العنصر موجود بالفعل في المفضلة', 'warning');
            return;
        }

        // Check max limit
        if (this.favorites.length >= this.maxFavorites) {
            this.showNotification(`الحد الأقصى ${this.maxFavorites} عناصر`, 'error');
            return;
        }

        // Add to favorites
        this.favorites.push({
            id,
            label,
            icon,
            route: id,
            order: this.favorites.length
        });

        this.saveFavorites();
        this.renderFavorites();
        this.updateFavoriteButton(navItem, true);
        this.updateCount();
        this.showNotification('تمت الإضافة إلى المفضلة', 'success');
    }

    removeFavorite(id) {
        this.favorites = this.favorites.filter(fav => fav.id !== id);
        this.saveFavorites();
        this.renderFavorites();
        this.updateCount();

        // Update button state
        const navItem = document.querySelector(`a[href="${id}"]`);
        if (navItem) {
            this.updateFavoriteButton(navItem, false);
        }

        this.showNotification('تمت الإزالة من المفضلة', 'info');
    }

    renderFavorites() {
        if (!this.favoritesContainer) return;

        // Empty state
        if (this.favorites.length === 0) {
            this.favoritesContainer.innerHTML = `
                <div class="text-center py-4 text-slate-400 text-sm">
                    <p>لا توجد مفضلة بعد</p>
                    <p class="text-xs mt-1">انقر على ⭐ لإضافة صفحاتك المفضلة</p>
                </div>`;
            return;
        }

        // Render favorites
        const html = this.favorites.map(fav => `
            <a href="${fav.route}"
               class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm
                      bg-gradient-to-r from-amber-50/50 to-transparent
                      hover:from-amber-100/60 transition-all duration-200">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg
                             bg-amber-400/20 text-amber-600 flex-shrink-0">${fav.icon}</span>
                <span class="flex-1 tracking-tight sidebar-text">${fav.label}</span>
                <button class="opacity-0 group-hover:opacity-100 p-1 hover:bg-red-100
                               rounded transition-all remove-favorite flex-shrink-0"
                        onclick="event.preventDefault(); event.stopPropagation(); window.favoritesManager.removeFavorite('${fav.id}')">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </a>
        `).join('');

        this.favoritesContainer.innerHTML = html;
    }

    attachEventListeners() {
        document.querySelectorAll('.sidebar-nav .nav-item').forEach(item => {
            const href = item.getAttribute('href');
            const isFavorite = this.favorites.some(fav => fav.id === href);

            // Only add button if it doesn't exist
            if (!item.querySelector('.favorite-toggle')) {
                this.addFavoriteButton(item, isFavorite);
            }
        });
    }

    addFavoriteButton(navItem, isFavorite = false) {
        const button = document.createElement('button');
        button.className = `favorite-toggle opacity-0 group-hover:opacity-100 p-1
                           rounded transition-all flex-shrink-0 ${isFavorite ? 'favorite-active text-amber-500' : 'text-slate-400'}`;
        button.innerHTML = `
            <svg class="w-4 h-4" fill="${isFavorite ? 'currentColor' : 'none'}"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>`;

        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (button.classList.contains('favorite-active')) {
                this.removeFavorite(navItem.getAttribute('href'));
            } else {
                this.addFavorite(navItem);
            }
        });

        navItem.appendChild(button);
    }

    updateFavoriteButton(navItem, isFavorite) {
        const button = navItem.querySelector('.favorite-toggle');
        if (!button) return;

        const svg = button.querySelector('svg');

        if (isFavorite) {
            button.classList.add('favorite-active', 'text-amber-500');
            button.classList.remove('text-slate-400');
            svg.setAttribute('fill', 'currentColor');
        } else {
            button.classList.remove('favorite-active', 'text-amber-500');
            button.classList.add('text-slate-400');
            svg.setAttribute('fill', 'none');
        }
    }

    updateCount() {
        if (this.favoritesCount) {
            this.favoritesCount.textContent = this.favorites.length;
        }
    }

    showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-amber-50 border-amber-200 text-amber-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };

        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-xl
                          shadow-lg backdrop-blur-md border ${colors[type]}
                          transition-opacity duration-300`;
        toast.style.opacity = '0';
        toast.textContent = message;
        document.body.appendChild(toast);

        // Fade in
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
        });

        // Fade out and remove
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.favoritesManager = new FavoritesManager();
});
