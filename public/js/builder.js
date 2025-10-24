/**
 * CMS Builder Script (Laravel + GrapesJS) — Patched
 * - Unifies Tailwind via Vite in canvas.styles
 * - Fixes gallery/subscribe typos
 * - Registers contact_form_Blocks in BlockManager
 * - Uses iframe window for computed styles on save
 * - Stable block IDs for server components
 * - Safe hover previews
 * - Allows inline scripts (optional) for legacy blocks
 */


function sanitizePreview(html) {
  if (typeof html !== 'string') return '';
  // strip script/link tags commonly embedded in saved components
  html = html.replace(/<script[\s\S]*?<\/script>/gi, '');
  html = html.replace(/<link[^>]+tailwind[^>]*>/gi, '');
  html = html.replace(/<link[^>]+font-?awesome[^>]*>/gi, '');
  return html;
}

// Fetch block files (HTML + optional CSS)
async function loadBlockFiles(htmlPath, cssPath = null) {
  try {
    const html = await fetch(htmlPath).then(res => res.text());

    if (cssPath) {
      const css = await fetch(cssPath).then(res => res.text());
      const blockId = htmlPath.split('/').slice(-2, -1)[0];
      const scopedClass = `block-${blockId}`;
      const scopedCss = css.replace(/(^|\})\s*([^{]+)/g, (match, brace, selector) => {
        if (selector.trim().startsWith('@')) return match;
        return `${brace} .${scopedClass} ${selector}`;
      });
      return `<style>${scopedCss}</style>\n<div class="${scopedClass}">\n${html}\n</div>`;
    }

    return html;
  } catch (e) {
    console.log(e);
  }
}

// Load multiple components by pattern
async function loadComponents(arr, pathname, labelname, times, follow) {
  let content;
  for (let i = 1; i <= times; i++) {
    try {
      if (follow == 1) {
        content = await loadBlockFiles(`/js/blocks/${pathname}/${pathname}${i}/${pathname}.html`, `/js/blocks/${pathname}/${pathname}${i}/${pathname}.css`);
      } else {
        content = await loadBlockFiles(`/js/blocks/${pathname}/${pathname}${i}.html`);
      }
      arr.push({
        id: `${pathname}${i}`,
        label: `${labelname} ${i}`,
        category: `UI/${labelname}`,
        content,
        // generate a scaled preview
        hoverPreview: `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(content)}</div>`
      });
    } catch (err) {
      console.warn(`⚠️ ${labelname} ${i} not found or failed to load.`);
    }
  }
  return arr;
}

