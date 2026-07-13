// <shader-bg url="..."> — renders @shadergradient/react into this element.
// Requires an internet connection (loads React + the shader engine from esm.sh);
// the CSS gradient fallback on .tp-hero shows until it mounts.
customElements.define('shader-bg', class extends HTMLElement {
  connectedCallback() {
    if (this._started) return;
    this._started = true;
    this._mount();
  }
  async _mount() {
    try {
      const [React, { createRoot }, SG] = await Promise.all([
        import('https://esm.sh/react'),
        import('https://esm.sh/react-dom/client'),
        import('https://esm.sh/@shadergradient/react'),
      ]);
      const R = React.default ?? React;
      const inner = document.createElement('div');
      inner.style.cssText = 'position:absolute;inset:0;';
      this.appendChild(inner);
      const root = createRoot(inner);
      const url = this.getAttribute('url') || '';
      root.render(
        R.createElement(SG.ShaderGradientCanvas,
          { style: { position: 'absolute', inset: 0, width: '100%', height: '100%' }, pixelDensity: 1, fov: 45 },
          R.createElement(SG.ShaderGradient, { control: 'query', urlString: url })
        )
      );
    } catch (e) {
      console.warn('shader-bg failed to load, keeping CSS fallback', e && (e.message || String(e)));
    }
  }
});
