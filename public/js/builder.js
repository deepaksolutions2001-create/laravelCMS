document.addEventListener("DOMContentLoaded", function () {
  // Initialize GrapesJS editor
  const editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    fromElement: true,  // Use existing HTML in container
    storageManager: false, // Disable default storage manager
    blockManager: { appendTo: '#blocks' },
    layerManager: { appendTo: '#layers' },
    styleManager: { appendTo: '#styles' },
  });

  // Block Manager reference
  const bm = editor.BlockManager;

  // Define widgets/blocks with categories and content
  const blocks = [
    { id: 'text', label: 'Text', category: 'Basic', content: '<p>Insert text here...</p>' },
    { id: 'heading', label: 'Heading', category: 'Basic', content: '<h1>Heading</h1>' },
    { id: 'button', label: 'Button', category: 'Basic', content: '<button>Click me</button>' },
    { id: 'image', label: 'Image', category: 'Media', content: { type: 'image' } },
    { id: 'video', label: 'Video', category: 'Media', content: '<video controls src="https://www.w3schools.com/html/mov_bbb.mp4" style="width:100%;"></video>' },
    { id: 'map', label: 'Google Map', category: 'Media', content: '<iframe src="https://maps.google.com/maps?q=London&t=&z=13&ie=UTF8&iwloc=&output=embed" style="width:100%; height:300px;" frameborder="0"></iframe>' },
    { id: 'section', label: 'Section', category: 'Layout', content: '<section style="padding:40px; background:#eee;">Section</section>' },
    { id: 'row-cols-2', label: '2 Columns', category: 'Layout', content: '<div style="display:flex;"><div style="flex:1; padding:10px;">Left</div><div style="flex:1; padding:10px;">Right</div></div>' },
    { id: 'row-cols-3', label: '3 Columns', category: 'Layout', content: '<div style="display:flex;"><div style="flex:1; padding:10px;">1</div><div style="flex:1; padding:10px;">2</div><div style="flex:1; padding:10px;">3</div></div>' },
    { id: 'quote', label: 'Quote', category: 'Text', content: '<blockquote>Quote content here</blockquote>' },
    { id: 'list', label: 'List', category: 'Text', content: '<ul><li>Item 1</li><li>Item 2</li></ul>' },
    { id: 'table', label: 'Table', category: 'Advanced', content: '<table border="1" cellpadding="5"><tr><td>Row</td><td>Data</td></tr></table>' },
    {
      id: 'card', label: 'Card', category: 'UI', content: `
      <div style="border:1px solid #ccc; padding:15px; border-radius:6px;">
        <h4>Card Title</h4>
        <p>Card description goes here.</p>
        <button>Read More</button>
      </div>` },
    {
      id: 'hero', label: 'Hero', category: 'UI', content: `
      <section style="padding:60px; text-align:center; background:#222; color:white;">
        <h1>Hero Title</h1><p>Hero subtitle text</p>
      </section>` },
    {
      id: 'form', label: 'Form', category: 'Forms', content: `
      <form><input type="text" placeholder="Name"><br><input type="email" placeholder="Email"><br><button>Submit</button></form>` },
    { id: 'input', label: 'Input', category: 'Forms', content: '<input type="text" placeholder="Your name">' },
    { id: 'textarea', label: 'Textarea', category: 'Forms', content: '<textarea placeholder="Your message"></textarea>' },
    {
      id: 'navbar', label: 'Navbar', category: 'UI', content: `
      <nav style="display:flex; background:#333; color:white; padding:10px;">
        <div style="flex:1;">Logo</div>
        <div><a href="#" style="color:white; margin:0 10px;">Home</a><a href="#" style="color:white;">About</a></div>
      </nav>` },
    {
      id: 'footer', label: 'Footer', category: 'UI', content: `
      <footer style="background:#222; color:white; padding:20px; text-align:center;">
        <p>Copyright © 2025</p>
      </footer>` },
    { id: 'alert', label: 'Alert Box', category: 'UI', content: '<div style="padding:10px; background:#f9c; color:#333;">Alert message</div>' },
    { id: 'badge', label: 'Badge', category: 'UI', content: '<span style="padding:5px 10px; background:#3498db; color:white; border-radius:10px;">Badge</span>' },
    { id: 'progress', label: 'Progress Bar', category: 'UI', content: '<div style="background:#ddd; height:20px;"><div style="width:60%; height:100%; background:#2ecc71;"></div></div>' },
    {
      id: 'accordion', label: 'Accordion', category: 'Advanced', content: `
      <div>
        <button onclick="this.nextElementSibling.style.display = (this.nextElementSibling.style.display === 'block' ? 'none' : 'block')">Toggle</button>
        <div style="display:none; padding:10px; border:1px solid #ccc;">Accordion content</div>
      </div>` },
  ];

  // Add blocks to GrapesJS Block Manager
  blocks.forEach(block => bm.add(block.id, block));

  // Load existing page content and styles if available
  if (PAGE_ID && PAGE_HTML) {
    editor.setComponents(PAGE_HTML);
    editor.setStyle(PAGE_CSS);
  }

  // Tab controls
  const tabs = {
    blocks: document.getElementById('blocks'),
    layers: document.getElementById('layers'),
    styles: document.getElementById('styles'),
  };

  // Tab buttons
  const tabButtons = {
    blocks: document.getElementById('tab-blocks'),
    layers: document.getElementById('tab-layers'),
    styles: document.getElementById('tab-styles'),
  };

  // Show specified tab and toggle active button
  function showTab(tabName) {
    Object.keys(tabs).forEach(name => {
      tabs[name].style.display = (name === tabName) ? 'block' : 'none';
      tabButtons[name].classList.toggle('active', name === tabName);
    });
    if (tabName === 'layers') editor.LayerManager.render();
  }

  // Attach tab click events
  Object.keys(tabButtons).forEach(name => {
    tabButtons[name].onclick = () => showTab(name);
  });

  // Save button click handler
  document.getElementById('btn-save').onclick = () => {
    const html = editor.getHtml();
    const css = editor.getCss();
    const title = document.getElementById('page-title').value.trim();

    if (!PAGE_ID) return alert("Error: PAGE_ID not defined.");
    if (!title) return alert("Please enter a title.");

    fetch(`/pages/${PAGE_ID}/save`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ html, css, title, publish: true }),
    })
      .then(res => {
        if (!res.ok) throw new Error("Save failed");
        return res.json();
      })
      .then(() => alert("Saved successfully"))
      .catch(() => alert("Save failed."));
  };

  // Preview button opens new tab/window
  document.getElementById('btn-preview').onclick = () => {
    if (!PAGE_ID) return alert("PAGE_ID not set.");
    window.open(`/preview/${PAGE_ID}`, '_blank');
  };
});
