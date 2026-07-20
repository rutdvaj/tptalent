/* <lightfall-bg colors="#94ffd1,#6bf5ff,#ffffff" bg-color="#16483B"> —
   WebGL "lightfall" streaks background (ported from the Lightfall Hero DC, ogl-based).
   Palette-driven via `colors` (comma-separated hex), same contract as <wave-field>.
   Renders a transparent canvas so the host element's own background shows through. */
(function () {
  if (customElements.get('lightfall-bg')) return;

  var MAX_COLORS = 8;

  function hexToRGB(hex) {
    var c = (hex || '').trim().replace('#', '');
    if (c.length === 3) c = c[0]+c[0]+c[1]+c[1]+c[2]+c[2];
    c = c.padEnd(6, '0');
    return [parseInt(c.slice(0,2),16)/255, parseInt(c.slice(2,4),16)/255, parseInt(c.slice(4,6),16)/255];
  }

  function prepColors(input) {
    var base = (input && input.length ? input : ['#94ffd1','#6bf5ff','#ffffff']).slice(0, MAX_COLORS);
    var count = base.length;
    var arr = [];
    for (var i = 0; i < MAX_COLORS; i++) arr.push(hexToRGB(base[Math.min(i, base.length - 1)]));
    var avg = [0,0,0];
    for (var j = 0; j < count; j++) { avg[0]+=arr[j][0]; avg[1]+=arr[j][1]; avg[2]+=arr[j][2]; }
    avg[0]/=count; avg[1]/=count; avg[2]/=count;
    return { arr: arr, count: count, avg: avg };
  }

  var vertex = [
    'attribute vec2 position;',
    'attribute vec2 uv;',
    'varying vec2 vUv;',
    'void main() { vUv = uv; gl_Position = vec4(position, 0.0, 1.0); }'
  ].join('\n');

  var fragment = [
    'precision highp float;',
    'uniform vec3 iResolution;',
    'uniform vec2 iMouse;',
    'uniform float iTime;',
    'uniform vec3 uColor0; uniform vec3 uColor1; uniform vec3 uColor2; uniform vec3 uColor3;',
    'uniform vec3 uColor4; uniform vec3 uColor5; uniform vec3 uColor6; uniform vec3 uColor7;',
    'uniform int uColorCount;',
    'uniform vec3 uBgColor; uniform vec3 uMouseColor;',
    'uniform float uSpeed; uniform int uStreakCount; uniform float uStreakWidth;',
    'uniform float uStreakLength; uniform float uGlow; uniform float uDensity;',
    'uniform float uTwinkle; uniform float uZoom; uniform float uBgGlow; uniform float uOpacity;',
    'uniform float uMouseEnabled; uniform float uMouseStrength; uniform float uMouseRadius;',
    'varying vec2 vUv;',
    'vec3 palette(float h) {',
    '  int count = uColorCount; if (count < 1) count = 1;',
    '  int idx = int(floor(clamp(h, 0.0, 0.999999) * float(count)));',
    '  if (idx <= 0) return uColor0;',
    '  if (idx == 1) return uColor1;',
    '  if (idx == 2) return uColor2;',
    '  if (idx == 3) return uColor3;',
    '  if (idx == 4) return uColor4;',
    '  if (idx == 5) return uColor5;',
    '  if (idx == 6) return uColor6;',
    '  return uColor7;',
    '}',
    'vec3 tanhv(vec3 x) { vec3 e = exp(-2.0 * x); return (1.0 - e) / (1.0 + e); }',
    'vec2 sceneC(vec2 frag, vec2 r) {',
    '  vec2 P = (frag + frag - r) / r.x;',
    '  float z = 0.0; float d = 1e3; vec4 O = vec4(0.0);',
    '  for (int k = 0; k < 39; k++) {',
    '    if (d <= 1e-4) break;',
    '    O = z * normalize(vec4(P, uZoom, 0.0)) - vec4(0.0, 4.0, 1.0, 0.0) / 4.5;',
    '    d = 1.0 - sqrt(length(O * O));',
    '    z += d;',
    '  }',
    '  return vec2(O.x, atan(O.z, O.y));',
    '}',
    'void mainImage(out vec4 o, vec2 C) {',
    '  vec2 r = iResolution.xy;',
    '  vec2 uv0 = (C + C - r) / r.x;',
    '  float T = 0.1 * iTime * uSpeed + 9.0;',
    '  float angRings = max(1.0, floor(6.28318530718 * max(uDensity, 0.05) + 0.5));',
    '  vec2 Y = vec2(5e-3, 6.28318530718 / angRings);',
    '  vec2 c0 = sceneC(C, r);',
    '  vec2 cdx = sceneC(C + vec2(1.0, 0.0), r);',
    '  vec2 cdy = sceneC(C + vec2(0.0, 1.0), r);',
    '  vec2 dCx = cdx - c0; vec2 dCy = cdy - c0;',
    '  dCx.y -= 6.28318530718 * floor(dCx.y / 6.28318530718 + 0.5);',
    '  dCy.y -= 6.28318530718 * floor(dCy.y / 6.28318530718 + 0.5);',
    '  vec2 fw = abs(dCx) + abs(dCy);',
    '  C = c0;',
    '  vec2 P = vec2(2.0, 1.0) * uv0 - (r / r.x) * vec2(0.0, 1.0);',
    '  vec4 O = vec4(uBgColor * 90.0 * uBgGlow / (1e3 * dot(P, P) + 6.0), 0.0);',
    '  float mGlow = 0.0;',
    '  if (uMouseEnabled > 0.5) {',
    '    vec2 mN = (iMouse + iMouse - r) / r.x;',
    '    float md = length(uv0 - mN);',
    '    mGlow = exp(-md * md / max(uMouseRadius * uMouseRadius, 1e-4)) * uMouseStrength;',
    '    O.rgb += uMouseColor * mGlow * 0.25;',
    '  }',
    '  float zr = 5e-4 * uStreakWidth;',
    '  vec2 rr = vec2(max(length(fw), 1e-5));',
    '  float tail = 19.0 / max(uStreakLength, 0.05);',
    '  for (int m = 0; m < 16; m++) {',
    '    if (m >= uStreakCount) break;',
    '    float jf = float(m) + 1.0;',
    '    float ic = fract(sin(dot(vec2(jf, floor(C.x / Y.x + 0.5)), vec2(7.0, 11.0)) * 73.0));',
    '    vec2 Pp = C - (T + T * ic) * vec2(0.0, 1.0);',
    '    Pp -= floor(Pp / Y + 0.5) * Y;',
    '    float h = fract(8663.0 * ic);',
    '    vec3 col = palette(h);',
    '    float weight = mix(1.5, 1.0 + sin(T + 7.0 * h + 4.0), uTwinkle);',
    '    weight *= (1.0 + mGlow * 2.0);',
    '    vec2 inner = vec2(length(max(Pp, vec2(-1.0, 0.0))), length(Pp) - zr) - zr;',
    '    vec2 sm = vec2(1.0) - smoothstep(-rr, rr, inner);',
    '    O.rgb += dot(sm, vec2(exp(tail * Pp.y), 3.0)) * col * weight;',
    '    C.x += Y.x / 8.0;',
    '  }',
    '  vec3 colr = sqrt(tanhv(max(O.rgb * uGlow - vec3(0.05, 0.03, 0.11), 0.0)));',
    '  o = vec4(colr, uOpacity);',
    '}',
    'void main() {',
    '  vec4 color;',
    '  mainImage(color, vUv * iResolution.xy);',
    '  gl_FragColor = color;',
    '}'
  ].join('\n');

  // Defaults mirror the Lightfall Hero DC's default props.
  var D = {
    colors: ['#94ffd1', '#6bf5ff', '#ffffff'],
    backgroundColor: '#0E5C43',
    speed: 0.5,
    streakCount: 2,
    streakWidth: 1,
    streakLength: 1,
    glow: 1,
    density: 0.6,
    twinkle: 1,
    zoom: 3,
    backgroundGlow: 0.5,
    opacity: 1,
    mouseInteraction: false,
    mouseStrength: 0.5,
    mouseRadius: 1,
    mouseDampening: 0.15
  };

  class LightfallBg extends HTMLElement {
    static get observedAttributes() { return ['colors', 'bg-color']; }

    attributeChangedCallback() {
      if (!this._uniforms) return;
      var pc = prepColors(this._colorList());
      var u = this._uniforms;
      u.uColor0.value = pc.arr[0]; u.uColor1.value = pc.arr[1]; u.uColor2.value = pc.arr[2]; u.uColor3.value = pc.arr[3];
      u.uColor4.value = pc.arr[4]; u.uColor5.value = pc.arr[5]; u.uColor6.value = pc.arr[6]; u.uColor7.value = pc.arr[7];
      u.uColorCount.value = pc.count;
      u.uMouseColor.value = pc.avg;
      u.uBgColor.value = hexToRGB(this.getAttribute('bg-color') || this.bgColor || D.backgroundColor);
    }

    _colorList() {
      var raw = (this.getAttribute('colors') || D.colors.join(',')).split(',');
      var out = [];
      for (var i = 0; i < raw.length; i++) { if (raw[i].trim()) out.push(raw[i].trim()); }
      return out.length ? out : D.colors.slice();
    }

    async connectedCallback() {
      var self = this;
      this.style.display = 'block';
      this.style.overflow = 'hidden';
      // Self-position as a full-cover background layer (mount style isn't always forwarded).
      if (!this.style.position || this.style.position === 'static') this.style.position = 'absolute';
      if (this.style.position === 'absolute' || this.style.position === 'fixed') {
        this.style.top = '0'; this.style.left = '0'; this.style.right = '0'; this.style.bottom = '0';
        if (this.style.zIndex === '') this.style.zIndex = '0';
      }
      this.style.width = '100%';
      this.style.height = '100%';

      var ogl;
      try {
        ogl = await import('https://cdn.jsdelivr.net/npm/ogl@1.0.11/+esm');
      } catch (e) {
        console.error('lightfall-bg: failed to load ogl', e);
        return;
      }
      if (!this.isConnected) return;

      var Renderer = ogl.Renderer, Program = ogl.Program, Mesh = ogl.Mesh, Triangle = ogl.Triangle;
      var renderer = new Renderer({ dpr: Math.min(window.devicePixelRatio || 1, 2), alpha: true, antialias: true, preserveDrawingBuffer: true });
      var gl = renderer.gl;
      var canvas = gl.canvas;
      canvas.style.cssText = 'display:block;width:100%;height:100%;';
      this.appendChild(canvas);
      this._renderer = renderer;

      var pc = prepColors(this._colorList());
      var uniforms = {
        iResolution: { value: [gl.drawingBufferWidth, gl.drawingBufferHeight, 1] },
        iMouse: { value: [0, 0] },
        iTime: { value: 0 },
        uColor0: { value: pc.arr[0] }, uColor1: { value: pc.arr[1] }, uColor2: { value: pc.arr[2] }, uColor3: { value: pc.arr[3] },
        uColor4: { value: pc.arr[4] }, uColor5: { value: pc.arr[5] }, uColor6: { value: pc.arr[6] }, uColor7: { value: pc.arr[7] },
        uColorCount: { value: pc.count },
        uBgColor: { value: hexToRGB(this.getAttribute('bg-color') || this.bgColor || D.backgroundColor) },
        uMouseColor: { value: pc.avg },
        uSpeed: { value: D.speed },
        uStreakCount: { value: Math.max(1, Math.min(16, Math.round(D.streakCount))) },
        uStreakWidth: { value: D.streakWidth },
        uStreakLength: { value: D.streakLength },
        uGlow: { value: D.glow },
        uDensity: { value: D.density },
        uTwinkle: { value: D.twinkle },
        uZoom: { value: D.zoom },
        uBgGlow: { value: D.backgroundGlow },
        uOpacity: { value: D.opacity },
        uMouseEnabled: { value: D.mouseInteraction ? 1 : 0 },
        uMouseStrength: { value: D.mouseStrength },
        uMouseRadius: { value: D.mouseRadius }
      };
      this._uniforms = uniforms;

      var program = new Program(gl, { vertex: vertex, fragment: fragment, uniforms: uniforms });
      var mesh = new Mesh(gl, { geometry: new Triangle(gl), program: program });

      var resize = function () {
        var rect = self.getBoundingClientRect();
        renderer.setSize(Math.max(1, rect.width), Math.max(1, rect.height));
        uniforms.iResolution.value = [gl.drawingBufferWidth, gl.drawingBufferHeight, 1];
      };
      resize();
      if (typeof ResizeObserver !== 'undefined') { this._ro = new ResizeObserver(resize); this._ro.observe(this); }

      var mouseTarget = [0, 0], lastTime = 0;
      this._onMove = function (e) {
        var rect = canvas.getBoundingClientRect();
        var scale = renderer.dpr || 1;
        mouseTarget = [(e.clientX - rect.left) * scale, (rect.height - (e.clientY - rect.top)) * scale];
        if (D.mouseDampening <= 0) uniforms.iMouse.value = mouseTarget;
      };
      if (D.mouseInteraction) window.addEventListener('pointermove', this._onMove, { passive: true });

      var loop = function (t) {
        self._raf = requestAnimationFrame(loop);
        uniforms.iTime.value = t * 0.001;
        if (D.mouseDampening > 0) {
          if (!lastTime) lastTime = t;
          var dt = (t - lastTime) / 1000; lastTime = t;
          var tau = Math.max(1e-4, D.mouseDampening);
          var factor = 1 - Math.exp(-dt / tau); if (factor > 1) factor = 1;
          var cur = uniforms.iMouse.value;
          cur[0] += (mouseTarget[0] - cur[0]) * factor;
          cur[1] += (mouseTarget[1] - cur[1]) * factor;
        } else lastTime = t;
        try { renderer.render({ scene: mesh }); } catch (e) { console.error(e); }
      };
      this._canvas = canvas;
      this._raf = requestAnimationFrame(loop);
    }

    disconnectedCallback() {
      if (this._raf) cancelAnimationFrame(this._raf);
      if (this._onMove) window.removeEventListener('pointermove', this._onMove);
      if (this._ro) { this._ro.disconnect(); this._ro = null; }
      if (this._canvas && this._canvas.parentElement === this) this.removeChild(this._canvas);
      this._uniforms = null;
    }
  }

  customElements.define('lightfall-bg', LightfallBg);
})();
