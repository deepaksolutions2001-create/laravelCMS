<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Review;
use App\Models\Propertie;


use Illuminate\Support\Str;

class AgentController extends Controller
{



    public function addAgent(Request $request)
    {
        // Optional avatar upload -> public disk -> browser URL
        $imageUrl = null;
        if ($request->hasFile('agent_image')) {
            $stored   = $request->file('agent_image')->store('agents', 'public'); // storage/app/public/agents/...
            $imageUrl = Storage::url($stored); // /storage/agents/... (requires storage:link)
        }

        // Accesses: accept comma list or array input and normalize to array for model cast/JSON
        $accessesInput = $request->input('agent_accesses_list', '');
        $accesses = is_array($accessesInput)
            ? array_filter(array_map('trim', $accessesInput))
            : array_filter(array_map('trim', explode(',', (string)$accessesInput))); // model cast serializes to JSON

        // Create via mass assignment; ensure fields are in $fillable
        $agent = Agent::create([
            'name'               => $request->agent_name,
            'title'              => $request->agent_title,
            'image'              => $imageUrl,                           // stored URL or null 
            'mobile'             => $request->agent_mobile,
            'email'              => $request->agent_email,
            'username'           => $request->agent_username,
            'password'           => bcrypt($request->agent_password),    // hash secret
            'can_property_list'  => (bool) $request->agent_can_property_list,
            'required_approval'  => (bool) $request->agent_required_approval,
            'listings_limit'     => (int)  $request->agent_listings_limit,
            'api_key'            => $request->agent_api_key,
            'api_secret'         => $request->agent_api_secret,
            'accesses'           => $accesses,                           // cast to array -> JSON in DB 
        ]); // create requires $fillable; otherwise MassAssignmentException
        if ($agent) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'agent added successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'agent add failed!');
        }
    }

    public function editAgent($id)
    {
        $agent = Agent::findOrFail($id);
        $isEdit = true;
        return view('add/agents', compact('agent', 'isEdit'));
    }

    public function updateAgent(Request $request, $id)
    {

        $agent = Agent::findOrFail($id); // Eloquent fetch for edit 

        // Update image if a new one is uploaded
        if ($request->hasFile('agent_image')) {
            $stored         = $request->file('agent_image')->store('agents', 'public'); // save on public disk 
            $agent->image   = Storage::url($stored); // convert to public URL for views 
        }

        // Normalize accesses
        $accessesInput   = $request->input('agent_accesses_list', $agent->accesses ?? []);
        $accesses        = is_array($accessesInput)
            ? array_filter(array_map('trim', $accessesInput))
            : array_filter(array_map('trim', explode(',', (string)$accessesInput))); // matches cast 

        // Fill scalar fields; keep existing if not sent
        $agent->fill([
            'name'               => $request->agent_name            ?? $agent->name,
            'title'              => $request->agent_title           ?? $agent->title,
            'mobile'             => $request->agent_mobile          ?? $agent->mobile,
            'email'              => $request->agent_email           ?? $agent->email,
            'username'           => $request->agent_username        ?? $agent->username,
            'can_property_list'  => $request->has('agent_can_property_list') ? (bool)$request->agent_can_property_list : $agent->can_property_list,
            'required_approval'  => $request->has('agent_required_approval') ? (bool)$request->agent_required_approval : $agent->required_approval,
            'listings_limit'     => $request->has('agent_listings_limit')    ? (int)$request->agent_listings_limit    : $agent->listings_limit,
            'api_key'            => $request->agent_api_key         ?? $agent->api_key,
            'api_secret'         => $request->agent_api_secret      ?? $agent->api_secret,
            'accesses'           => $accesses,
        ]); // fill respects $fillable and casts on save

        if ($request->filled('agent_password')) {
            $agent->password = bcrypt($request->agent_password); // optional password rotation 
        }

        if ($agent->save()) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'agent update successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'agent update failed!');
        }
    }

    public function deleteAgent($id)
    {

        $property = Agent::findOrFail($id); // id-based fetch 
        $val = $property->image; // '/storage/agents/mmq...png'
        $path = ltrim(parse_url($val, PHP_URL_PATH) ?? $val, '/');      // 'storage/agents/mmq...png' or 'agents/mmq...png'
        $relative = Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path; // 'agents/mmq...png'
        Storage::disk('public')->delete($relative);

        if ($property->delete()) {
            // remove row 
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('agent_deleted', $id)
                ->with('success', 'agent Deleted Successfully');
        } else {
            return redirect()->route('dashboard')
                ->with('agent_deleted', $id)
                ->with('failed', 'agent Delete failed');
        }
    }


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
}
