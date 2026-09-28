<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('created_at')->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'body'    => 'nullable|string',
            'publish_now' => 'nullable|boolean',
        ]);

        $announcement = new Announcement();
        $announcement->title = $validated['title'];
        $announcement->body = $validated['body'] ?? null;
       $announcement->created_by = auth()->id();
        $announcement->updated_by = auth()->id();

        if ($request->boolean('publish_now')) {
            $announcement->is_published = true;
            $announcement->published_at = now();
        } else {
            $announcement->is_published = false;
            $announcement->published_at = null;
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement created.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'body'    => 'nullable|string',
            'publish_now' => 'nullable|boolean',
        ]);

        $announcement->title = $validated['title'];
        $announcement->body = $validated['body'] ?? null;
        $announcement->updated_by = auth()->id();

        if ($request->boolean('publish_now')) {
            $announcement->is_published = true;
            $announcement->published_at = $announcement->published_at ?? now();
        } else {
            $announcement->is_published = false;
            $announcement->published_at = null;
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return back()->with('status', 'Announcement deleted.');
    }
}