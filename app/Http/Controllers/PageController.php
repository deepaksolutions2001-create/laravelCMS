<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{

  /**
   * Create a new blank page and redirect to the dashboard.
   */
  public function create(Request $request)
  {

    // Create a new draft page with default blank values
    $page = Page::create([
      'user_id' => session()->get('user_id'),
      'title' => $request->title,
      'html' => '',
      'css' => '',
      'slug' => 'Untitled-Page',
      'status' => 'draft', // Default status
      'meta_description' => $request->meta_description,
    ]);

    // Redirect to the edit (builder) view for the newly created page
    // Redirect to dashboard with the edit URL
    return redirect()->route('dashboard')
      ->with('page_created', $page->id)
      ->with('success', 'Page created successfully!');
  }


  //open builder for edit page 
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

    // Respond with success JSON and page URL
    return response()->json([
      'success' => true,
      'url' => route('publish.pages', ['slug' => $page->slug]),
      'message' => 'Page published successfully!',
    ]);
  }

  public function slug($slug)
  {
    // Find page by slug instead of id
    $page = Page::where('slug', $slug)->firstOrFail();

    return view('pages.publish', compact('page'));
  }



  //funtion for delete page 

  public function pageDelete($id)
  {


    $page = Page::find($id);
    $page->delete();
    // Redirect to the edit (builder) view for the newly created page
    // Redirect to dashboard with the edit URL
    return redirect()->route('dashboard')
      ->with('page_created', $id)
      ->with('success', 'Page Deleted Successfully');
  }
}
