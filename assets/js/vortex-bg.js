/* <vortex-bg colors="#94ffd1,#6bf5ff,#ffffff"> — self-contained Canvas 2D
   particle flow-field ("vortex") background, ported from the Sea Mint
   Tecno Prism Vortex handoff. No external deps (the source design loads
   simplex-noise from esm.sh at runtime; this inlines a small public-
   domain 3D simplex noise implementation instead — same self-hosted
   approach as shader-bg.js, just without needing a build step since
   this one's tiny). Palette-driven via `colors` (first two hex values
   set the hue sweep), responsive via ResizeObserver, and reacts to the
   pointer: particles swirl around the cursor within a radius.
*/
(function () {
  if (customElements.get('vortex-bg')) return;

  function mulberry32(a) {
    return function () {
      a |= 0; a = (a + 0x6D2B79F5) | 0;
      var t = Math.imul(a ^ (a >>> 15), 1 | a);
      t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
      return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
  }

  function makeNoise3D(seed) {
    var random = mulberry32(seed || 1);
    var p = new Uint8Array(256);
    for (var i = 0; i < 256; i++) p[i] = i;
    for (var i = 255; i > 0; i--) {
      var n = Math.floor((i + 1) * random());
      var q = p[i]; p[i] = p[n]; p[n] = q;
    }
    var perm = new Uint8Array(512), permMod12 = new Uint8Array(512);
    for (var i = 0; i < 512; i++) { perm[i] = p[i & 255]; permMod12[i] = perm[i] % 12; }
    var grad3 = new Float32Array([1,1,0, -1,1,0, 1,-1,0, -1,-1,0, 1,0,1, -1,0,1, 1,0,-1, -1,0,-1, 0,1,1, 0,-1,1, 0,1,-1, 0,-1,-1]);
    var F3 = 1 / 3, G3 = 1 / 6;
    return function (xin, yin, zin) {
      var s = (xin + yin + zin) * F3;
      var i = Math.floor(xin + s), j = Math.floor(yin + s), k = Math.floor(zin + s);
      var t = (i + j + k) * G3;
      var X0 = i - t, Y0 = j - t, Z0 = k - t;
      var x0 = xin - X0, y0 = yin - Y0, z0 = zin - Z0;
      var i1, j1, k1, i2, j2, k2;
      if (x0 >= y0) {
        if (y0 >= z0) { i1=1;j1=0;k1=0; i2=1;j2=1;k2=0; }
        else if (x0 >= z0) { i1=1;j1=0;k1=0; i2=1;j2=0;k2=1; }
        else { i1=0;j1=0;k1=1; i2=1;j2=0;k2=1; }
      } else {
        if (y0 < z0) { i1=0;j1=0;k1=1; i2=0;j2=1;k2=1; }
        else if (x0 < z0) { i1=0;j1=1;k1=0; i2=0;j2=1;k2=1; }
        else { i1=0;j1=1;k1=0; i2=1;j2=1;k2=0; }
      }
      var x1=x0-i1+G3, y1=y0-j1+G3, z1=z0-k1+G3;
      var x2=x0-i2+2*G3, y2=y0-j2+2*G3, z2=z0-k2+2*G3;
      var x3=x0-1+3*G3, y3=y0-1+3*G3, z3=z0-1+3*G3;
      var ii=i&255, jj=j&255, kk=k&255;
      var gi0=permMod12[ii+perm[jj+perm[kk]]]*3;
      var gi1=permMod12[ii+i1+perm[jj+j1+perm[kk+k1]]]*3;
      var gi2=permMod12[ii+i2+perm[jj+j2+perm[kk+k2]]]*3;
      var gi3=permMod12[ii+1+perm[jj+1+perm[kk+1]]]*3;
      var n0=0,n1=0,n2=0,n3=0;
      var t0=0.6-x0*x0-y0*y0-z0*z0;
      if (t0>=0) { t0*=t0; n0=t0*t0*(grad3[gi0]*x0+grad3[gi0+1]*y0+grad3[gi0+2]*z0); }
      var t1=0.6-x1*x1-y1*y1-z1*z1;
      if (t1>=0) { t1*=t1; n1=t1*t1*(grad3[gi1]*x1+grad3[gi1+1]*y1+grad3[gi1+2]*z1); }
      var t2=0.6-x2*x2-y2*y2-z2*z2;
      if (t2>=0) { t2*=t2; n2=t2*t2*(grad3[gi2]*x2+grad3[gi2+1]*y2+grad3[gi2+2]*z2); }
      var t3=0.6-x3*x3-y3*y3-z3*z3;
      if (t3>=0) { t3*=t3; n3=t3*t3*(grad3[gi3]*x3+grad3[gi3+1]*y3+grad3[gi3+2]*z3); }
      return 32*(n0+n1+n2+n3);
    };
  }

  function hexToHue(hex) {
    hex = (hex || '').trim().replace('#', '');
    if (hex.length === 3) hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
    var n = parseInt(hex || '94ffd1', 16);
    var r = ((n >> 16) & 255) / 255, g = ((n >> 8) & 255) / 255, b = (n & 255) / 255;
    var max = Math.max(r,g,b), min = Math.min(r,g,b), h = 0, d = max - min;
    if (d !== 0) {
      if (max === r) h = ((g - b) / d) % 6;
      else if (max === g) h = (b - r) / d + 2;
      else h = (r - g) / d + 4;
      h *= 60;
      if (h < 0) h += 360;
    }
    return h;
  }

  var TAU = 2 * Math.PI;

  customElements.define('vortex-bg', class extends HTMLElement {
    connectedCallback() {
      this.style.display = 'block';
      this.style.overflow = 'hidden';
      if (!this.style.position || this.style.position === 'static') this.style.position = 'absolute';
      if (this.style.position === 'absolute' || this.style.position === 'fixed') {
        this.style.top = '0'; this.style.left = '0'; this.style.right = '0'; this.style.bottom = '0';
        if (this.style.zIndex === '') this.style.zIndex = '0';
      }
      this.style.width = '100%';
      this.style.height = '100%';

      var canvas = document.createElement('canvas');
      canvas.style.cssText = 'display:block;width:100%;height:100%;';
      this.appendChild(canvas);
      this._canvas = canvas;
      var ctx = canvas.getContext('2d');
      this._ctx = ctx;

      this._noise3D = makeNoise3D(7);
      this._tick = 0;
      this._center = [0, 0];
      this._mouse = { x: 0, y: 0, active: 0 };

      this._onMove = function (e) {
        var rect = canvas.getBoundingClientRect();
        this._mouse.x = e.clientX - rect.left;
        this._mouse.y = e.clientY - rect.top;
        this._mouse.active = 1;
      }.bind(this);
      this._onLeave = function () { this._mouse.active = 0; }.bind(this);
      this.addEventListener('pointermove', this._onMove);
      this.addEventListener('pointerleave', this._onLeave);

      this._resize = this._resize.bind(this);
      this._resize();
      if (typeof ResizeObserver !== 'undefined') { this._ro = new ResizeObserver(this._resize); this._ro.observe(this); }
      window.addEventListener('resize', this._resize);

      this._initParticles();
      this._loop = this._loop.bind(this);
      this._raf = requestAnimationFrame(this._loop);
    }

    disconnectedCallback() {
      if (this._raf) cancelAnimationFrame(this._raf);
      if (this._ro) this._ro.disconnect();
      window.removeEventListener('resize', this._resize);
      this.removeEventListener('pointermove', this._onMove);
      this.removeEventListener('pointerleave', this._onLeave);
    }

    get cfg() {
      var colors = (this.getAttribute('colors') || '#94ffd1,#6bf5ff,#ffffff').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
      // Anchor the sweep's start hue to the palette's first color, but
      // keep the sweep's width fixed at the design's own tuned value —
      // deriving it from just 2 close-together palette hues (mint to
      // cyan is only ~30°) produced a visibly narrower, flatter-looking
      // color range than intended.
      var hue1 = hexToHue(colors[0]);
      var rangeHue = 78;
      var countAttr = parseFloat(this.getAttribute('particle-count'));
      return {
        particleCount: isNaN(countAttr) ? 720 : countAttr,
        particlePropCount: 9,
        rangeY: 120,
        baseTTL: 50, rangeTTL: 150,
        baseSpeed: 0.6, rangeSpeed: 3.2,
        baseRadius: 1.5, rangeRadius: 3.6,
        baseHue: hue1, rangeHue: rangeHue,
        noiseSteps: 3,
        xOff: 0.00125, yOff: 0.00125, zOff: 0.0006,
        mouseRadius: 200, mouseStrength: 0.85
      };
    }

    _resize() {
      var canvas = this._canvas;
      var rect = this.getBoundingClientRect();
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      var w = Math.max(1, Math.floor(rect.width)), h = Math.max(1, Math.floor(rect.height));
      canvas.width = w * dpr; canvas.height = h * dpr;
      canvas.style.width = w + 'px'; canvas.style.height = h + 'px';
      this._ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      this._center[0] = 0.5 * w; this._center[1] = 0.5 * h;
      this._cssW = w; this._cssH = h;
    }

    _initParticles() {
      var c = this.cfg;
      this._particlePropCount = c.particlePropCount;
      this._len = c.particleCount * c.particlePropCount;
      this._props = new Float32Array(this._len);
      for (var i = 0; i < this._len; i += c.particlePropCount) this._initParticle(i);
    }

    _initParticle(i) {
      var c = this.cfg;
      var w = this._cssW || 1;
      var x = Math.random() * w;
      var y = this._center[1] + (Math.random() * 2 - 1) * c.rangeY;
      var ttl = c.baseTTL + Math.random() * c.rangeTTL;
      var speed = c.baseSpeed + Math.random() * c.rangeSpeed;
      var radius = c.baseRadius + Math.random() * c.rangeRadius;
      var hue = c.baseHue + Math.random() * c.rangeHue;
      this._props.set([x, y, 0, 0, 0, ttl, speed, radius, hue], i);
    }

    _loop() {
      this._raf = requestAnimationFrame(this._loop);
      this._tick++;
      var canvas = this._canvas, ctx = this._ctx;
      var w = this._cssW || canvas.width, h = this._cssH || canvas.height;
      ctx.clearRect(0, 0, w, h);
      ctx.fillStyle = '#020905';
      ctx.fillRect(0, 0, w, h);
      ctx.lineCap = 'round';
      this._drawParticles(ctx);
      this._renderGlow(canvas, ctx);
    }

    _drawParticles(ctx) {
      for (var i = 0; i < this._len; i += this._particlePropCount) this._updateParticle(i, ctx);
    }

    _updateParticle(i, ctx) {
      var c = this.cfg;
      var w = this._cssW || 1, h = this._cssH || 1;
      var i2=1+i, i3=2+i, i4=3+i, i5=4+i, i6=5+i, i7=6+i, i8=7+i, i9=8+i;
      var x = this._props[i], y = this._props[i2];
      var n = this._noise3D(x * c.xOff, y * c.yOff, this._tick * c.zOff) * c.noiseSteps * TAU;
      var vx = this._props[i3] * 0.5 + Math.cos(n) * 0.5;
      var vy = this._props[i4] * 0.5 + Math.sin(n) * 0.5;

      // Pointer interaction: particles within radius swirl around the
      // cursor (tangential force), fading out with distance.
      var m = this._mouse;
      if (m.active) {
        var dx = x - m.x, dy = y - m.y;
        var dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < c.mouseRadius && dist > 0.001) {
          var pull = (1 - dist / c.mouseRadius) * c.mouseStrength;
          var tx = -dy / dist, ty = dx / dist;
          vx = vx * (1 - pull) + tx * pull;
          vy = vy * (1 - pull) + ty * pull;
        }
      }

      var life = this._props[i5];
      var ttl = this._props[i6];
      var speed = this._props[i7];
      var x2 = x + vx * speed, y2 = y + vy * speed;
      var radius = this._props[i8], hue = this._props[i9];

      var hm = 0.5 * ttl;
      var fade = Math.abs(((life + hm) % ttl) - hm) / hm;
      ctx.lineWidth = radius;
      ctx.strokeStyle = 'hsla(' + hue + ', 85%, 55%, ' + fade + ')';
      ctx.beginPath();
      ctx.moveTo(x, y);
      ctx.lineTo(x2, y2);
      ctx.stroke();

      life++;
      this._props[i] = x2; this._props[i2] = y2;
      this._props[i3] = vx; this._props[i4] = vy;
      this._props[i5] = life;

      if (x2 > w || x2 < 0 || y2 > h || y2 < 0 || life > ttl) this._initParticle(i);
    }

    _renderGlow(canvas, ctx) {
      // Self-composite feedback (redraw the canvas onto itself, blurred +
      // brightened, with additive blending) — must happen in the canvas's
      // raw device-pixel space, not the DPR-scaled CSS coordinate space
      // _resize() sets up for regular drawing, or the glow layer ends up
      // scaled down into a corner. save/restore keeps the DPR transform
      // intact for the next frame's clearRect/fillRect/stroke calls.
      ctx.save();
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.filter = 'blur(7px) brightness(150%)';
      ctx.globalCompositeOperation = 'lighter';
      ctx.drawImage(canvas, 0, 0);
      ctx.restore();
    }
  });
})();
