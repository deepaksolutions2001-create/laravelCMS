<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;


class BlogController extends Controller
{
    //here we create blog
    public function createBlog(Request $request)
    {


        // Create a new draft blog with default blank values
        $blog = Blog::create([
            'user_id' => session()->get('user_id'),
            'title' => $request->blog_title,
            'html' => '',
            'css' => '',
            'slug' => 'Untitled-Page',
            'status' => 'draft', // Default status
            'meta_description' => $request->blog_meta_description,
            'category' => $request->blog_category_name,
            'meta_title' => $request->blog_meta_title,
        ]);

        // Redirect to the edit (builder) view for the newly created page
        // Redirect to dashboard with the edit URL
        return redirect()->route('dashboard')
            ->with('blog_created', $blog->id)
            ->with('success', 'blog created successfully!');
    }


    //here we edit blog
    public function editBlog($id)
    {
        // Find the page by its ID (or fail if not found)
        $page = Blog::findOrFail($id);
        $val = 'blog';

        // Load the builder view with the page data
        return view('pages.builder', compact('page', 'val'));
    }

    //here we save blog 
    public function saveBlog(Request $request, $id)
    {
        // Find the page or return 404 if not found
        $page = Blog::findOrFail($id);

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


    //here we view blog
    public function viewBlog($id)
    {
        // Get the page details
        $page = Blog::findOrFail($id);

        // Load preview blade (frontend style)
        return view('pages.preview', compact('page'));
    }



    ///here we delete blog
    public function deleteBlog($id)
    {

        $blog = Blog::findOrFail($id);
        if ($blog->delete()) {
            return redirect()->route('dashboard')
                ->with('success', 'blog Deleted Successfully');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'blog Delete failed');
        }
    }
}
