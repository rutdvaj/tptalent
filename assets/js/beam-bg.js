/* <beam-bg colors="#94ffd1,#6bf5ff,#ffffff"> — self-contained Canvas 2D
   "meteor beam" background, ported 1:1 from the Tecno Prism animated-
   background handoff (Solutions Page hero's buildHeroBg()/beamStreak()).
   64 rotated lanes, each carrying a permanent faint guide line; ~30% of
   lanes additionally get an animated tapered "comet" streak sweeping the
   full lane height (-20% to 300%) on a loop. No WebGL/CDN dependency —
   same pattern as vortex-bg.js/sea-mint-grid-glow.js.
*/
(function () {
  if (customElements.get('beam-bg')) return;

  var LANE_WIDTH = 40;
  var LANE_ANGLE = 12 * Math.PI / 180;
  var LANE_CHANCE = 0.3;
  // Fixed regardless of the `colors` attribute — the source design keeps
  // this line color constant across accent variants.
  var LINE_COLOR = 'rgba(167,223,194,0.10)';

  function hexToRgb(hex) {
    hex = (hex || '').trim().replace('#', '');
    if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    var n = parseInt(hex || '94ffd1', 16);
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
  }
  function rgba(rgb, a) { return 'rgba(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ',' + a + ')'; }

  function Beam(laneX) {
    this.laneX = laneX;
    this.reset(true);
  }
  Beam.prototype.reset = function (initial) {
    var fast = Math.random() < 0.3;
    this.duration = fast ? (1.2 + Math.random() * 2) : (6 + Math.random() * 8);
    this.length = 24 + Math.random() * 60;
    // Source clip-path (polygon(54% 0,54% 0,60% 100%,40% 100%)) tapers
    // to a point at top and a base spanning only the middle 20% of the
    // beam's own (already-thin, 3-7px) container — a slender sliver,
    // not a solid bar.
    this.baseHalfWidth = Math.max(0.5, (3 + Math.random() * 4) * 0.1);
    this.opacity = fast ? (0.8 + Math.random() * 0.2) : (0.35 + Math.random() * 0.55);
    this.progress = initial ? Math.random() : 0;
  };
  Beam.prototype.update = function (dt) {
    this.progress += dt / this.duration;
    if (this.progress >= 1) this.reset(false);
  };
  // Sweeps translateY(-20%) -> translateY(300%) of the full lane height,
  // same 320%-of-height travel as the source CSS keyframes.
  Beam.prototype.draw = function (ctx, canvasHeight, rgbA, rgbB) {
    var y = canvasHeight * (-0.2 + this.progress * 3.2);
    var fadeIn = Math.min(1, this.progress / 0.12);
    var fadeOut = Math.min(1, (1 - this.progress) / 0.12);
    var alpha = this.opacity * Math.min(fadeIn, fadeOut);
    if (alpha <= 0.01) return;
    ctx.save();
    ctx.globalAlpha = alpha;
    ctx.beginPath();
    ctx.moveTo(this.laneX, y);
    ctx.lineTo(this.laneX + this.baseHalfWidth, y + this.length);
    ctx.lineTo(this.laneX - this.baseHalfWidth, y + this.length);
    ctx.closePath();
    var grad = ctx.createLinearGradient(0, y, 0, y + this.length);
    grad.addColorStop(0, rgba(rgbA, 0.5));
    grad.addColorStop(0.75, rgba(rgbA, 1));
    grad.addColorStop(1, rgba(rgbB, 1));
    ctx.fillStyle = grad;
    ctx.fill();
    ctx.restore();
  };

  customElements.define('beam-bg', class extends HTMLElement {
    connectedCallback() {
      if (this._started) return;
      this._started = true;
      this.style.cssText = 'display:block;position:absolute;inset:0;overflow:hidden;pointer-events:none;';
      var canvas = document.createElement('canvas');
      canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;';
      this.appendChild(canvas);
      var ctx = canvas.getContext('2d');
      var self = this;
      var beams = [];
      var laneXs = [];
      var last = 0;
      var raf;

      function colorList() {
        var attr = self.getAttribute('colors') || '#94ffd1,#6bf5ff,#ffffff';
        return attr.split(',').map(hexToRgb);
      }
      function dims() {
        var r = self.getBoundingClientRect();
        return { w: Math.max(1, r.width), h: Math.max(1, r.height) };
      }
      function resize() {
        var d = dims();
        canvas.width = d.w;
        canvas.height = d.h;
        var laneCount = Math.ceil(d.w / LANE_WIDTH) + 2;
        laneXs = [];
        beams = [];
        for (var i = 0; i < laneCount; i++) {
          var x = i * LANE_WIDTH + LANE_WIDTH / 2;
          laneXs.push(x);
          if (Math.random() < LANE_CHANCE) beams.push(new Beam(x));
        }
      }
      function animate(ts) {
        raf = requestAnimationFrame(animate);
        var dt = last ? (ts - last) / 1000 : 0;
        last = ts;
        var cols = colorList();
        var rgbA = cols[0], rgbB = cols[1] || cols[0];
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.save();
        ctx.translate(canvas.width / 2, 0);
        ctx.rotate(LANE_ANGLE);
        ctx.translate(-canvas.width / 2, 0);
        // Permanent faint guide line for every lane — always visible,
        // independent of whether that lane also has an active beam.
        ctx.strokeStyle = LINE_COLOR;
        ctx.lineWidth = 1;
        laneXs.forEach(function (x) {
          ctx.beginPath();
          ctx.moveTo(x, 0);
          ctx.lineTo(x, canvas.height);
          ctx.stroke();
        });
        // Animated comet streaks on top.
        beams.forEach(function (b) {
          b.update(dt);
          b.draw(ctx, canvas.height, rgbA, rgbB);
        });
        ctx.restore();
      }

      // ResizeObserver instead of a plain window 'resize' listener —
      // getBoundingClientRect() called synchronously from
      // connectedCallback can race ahead of the browser's first layout
      // pass and return a near-zero rect (seen in practice: a 1px-wide
      // canvas that only self-corrected once the window was manually
      // resized). ResizeObserver always delivers an initial callback
      // with the settled size, so resize() never runs against stale
      // dimensions.
      resize();
      // Belt-and-suspenders: same double-rAF pattern shader-bg.js uses
      // for its own fade-in, guaranteeing at least one correctly-sized
      // resize() after layout has definitely settled, independent of
      // whether ResizeObserver's own initial callback timing can be
      // relied on in every environment.
      requestAnimationFrame(function () { requestAnimationFrame(function () { resize(); }); });
      if (window.ResizeObserver) {
        this._ro = new ResizeObserver(function () { resize(); });
        this._ro.observe(this);
      } else {
        this._onResize = function () { resize(); };
        window.addEventListener('resize', this._onResize);
      }
      raf = requestAnimationFrame(function (ts) { last = ts; animate(ts); });
      this._stop = function () { if (raf) cancelAnimationFrame(raf); };
    }
    disconnectedCallback() {
      if (this._ro) this._ro.disconnect();
      if (this._onResize) window.removeEventListener('resize', this._onResize);
      if (this._stop) this._stop();
    }
  });
})();
