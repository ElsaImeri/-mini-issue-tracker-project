<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount('issues')->latest()->paginate(20);
        return view('tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'nullable|string|size:7|starts_with:#',
        ]);

        Tag::create([
            'name' => $request->name,
            'color' => $request->color ?? $this->generateRandomColor(),
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function show(Tag $tag): View
    {
        $tag->load(['issues' => function ($query) {
            $query->with(['project', 'comments'])->latest();
        }]);

        return view('tags.show', compact('tag'));
    }

    public function edit(Tag $tag): View
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|size:7|starts_with:#',
        ]);

        $tag->update([
            'name' => $request->name,
            'color' => $request->color ?? $tag->color,
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    // API endpoint for AJAX requests (for modal in issues)
    public function storeApi(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'nullable|string|size:7|starts_with:#',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'color' => $request->color ?? $this->generateRandomColor(),
        ]);

        return response()->json([
            'success' => true,
            'tag' => $tag
        ]);
    }

    private function generateRandomColor(): string
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33F3', '#33FFF3', '#FF8333', '#33FF83', '#8333FF'];
        return $colors[array_rand($colors)];
    }
}