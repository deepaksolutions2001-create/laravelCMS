
document.addEventListener("DOMContentLoaded", async function () {


  // === Dynamically load all component blocks to spefici availble ===
  const heroBlocks = [];
  const aboutBlocks = [];


  for (let i = 1; i <= 3; i++) {
    try {
      // here we laod hero  blocks
      const content = await loadBlockFiles(`/js/blocks/hero/hero${i}/hero.html`, `/js/blocks/hero/hero${i}/hero.css`);
      heroBlocks.push({
        id: `hero${i}`,
        label: `Hero ${i}`,
        category: 'UI/Hero',
        content,
      });
    } catch (err) {
      console.warn(`⚠️ Hero ${i} not found or failed to load.`);
    }
  }


  for (let i = 1; i <= 3; i++) {
    try {
      // here we laod hero  blocks
      const content = await loadBlockFiles(`/js/blocks/about/about${i}/about.html`, `/js/blocks/about/about${i}/about.css`);
      heroBlocks.push({
        id: `about${i}`,
        label: `About ${i}`,
        category: 'UI/About',
        content,
      });
    } catch (err) {
      console.warn(`⚠️ Hero ${i} not found or failed to load.`);
    }
  }
  // === All Blocks (same as before) ===
  const blocks = [
    { id: 'text', label: 'Text', category: 'Basic', content: '<p>Insert text here...</p>' },
    { id: 'heading', label: 'Heading', category: 'Basic', content: '<h1>Heading</h1>' },
    { id: 'button', label: 'Button', category: 'Basic', content: '<button>Click me</button>' },

    { id: 'image', label: 'Image', category: 'Media', content: { type: 'image' } },
    { id: 'video', label: 'Video', category: 'Media', content: '<video controls src="https://www.w3schools.com/html/mov_bbb.mp4" style="width:100%;"></video>' },
    { id: 'map', label: 'Google Map', category: 'Media', content: '<iframe src="https://maps.google.com/maps?q=London&t=&z=13&ie=UTF8&iwloc=&output=embed" style="width:100%; height:300px;" frameborder="0"></iframe>' },

    // ✅ Section block (droppable container)
    {
      id: 'section',
      label: 'Section',
      category: 'Layout',
      content: {
        tagName: 'section',
        attributes: { style: 'padding:40px; background:#eee; min-height:120px; border:1px dashed #ccc;' },
        components: '<div style="text-align:center; color:#888;">Drop content here</div>',
        droppable: true,
        draggable: true,
      },
    },

    // ✅ 2 Columns layout
    {
      id: 'row-cols-2',
      label: '2 Columns',
      category: 'Layout',
      content: {
        tagName: 'div',
        attributes: { style: 'display:flex; gap:10px; min-height:100px; border:1px dashed #bbb; padding:10px;' },
        components: [
          {
            tagName: 'div',
            attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' },
            components: '<div style="text-align:center; color:#888;">Drop here (Left)</div>',
            droppable: true,
          },
          {
            tagName: 'div',
            attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' },
            components: '<div style="text-align:center; color:#888;">Drop here (Right)</div>',
            droppable: true,
          },
        ],
        droppable: true,
      },
    },

    // ✅ 3 Columns layout
    {
      id: 'row-cols-3',
      label: '3 Columns',
      category: 'Layout',
      content: {
        tagName: 'div',
        attributes: { style: 'display:flex; gap:10px; min-height:100px; border:1px dashed #bbb; padding:10px;' },
        components: [
          {
            tagName: 'div',
            attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' },
            components: '<div style="text-align:center; color:#888;">Drop here (1)</div>',
            droppable: true,
          },
          {
            tagName: 'div',
            attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' },
            components: '<div style="text-align:center; color:#888;">Drop here (2)</div>',
            droppable: true,
          },
          {
            tagName: 'div',
            attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' },
            components: '<div style="text-align:center; color:#888;">Drop here (3)</div>',
            droppable: true,
          },
        ],
        droppable: true,
      },
    },
    // ✅ Updated list block uses the new editable-list type
    { id: 'list', label: 'List', category: 'Text', content: { type: 'editable-list' } },

    { id: 'quote', label: 'Quote', category: 'Text', content: '<blockquote>Quote content here</blockquote>' },
    { id: 'table', label: 'Table', category: 'Advanced', content: '<table border="1" cellpadding="5"><tr><td>Row</td><td>Data</td></tr></table>' },

    {
      id: 'accordion',
      label: 'Accordion',
      category: 'Advanced',
      content: `
      <div>
        <button onclick="this.nextElementSibling.style.display = (this.nextElementSibling.style.display === 'block' ? 'none' : 'block')">Toggle</button>
        <div style="display:none; padding:10px; border:1px solid #ccc;">Accordion content</div>
      </div>`
    },

    { id: 'form', label: 'Form', category: 'Forms', content: '<form><input type="text" placeholder="Name"><br><input type="email" placeholder="Email"><br><button>Submit</button></form>' },
    { id: 'input', label: 'Input', category: 'Forms', content: '<input type="text" placeholder="Your name">' },
    { id: 'textarea', label: 'Textarea', category: 'Forms', content: '<textarea placeholder="Your message"></textarea>' },

    { id: 'card', label: 'Card', category: 'UI', content: '<div style="border:1px solid #ccc; padding:15px; border-radius:6px;"><h4>Card Title</h4><p>Card description goes here.</p><button>Read More</button></div>' },
    { id: 'navbar', label: 'Navbar', category: 'UI', content: '<nav style="display:flex; background:#333; color:white; padding:10px;"><div style="flex:1;">Logo</div><div><a href="#" style="color:white; margin:0 10px;">Home</a><a href="#" style="color:white;">About</a></div></nav>' },
    { id: 'footer', label: 'Footer', category: 'UI', content: '<footer style="background:#222; color:white; padding:20px; text-align:center;"><p>Copyright © 2025</p></footer>' },
    { id: 'alert', label: 'Alert Box', category: 'UI', content: '<div style="padding:10px; background:#f9c; color:#333;">Alert message</div>' },
    { id: 'badge', label: 'Badge', category: 'UI', content: '<span style="padding:5px 10px; background:#3498db; color:white; border-radius:10px;">Badge</span>' },
    { id: 'progress', label: 'Progress Bar', category: 'UI', content: '<div style="background:#ddd; height:20px;"><div style="width:60%; height:100%; background:#2ecc71;"></div></div>' },
  ];


  // === Initialize GrapesJS ===
  const editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    fromElement: true,
    storageManager: false,
    panels: { defaults: [] },
    blockManager: { appendTo: '#blocks' },
    layerManager: { appendTo: '#layers' },
    styleManager: { appendTo: '#styles' },
    // Keep styles and traits managers
    traitManager: { appendTo: '.traits-container' },
    selectorManager: { appendTo: '.classes-container' },
    canvas: {
      styles: ['https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'],
    },
    plugins: ['gjs-code-editor'], // ✅ Required
    pluginsOpts: {
      'gjs-code-editor': {
        theme: 'material',
        editHtml: 1,
        editCss: 1,
        split: 1,
        modalTitle: 'Edit HTML & CSS',
      },
    },
    /* blockManager: {
      appendTo: "#sidebar",
      blocks: [
        ...blocks, ...heroBlocks, ...aboutBlocks
      ]
    } */

  });

  window.editor = editor; // 


  // ✅ Make <section> globally droppable and visible when empty
  editor.DomComponents.addType('section', {
    model: {
      defaults: {
        droppable: true,
        editable: true,
        highlightable: true,
        draggable: '*:not(section)', // avoid nesting sections inside each other
        attributes: { style: 'min-height:80px; border:1px dashed #ccc;' },
      },
    },
  });

  const bm = editor.BlockManager;

  // === Helper to load HTML + CSS and merge ===
  async function loadBlockFiles(htmlPath, cssPath) {
    const [html, css] = await Promise.all([
      fetch(htmlPath).then(res => res.text()),
      fetch(cssPath).then(res => res.text())
    ]);
    return `<style>${css}</style>\n${html}`;
  }



  // === ✅ Custom editable list component ===
  editor.DomComponents.addType('editable-list', {
    model: {
      defaults: {
        tagName: 'div',
        attributes: { class: 'editable-list-wrapper' },
        components: `
          <style>
            .editable-list-wrapper {
              display: block;
              padding: 8px;
              border: 1px dashed #e6e6e6;
              border-radius: 6px;
            }
            .editable-list { padding-left: 1.25rem; margin: 0; }
            .editable-list li { margin: 6px 0; }
            .add-item-btn {
              display: inline-block;
              margin-top: 8px;
              background: #4caf50;
              color: #fff;
              border: none;
              padding: 6px 10px;
              border-radius: 6px;
              cursor: pointer;
              font-weight: 600;
            }
            .add-item-btn:hover { opacity: .95; }
          </style>
          <ul class="editable-list">
            <li contenteditable="true">Item 1</li>
            <li contenteditable="true">Item 2</li>
          </ul>
          <button class="add-item-btn" type="button">+ Add item</button>
        `,
        script: function () {
          const root = this;
          const btn = root.querySelector('.add-item-btn');
          const ul = root.querySelector('.editable-list');
          if (!btn || !ul) return;
          if (btn.__hasListener) return;

          btn.addEventListener('click', () => {
            const li = document.createElement('li');
            li.textContent = 'New Item';
            li.setAttribute('contenteditable', 'true');
            ul.appendChild(li);
            setTimeout(() => li.focus?.(), 0);
          });

          btn.__hasListener = true;
        },
      },
    },
  });



  // here we create hero block
  // ✅ Add all blocks to block manager
  blocks.forEach(block => bm.add(block.id, block));
  // ✅ Add dynamically loaded hero blocks
  heroBlocks.forEach(block => bm.add(block.id, block));



  // here we create about block
  // ✅ Add all blocks to block manager
  blocks.forEach(block => bm.add(block.id, block));
  // ✅ Add dynamically loaded hero blocks
  aboutBlocks.forEach(block => bm.add(block.id, block));




  // === Add blocks to GrapesJS ===
  blocks.forEach(block => bm.add(block.id, block));
  // === Combine subcategories visually under UI ===
  editor.on('load', () => {
    const uiCat = bm.getCategories().find(cat => cat.id === 'UI');
    if (uiCat) {
      const heroBlocks = bm.getAll().filter(b => b.get('category').id === 'UI/Hero');
      heroBlocks.forEach(b => b.set('category', uiCat));
    }
  });

  // === Tabs / Save / Preview (unchanged) ===
  const tabs = {
    blocks: document.getElementById('blocks'),
    layers: document.getElementById('layers'),
    styles: document.getElementById('styles'),
  };
  const tabButtons = {
    blocks: document.getElementById('tab-blocks'),
    layers: document.getElementById('tab-layers'),
    styles: document.getElementById('tab-styles'),
  };

  function showTab(tabName) {
    Object.keys(tabs).forEach(name => {
      tabs[name].style.display = (name === tabName) ? 'block' : 'none';
      tabButtons[name].classList.toggle('active', name === tabName);
    });
    if (tabName === 'layers') editor.LayerManager.render();
  }

  Object.keys(tabButtons).forEach(name => {
    tabButtons[name].onclick = () => showTab(name);
  });

  if (typeof PAGE_ID !== "undefined" && PAGE_HTML) {
    editor.setComponents(PAGE_HTML);
    editor.setStyle(PAGE_CSS);
  }

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
      .then(res => res.ok ? res.json() : Promise.reject())
      .then(() => alert("Saved successfully"))
      .catch(() => alert("Save failed."));
  };

  document.getElementById('btn-preview').onclick = () => {
    if (!PAGE_ID) return alert("PAGE_ID not set.");
    window.open(`/preview/${PAGE_ID}`, '_blank');
  };

  // === Handle Publish Button ===
  document.getElementById('btn-publish').addEventListener('click', function () {
    if (!PAGE_ID) {
      alert('Please save the page before publishing.');
      return;
    }

    const html = editor.getHtml();
    const css = editor.getCss();
    const title = document.getElementById('page-title').value;

    fetch(`/pages/${PAGE_ID}/publish`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        title,
        html,
        css,
      }),
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('✅ Page published successfully!');
          console.log('Published URL:', data.url);
          window.open(data.url, '_blank');
        } else {
          alert('⚠️ Failed to publish page.');
        }
      })
      .catch(err => {
        console.error('Publish error:', err);
        alert('Error while publishing.');
      });
  });


});
editor.on('component:selected', () => {
  document.querySelector('.custom-sidebar').style.display = 'block';
});

editor.on('canvas:drop', () => {
  document.querySelector('.custom-sidebar').style.display = 'block';
});

editor.on('canvas:click', (ev) => {
  if (!editor.getSelected()) {
    document.querySelector('.custom-sidebar').style.display = 'none';
  }
});




