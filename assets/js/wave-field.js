/* <wave-field> — WebGL point-wave background, palette-driven via `colors` attr (comma-separated hex). */
(function () {
  if (customElements.get('wave-field')) return;

  var VERT = '#define M_PI 3.1415926535897932384626433832795\n' +
    'attribute vec3 a_position; attribute vec4 a_color;\n' +
    'uniform float u_time; uniform float u_speed; uniform float u_size; uniform vec3 u_field; uniform mat4 u_projection;\n' +
    'varying vec4 v_color;\n' +
    'void main() {\n' +
    '  vec3 pos = a_position;\n' +
    '  float waveX = cos((pos.x / u_field.x) * M_PI * 6.0 + u_time * u_speed);\n' +
    '  float waveZ = sin((pos.z / u_field.z) * M_PI * 6.0 + u_time * u_speed * 0.75);\n' +
    '  pos.y += (waveX + waveZ) * u_field.y;\n' +
    '  gl_Position = u_projection * vec4(pos, 1.0);\n' +
    '  float ps = u_size / max(gl_Position.w, 0.25);\n' +
    '  gl_PointSize = clamp(ps, 4.0, 120.0);\n' +
    '  v_color = a_color;\n' +
    '}';
  var FRAG = 'precision mediump float; varying vec4 v_color; uniform float u_glow;\n' +
    'void main() {\n' +
    '  vec2 uv = gl_PointCoord * 2.0 - 1.0;\n' +
    '  float dist = dot(uv, uv);\n' +
    '  if (dist > 1.0) discard;\n' +
    '  float falloff = pow(1.0 - clamp(dist, 0.0, 1.0), 2.3);\n' +
    '  gl_FragColor = vec4(v_color.rgb * (1.0 + falloff * u_glow), falloff * v_color.a);\n' +
    '}';

  function clamp01(v) { return Math.min(1, Math.max(0, v)); }
  function lerp(a, b, t) { return a + (b - a) * t; }
  function hexToRgb(h) {
    h = (h || '').trim().replace('#', '');
    if (h.length !== 6) return [0.5, 1, 0.85];
    var n = parseInt(h, 16);
    return [((n >> 16) & 255) / 255, ((n >> 8) & 255) / 255, (n & 255) / 255];
  }
  function perspective(out, fovy, aspect, near, far) {
    var f = 1 / Math.tan(fovy / 2), nf = 1 / (near - far);
    out.set([f / aspect, 0, 0, 0, 0, f, 0, 0, 0, 0, (far + near) * nf, -1, 0, 0, 2 * far * near * nf, 0]);
  }
  function lookAt(out, eye, center, up) {
    var z0 = eye[0] - center[0], z1 = eye[1] - center[1], z2 = eye[2] - center[2];
    var len = Math.hypot(z0, z1, z2);
    if (len === 0) { z2 = 1; } else { z0 /= len; z1 /= len; z2 /= len; }
    var x0 = up[1] * z2 - up[2] * z1, x1 = up[2] * z0 - up[0] * z2, x2 = up[0] * z1 - up[1] * z0;
    len = Math.hypot(x0, x1, x2);
    if (len === 0) { x0 = x1 = x2 = 0; } else { x0 /= len; x1 /= len; x2 /= len; }
    var y0 = z1 * x2 - z2 * x1, y1 = z2 * x0 - z0 * x2, y2 = z0 * x1 - z1 * x0;
    out.set([x0, y0, z0, 0, x1, y1, z1, 0, x2, y2, z2, 0,
      -(x0 * eye[0] + x1 * eye[1] + x2 * eye[2]),
      -(y0 * eye[0] + y1 * eye[1] + y2 * eye[2]),
      -(z0 * eye[0] + z1 * eye[1] + z2 * eye[2]), 1]);
  }
  function multiply(out, a, b) {
    for (var i = 0; i < 4; i++) {
      var b0 = b[i * 4], b1 = b[i * 4 + 1], b2 = b[i * 4 + 2], b3 = b[i * 4 + 3];
      out[i * 4] = b0 * a[0] + b1 * a[4] + b2 * a[8] + b3 * a[12];
      out[i * 4 + 1] = b0 * a[1] + b1 * a[5] + b2 * a[9] + b3 * a[13];
      out[i * 4 + 2] = b0 * a[2] + b1 * a[6] + b2 * a[10] + b3 * a[14];
      out[i * 4 + 3] = b0 * a[3] + b1 * a[7] + b2 * a[11] + b3 * a[15];
    }
  }

  var S = { fieldBase: 420, step: 6, amplitude: 22, baseHeight: -42, basePointSize: 28, speed: 0.6, fov: 52, cameraHeight: 82, cameraDistance: 320, maxDpr: 1.75, glow: 3.2 };

  class WaveField extends HTMLElement {
    static get observedAttributes() { return ['colors']; }
    attributeChangedCallback() { if (this._gl) this._rebuild(true); }

    connectedCallback() {
      var self = this;
      this.style.display = 'block';
      this.style.overflow = 'hidden';
      var c = document.createElement('canvas');
      c.style.cssText = 'display:block;width:100%;height:100%;';
      this.appendChild(c);
      this._canvas = c;
      this._w = 1; this._h = 1; this._dpr = 1; this._count = 0;
      this._pointer = { x: 0, y: 0, tx: 0, ty: 0 };
      this._layout = {};
      this._proj = new Float32Array(16); this._view = new Float32Array(16); this._vp = new Float32Array(16);
      this._field = new Float32Array([S.fieldBase, S.amplitude, S.fieldBase]);

      var gl = c.getContext('webgl', { alpha: true, antialias: true, premultipliedAlpha: false });
      if (!gl) return;
      this._gl = gl;
      var mk = function (type, src) {
        var sh = gl.createShader(type);
        gl.shaderSource(sh, src); gl.compileShader(sh);
        if (!gl.getShaderParameter(sh, gl.COMPILE_STATUS)) { console.error('wave-field shader:', gl.getShaderInfoLog(sh)); return null; }
        return sh;
      };
      var vs = mk(gl.VERTEX_SHADER, VERT), fs = mk(gl.FRAGMENT_SHADER, FRAG);
      if (!vs || !fs) return;
      var p = gl.createProgram();
      gl.attachShader(p, vs); gl.attachShader(p, fs); gl.linkProgram(p);
      gl.deleteShader(vs); gl.deleteShader(fs);
      if (!gl.getProgramParameter(p, gl.LINK_STATUS)) { console.error('wave-field link:', gl.getProgramInfoLog(p)); return; }
      gl.useProgram(p);
      this._prog = p;
      this._aPos = gl.getAttribLocation(p, 'a_position');
      this._aCol = gl.getAttribLocation(p, 'a_color');
      this._u = {
        time: gl.getUniformLocation(p, 'u_time'), speed: gl.getUniformLocation(p, 'u_speed'),
        size: gl.getUniformLocation(p, 'u_size'), field: gl.getUniformLocation(p, 'u_field'),
        proj: gl.getUniformLocation(p, 'u_projection'), glow: gl.getUniformLocation(p, 'u_glow')
      };
      this._bufPos = gl.createBuffer(); this._bufCol = gl.createBuffer();
      gl.bindBuffer(gl.ARRAY_BUFFER, this._bufPos);
      gl.vertexAttribPointer(this._aPos, 3, gl.FLOAT, false, 0, 0);
      gl.enableVertexAttribArray(this._aPos);
      gl.bindBuffer(gl.ARRAY_BUFFER, this._bufCol);
      gl.vertexAttribPointer(this._aCol, 4, gl.FLOAT, false, 0, 0);
      gl.enableVertexAttribArray(this._aCol);
      gl.enable(gl.BLEND);
      gl.blendFunc(gl.SRC_ALPHA, gl.ONE);
      gl.disable(gl.DEPTH_TEST);

      this._onResize = function () { self._resize(); };
      this._onMove = function (e) {
        self._pointer.tx = (e.clientX / window.innerWidth) * 2 - 1;
        self._pointer.ty = (e.clientY / window.innerHeight) * 2 - 1;
      };
      this._onTouch = function (e) {
        if (!e.touches || !e.touches.length) return;
        self._onMove(e.touches[0]);
      };
      this._resize();
      if (typeof ResizeObserver !== 'undefined') {
        this._ro = new ResizeObserver(this._onResize);
        this._ro.observe(this);
      }
      window.addEventListener('resize', this._onResize, { passive: true });
      window.addEventListener('pointermove', this._onMove, { passive: true });
      window.addEventListener('touchmove', this._onTouch, { passive: true });
      this._t0 = performance.now();
      var loop = function (now) {
        self._frame = requestAnimationFrame(loop);
        self._draw(now);
      };
      this._frame = requestAnimationFrame(loop);
    }

    disconnectedCallback() {
      window.removeEventListener('resize', this._onResize);
      window.removeEventListener('pointermove', this._onMove);
      window.removeEventListener('touchmove', this._onTouch);
      if (this._ro) { this._ro.disconnect(); this._ro = null; }
      if (this._frame) cancelAnimationFrame(this._frame);
      var gl = this._gl;
      if (gl) {
        if (this._bufPos) gl.deleteBuffer(this._bufPos);
        if (this._bufCol) gl.deleteBuffer(this._bufCol);
        if (this._prog) gl.deleteProgram(this._prog);
      }
      this._gl = null; this._prog = null;
    }

    _palette() {
      var raw = (this.getAttribute('colors') || '#94ffd1,#6bf5ff,#ffffff').split(',');
      var out = [];
      for (var i = 0; i < raw.length; i++) { if (raw[i].trim()) out.push(hexToRgb(raw[i])); }
      while (out.length < 2) out.push([1, 1, 1]);
      return out;
    }

    _resize() {
      var gl = this._gl, c = this._canvas;
      if (!gl || !c) return;
      var r = this.getBoundingClientRect();
      this._w = Math.max(1, r.width); this._h = Math.max(1, r.height);
      this._dpr = Math.min(window.devicePixelRatio || 1, S.maxDpr);
      var cw = Math.floor(this._w * this._dpr), ch = Math.floor(this._h * this._dpr);
      if (c.width !== cw || c.height !== ch) {
        c.width = cw; c.height = ch;
        gl.viewport(0, 0, cw, ch);
      }
      this._rebuild(false);
    }

    _rebuild(onlyColors) {
      var gl = this._gl;
      if (!gl || !this._prog) return;
      var aspect = this._h === 0 ? 1 : this._w / this._h;
      var step = S.step;
      var xSeg = Math.max(24, Math.round((S.fieldBase * Math.max(1, aspect)) / step));
      var zSeg = Math.max(24, Math.round(S.fieldBase / step));
      var L = this._layout;
      L.xCount = xSeg + 1; L.zCount = zSeg + 1;
      L.width = xSeg * step; L.depth = zSeg * step;
      L.xMin = -L.width * 0.5; L.zMin = -L.depth * 0.5;
      this._count = L.xCount * L.zCount;
      this._field[0] = L.width; this._field[1] = S.amplitude; this._field[2] = L.depth;

      if (!onlyColors) {
        var pos = new Float32Array(this._count * 3), ptr = 0;
        for (var xi = 0; xi < L.xCount; xi++) {
          var x = L.xMin + xi * step;
          for (var zi = 0; zi < L.zCount; zi++) {
            pos[ptr++] = x; pos[ptr++] = S.baseHeight; pos[ptr++] = L.zMin + zi * step;
          }
        }
        gl.bindBuffer(gl.ARRAY_BUFFER, this._bufPos);
        gl.bufferData(gl.ARRAY_BUFFER, pos, gl.DYNAMIC_DRAW);
      }

      var pal = this._palette();
      var c0 = pal[0], c1 = pal[1];
      var cols = new Float32Array(this._count * 4), cp = 0;
      for (var xj = 0; xj < L.xCount; xj++) {
        var t = L.xCount > 1 ? xj / (L.xCount - 1) : 0;
        for (var zj = 0; zj < L.zCount; zj++) {
          var d = L.zCount > 1 ? zj / (L.zCount - 1) : 0;
          var wave = Math.sin((t + d * 0.65) * Math.PI * 1.5);
          var mix = clamp01(0.25 + 0.55 * t + 0.2 * wave);
          var fade = 0.55 + 0.35 * (1 - d);
          cols[cp++] = clamp01(lerp(c0[0], c1[0], mix) * fade);
          cols[cp++] = clamp01(lerp(c0[1], c1[1], mix) * (0.92 + 0.08 * Math.sin(d * 6)));
          cols[cp++] = clamp01(lerp(c0[2], c1[2], mix) * (0.85 + 0.12 * Math.cos(t * 5)));
          cols[cp++] = clamp01(0.9 + (1 - d) * 0.1);
        }
      }
      gl.bindBuffer(gl.ARRAY_BUFFER, this._bufCol);
      gl.bufferData(gl.ARRAY_BUFFER, cols, gl.DYNAMIC_DRAW);
    }

    _draw(now) {
      var gl = this._gl;
      if (!gl || !this._prog) return;
      var P = this._pointer;
      P.x += (P.tx - P.x) * 0.08;
      P.y += (P.ty - P.y) * 0.08;
      var elapsed = (now - this._t0) * 0.001;
      var aspect = this._h === 0 ? 1 : this._w / this._h;
      perspective(this._proj, (S.fov * Math.PI) / 180, aspect, 0.1, 1000);
      lookAt(this._view,
        [P.x * 32, S.cameraHeight - P.y * 24, S.cameraDistance - P.y * 60],
        [P.x * 20, S.baseHeight, 0], [0, 1, 0]);
      multiply(this._vp, this._proj, this._view);
      gl.clearColor(0, 0, 0, 0);
      gl.clear(gl.COLOR_BUFFER_BIT);
      gl.uniform1f(this._u.time, elapsed);
      gl.uniform1f(this._u.speed, S.speed);
      gl.uniform1f(this._u.size, S.basePointSize * this._dpr);
      gl.uniform3fv(this._u.field, this._field);
      gl.uniformMatrix4fv(this._u.proj, false, this._vp);
      gl.uniform1f(this._u.glow, S.glow);
      gl.drawArrays(gl.POINTS, 0, this._count);
    }
  }

  customElements.define('wave-field', WaveField);
})();
