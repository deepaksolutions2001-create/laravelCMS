<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Agent;
use App\Models\Subcriber;



class PropertiesController extends Controller
{

    //return view of property
    public function addProperty()
    {
        $agent = Agent::get();
        return view('add/properties', compact('agent'));
    }


    public function saveProperty(Request $request)
    {

        $property = Propertie::create([
            'user_id'          => session()->get('user_id'),
            'type'             => $request->input('prop_type', 'house'),
            'title'            => $request->input('prop_title', 'Untitled Property'),
            'description'      => $request->input('prop_description', ''),

            // Address
            'unit_number'      => $request->input('prop_unit_number', ''),
            'street_number'    => $request->input('prop_street_number', ''),
            'street_name'      => $request->input('prop_street_name', ''),
            'city'             => $request->input('prop_city', ''),
            'state'            => $request->input('prop_state', ''),
            'postal_code'      => $request->input('prop_postal_code', ''),

            // Facts
            'bedrooms'         => $request->input('prop_bedrooms', ''),
            'bathrooms'        => $request->input('prop_bathrooms', ''),
            'car_spaces'       => $request->input('prop_car_spaces', ''),
            'land_size'        => $request->input('prop_land_size', ''),
            'land_size_type'   => $request->input('prop_land_size_type', ''),

            // Media & lists start empty so builder can fill later
            'images'           => [],
            'videos'           => [],
            'floor_plan_images' => [],
            'documents'        => [],
            'documents_name'   => null,

            // Map/assignment
            'map_url'          => $request->input('prop_map_url', ''),
            'inspection'       => [],
            'agents'           => $request->input('agent_ids', ''),

            // Status/pricing defaults
            'status'           => $request->input('prop_status', 'archieved'),
            'contract'         => $request->input('prop_contract', 'sale'),
            'min_price'        => $request->input('prop_min_price', ''),
            'max_price'        => $request->input('prop_max_price', ''),
        ]);

        // Buckets for file paths
        $images = [];
        $videos = [];
        $floors = [];
        $docs = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('properties/images', 'public');
            }
        }
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videos[] = $file->store('properties/videos', 'public');
            }
        }
        if ($request->hasFile('floor_plan_images')) {
            foreach ($request->file('floor_plan_images') as $file) {
                $floors[] = $file->store('properties/floorplans', 'public');
            }
        }
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $docs[] = $file->store('properties/documents', 'public');
            }
        }

        // Update property with stored paths
        $property->update([
            'images'            => $images,
            'videos'            => $videos,
            'floor_plan_images' => $floors,
            'documents'         => $docs,
        ]);

        if ($property) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'property added successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'property add failed!');
        }
    }


    public function deleteProperty($id)
    {


        $property = Propertie::findOrFail($id); // id-based fetch 
        foreach (['images', 'videos', 'floor_plan_images', 'documents'] as $key) {
            foreach (($property->{$key} ?? []) as $val) {
                $path = ltrim(parse_url($val, PHP_URL_PATH) ?? $val, '/');      // 'storage/agents/mmq...png' or 'agents/mmq...png'
                $relative = Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path; // 'agents/mmq...png'
                Storage::disk('public')->delete($relative); // cleanup files on public disk 
            }
        }
        $property->delete(); // remove row 
        // Redirect to the edit (builder) view for the newly created page
        // Redirect to dashboard with the edit URL
        return redirect()->route('dashboard')
            ->with('property_deleted', $id)
            ->with('success', 'property Deleted Successfully');
    }

    public function editProperty($id)
    {
        $property = Propertie::findOrFail($id);
        // $ids = collect($property->agents ?? [])
        //     ->map(fn($v) => (int)$v)->filter()->values()->all();//get all agent id from proeprty
        $vals = true;
        $agent = Agent::get();

        return view('add/properties', compact('property', 'vals', 'agent'));
    }

    public function updateproperty(Request $r, $id)
    {

        // Fetch by id
        $property = Propertie::findOrFail($id); // id-based fetc

        // Fill scalar fields from your prop_* inputs
        $property->fill([
            // basic
            'type'            => $r->input('prop_type', $property->type),
            'status'          => $r->input('prop_status', $property->status),
            'title'           => $r->input('prop_title', $property->title),
            'description'     => $r->input('prop_description', $property->description),

            // address
            'unit_number'     => $r->input('prop_unit_number', $property->unit_number),
            'street_number'   => $r->input('prop_street_number', $property->street_number),
            'street_name'     => $r->input('prop_street_name', $property->street_name),
            'city'            => $r->input('prop_city', $property->city),
            'state'           => $r->input('prop_state', $property->state),
            'postal_code'     => $r->input('prop_postal_code', $property->postal_code),

            // facts
            'bedrooms'        => $r->input('prop_bedrooms', $property->bedrooms),
            'bathrooms'       => $r->input('prop_bathrooms', $property->bathrooms),
            'car_spaces'      => $r->input('prop_car_spaces', $property->car_spaces),
            'land_size'       => $r->input('prop_land_size', $property->land_size),
            'land_size_type'  => $r->input('prop_land_size_type', $property->land_size_type),

            // pricing/map
            'contract'        => $r->input('prop_contract', $property->contract),
            'min_price'       => $r->input('prop_min_price', $property->min_price),
            'max_price'       => $r->input('prop_max_price', $property->max_price),
            'map_url'         => $r->input('prop_map_url', $property->map_url),

            // lists (comma-separated -> arrays)
            'agents'           => $r->input('agent_ids', ''),

            'inspection'      => array_filter(array_map('trim', explode(',', (string)$r->input('prop_inspection', isset($property->inspection) ? implode(',', $property->inspection) : '')))),

            // docs label
            'documents_name'  => $r->input('documents_name', $property->documents_name),
        ]); // mass assignment with $fillable on model

        // Ensure arrays exist when null
        $property->images            = $property->images            ?? [];
        $property->videos            = $property->videos            ?? [];
        $property->floor_plan_images = $property->floor_plan_images ?? [];
        $property->documents         = $property->documents         ?? [];

        // Remove selected media by index and delete files from public disk
        $removals = [
            'images'            => (array)$r->input('remove_images', []),
            'videos'            => (array)$r->input('remove_videos', []),
            'floor_plan_images' => (array)$r->input('remove_floor_plans', []),
            'documents'         => (array)$r->input('remove_documents', []),
        ];

        foreach ($removals as $key => $idxs) {
            $current = $property->{$key} ?? [];
            foreach ($idxs as $i) {
                if (isset($current[$i])) {
                    Storage::disk('public')->delete($current[$i]); // remove file from storage/app/public 
                    unset($current[$i]);
                }
            }
            $property->{$key} = array_values($current); // reindex after unset
        }

        // Append new uploads (store on public disk for Storage::url rendering)
        $folders = [
            'images'            => 'properties/images',
            'videos'            => 'properties/videos',
            'floor_plan_images' => 'properties/floorplans',
            'documents'         => 'properties/documents',
        ];

        foreach ($folders as $key => $dir) {
            if ($r->hasFile($key)) {
                $add = [];
                foreach ($r->file($key) as $file) {
                    $add[] = $file->store($dir, 'public'); // returns relative path under public disk 
                }
                $property->{$key} = array_merge($property->{$key} ?? [], $add);
            }
        }

        if ($property->save()) {
            // Redirect to the edit (builder) view for the newly created page
            // Redirect to dashboard with the edit URL
            return redirect()->route('dashboard')
                ->with('success', 'property update successfully!');
        } else {
            return redirect()->route('dashboard')
                ->with('failed', 'property update failed!');
        }
    }


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
}
