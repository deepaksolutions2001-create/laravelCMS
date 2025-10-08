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
            'is_published' => false,
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
        $page->is_published = $request->input('publish') ? true : false;
        $page->save();

        return response()->json(['success' => true]);
    }

    public function preview($id)
{
    $page = Page::findOrFail($id);

    // Pass HTML and CSS content to the preview view
    return view('pages.preview', compact('page'));
}
}
