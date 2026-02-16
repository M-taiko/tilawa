<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('tenant_id', session('current_tenant_id'))
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,teachers,students',
            'priority' => 'required|in:low,normal,high,urgent',
            'expires_at' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);

        Announcement::create([
            'tenant_id' => session('current_tenant_id'),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'target_audience' => $validated['target_audience'],
            'priority' => $validated['priority'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم إضافة الإعلان بنجاح');
    }

    public function edit(Announcement $announcement)
    {
        $this->authorize($announcement);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize($announcement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,teachers,students',
            'priority' => 'required|in:low,normal,high,urgent',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'target_audience' => $validated['target_audience'],
            'priority' => $validated['priority'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم تحديث الإعلان بنجاح');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize($announcement);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم حذف الإعلان بنجاح');
    }

    private function authorize(Announcement $announcement)
    {
        if ($announcement->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }
    }
}
