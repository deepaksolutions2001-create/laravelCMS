<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
  /**
   * Display a listing of all pages in the CMS.
   */
  public function index()
  {
    // Fetch all pages from the database
    $pages = Page::all();

    // Return the list view with all pages
    return view('pages.index', compact('pages'));
  }

  /**
   * Create a new blank page and redirect to the builder.
   */
  public function create()
  {
    // Create a new draft page with default blank values
    $page = Page::create([
      'user_id' => session()->get('user_id'),
      'title' => 'Untitled Page',
      'html' => '',
      'css' => '',
      'slug' => 'Untitled-Page',
      'status' => 'draft', // Default status
    ]);

    // Redirect to the edit (builder) view for the newly created page
    return redirect()->route('pages.edit', ['id' => $page->id]);
  }

  /**
   * Show the GrapesJS builder UI for an existing page.
   */
  public function edit($id)
  {
    // Find the page by its ID (or fail if not found)
    $page = Page::findOrFail($id);

    // Load the builder view with the page data
    return view('pages.builder', compact('page'));
  }

  /**
   * Save a page in draft mode (AJAX save from GrapesJS).
   */
  public function save(Request $request, $id)
  {
    // Find the page or return 404 if not found
    $page = Page::findOrFail($id);

    // Update core content fields
    $page->title = $request->input('title', $page->title);
    $page->html = $request->input('html', $page->html);
    $page->css = $request->input('css', $page->css);
    $page->status = $request->input('status', 'draft');

    // ✅ Update meta fields if provided
    $page->meta_title = $request->input('meta_title', $page->title);
    $page->meta_description = $request->input('meta_description', $page->meta_description);
    $page->meta_keywords = $request->input('meta_keywords', $page->meta_keywords);
    $page->meta_fokus_keyword = $request->input('meta_fokus_keyword', $page->meta_fokus_keyword);
    $page->meta_og_image = $request->input('meta_og_image', $page->meta_og_image);
    $page->meta_custom = $request->input('meta_custom', $page->meta_custom);



    // Generate clean filename using slug of page title
    $page->slug = Str::slug($page->title ?: 'untitled') . '.html';

    // Save all changes to the database
    $page->save();

    // Return JSON response to confirm success
    return response()->json(['success' => true]);
  }

  /**
   * Preview page content in frontend (without publishing).
   */
  public function preview($id)
  {
    // Get the page details
    $page = Page::findOrFail($id);

    // Load preview blade (frontend style)
    return view('pages.preview', compact('page'));
  }

  /**
   * Publish the page: set status to "published", clean HTML, and save static file.
   */
  public function publish(Request $request, $id)
  {
    // Fetch the page to be published
    $page = Page::findOrFail($id);

    // 🧠 Update main content and mark as published
    $page->title = $request->input('title', $page->title);
    $page->html = $request->input('html', $page->html);
    $page->css = $request->input('css', $page->css);
    $page->status = 'published';

    // ✅ Update meta information
    $page->meta_title = $request->input('meta_title', $page->title);
    $page->meta_description = $request->input('meta_description', $page->meta_description);
    $page->meta_keywords = $request->input('meta_keywords', $page->meta_keywords);
    $page->meta_fokus_keyword = $request->input('meta_fokus_keywords', $page->meta_fokus_keyword);
    $page->meta_og_image = $request->input('meta_og_image', $page->meta_og_image);
    $page->meta_custom = $request->input('meta_custom', $page->meta_custom);

    // 🧹 Remove unwanted tags (meta, title, head, html, link) before saving clean HTML
    $cleanHtml = preg_replace('/<(meta|title|head|html|link)[^>]*>/i', '', $request->input('html'));
    $page->html = $cleanHtml;

    // Save updated data to the database
    $page->save();

    // === ✅ Optional: Save as a static HTML file for frontend ===
    $path = public_path('pages');

    // Create "pages" directory if it doesn’t exist
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    // Generate clean filename using slug of page title
    $filename = Str::slug($page->title ?: 'untitled') . '.html';
    $filePath = $path . '/' . $filename;

    // Build complete HTML structure with meta + CSS + content
    $content = "<!DOCTYPE html>
<html lang=\"en\">
<head>
  <meta charset=\"UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  <title>" . e($page->meta_title ?: $page->title) . "</title>
  <meta name=\"description\" content=\"" . e($page->meta_description) . "\">
  <meta name=\"keywords\" content=\"" . e($page->meta_keywords) . "\">
  <meta property=\"og:image\" content=\"" . e($page->meta_og_image) . "\">
  <meta name=\"csrf-token\" content=\"\">
  <style>{$page->css}</style>

  <script src='https://cdn.tailwindcss.com'></script>
  <script src='https://cdn.tailwindcss.com?plugins=forms,typography'></script>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' crossorigin='anonymous'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css' crossorigin='anonymous'>

  <script>
  (async function(){
    try {
      // 1) Get a fresh CSRF token and start a session
      const r = await fetch('/csrf-token', { credentials: 'same-origin' });
      const data = await r.json();
      const token = data && data.token;

      // 2) Update meta for other scripts to read
      var meta = document.querySelector('meta[name=\"csrf-token\"]');
      if (meta && token) meta.setAttribute('content', token);

      // 3) Ensure every non-GET form has _token
      document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('form').forEach(function(f){
          var m = (f.getAttribute('method') || 'post').toLowerCase();
          if (m === 'get' || !token) return;
          var t = f.querySelector('input[name=\"_token\"]');
          if (!t) {
            t = document.createElement('input');
            t.type = 'hidden';
            t.name = '_token';
            f.appendChild(t);
          }
          t.value = token;
        });
      });
    } catch(e) { /* no-op */ }
  })();
  </script>
</head>
<body>{$page->html}</body>
</html>";

    // Write the file to /public/pages directory
    file_put_contents($filePath, $content);

    // Respond with success JSON and page URL
    return response()->json([
      'success' => true,
      'url' => url('pages/' . $filename),
      'message' => 'Page published successfully!',
    ]);
  }
}
