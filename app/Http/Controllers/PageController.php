<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    // Show list of all pages
    public function index()
    {
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }

    // Create a new blank page and redirect to builder
    public function create()
    {
        $page = Page::create([
            'title' => 'Untitled Page',
            'html' => '',
            'css' => '',
            'status' => 'draft', // default value
        ]);

        return redirect()->route('pages.edit', ['id' => $page->id]);
    }

    // Show builder UI for an existing page
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('pages.builder', compact('page'));
    }

    // Save updates from builder (title, html, css)
    public function save(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $page->title = $request->input('title', $page->title);
        $page->html = $request->input('html', $page->html);
        $page->css = $request->input('css', $page->css);

        // Handle status update
        if ($request->has('status')) {
            $page->status = $request->input('status'); // 'draft' or 'published'
        }

        $page->save();

        return response()->json(['success' => true, 'status' => $page->status]);
    }

    // Preview page content (frontend render)
    public function preview($id)
    {
        $page = Page::findOrFail($id);
        return view('pages.preview', compact('page'));
    }


    public function publish(Request $request, $id)
{
    $page = Page::findOrFail($id);

    $page->title = $request->input('title', $page->title);
    $page->html = $request->input('html', $page->html);
    $page->css = $request->input('css', $page->css);
    $page->status = 'published';
    $page->save();

    // === Optionally save to a static file ===
    $path = public_path('pages');
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    $filename = \Str::slug($page->title) . '.html';
    $filePath = $path . '/' . $filename;

    $content = "<!DOCTYPE html>
<html>
<head>
<title>{$page->title}</title>
<style>{$page->css}</style>
</head>
<body>{$page->html}</body>
</html>";

    file_put_contents($filePath, $content);

    return response()->json([
        'success' => true,
        'url' => url('pages/' . $filename),
    ]);
}
}