document.addEventListener("DOMContentLoaded", async function () {
  // Prefill meta fields
  if (window.META_DATA) {
    document.getElementById('meta-title').value = META_DATA.title || '';
    document.getElementById('meta-description').value = META_DATA.description || '';
    document.getElementById('meta-keywords').value = META_DATA.keywords || '';
    document.getElementById('meta-fokus-keyword').value = META_DATA.fokus_keyword || '';
    document.getElementById('meta-og-image').value = META_DATA.og_image || '';

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

  // Declare block arrays

  let aboutBlocks = [];
  let bannerBlocks = [];
  let blogBlocks = [];
  let contactBlocks = [];
  let counterBlocks = [];
  let footerBlocks = [];
  let galleryBlocks = [];
  let heroBlocks = [];
  let productBlocks = [];
  let reviewBlocks = [];
  let serviceBlocks = [];
  let socialBlocks = [];
  let stepBlocks = [];
  let subscribeBlocks = [];
  let teamBlocks = [];
  let visionBlocks = [];
  let whyBlocks = [];
  let workBlocks = [];

  // Load internal components (1 = has own CSS file)

  aboutBlocks = await loadComponents(aboutBlocks, 'about', 'About', 16, 0);
  bannerBlocks = await loadComponents(bannerBlocks, 'banner', 'Banner', 4, 0);
  blogBlocks = await loadComponents(blogBlocks, 'blog', 'Blog', 13, 0);
  contactBlocks = await loadComponents(contactBlocks, 'contact', 'Contact', 15, 0);
  counterBlocks = await loadComponents(counterBlocks, 'counter', 'Counter', 3, 0);
  footerBlocks = await loadComponents(footerBlocks, 'footer', 'Footer', 8, 0);
  galleryBlocks = await loadComponents(galleryBlocks, 'gallery', 'Gallery', 8, 0);       // fixed
  heroBlocks = await loadComponents(heroBlocks, 'hero', 'Hero', 12, 0);
  productBlocks = await loadComponents(productBlocks, 'product', 'Product', 13, 0);
  reviewBlocks = await loadComponents(reviewBlocks, 'review', 'Review', 18, 0);
  serviceBlocks = await loadComponents(serviceBlocks, 'service', 'Service', 23, 0);
  socialBlocks = await loadComponents(socialBlocks, 'social', 'Social', 1, 0);
  stepBlocks = await loadComponents(stepBlocks, 'step', 'Step', 5, 0);
  subscribeBlocks = await loadComponents(subscribeBlocks, 'subscribe', 'Subscribe', 6, 0);  // fixed
  teamBlocks = await loadComponents(teamBlocks, 'team', 'Team', 14, 0);
  visionBlocks = await loadComponents(visionBlocks, 'vision', 'Vision', 3, 0);
  whyBlocks = await loadComponents(whyBlocks, 'why', 'Why', 9, 0);
  workBlocks = await loadComponents(workBlocks, 'work', 'Work', 7, 0);

  // Base blocks
  const blocks = [
    { id: 'text', label: 'Text', category: 'Basic', content: '<p>Insert text here...</p>' },
    { id: 'heading', label: 'Heading', category: 'Basic', content: '<h1>Heading</h1>' },
    { id: 'button', label: 'Button', category: 'Basic', content: '<button>Click me</button>' },

    { id: 'image', label: 'Image', category: 'Media', content: { type: 'image' } },
    { id: 'video', label: 'Video', category: 'Media', content: '<video controls src="https://www.w3schools.com/html/mov_bbb.mp4" style="width:100%;"></video>' },
    { id: 'map', label: 'Google Map', category: 'Media', content: '<iframe src="https://maps.google.com/maps?q=London&t=&z=13&ie=UTF8&iwloc=&output=embed" style="width:100%; height:300px;" frameborder="0"></iframe>' },

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

    {
      id: 'row-cols-2',
      label: '2 Columns',
      category: 'Layout',
      content: {
        tagName: 'div',
        attributes: { style: 'display:flex; gap:10px; min-height:100px; border:1px dashed #bbb; padding:10px;' },
        components: [
          { tagName: 'div', attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' }, components: '<div style="text-align:center; color:#888;">Drop here (Left)</div>', droppable: true },
          { tagName: 'div', attributes: { style: 'flex:1; padding:10px; border:1px dashed #ccc; min-height:100px;' }, components: '<div style="text-align:center; color:#888;">Drop here (Right)</div>', droppable: true },
        ],
      },
    },

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

  // Load server components (Custom + Page)
  function loadCustomComponents(editor) {
    fetch('/admin/components/list', { cache: 'no-store' })
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.components)) return;
        const bm = editor.BlockManager;

        data.components.forEach(comp => {
          if (comp.category == 'Custom Components') {
            const wrappedHtml = `<div>
  <style>${comp.css || ''}</style>
  ${comp.html}</div>`;

            bm.add(`custom-${comp.id}`, {
              label: comp.name,
              category: comp.category || 'Custom Components',
              attributes: { class: 'fa fa-cube', 'data-bid': `custom-${comp.id}` },
              content: wrappedHtml,
              preview: `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(comp.html)}</div>`,
              hoverPreview: `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(comp.html)}</div>`
            });
          }
        });

        console.log('✅ Custom components loaded:', data.components.length);
      })
      .catch(err => console.error('Error loading components:', err));
  }

  function scopeCss(css, scopeClass) {
    if (!css) return '';
    return css.replace(/(^|})\s*([^@}{][^{]+){/g, (m, b, sel) => {
      const scoped = sel.split(',').map(s => `.${scopeClass} ${s.trim()}`).join(', ');
      return `${b} ${scoped}{`;
    });
  }

  function loadPageComponents(editor) {
    fetch('/admin/components/list', { cache: 'no-store' })
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.components)) return;
        const bm = editor.BlockManager;

        data.components.forEach(comp => {
          if (comp.category == 'Page Components') {
            const scopeClass = 'cmp-scope';
            const safeCss = comp.css ? scopeCss(comp.css, scopeClass) : '';
            // HTML only + script mounts CSS into iframe head once
            //             const content = `
            // <div class="page-component-wrapper ${scopeClass}" data-db-id="${comp.id}" data-comp-css-key="page-${comp.id}" data-comp-css="${encodeURIComponent(safeCss)}" Cname="${comp.name}">
            //   ${comp.html || ''}
            // </div>
            // <script>
            //   (function(){
            //     var root = this;
            //     var key = root.getAttribute('data-comp-css-key');
            //     var css = decodeURIComponent(root.getAttribute('data-comp-css')||'');
            //     if (!css || !key) return;
            //     var doc = document;
            //     if (!doc.getElementById('gjs-css-' + key)) {
            //       var style = doc.createElement('style');
            //       style.id = 'gjs-css-' + key;
            //       style.type = 'text/css';
            //       style.appendChild(doc.createTextNode(css));
            //       doc.head.appendChild(style);
            //     }
            //   }).call(this);
            // </script>`;
            const content = comp.html;

            bm.add(`page-${comp.id}`, {
              label: comp.name || `Page Components ${comp.id}`,
              category: '📄 Page Components',
              attributes: { class: 'fa fa-file', 'data-bid': `page-${comp.id}` },
              content,
              componentId: comp.id,
              componentName: comp.name,
              preview: `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(comp.html)}</div>`,
              hoverPreview: `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(comp.html)}</div>`
            });
          }
        });

        console.log('✅ Page components loaded:', data.components.length);
      })
      .catch(err => console.error('❌ Error loading page components:', err));
  }

  // Init editor with Vite Tailwind in iframe
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
      styles: [
        window.APP_CSS,
        '/css/builder_view.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css'
      ],
    },
    parser: { optionsHtml: { allowScripts: true } }, // enable only if you keep inline scripts in blocks
    plugins: ['grapesjs-plugin-code-editor'],
    pluginsOpts: { 'grapesjs-plugin-code-editor': {} },
  });
  window.editor = editor;
  enableBlockHoverPreview(editor);

  // Helper: code modal (unchanged)
  function openCodeModal(title, code, mode) {
    const modalEl = document.createElement('div');
    modalEl.style.cssText = `
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: #1e1e2f; z-index: 9999; display: flex; flex-direction: column; overflow: hidden;`;
    modalEl.innerHTML = `
    <div style="background:#111827;color:#fff;padding:10px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #333;flex-shrink:0;">
      <h4 style="margin:0;font-size:16px;">${title}</h4>
      <button id="close-code-view" style="background:#ef4444;border:none;color:#fff;padding:6px 12px;border-radius:4px;cursor:pointer;">Close</button>
    </div>
    <div id="code-editor-container" style="flex: 1; display: flex; overflow: hidden; width: 100%; height: 100%;">
      <textarea id="code-view-area" style="flex:1;width:100%;height:100%;border:none;outline:none;resize:none;font-size:14px;"></textarea>
    </div>`;
    document.body.appendChild(modalEl);
    const cm = CodeMirror.fromTextArea(document.getElementById('code-view-area'), {
      mode, theme: 'dracula', lineNumbers: true, lineWrapping: true, readOnly: true, viewportMargin: Infinity,
    });
    cm.setValue(code);
    setTimeout(() => {
      const cmEl = modalEl.querySelector('.CodeMirror');
      const container = document.getElementById('code-editor-container');
      Object.assign(container.style, { display: 'flex', flex: '1', width: '100%', height: '100%' });
      Object.assign(cmEl.style, { width: '100%', height: '100%', flex: '1', maxWidth: 'none', overflow: 'auto' });
      cm.refresh();
    }, 150);
    modalEl.querySelector('#close-code-view').addEventListener('click', () => {
      cm.toTextArea();
      modalEl.remove();
    });
  }

  loadCustomComponents(editor);
  loadPageComponents(editor);

  // Save selected element as reusable component (extract CSS correctly from iframe)
  editor.Commands.add('save-component', {
    run(editor) {
      const selected = editor.getSelected();
      if (!selected) return alert('Please select an element to save as a component.');

      const name = prompt('Enter component name:');
      if (!name) return;

      const id = selected.getId();
      const html = selected.toHTML();
      let css = '';

      // Try 1: match component CSS from global editor CSS
      const allCss = editor.getCss();
      const regex = new RegExp(`#${id}\\s*{[\\s\\S]*?}`, 'g');
      const matches = allCss.match(regex);
      if (matches && matches.length > 0) css = matches.join('\n\n');

      // Try 2: computed styles from iframe
      if (!css.trim()) {
        try {
          const frame = editor.Canvas.getFrameEl();
          const doc = frame?.contentDocument;
          const win = frame?.contentWindow;
          const el = doc?.querySelector(`#${id}`);
          if (el && win) {
            const styles = win.getComputedStyle(el);
            const includeProps = [
              'color', 'background-color', 'font-size', 'font-family', 'font-weight', 'text-align', 'margin', 'padding',
              'border', 'border-radius', 'width', 'height', 'display', 'justify-content', 'align-items', 'flex-direction',
              'gap', 'line-height', 'opacity', 'overflow', 'z-index', 'cursor', 'position', 'top', 'left', 'right', 'bottom',
              'transform', 'transition', 'box-shadow'
            ];
            css = `#${id} {\n`;
            for (const prop of includeProps) {
              const val = styles.getPropertyValue(prop);
              if (val && !['initial', 'auto', 'none', '0px', 'transparent'].includes(val)) {
                css += `  ${prop}: ${val};\n`;
              }
            }
            css += `}\n`;
          }
        } catch (e) {
          console.warn('Computed style extraction failed:', e);
        }
      }

      // Try 3: inline style map
      if (!css.trim()) {
        const styleObj = selected.getStyle();
        if (Object.keys(styleObj).length > 0) {
          css = `#${id} {\n`;
          for (const [key, val] of Object.entries(styleObj)) css += `  ${key}: ${val};\n`;
          css += `}\n`;
        }
      }
      if (!css.trim()) css = `#${id} {}`;

      fetch('/admin/components/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ name, category: 'Custom Components', html, css }),
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

  // Save button hook
  document.getElementById('btn-save-component').addEventListener('click', () => {
    editor.runCommand('save-component');
  });

  // Custom <section> type
  editor.DomComponents.addType('section', {
    model: {
      defaults: {
        droppable: true,
        editable: true,
        highlightable: true,
        draggable: '*:not(section)',
        attributes: { style: 'min-height:80px; border:1px dashed #ccc;' },
      },
    },
  });

  const bm = editor.BlockManager;

  // Custom editable-list type (unchanged)
  editor.DomComponents.addType('editable-list', {
    model: {
      defaults: {
        tagName: 'div',
        attributes: { class: 'editable-list-wrapper' },
        components: `
          <style>
            .editable-list-wrapper { display:block; padding:8px; border:1px dashed #e6e6e6; border-radius:6px; }
            .editable-list { padding-left: 1.25rem; margin: 0; }
            .editable-list li { margin: 6px 0; }
            .add-item-btn { display:inline-block; margin-top:8px; background:#4caf50; color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-weight:600; }
            .add-item-btn:hover { opacity:.95; }
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

  // Register blocks
  // Register blocks (adds data-bid so tiles are reliably identifiable)
  blocks.forEach(b => {
    const id = b.id;
    b.attributes = Object.assign({}, b.attributes, { 'data-bid': id });
    if (typeof b.content === 'string') {
      b.hoverPreview = `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(b.content)}</div>`;
    }
    bm.add(id, b);
  });

  [aboutBlocks, bannerBlocks, blogBlocks, contactBlocks, counterBlocks, footerBlocks,
    galleryBlocks, heroBlocks, productBlocks, reviewBlocks, serviceBlocks, socialBlocks,
    stepBlocks, subscribeBlocks, teamBlocks, visionBlocks, whyBlocks, workBlocks]
    .forEach(group => {
      group.forEach(b => {
        b.attributes = Object.assign({}, b.attributes, { 'data-bid': b.id });
        bm.add(b.id, b);
      });
    });





  editor.on('load', () => {
    const uiCat = bm.getCategories().find(cat => cat.id === 'UI');
    if (uiCat) {
      const heroBlks = bm.getAll().filter(b => {
        const cat = b.get('category');
        return cat && (cat.id === 'UI/Hero' || cat === 'UI/Hero');
      });
      heroBlks.forEach(b => b.set('category', uiCat));
    }
  });

  // Tabs (unchanged)
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

  // Load existing page data
  if (typeof PAGE_ID !== "undefined" && PAGE_HTML) {
    editor.setComponents(PAGE_HTML);
    editor.setStyle(PAGE_CSS);
  }

  // Save helpers
  async function savePageData(url) {
    const html = editor.getHtml();
    const css = editor.getCss();
    const meta = {
      meta_title: document.getElementById('meta-title').value,
      meta_description: document.getElementById('meta-description').value,
      meta_keywords: document.getElementById('meta-keywords').value,
      meta_og_image: document.getElementById('meta-og-image').value,
      meta_fokus_keyword: document.getElementById('meta-fokus-keyword').value,
    };
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title: document.getElementById('page-title').value,
        html, css, ...meta,
      }),
    });
    return await response.json();
  }

  let isSaving = false;
  // Put these helpers above savePageAsComponent
  async function savePageAsComponent(url) {
    if (isSaving) return;
    isSaving = true;

    try {
      //const html = cleanHtml(editor.getHtml());
      const html = editor.getHtml();
      const css = editor.getCss ? editor.getCss() : '';
      const js = editor.getJs ? editor.getJs() : '';

      // 🔍 1️⃣ Try to detect DB ID from hidden input
      let id = document.getElementById('component-id')?.value || null;


      // 🔍 2️⃣ If not found, check inside editor content for data-db-id
      if (!id) {
        const wrapper = editor.getWrapper();
        const dbWrapper = wrapper.find('.page-component-wrapper')[0]; // GrapesJS node
        if (dbWrapper) {
          id = dbWrapper.getAttributes()['data-db-id'] || null;
        }
      }

      const name = (document.getElementById('component-name')?.value || 'Untitled component').trim();

      console.log('🧩 Saving Component:', { id, name, html, css, js });

      const payload = {
        id, // ✅ ensures Laravel knows which record to update
        name,
        category: 'Page Components',
        html,
        js,
      };

      if (css && css.trim().length > 0) {
        payload.css = css;
      }

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      // ✅ Store back updated ID in hidden field (for next edit)
      if (data?.id) {
        let idInput = document.getElementById('component-id');
        if (!idInput) {
          idInput = document.createElement('input');
          idInput.type = 'hidden';
          idInput.id = 'component-id';
          document.body.appendChild(idInput);
        }
        idInput.value = data.id;
      }

      alert(data.message || 'Component saved successfully');
    } catch (e) {
      console.error(e);
      alert('Save failed');
    } finally {
      isSaving = false;
    }
  }

  function cleanHtml(html) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    tempDiv.querySelectorAll('meta, title, link').forEach(el => el.remove());
    return tempDiv.innerHTML;
  }

  // Save modal logic
  const modal = document.getElementById("saveOptionModal");
  const btnSave = document.getElementById("btn-save");
  const saveAsPageBtn = document.getElementById("saveAsPage");
  const saveAsComponentBtn = document.getElementById("saveAsComponent");
  const cancelSaveBtn = document.getElementById("cancelSave");

  if (!btnSave || !modal) {
    console.error("❌ Save button or modal not found in DOM!");
  } else {
    const showModal = () => {
      modal.classList.add("show");
      modal.style.alignItems = "center";
      modal.style.justifyContent = "center";
    };
    const hideModal = () => (modal.classList.remove("show"));

    btnSave.addEventListener("click", e => { e.preventDefault(); showModal(); });
    cancelSaveBtn?.addEventListener("click", hideModal);

    saveAsPageBtn?.addEventListener("click", async () => {
      hideModal();
      const result = await savePageData(`/pages/${PAGE_ID}/save`);
      if (result.success) alert('✅ Page saved successfully!');
    });

    saveAsComponentBtn?.addEventListener("click", async () => {
      hideModal();
      const result = await savePageAsComponent('/admin/components/saveAsComponent');
      if (result.success) alert('✅ Page As Component saved successfully!');
    });
  }

  // Preview / Publish
  document.getElementById('btn-preview').onclick = async () => {
    const result = await savePageData(`/pages/${PAGE_ID}/save`);
    if (result.success) window.open(`/preview/${PAGE_ID}`, '_blank');
  };
  document.getElementById('btn-publish').onclick = async () => {
    const result = await savePageData(`/pages/${PAGE_ID}/publish`);
    if (result.success) {
      alert('🚀 Page published successfully!');
      if (result.url) window.open(result.url, '_blank');
    }
  };

  // Code views
  document.getElementById('btn-html-view').addEventListener('click', () => {
    let htmlCode = editor.getHtml();
    if (typeof html_beautify !== 'undefined') {
      htmlCode = html_beautify(htmlCode, { indent_size: 2, wrap_line_length: 80 });
    }
    openCodeModal('HTML Code View', htmlCode, 'htmlmixed');
  });
  document.getElementById('btn-css-view').addEventListener('click', () => {
    let cssCode = editor.getCss();
    if (typeof css_beautify !== 'undefined') {
      cssCode = css_beautify(cssCode, { indent_size: 2, wrap_line_length: 80 });
    }
    openCodeModal('CSS Code View', cssCode, 'css');
  });

  // Sidebar show/hide
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

  // Hover preview box (safe)
  function enableBlockHoverPreview(editor) {
    const panel = document.getElementById('blocks');
    if (!panel) return; // [web:29]

    // Floating preview box (create once)
    let previewBox = document.getElementById('gjs-block-preview-box');
    if (!previewBox) {
      previewBox = document.createElement('div');
      previewBox.id = 'gjs-block-preview-box';
      previewBox.style.cssText = `
      position:fixed; z-index:99999; display:none; pointer-events:none;
      background:#fff; border:1px solid #ddd; border-radius:10px; padding:10px;
      box-shadow:0 8px 24px rgba(0,0,0,.18); max-width:360px; max-height:320px; overflow:auto;`;
      document.body.appendChild(previewBox);
    } // [web:29]

    const listSelector = '.gjs-blocks-c, .gjs-blocks-cs, .gjs-blocks-cw, .gjs-blocks, #blocks'; // all possible lists [web:1]

    const moveBox = (e) => {
      const pad = 14;
      const maxX = window.scrollX + window.innerWidth - (previewBox.offsetWidth + pad);
      const maxY = window.scrollY + window.innerHeight - (previewBox.offsetHeight + pad);
      previewBox.style.left = Math.min(e.pageX + pad, maxX) + 'px';
      previewBox.style.top = Math.min(e.pageY + pad, maxY) + 'px';
    }; // [web:29]

    const getPreviewHtml = (block) => {
      const hv = block?.get('hoverPreview');
      const pv = block?.get('preview');
      const ct = block?.get('content');
      if (typeof hv === 'string') return hv; // [web:1]
      if (typeof pv === 'string') return pv; // [web:1]
      if (typeof ct === 'string')
        return `<div style="width:240px;transform:scale(.5);transform-origin:top left;">${sanitizePreview(ct)}</div>`; // [web:1]
      return '<div style="padding:12px;color:#666;">Preview not available</div>'; // [web:1]
    };

    const setPreviewContent = (block) => {
      const html = getPreviewHtml(block);
      if (!window.APP_CSS) { previewBox.innerHTML = html; return; } // [web:29]
      previewBox.innerHTML = `<iframe style="width:360px;height:280px;border:0;border-radius:8px;background:#fff;"></iframe>`;
      const iframe = previewBox.querySelector('iframe');
      const doc = iframe.contentDocument;
      doc.open();
      doc.write(`
      <html>
        <head>
          <link rel="stylesheet" href="${window.APP_CSS}">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
          <style>body{margin:8px;font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial}</style>
        </head>
        <body>${html}</body>
      </html>
    `);
      doc.close();
    }; // [web:29][web:109][web:111]

    // Resolve model directly from stamped data-bid, with fallbacks
    const resolveBlock = (tile, listEl) => {
      const bid = tile?.dataset?.bid;
      if (bid) {
        const m = editor.BlockManager.get(bid);
        if (m) return m; // [web:1]
      }
      const idAttr = tile.getAttribute('data-id') || tile.id || tile.dataset?.id;
      if (idAttr) {
        const m = editor.BlockManager.get(idAttr);
        if (m) return m; // [web:1]
      }
      const tiles = Array.from(listEl.querySelectorAll('.gjs-block'));
      const idx = tiles.indexOf(tile);
      const all = editor.BlockManager.getAll();
      const labelEls = listEl.querySelectorAll('.gjs-block-label, .gjs-block__label, .gjs-title, .gjs-block .gjs-title');
      const text = labelEls[idx]?.textContent?.trim();
      if (text) {
        const match = all.find(b => (b.get('label') + '').trim() === text);
        if (match) return match; // [web:1]
      }
      return all.at(idx) || null; // [web:1]
    };

    // Single, panel-level delegation so EVERY category list works
    const onOver = (ev) => {
      const tile = ev.target.closest('.gjs-block');
      if (!tile || !panel.contains(tile)) return; // [web:1]
      const listEl = tile.closest(listSelector) || panel;
      const block = resolveBlock(tile, listEl);
      if (!block) return; // [web:1]
      setPreviewContent(block);
      previewBox.style.display = 'block';
      moveBox(ev);
    }; // [web:1][web:29]

    const onMove = (ev) => {
      if (previewBox.style.display === 'block') moveBox(ev);
    }; // [web:29]

    const onOut = (ev) => {
      const toEl = ev.relatedTarget;
      if (toEl && toEl.closest && toEl.closest('.gjs-block')) return;
      previewBox.style.display = 'none';
    }; // [web:29]

    // Clean old and bind new (idempotent)
    panel.removeEventListener('mouseover', onOver);
    panel.removeEventListener('mousemove', onMove);
    panel.removeEventListener('mouseout', onOut);
    panel.addEventListener('mouseover', onOver, { passive: true });
    panel.addEventListener('mousemove', onMove, { passive: true });
    panel.addEventListener('mouseout', onOut, { passive: true }); // [web:29]

    // Rebind when lists re-render (expand/collapse categories, lazy loads)
    const mo = new MutationObserver(() => {
      panel.removeEventListener('mouseover', onOver);
      panel.removeEventListener('mousemove', onMove);
      panel.removeEventListener('mouseout', onOut);
      panel.addEventListener('mouseover', onOver, { passive: true });
      panel.addEventListener('mousemove', onMove, { passive: true });
      panel.addEventListener('mouseout', onOut, { passive: true });
    });
    mo.observe(panel, { childList: true, subtree: true }); // [web:29]
  }

});
