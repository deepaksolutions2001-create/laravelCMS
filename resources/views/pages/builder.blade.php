<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Page Builder</title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Pacifico&display=swap"
  rel="stylesheet">

<!-- CodeMirror (for syntax highlighting & formatting) -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.css"
/>
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/theme/dracula.min.css"
/>



<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.9/beautify-html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.9/beautify-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.10/mode/css/css.min.js"></script>
<!-- 🧼 JS Beautify for proper formatting -->
<script src="https://cdn.jsdelivr.net/npm/js-beautify@1.14.9/js/lib/beautify-html.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-beautify@1.14.9/js/lib/beautify-css.min.js"></script>
  <!-- 🧩 GrapesJS CSS -->
  <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
  <link rel="stylesheet" href="/css/hero.css" />
  <link rel="stylesheet" href="/css/builder_view.css" />
<script>
  // A real CSS file URL (not the Play CDN script) for the GrapesJS iframe
  window.APP_CSS = 'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.17/dist/tailwind.min.css';
</script>


  <style>
    /* Customize GrapesJS panel width */
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

    /* Custom scrollbar for side panels */
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

  <!-- 🎨 Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- <script>
  tailwind.config = {
  theme: { extend: {} },
  safelist: [
    'hover:bg-blue-500',
    'hover:text-white',
    'hover:scale-105',
    'transition',
    'duration-300',
    'ease-in-out',
    'hover:bg-purple-500',
    'hover:bg-pink-500',
    'hover:bg-yellow-500',
    'hover:bg-gray-700',
    'hover:bg-indigo-500',
    'hover:bg-green-500'
  ]
}; -->

  <script>
    // === Pass Laravel Blade variables into JavaScript ===
    const PAGE_ID = {{ $page->id ?? 'null' }};
    const PAGE_HTML = {!! json_encode($page->html ?? '') !!};
    const PAGE_CSS = {!! json_encode($page->css ?? '') !!};
  </script>
</head>

<body class="bg-gray-100">

  <!-- 🧭 Topbar with Page Title + Buttons -->
  <div id="topbar" class="flex items-center justify-between p-3 bg-white border-b border-gray-200">
    <!-- Editable Page Title -->
    <input
      id="page-title"
      type="text"
      value="{{ $page->title ?? 'Untitled Page' }}"
      placeholder="Page title..."
      class="border rounded px-3 py-2 w-1/2 focus:ring focus:ring-indigo-300"
    />
      <!-- 👇 Add this hidden field just below -->
<input type="hidden" id="component-id" value="">
<input type="hidden" id="component-name" value="">

    <!-- Control Buttons -->
    <div class="space-x-2">
     <button id="btn-html-view" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-500">
  HTML View
</button>
<button id="btn-css-view" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-500">
  CSS View
</button>
       <button id="btn-save-component" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-500">
  Save Component
</button>
      <button id="btn-preview" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
        Preview
      </button>
      <button id="btn-save" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
        Save
      </button>
      <button id="btn-publish" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
        Publish
      </button>
    </div>

  </div>

  <!-- 🧱 Builder Main Wrapper -->
  <div id="builder-wrap" class="flex h-[calc(100vh-60px)]">
    <!-- GrapesJS Editor Area -->
    <div id="gjs" class="flex-1 bg-white">
      {!! $page->html ?? '<h1>Welcome to your page</h1>' !!}
    </div>

    <!-- 📚 Right Sidebar -->
    <div id="sidebar" class="w-80 bg-gray-900 text-white flex flex-col">

      <!-- Tabs Navigation -->
     <div id="sidebar-nav" class="flex border-b border-gray-700 overflow-x-auto scrollbar-thin">
            <!-- Tab button -->
        <button id="tab-traits" class="flex-1 py-2 hover:bg-gray-800">Traits</button>
        <button id="tab-blocks" class="flex-1 py-2 active bg-gray-800">Blocks</button>
        <button id="tab-layers" class="flex-1 py-2 hover:bg-gray-800">Layers</button>
        <button id="tab-styles" class="flex-1 py-2 hover:bg-gray-800">Styles</button>
        <button id="tab-meta" class="flex-1 py-2 hover:bg-gray-800">Meta</button> <!-- ✅ Meta Tab -->
       
      </div>

      <!-- ✅ META TAB SECTION -->
      <div id="meta" class="flex-1 overflow-auto hidden p-4 space-y-4">
        <h3 class="text-lg font-semibold mb-2">Page Meta Tags</h3>

        <label class="block text-sm text-gray-400 mb-1">Meta Title</label>
        <input id="meta-title" type="text" class="w-full px-2 py-1 rounded text-black">

        <label class="block text-sm text-gray-400 mb-1">Meta Description</label>
        <textarea id="meta-description" class="w-full px-2 py-1 rounded text-black" rows="3"></textarea>

        <label class="block text-sm text-gray-400 mb-1">Meta Fokus Keyword</label>
        <textarea id="meta-fokus-keyword" class="w-full px-2 py-1 rounded text-black" rows="3"></textarea>

        <label class="block text-sm text-gray-400 mb-1">Meta Keywords</label>
        <input id="meta-keywords" type="text" class="w-full px-2 py-1 rounded text-black">

        <label class="block text-sm text-gray-400 mb-1">OG Image (for social share)</label>
        <input id="meta-og-image" type="text" class="w-full px-2 py-1 rounded text-black" placeholder="/images/preview.png">
      </div>

      <div id="traits" class="flex-1 overflow-auto hidden p-4"></div>
      <!-- Blocks Tab Content -->
      <div id="blocks" class="flex-1 overflow-auto"></div>

      <!-- Layers Tab Content -->
      <div id="layers" class="flex-1 overflow-auto hidden"></div>

      <!-- Styles Tab Content -->
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
    </div>
  </div>


  <div id="saveOptionModal" class="modal" style="display:none;">
  <div class="modal-content" style="background:#2c2c2c;color:#fff;padding:20px;border-radius:8px;max-width:400px;margin:auto;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);">
    <h3>Save Options</h3>
    <p>How would you like to save this?</p>
    <div style="display:flex;gap:10px;margin-top:15px;">
      <button id="saveAsPage" class="btn btn-primary">Save as Page</button>
      <button id="saveAsComponent" class="btn btn-secondary">Save as Component</button>
      <button id="cancelSave" class="btn btn-danger">Cancel</button>
    </div>
  </div>
</div>


  <!-- 🧠 Pass Page + Meta Data to JS -->
  <script>
    window.PAGE_ID = {{ $page->id }};
    window.PAGE_HTML = @json($page->html);
    window.PAGE_CSS = @json($page->css);

    // ✅ Pass meta fields for reloading into the Meta tab
    window.META_DATA = {
      title: @json($page->title),
      description: @json($page->meta_description),
      keywords: @json($page->meta_keywords),
      fokus_keyword: @json($page->meta_fokus_keyword),
      og_image: @json($page->meta_og_image),
      custom: @json($page->meta_custom),
    };
  </script>

  <!-- 🧩 GrapesJS Core -->
  <script src="https://unpkg.com/grapesjs/dist/grapes.min.js"></script>

  <!-- Code Editor Plugin -->
  <!-- <script src="https://unpkg.com/grapesjs-code-editor@1.0.2/dist/grapesjs-code-editor.min.js"></script> -->
  <script src="https://unpkg.com/grapesjs-plugin-code-editor@1.0.2/dist/grapesjs-plugin-code-editor.min.js"></script>

  <!-- 🔧 Custom Builder Logic -->
  <script src="/js/builder.js"></script>
</body>
</html>
