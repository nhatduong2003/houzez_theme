(()=>{if(window._wpLoadBlockEditor){const e=window.wp.data.subscribe((()=>{const t="editorplus-template.php"===window.wp.data.select("core/editor").getEditedPostAttribute("template"),o=document.querySelector(".edit-post-visual-editor__post-title-wrapper"),r=document.querySelector(".editor-styles-wrapper");o&&r&&(t?(Promise.resolve().then((()=>o.style.display="none")),r.style.paddingTop="0",r.style.backgroundColor="#ffffff"):(o.style.removeProperty("display"),r.style.removeProperty("padding-top"),r.style.removeProperty("background-color")),e())}))}})();