/* <beam-bg colors="#94ffd1,#6bf5ff,#ffffff"> — self-contained Canvas 2D
   "meteor beam" background (from the Tecno Prism animated-background
   handoff, Solutions page hero). Rotated lanes with occasional falling
   light-streaks, looping on independent random durations. No WebGL/CDN
   dependency — same pattern as sea-mint-grid-glow.js/vortex-bg.js.
*/
(function () {
  if (customElements.get('beam-bg')) return;

  var LANE_WIDTH = 40;
  var LANE_ANGLE = 12 * Math.PI / 180;
  var LANE_CHANCE = 0.3;

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
    this.width = 3 + Math.random() * 4;
    this.opacity = fast ? (0.8 + Math.random() * 0.2) : (0.35 + Math.random() * 0.55);
    this.progress = initial ? Math.random() : 0;
  };
  Beam.prototype.update = function (dt) {
    this.progress += dt / this.duration;
    if (this.progress >= 1) this.reset(false);
  };
  Beam.prototype.draw = function (ctx, canvasHeight, rgb) {
    var travel = canvasHeight * 1.2;
    var y = -canvasHeight * 0.2 + this.progress * travel;
    var fadeIn = Math.min(1, this.progress / 0.12);
    var fadeOut = Math.min(1, (1 - this.progress) / 0.12);
    var alpha = this.opacity * Math.min(fadeIn, fadeOut);
    if (alpha <= 0.01) return;
    var grad = ctx.createLinearGradient(0, y, 0, y + this.length);
    grad.addColorStop(0, rgba(rgb, 0));
    grad.addColorStop(0.7, rgba(rgb, alpha));
    grad.addColorStop(1, rgba(rgb, alpha));
    ctx.fillStyle = grad;
    ctx.fillRect(this.laneX - this.width / 2, y, this.width, this.length);
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
        beams = [];
        for (var i = 0; i < laneCount; i++) {
          if (Math.random() < LANE_CHANCE) {
            beams.push(new Beam(i * LANE_WIDTH - LANE_WIDTH / 2));
          }
        }
      }
      function animate(ts) {
        raf = requestAnimationFrame(animate);
        var dt = last ? (ts - last) / 1000 : 0;
        last = ts;
        var cols = colorList();
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.save();
        ctx.translate(canvas.width / 2, 0);
        ctx.rotate(LANE_ANGLE);
        ctx.translate(-canvas.width / 2, 0);
        beams.forEach(function (b, i) {
          b.update(dt);
          b.draw(ctx, canvas.height, cols[i % cols.length]);
        });
        ctx.restore();
      }

      resize();
      raf = requestAnimationFrame(function (ts) { last = ts; animate(ts); });
      this._onResize = function () { resize(); };
      window.addEventListener('resize', this._onResize);
      this._stop = function () { if (raf) cancelAnimationFrame(raf); };
    }
    disconnectedCallback() {
      if (this._onResize) window.removeEventListener('resize', this._onResize);
      if (this._stop) this._stop();
    }
  });
})();
