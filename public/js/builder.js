/**
 * CMS Builder Script (Laravel + GrapesJS)
 * --------------------------------------------------
 * This script powers the page builder interface for your CMS project.
 * It handles:
 *  - Meta field loading and saving
 *  - Dynamic GrapesJS block loading (Hero, About, etc.)
 *  - GrapesJS initialization and configuration
 *  - Custom editable components (like editable lists)
 *  - Save, Preview, and Publish logic for pages
 */

document.addEventListener("DOMContentLoaded", async function () {



  // ============================================================
  // 🧠 PREFILL META FIELDS IF META_DATA EXISTS
  // ============================================================
  if (window.META_DATA) {
    document.getElementById('meta-title').value = META_DATA.title || '';
    document.getElementById('meta-description').value = META_DATA.description || '';
    document.getElementById('meta-keywords').value = META_DATA.keywords || '';
    document.getElementById('meta-og-image').value = META_DATA.og_image || '';

    // ✅ Rebuild dynamic custom meta tags if any exist
    if (META_DATA.custom) {
      try {
        const customTags = JSON.parse(META_DATA.custom);
        const container = document.getElementById('meta-list');

        customTags.forEach(tag => {
          const row = document.createElement('div');
          row.classList.add('flex', 'space-x-2', 'mb-2');
          row.innerHTML = `
            <input type="text" value="${tag.name}" class="flex-1 px-2 py-1 text-black rounded meta-name">
            <input type="text" value="${tag.content}" class="flex-1 px-2 py-1 text-black rounded meta-content">
            <button class="bg-red-600 text-white px-2 rounded remove-meta">x</button>
          `;
          container.appendChild(row);
          row.querySelector('.remove-meta').addEventListener('click', () => row.remove());
        });
      } catch (err) {
        console.error('Error parsing custom meta tags', err);
      }
    }
  }

  // ============================================================
  // 📦 LOAD DYNAMIC BLOCKS (Hero, About, etc.)
  // ============================================================

  const heroBlocks = [];
  const aboutBlocks = [];
  const contact_form_Blocks = [];

  // Load Hero blocks dynamically (hero1..hero3)
  for (let i = 1; i <= 3; i++) {
    try {
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

  // Load About blocks dynamically (about1..about3)
  // NOTE: preserved original logic: these were pushed into heroBlocks in the original file,
  // so we keep that exact behavior here (no logic change).
  for (let i = 1; i <= 3; i++) {
    try {
      const content = await loadBlockFiles(`/js/blocks/about/about${i}/about.html`, `/js/blocks/about/about${i}/about.css`);
      heroBlocks.push({ // ⚠️ Intentionally pushing to heroBlocks to preserve original behavior
        id: `about${i}`,
        label: `About ${i}`,
        category: 'UI/About',
        content,
      });
    } catch (err) {
      console.warn(`⚠️ About ${i} not found or failed to load.`);
    }
  }

  // Load contact form blocks dynamically (contact form 1..form 2)
  // NOTE: preserved original logic: these were pushed into heroBlocks in the original file,
  // so we keep that exact behavior here (no logic change).
  for (let i = 1; i <= 5; i++) {
    try {
      const content = await loadBlockFiles(`/js/blocks/contact_form/contact_form${i}/contact_form.html`, `/js/blocks/contact_form/contact_form${i}/contact_form.css`);
      heroBlocks.push({ // ⚠️ Intentionally pushing to heroBlocks to preserve original behavior
        id: `contact_form${i}`,
        label: `Conatct_Form${i}`,
        category: 'UI/Contact_Form',
        content,
      });
    } catch (err) {
      console.warn(`⚠️ About ${i} not found or failed to load.`);
    }
  }


  // ============================================================
  // 🧩 BASE BLOCKS (Default GrapesJS components)
  // ============================================================

  const blocks = [
    // === Basic ===
    { id: 'text', label: 'Text', category: 'Basic', content: '<p>Insert text here...</p>' },
    { id: 'heading', label: 'Heading', category: 'Basic', content: '<h1>Heading</h1>' },
    { id: 'button', label: 'Button', category: 'Basic', content: '<button>Click me</button>' },

    // === Media ===
    { id: 'image', label: 'Image', category: 'Media', content: { type: 'image' } },
    { id: 'video', label: 'Video', category: 'Media', content: '<video controls src="https://www.w3schools.com/html/mov_bbb.mp4" style="width:100%;"></video>' },
    { id: 'map', label: 'Google Map', category: 'Media', content: '<iframe src="https://maps.google.com/maps?q=London&t=&z=13&ie=UTF8&iwloc=&output=embed" style="width:100%; height:300px;" frameborder="0"></iframe>' },

    // === Layout ===
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

    // ✅ 2 Columns
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
      },
    },

    // ✅ 3 Columns
    {
      id: 'row-cols-3',
      label: '3 Columns',
      category: 'Layout',
      content: {
        tagName: 'div',
        attributes: { style: 'display:flex; gap:10px; min-height:100px; border:1px dashed #bbb; padding:10px;' },
        components: [
          { tagName: 'div', attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' }, components: '<div style="text-align:center; color:#888;">Drop here (1)</div>', droppable: true },
          { tagName: 'div', attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' }, components: '<div style="text-align:center; color:#888;">Drop here (2)</div>', droppable: true },
          { tagName: 'div', attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' }, components: '<div style="text-align:center; color:#888;">Drop here (3)</div>', droppable: true },
        ],
      },
    },

    // === Text ===
    { id: 'list', label: 'List', category: 'Text', content: { type: 'editable-list' } },
    { id: 'quote', label: 'Quote', category: 'Text', content: '<blockquote>Quote content here</blockquote>' },

    // === Advanced ===
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

    // === Forms ===
    { id: 'form', label: 'Form', category: 'Forms', content: '<form><input type="text" placeholder="Name"><br><input type="email" placeholder="Email"><br><button>Submit</button></form>' },
    { id: 'input', label: 'Input', category: 'Forms', content: '<input type="text" placeholder="Your name">' },
    { id: 'textarea', label: 'Textarea', category: 'Forms', content: '<textarea placeholder="Your message"></textarea>' },

    // === UI ===
    { id: 'card', label: 'Card', category: 'UI', content: '<div style="border:1px solid #ccc; padding:15px; border-radius:6px;"><h4>Card Title</h4><p>Card description goes here.</p><button>Read More</button></div>' },
    { id: 'navbar', label: 'Navbar', category: 'UI', content: '<nav style="display:flex; background:#333; color:white; padding:10px;"><div style="flex:1;">Logo</div><div><a href="#" style="color:white; margin:0 10px;">Home</a><a href="#" style="color:white;">About</a></div></nav>' },
    { id: 'footer', label: 'Footer', category: 'UI', content: '<footer style="background:#222; color:white; padding:20px; text-align:center;"><p>Copyright © 2025</p></footer>' },
    { id: 'alert', label: 'Alert Box', category: 'UI', content: '<div style="padding:10px; background:#f9c; color:#333;">Alert message</div>' },
    { id: 'badge', label: 'Badge', category: 'UI', content: '<span style="padding:5px 10px; background:#3498db; color:white; border-radius:10px;">Badge</span>' },
    { id: 'progress', label: 'Progress Bar', category: 'UI', content: '<div style="background:#ddd; height:20px;"><div style="width:60%; height:100%; background:#2ecc71;"></div></div>' },
  ];

  // ✅ FIXED VERSION — Ensures loaded components retain CSS correctly
  function loadCustomComponents(editor) {
    fetch('/admin/components/list', { cache: 'no-store' })
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.components)) return;

        const bm = editor.BlockManager;

        data.components.forEach(comp => {
          // 🧠 Create a wrapper that keeps CSS scoped inside GrapesJS Canvas
          const wrappedHtml = `<div>
  <style>${comp.css || ''}</style>
  ${comp.html}</div>
`;


          bm.add(comp.name, {
            label: comp.name,
            category: comp.category || 'Custom Components',
            attributes: { class: 'fa fa-cube' },
            content: wrappedHtml,
          });
        });

        console.log('✅ Custom components loaded:', data.components.length);
      })
      .catch(err => console.error('Error loading components:', err));
  }



  function loadPageComponents(editor) {
    fetch('/admin/components/list', { cache: 'no-store' })
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.components)) return;

        const bm = editor.BlockManager;


        pageComponents.forEach(comp => {
          // ✅ Each block content stores the DB ID
          const wrappedHtml = `
<div class="page-component-wrapper" data-db-id="${comp.id}">
  ${comp.html || ''}
</div>
<style>${comp.css || ''}</style>
        `;

          bm.add(`page-${comp.id}`, {
            label: comp.name || `Page Components ${comp.id}`,
            category: '📄 Page Components',
            attributes: { class: 'fa fa-layer-group' },
            content: wrappedHtml,

            // ✅ Optional: Add custom metadata to help you later
            componentId: comp.id, // internal GrapesJS metadata
            componentName: comp.name,
          });
        });

        console.log('✅ Page components loaded:', pageComponents.length);
      })
      .catch(err => console.error('❌ Error loading page components:', err));
  }
  // ============================================================
  // 🧱 INITIALIZE GRAPESJS EDITOR
  // ============================================================

  const editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    fromElement: true,
    storageManager: false,
    panels: { defaults: [] },
    blockManager: { appendTo: '#blocks' },
    layerManager: { appendTo: '#layers' },
    styleManager: { appendTo: '#styles' },
    traitManager: { appendTo: '.traits-container' },
    selectorManager: { appendTo: '.classes-container' },
    canvas: {
      styles: ['https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'],
    },
    plugins: ['grapesjs-plugin-code-editor'],
    pluginsOpts: {
      'grapesjs-plugin-code-editor': {

      }
    },
  });
  function openCodeModal(title, code, mode) {
    const modalEl = document.createElement('div');
    modalEl.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: #1e1e2f;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  `;

    modalEl.innerHTML = `
    <div style="
      background:#111827;
      color:#fff;
      padding:10px 20px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      border-bottom:1px solid #333;
      flex-shrink:0;
    ">
      <h4 style="margin:0;font-size:16px;">${title}</h4>
      <button id="close-code-view" 
              style="background:#ef4444;border:none;color:#fff;
                     padding:6px 12px;border-radius:4px;cursor:pointer;">
        Close
      </button>
    </div>

    <!-- main editor area -->
    <div id="code-editor-container" style="
      flex: 1;
      display: flex;
      overflow: hidden;
      width: 100%;
      height: 100%;
    ">
      <textarea id="code-view-area" style="
        flex: 1;
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        resize: none;
        font-size: 14px;
      "></textarea>
    </div>
  `;

    document.body.appendChild(modalEl);

    // ✅ Initialize CodeMirror
    const cm = CodeMirror.fromTextArea(document.getElementById('code-view-area'), {
      mode: mode,
      theme: 'dracula',
      lineNumbers: true,
      lineWrapping: true,
      readOnly: true,
      viewportMargin: Infinity,
    });

    cm.setValue(code);

    // ✅ Force CodeMirror to stretch full width/height
    setTimeout(() => {
      const cmEl = modalEl.querySelector('.CodeMirror');
      const container = document.getElementById('code-editor-container');
      Object.assign(container.style, { display: 'flex', flex: '1', width: '100%', height: '100%' });
      Object.assign(cmEl.style, {
        width: '100%',
        height: '100%',
        flex: '1',
        maxWidth: 'none',
        overflow: 'auto',
      });
      cm.refresh();
    }, 150);

    // ✅ Close button
    modalEl.querySelector('#close-code-view').addEventListener('click', () => {
      cm.toTextArea();
      modalEl.remove();
    });
  }
  loadCustomComponents(editor);
  loadPageComponents(editor);

  editor.Commands.add('save-component', {
    run(editor) {
      const selected = editor.getSelected();
      if (!selected) {
        alert('Please select an element to save as a component.');
        return;
      }

      const name = prompt('Enter component name:');
      if (!name) return;

      const id = selected.getId();
      const html = selected.toHTML();
      let css = '';

      // === Try: Get from GrapesJS global CSS ===
      const allCss = editor.getCss();
      const regex = new RegExp(`#${id}\\s*{[\\s\\S]*?}`, 'g');
      const matches = allCss.match(regex);
      if (matches && matches.length > 0) {
        css = matches.join('\n\n');
      }

      // === Try: Computed Styles (from iframe DOM) ===
      if (!css.trim()) {
        try {
          const frame = editor.Canvas.getFrameEl();
          const doc = frame?.contentDocument;
          const el = doc?.querySelector(`#${id}`);

          if (el) {
            const styles = window.getComputedStyle(el);
            const includeProps = [
              'color', 'background-color', 'font-size', 'font-family',
              'font-weight', 'text-align', 'margin', 'padding',
              'border', 'border-radius', 'width', 'height',
              'display', 'justify-content', 'align-items',
              'flex-direction', 'gap', 'line-height',
              'opacity', 'overflow', 'z-index', 'cursor',
              'position', 'top', 'left', 'right', 'bottom',
              'transform', 'transition', 'box-shadow'
            ];


            css = `#${id} {\n`;
            for (const prop of includeProps) {
              const val = styles.getPropertyValue(prop);
              if (val && val !== 'initial' && val !== 'auto' && val !== 'none' && val !== '0px' && val !== 'transparent') {
                css += `  ${prop}: ${val};\n`;
              }
            }
            css += `}\n`;
          }
        } catch (e) {
          console.warn('Computed style extraction failed:', e);
        }
      }

      // === Fallback: Inline GrapesJS Styles ===
      if (!css.trim()) {
        const styleObj = selected.getStyle();
        if (Object.keys(styleObj).length > 0) {
          css = `#${id} {\n`;
          for (const [key, val] of Object.entries(styleObj)) {
            css += `  ${key}: ${val};\n`;
          }
          css += `}\n`;
        }
      }

      // Prevent empty output
      if (!css.trim()) css = `#${id} {}`;

      // === Save to DB ===
      fetch('/admin/components/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          name,
          category: 'Custom Components',
          html,
          css,
        }),
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert('✅ Component saved successfully!');
            if (typeof loadCustomComponents === 'function') loadCustomComponents(editor);
          } else {
            alert('❌ Error saving component.');
          }
        })
        .catch(err => {
          console.error('Error saving component:', err);
          alert('❌ Failed to save component.');
        });
    },
  });



  // 👇 Hook save button
  document.getElementById('btn-save-component').addEventListener('click', () => {
    editor.runCommand('save-component');
  });

  // expose editor globally as in original code
  window.editor = editor;

  // ============================================================
  // 🧩 DEFINE CUSTOM COMPONENTS
  // ============================================================

  // ✅ Make <section> globally droppable
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

  // ============================================================
  // ⚙️ HELPER FUNCTION - Load HTML + CSS together
  // ============================================================

  async function loadBlockFiles(htmlPath, cssPath) {
    const [html, css] = await Promise.all([
      fetch(htmlPath).then(res => res.text()),
      fetch(cssPath).then(res => res.text())
    ]);

    // 🧠 Generate a unique class per block based on its folder name
    const blockId = htmlPath.split('/').slice(-2, -1)[0]; // e.g. hero1
    const scopedClass = `block-${blockId}`;

    // 🪄 Wrap the HTML in a unique container
    const wrappedHtml = `<div class="${scopedClass}">\n${html}\n</div>`;

    // 🪄 Prefix all CSS selectors with the unique class to isolate scope
    const scopedCss = css.replace(/(^|\})\s*([^{]+)/g, (match, brace, selector) => {
      if (selector.trim().startsWith('@')) return match; // keep @media/@keyframes untouched
      return `${brace} .${scopedClass} ${selector}`;
    });

    return `<style>${scopedCss}</style>\n${wrappedHtml}`;
  }

  // ============================================================
  // 🧩 CUSTOM EDITABLE LIST COMPONENT
  // ============================================================

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

          // Add item dynamically
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

  // ============================================================
  // 🧱 ADD ALL BLOCKS TO BLOCK MANAGER
  // ============================================================

  // Keep the original sequence and redundancies exactly as provided
  blocks.forEach(block => bm.add(block.id, block));
  heroBlocks.forEach(block => bm.add(block.id, block));
  blocks.forEach(block => bm.add(block.id, block));
  aboutBlocks.forEach(block => bm.add(block.id, block));
  blocks.forEach(block => bm.add(block.id, block));
  contact_form_Blocks.forEach(block => bm.add(block.id, block));

  // Combine UI/Hero category into UI (visual grouping) — preserved
  editor.on('load', () => {
    const uiCat = bm.getCategories().find(cat => cat.id === 'UI');
    if (uiCat) {
      const heroBlocks = bm.getAll().filter(b => b.get('category').id === 'UI/Hero');
      heroBlocks.forEach(b => b.set('category', uiCat));
    }
  });

  // ============================================================
  // 🧭 TAB HANDLING (Blocks / Layers / Styles / Meta)
  // ============================================================

  const tabs = {
    blocks: document.getElementById('blocks'),
    layers: document.getElementById('layers'),
    styles: document.getElementById('styles'),
    meta: document.getElementById('meta'),
  };
  const tabButtons = {
    blocks: document.getElementById('tab-blocks'),
    layers: document.getElementById('tab-layers'),
    styles: document.getElementById('tab-styles'),
    meta: document.getElementById('tab-meta'),
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

  // ============================================================
  // 🧾 LOAD PAGE DATA (if editing existing page)
  // ============================================================

  if (typeof PAGE_ID !== "undefined" && PAGE_HTML) {
    editor.setComponents(PAGE_HTML);
    editor.setStyle(PAGE_CSS);
  }

  // ============================================================
  // 💾 SAVE, PREVIEW, AND PUBLISH FUNCTIONS
  // ============================================================

  async function savePageData(url) {
    const html = editor.getHtml();
    const css = editor.getCss();

    const meta = {
      meta_title: document.getElementById('meta-title').value,
      meta_description: document.getElementById('meta-description').value,
      meta_keywords: document.getElementById('meta-keywords').value,
      meta_og_image: document.getElementById('meta-og-image').value,
    };

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title: document.getElementById('page-title').value,
        html,
        css,
        ...meta,
      }),
    });

    return await response.json();
  }
  let isSaving = false;
  async function savePageAsComponent(url) {
    if (isSaving) return; // prevent double-fire
    isSaving = true;

    try {
      const html = cleanHtml(editor.getHtml());
      const css = editor.getCss();

      // 🔍 Try 1: Get ID from hidden input
      let id = document.getElementById('component-id')?.value || null;

      // 🔍 Try 2: If not found, look inside the canvas for a wrapped component
      if (!id) {
        const wrapper = editor.getWrapper();
        const comp = wrapper.find('.page-component-wrapper')[0];
        if (comp) {
          const attrs = comp.getAttributes();
          id = attrs['data-db-id'] || null;
          console.log('📦 Auto-detected component ID from canvas:', id);
        }
      }

      const name = (document.getElementById('page-title')?.value || 'Untitled Page').trim();

      console.log('🧩 Saving Component:', { id, name });

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          id,
          name,
          category: 'Page Components',
          html,
          css,
        }),
      });

      const data = await response.json();

      if (data?.id) {
        document.getElementById('component-id').value = data.id;
      }

      alert(data.message || 'Component saved');
    } catch (e) {
      console.error(e);
      alert('Save failed');
    } finally {
      isSaving = false;
    }
  }

  // Utility function
  function cleanHtml(html) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;

    // Remove meta, title, link tags
    tempDiv.querySelectorAll('meta, title, link').forEach(el => el.remove());

    return tempDiv.innerHTML;
  }


  // // ✅ Save
  // document.getElementById('btn-save').onclick = async () => {
  //   const result = await savePageData(`/pages/${PAGE_ID}/save`);
  //   if (result.success) alert('✅ Page saved successfully!');
  // };

  // ============================================================
  // 💾 SAVE MODAL LOGIC — FIXED (runs outside editor.on('load'))
  // ============================================================

  const modal = document.getElementById("saveOptionModal");
  const btnSave = document.getElementById("btn-save");
  const saveAsPageBtn = document.getElementById("saveAsPage");
  const saveAsComponentBtn = document.getElementById("saveAsComponent");
  const cancelSaveBtn = document.getElementById("cancelSave");

  if (!btnSave || !modal) {
    console.error("❌ Save button or modal not found in DOM!");
  } else {
    // ✅ Show/Hide helpers
    const showModal = () => {
      console.log("✅ Save button listener attached");
      modal.classList.add("show");
      modal.style.alignItems = "center";
      modal.style.justifyContent = "center";
    };
    const hideModal = () => (modal.classList.remove("show"));

    // 🟢 Show modal when Save clicked
    btnSave.addEventListener("click", (e) => {
      e.preventDefault();
      showModal();
    });

    // 🔴 Close on cancel
    cancelSaveBtn?.addEventListener("click", hideModal);

    // 🟣 Handle Save as Page
    saveAsPageBtn?.addEventListener("click", async () => {
      hideModal();
      const result = await savePageData(`/pages/${PAGE_ID}/save`);
      if (result.success) alert('✅ Page saved successfully!');
    });

    // 🟠 Handle Save as Component
    saveAsComponentBtn?.addEventListener("click", async () => {
      hideModal();
      const result = await savePageAsComponent('/admin/components/saveAsComponent');
      if (result.success) alert('✅ Page As Component saved successfully!');
    });
  }




  // ✅ Preview
  document.getElementById('btn-preview').onclick = async () => {
    const result = await savePageData(`/pages/${PAGE_ID}/save`);
    if (result.success) window.open(`/preview/${PAGE_ID}`, '_blank');
  };

  // ✅ Publish
  document.getElementById('btn-publish').onclick = async () => {
    const result = await savePageData(`/pages/${PAGE_ID}/publish`);
    if (result.success) {
      alert('🚀 Page published successfully!');
      // ✅ Open published static file in new tab if backend returns url
      if (result.url) window.open(result.url, '_blank');
    }
  };



  // === HTML View Button ===
  document.getElementById('btn-html-view').addEventListener('click', () => {
    let htmlCode = editor.getHtml();
    if (typeof html_beautify !== 'undefined') {
      htmlCode = html_beautify(htmlCode, { indent_size: 2, wrap_line_length: 80 });
    }
    openCodeModal('HTML Code View', htmlCode, 'htmlmixed');
  });

  // === CSS View Button ===
  document.getElementById('btn-css-view').addEventListener('click', () => {
    let cssCode = editor.getCss();
    if (typeof css_beautify !== 'undefined') {
      cssCode = css_beautify(cssCode, { indent_size: 2, wrap_line_length: 80 });
    }
    openCodeModal('CSS Code View', cssCode, 'css');
  });
  // ============================================================
  // 🧭 Keep the editor event listeners here (inside DOMContentLoaded so `editor` is in scope)
  // ============================================================

  editor.on('component:selected', () => {
    const sidebar = document.querySelector('.custom-sidebar');
    if (sidebar) sidebar.style.display = 'block';
  });

  editor.on('canvas:drop', () => {
    const sidebar = document.querySelector('.custom-sidebar');
    if (sidebar) sidebar.style.display = 'block';
  });

  editor.on('canvas:click', (ev) => {
    if (!editor.getSelected()) {
      const sidebar = document.querySelector('.custom-sidebar');
      if (sidebar) sidebar.style.display = 'none';
    }
  });

}); // End DOMContentLoaded



