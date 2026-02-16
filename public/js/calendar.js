/**
 * Calendar Modal Management
 */
let currentModal = null;

/**
 * Drag and Drop State
 */
let draggedScheduleId = null;
let draggedElement = null;

function showScheduleModal(scheduleId) {
    const modal = document.getElementById('scheduleModal');
    const content = document.getElementById('scheduleModalContent');

    if (!modal || !content) return;

    // Show loading state
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="flex flex-col items-center gap-3">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
                <p class="text-sm text-gray-600 font-medium">جاري التحميل...</p>
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    currentModal = scheduleId;

    // Fetch schedule details
    fetch(`/admin/schedules/${scheduleId}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch');
            return response.json();
        })
        .then(data => {
            renderScheduleDetails(data, content);
        })
        .catch(error => {
            console.error('Error fetching schedule:', error);
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-1">حدث خطأ في تحميل البيانات</h4>
                    <p class="text-sm text-gray-600">الرجاء المحاولة مرة أخرى</p>
                    <button onclick="closeScheduleModal()" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                        إغلاق
                    </button>
                </div>
            `;
        });
}

function renderScheduleDetails(data, container) {
    const cls = data.study_class;
    const groupColors = {
        men: { bg: 'bg-blue-100', text: 'text-blue-600' },
        women: { bg: 'bg-pink-100', text: 'text-pink-600' },
        kids: { bg: 'bg-emerald-100', text: 'text-emerald-600' }
    };
    const colors = groupColors[cls.group] || groupColors.men;

    container.innerHTML = `
        ${!data.is_active ? `
            <div class="mb-4 rounded-lg bg-yellow-50 border-r-4 border-yellow-400 p-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-yellow-800 font-medium">هذا الموعد غير نشط حالياً</p>
                </div>
            </div>
        ` : ''}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg ${colors.bg} ${colors.text} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 mb-1">الوقت</p>
                        <p class="font-bold text-gray-900">${formatTime(data.start_time)} - ${formatTime(data.end_time)}</p>
                        <p class="text-sm text-gray-600 mt-1">${data.duration_minutes} دقيقة</p>
                    </div>
                </div>

                ${cls.teacher ? `
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 mb-1">المعلم</p>
                            <p class="font-bold text-gray-900 truncate">${cls.teacher.name}</p>
                        </div>
                    </div>
                ` : ''}
            </div>

            <div class="space-y-3">
                ${data.location ? `
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 mb-1">الموقع</p>
                            <p class="font-bold text-gray-900">${data.location}</p>
                        </div>
                    </div>
                ` : ''}

                <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 mb-1">المجموعة</p>
                        <p class="font-bold text-gray-900">${getGroupLabel(cls.group)}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
            <a href="/admin/schedules/${data.id}/edit"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                تعديل الموعد
            </a>
            <a href="/admin/classes/${cls.id}/edit"
               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                عرض الحلقة
            </a>
        </div>
    `;
}

function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    if (modal) {
        modal.classList.add('hidden');
        currentModal = null;
    }
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const period = hour >= 12 ? 'م' : 'ص';
    const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
    return `${displayHour}:${minutes} ${period}`;
}

function getGroupLabel(group) {
    const labels = {
        'men': 'رجال',
        'women': 'نساء',
        'kids': 'أطفال'
    };
    return labels[group] || group;
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && currentModal) {
        closeScheduleModal();
    }
});


/**
 * Drag and Drop Handlers
 */
function handleDragStart(e) {
    draggedElement = e.currentTarget;
    draggedScheduleId = e.currentTarget.dataset.scheduleId;

    e.currentTarget.classList.add('schedule-item-dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.currentTarget.innerHTML);

    // Stop the click event from firing when drag starts
    e.currentTarget.onclick = null;
    setTimeout(() => {
        e.currentTarget.onclick = function() { showScheduleModal(draggedScheduleId); };
    }, 100);
}

function handleDragEnd(e) {
    e.currentTarget.classList.remove('schedule-item-dragging');

    // Remove drag-over class from all cells
    document.querySelectorAll('.calendar-day-cell').forEach(cell => {
        cell.classList.remove('drag-over');
    });
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    e.currentTarget.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    e.preventDefault();

    const targetCell = e.currentTarget;
    targetCell.classList.remove('drag-over');

    if (!draggedScheduleId) return false;

    const newDay = targetCell.dataset.day;
    const newTime = targetCell.dataset.time;
    const oldDay = draggedElement.dataset.currentDay;
    const oldTime = draggedElement.dataset.currentTime;

    // Check if actually moved
    if (newDay === oldDay && newTime === oldTime) {
        return false;
    }

    // Show confirmation
    if (!confirm(`هل تريد نقل هذا الموعد إلى ${getDayNameArabic(newDay)} الساعة ${newTime}؟`)) {
        return false;
    }

    // Update schedule via AJAX
    updateScheduleTime(draggedScheduleId, newDay, newTime);

    return false;
}

function updateScheduleTime(scheduleId, newDay, newTime) {
    // Show loading indicator
    const loadingToast = showToast('جاري تحديث الموعد...', 'info');

    fetch(`/admin/schedules/${scheduleId}`)
    .then(response => response.json())
    .then(schedule => {
        // Calculate end time based on duration
        const startTime = new Date(`2000-01-01 ${newTime}`);
        const endTime = new Date(startTime.getTime() + schedule.duration_minutes * 60000);
        const endTimeStr = endTime.toTimeString().slice(0, 5);

        // Create form data
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('class_id', schedule.class_id);
        formData.append('day_of_week', newDay);
        formData.append('start_time', newTime);
        formData.append('end_time', endTimeStr);
        if (schedule.location) formData.append('location', schedule.location);
        if (schedule.is_active) formData.append('is_active', '1');

        // Update the schedule
        return fetch(`/admin/schedules/${scheduleId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });
    })
    .then(response => {
        hideToast(loadingToast);

        if (response.ok || response.redirected) {
            showToast('تم تحديث الموعد بنجاح!', 'success');
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to update');
        }
    })
    .catch(error => {
        console.error('Error updating schedule:', error);
        hideToast(loadingToast);
        showToast('حدث خطأ في تحديث الموعد. الرجاء المحاولة مرة أخرى.', 'error');
    });
}

function getDayNameArabic(day) {
    const days = {
        'sunday': 'الأحد',
        'monday': 'الاثنين',
        'tuesday': 'الثلاثاء',
        'wednesday': 'الأربعاء',
        'thursday': 'الخميس',
        'friday': 'الجمعة',
        'saturday': 'السبت'
    };
    return days[day] || day;
}

// Simple toast notification system
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 left-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 transition-all transform translate-y-0 ${
        type === 'success' ? 'bg-green-600' :
        type === 'error' ? 'bg-red-600' :
        'bg-blue-600'
    }`;
    toast.textContent = message;
    toast.style.animation = 'slideInUp 0.3s ease-out';
    document.body.appendChild(toast);
    return toast;
}

function hideToast(toast) {
    if (toast && toast.parentNode) {
        toast.style.animation = 'slideOutDown 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

// Add CSS for toast animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    @keyframes slideOutDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
