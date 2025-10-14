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

   public function list()
{
    try {
        $components = Component::select('id', 'name', 'category', 'html', 'css')->get();

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
