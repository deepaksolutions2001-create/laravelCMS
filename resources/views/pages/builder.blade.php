<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Page Builder</title>

  <!-- GrapesJS CSS -->
  <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
  <link rel="stylesheet" href="/css/hero.css" />
  <link rel="stylesheet" href="/css/builder_view.css" />

  <style>
    .gjs-pn-panel {
      width: 20%;
    }

    /* === Custom Classes & Traits Panel Styling === */
    .panel-section {
      background: #111;
      border: 1px solid #333;
      border-radius: 6px;
      margin: 10px;
      padding: 10px;
      color: #fff;
    }

    .panel-section h4 {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 8px;
      border-bottom: 1px solid #333;
      padding-bottom: 4px;
    }

    .classes-container,
    .traits-container {
      background: #000;
      padding: 8px;
      border-radius: 6px;
      max-height: 250px;
      overflow-y: auto;
    }

    .classes-container::-webkit-scrollbar,
    .traits-container::-webkit-scrollbar {
      width: 6px;
    }

    .classes-container::-webkit-scrollbar-thumb,
    .traits-container::-webkit-scrollbar-thumb {
      background: #333;
      border-radius: 3px;
    }

    .classes-container::-webkit-scrollbar-thumb:hover,
    .traits-container::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    // Pass PHP variables to JS
    const PAGE_ID = {{ $page->id ?? 'null' }};
    const PAGE_HTML = {!! json_encode($page->html ?? '') !!};
    const PAGE_CSS = {!! json_encode($page->css ?? '') !!};
  </script>
</head>
<body class="bg-gray-100">

  <!-- Topbar -->
  <div id="topbar" class="flex items-center justify-between p-3 bg-white border-b border-gray-200">
    <input id="page-title" type="text" value="{{ $page->title ?? 'Untitled Page' }}"
           placeholder="Page title..."
           class="border rounded px-3 py-2 w-1/2 focus:ring focus:ring-indigo-300" />
    <div class="space-x-2">
  <button id="btn-preview" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Preview</button>
  <button id="btn-save" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">Save</button>
  <button id="btn-publish" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Publish</button>
</div>
  </div>

  <!-- Builder Wrapper -->
  <div id="builder-wrap" class="flex h-[calc(100vh-60px)]">
    <!-- GrapesJS Editor -->
    <div id="gjs" class="flex-1 bg-white">{!! $page->html ?? '<h1>Welcome to your page</h1>' !!}</div>

    <!-- Sidebar -->
    <div id="sidebar" class="w-80 bg-gray-900 text-white flex flex-col">
      <div id="sidebar-nav" class="flex border-b border-gray-700">
        <button id="tab-blocks" class="flex-1 py-2 active bg-gray-800">Blocks</button>
        <button id="tab-layers" class="flex-1 py-2 hover:bg-gray-800">Layers</button>
        <button id="tab-styles" class="flex-1 py-2 hover:bg-gray-800">Styles</button>
          <button id="tab-meta" class="flex-1 py-2 hover:bg-gray-800">Meta</button> <!-- ✅ NEW -->

      </div>

      <div id="blocks" class="flex-1 overflow-auto"></div>
      <div id="layers" class="flex-1 overflow-auto hidden"></div>

      <!-- 👇 Added "Styles" section (with Settings + Classes) -->
      <div id="styles" class="flex-1 overflow-auto hidden">
        <div class="panel-section">
          <h4>Classes</h4>
          <div class="classes-container"></div>
        </div>

        <div class="panel-section">
          <h4>Settings</h4>
          <div class="traits-container"></div>
        </div>
      </div>
      <!-- 👆 END Added -->
    </div>
  </div>

  <!-- GrapesJS Core -->
  <script src="https://unpkg.com/grapesjs/dist/grapes.min.js"></script>

  <!-- Code Editor Plugin -->
  <script src="https://unpkg.com/grapesjs-code-editor@1.0.2/dist/grapesjs-code-editor.min.js"></script>

  <!-- Your builder logic -->
  <script src="/js/builder.js"></script>
</body>
</html>
