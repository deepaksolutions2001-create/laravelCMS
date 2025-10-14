<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;

class ComponentController extends Controller
{
    public function save(Request $request)
{
    try {
        $component = new Component();
        $component->name = $request->input('name');
        $component->category = $request->input('category');
        $component->html = $request->input('html');

        // ✅ Fix: Convert CSS array/object to string (JSON or plain)
        $css = $request->input('css');
        if (is_array($css)) {
            $css = json_encode($css);
        } elseif (is_object($css)) {
            $css = json_encode($css);
        }
        $component->css = $css;

        $component->save();

        return response()->json(['success' => true]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}






public function savePageAsComponent(Request $request)
{
    
        $component = new Component();
        $component->name = $request->input('name',$component->name);
        $component->category = $request->input('category',$component->category); // 🔧 remove accidental spaces
        $component->html = $request->input('html');
          $component->css = $request->input('css', $component->css);

       // Save all changes to the database
        $component->save();

        // Return JSON response to confirm success
        return response()->json(['success' => true]);

}



  public function list()
{
    try {
        $components = Component::select('id', 'name', 'category', 'html', 'css')->get();

        // 🔧 Normalize data to prevent small format mismatches
        $components->transform(function ($item) {
            $item->category = trim($item->category ?? '');
            $item->css = is_string($item->css) ? $item->css : json_encode($item->css);
            return $item;
        });

        return response()
            ->json(['success' => true, 'components' => $components])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0'); // 🧹 no cache
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function listId($id)
{
    
    try {
        $components = Component::select('id', 'name', 'category', 'html', 'css')->where('id',$id)->get();

        return response()->json([
            'success' => true,
            'components' => $components
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
