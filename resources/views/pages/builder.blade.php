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

<script src="https://cdn.tailwindcss.com"></script>
  

  <script>
    // Pass PHP variables to JS
    const PAGE_ID = {{ $page->id ?? 'null' }};
    const PAGE_HTML = {!! json_encode($page->html ?? '') !!};
    const PAGE_CSS = {!! json_encode($page->css ?? '') !!};
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
      @theme {
        --color-clifford: #da373d;
      }
    </style>
</head>
<body>

  <!-- Topbar with title, preview & save -->
  <div id="topbar">
    <input id="page-title" type="text" value="{{ $page->title ?? 'Untitled Page' }}" placeholder="Page title..." />
    <button id="btn-preview">Preview</button>
    <button id="btn-save">Save</button>
  </div>

  <!-- Builder Main Container -->
  <div id="builder-wrap">
    <!-- GrapesJS editor container -->
    <div id="gjs">{!! $page->html ?? '<h1>Welcome to your page</h1>' !!}</div>

    <!-- Sidebar with tabs -->
    <div id="sidebar">
      <div id="sidebar-nav">
        <button id="tab-blocks" class="active">Blocks</button>
        <button id="tab-layers">Layers</button>
        <button id="tab-styles">Styles</button>
      </div>

      <!-- Panels -->
      <div id="blocks"></div>
      <div id="layers"></div>
      <div id="styles"></div>
    </div>
  </div>

  <!-- GrapesJS script -->
  <script src="https://unpkg.com/grapesjs"></script>
  <!-- Your custom builder logic -->
  <script src="/js/builder.js"></script>

</body>
</html>
