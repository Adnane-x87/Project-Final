/**
 * Component Loader
 * Usage: <div data-component="navbar"></div>
 * With props: <div data-component="stat-card" data-icon="📅" data-value="24" data-label="Réservations"></div>
 */
async function loadComponents() {
  if (window.location.protocol === 'file:') {
    console.error("CORS Error: Components cannot be loaded when opening the HTML file directly (file://). Please use a local server (Live Server, python -m http.server 5500, etc.).");
    const warning = document.createElement("div");
    warning.style.cssText = "background: #f82; color: #fff; padding: 10px; text-align: center; border-radius: 4px; margin: 10px; font-weight: bold; position: sticky; top: 0; z-index: 9999;";
    warning.innerText = "⚠️ Attention : Les composants (navbar, etc.) ne peuvent pas se charger en ouvrant le fichier directement. Utilisez 'Live Server' ou un serveur local.";
    document.body.prepend(warning);
  }

  const elements = [...document.querySelectorAll("[data-component]")];

  for (const el of elements) {
    const name = el.dataset.component;
    try {
      // Use relative path to support subdirectories
      const res = await fetch(`./components/${name}.html`);
      if (!res.ok) {
        console.warn(`Component not found: ${name}`);
        continue;
      }

      let html = await res.text();

      // Replace [[key]] placeholders with data-* attributes
      Object.entries(el.dataset).forEach(([key, val]) => {
        html = html.replaceAll(`[[${key}]]`, val);
      });

      // Replace the placeholder element with the component HTML
      const tmp = document.createElement("div");
      tmp.innerHTML = html;
      el.replaceWith(...tmp.childNodes);
    } catch (e) {
      console.warn(`Failed to load component "${name}":`, e);
    }
  }

  // Highlight active nav link
  document.querySelectorAll(".nav-link[href]").forEach((a) => {
    const isCurrent =
      a.getAttribute("href") === location.pathname.split("/").pop() ||
      a.getAttribute("href") === "./" + location.pathname.split("/").pop();
    if (isCurrent) a.classList.add("active");
  });
}

document.addEventListener("DOMContentLoaded", loadComponents);
