<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Page Builder</title>

  <!-- GrapesJS CSS -->
  <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />

  <style>
    /* General Reset */
    body, html {
      margin: 0; padding: 0; height: 100%; font-family: sans-serif;
    }

    /* Topbar */
    #topbar {
      height: 50px;
      background: #2c3e50;
      color: white;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }
    #topbar input {
      padding: 6px 10px;
      margin-right: 10px;
      border-radius: 4px;
      border: none;
      font-size: 16px;
      width: 250px;
    }
    #topbar button {
      margin-left: 10px;
      background: #3498db;
      color: white;
      border: none;
      padding: 6px 14px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }
    #topbar button:hover {
      background: #2980b9;
    }

    /* Builder container */
    #builder-wrap {
      display: flex;
      height: calc(100% - 50px);
    }

    /* GrapesJS canvas */
    #gjs {
      flex: 1;
      height: 100%;
      border-right: 1px solid #ccc;
    }

    /* Sidebar for blocks, layers, styles */
    #sidebar {
      width: 320px;
      background: #f4f4f4;
      display: flex;
      flex-direction: column;
      border-left: 1px solid #ccc;
    }
    #sidebar-nav {
      display: flex;
      justify-content: space-around;
      background: #eaeaea;
      padding: 12px 0;
      border-bottom: 1px solid #ccc;
    }
    #sidebar-nav button {
      flex: 1;
      border: none;
      background: none;
      padding: 10px 0;
      cursor: pointer;
      font-weight: bold;
      font-size: 15px;
      color: #333;
      transition: background 0.3s ease;
    }
    #sidebar-nav button:hover,
    #sidebar-nav button.active {
      background: #ddd;
    }
    #blocks, #layers, #styles {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
      display: none;
    }
    #blocks {
      display: block; /* Show blocks tab by default */
    }
  </style>

  <script>
    // Pass PHP variables to JS
    const PAGE_ID = {{ $page->id ?? 'null' }};
    const PAGE_HTML = {!! json_encode($page->html ?? '') !!};
    const PAGE_CSS = {!! json_encode($page->css ?? '') !!};
  </script>
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
