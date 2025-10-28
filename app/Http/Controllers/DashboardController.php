<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\UserModel;
use App\Models\Propertie;

use App\Models\Agent;
use App\Models\Subcriber;
use App\Models\Review;
use App\Models\FormSubmission;
use Illuminate\Support\Str;
use App\Models\Blog;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class DashboardController extends Controller
{

    //here we check open dashbaord view 
    public function dashboard(Request $request)
    {
        // Fetch all pages from the database
        // Get paginated pages (7 per page)
        $pages = Page::orderBy('created_at', 'desc')->paginate(7);
        $properties = Propertie::orderBy('created_at', 'desc')->paginate(6);
        $agent = Agent::orderBy('created_at', 'desc')->paginate(6);


        //for subscriber model
        $subcriber = Subcriber::orderBy('created_at', 'desc')->paginate(12);

        $total  = Subcriber::count();
        $active = Subcriber::where('status', 'subscribed')->count();
        $unsub  = Subcriber::where('status', 'unsubscribed')->count();

        //here we fetch data of review and make them in grouping
        $review = Review::get();
        $grouped = [
            'service'  => Review::where('type', 'service')->latest()->paginate(6, ['*'], 'service_page')->withQueryString(),
            'agent'    => Review::where('type', 'agent')->latest()->paginate(6, ['*'], 'agent_page')->withQueryString(),
            'property' => Review::where('type', 'property')->latest()->paginate(6, ['*'], 'property_page')->withQueryString(),
        ];


        //here we fetch form data 

        $types = FormSubmission::query()
            ->distinct()
            ->orderBy('form_type')
            ->pluck('form_type');

        $activeType = $request->query('type', $types->first()); // current tab 
        $pageName = Str::slug($activeType, '_') . '_page'; // safe page param
        $submissions = FormSubmission::where('form_type', $activeType)
            ->latest()
            ->paginate(10, ['*'], $pageName) // custom page name per tab
            ->withQueryString(); // keep ?type and filters in links 


        //here we fetch blog data and show into dashboard view 
        $blog = Blog::orderBy('created_at', 'desc')->paginate(8);
        // Return the list view with all pages
        return view('dashboard', compact('pages', 'properties', 'agent', 'subcriber', 'total', 'active', 'unsub', 'grouped', 'types', 'activeType', 'submissions', 'blog'));
    }

    //here we fetch data of review and also some detials of agent of proeprty and show into review detilas view
    public function  reviewDetail($id, $type)
    {
        $review = Review::findOrFail($id);
        $data = null;
        if ($type === 'agent') {
            $id = data_get($review->data, 'agent_id'); // from JSON
            $data = $id ? Agent::find($id) : null;
        } elseif ($type === 'property') {
            $id = data_get($review->data, 'property_id'); // from JSON
            $data = $id ? Propertie::find($id) : null;
        }
        return view('add/review', compact('review', 'data', 'type'));
    }

    //here we delete subcriber details
    public function deleteSubcriber($id)
    {
        $subcriber = Subcriber::findOrFail($id);
        if ($subcriber->delete()) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'delete successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'delete failed!');
        }
    }


    //here we fetchd form data for particular id 
    public function formDetail($id)
    {

        $submission = FormSubmission::findOrFail($id); // 404 if missing
        return view('add.form', compact('submission'));
    }

    //here we delete form data 
    public function formDelete($id)
    {

        $submission = FormSubmission::findOrFail($id);
        if ($submission->delete()) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'form deleted successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'form delete failed!');
        }
    }
}
