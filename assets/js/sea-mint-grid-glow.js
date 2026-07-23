// <sea-mint-grid-glow> — self-contained Canvas 2D grid + drifting glow-dot
// background (from the "Sea Mint Tecno Prism" Industries hero handoff).
// No WebGL/CDN dependency — same pattern as wave-field.js/lightfall-bg.js.
(function () {
  if (customElements.get('sea-mint-grid-glow')) return;

  var GRID_SIZE = 50;
  var GLOW_COUNT = 12;
  var GRID_COLOR = 'rgba(148,255,209,0.07)';
  var GLOW_COLORS = ['#FF8063', '#E5C5FA', '#ffffff', '#F2A3AF'];

  function Glow(w, h) {
    this.setBounds(w, h);
    this.x = Math.floor(Math.random() * (w / GRID_SIZE)) * GRID_SIZE;
    this.y = Math.floor(Math.random() * (h / GRID_SIZE)) * GRID_SIZE;
    this.radius = Math.random() * 90 + 45;
    this.speed = Math.random() * 0.015 + 0.01;
    this.color = GLOW_COLORS[Math.floor(Math.random() * GLOW_COLORS.length)];
    this.alpha = 0;
    this.setNewTarget();
  }
  Glow.prototype.setBounds = function (w, h) { this.w = w; this.h = h; };
  Glow.prototype.setNewTarget = function () {
    this.targetX = Math.floor(Math.random() * (this.w / GRID_SIZE)) * GRID_SIZE;
    this.targetY = Math.floor(Math.random() * (this.h / GRID_SIZE)) * GRID_SIZE;
  };
  Glow.prototype.update = function () {
    this.x += (this.targetX - this.x) * this.speed;
    this.y += (this.targetY - this.y) * this.speed;
    if (Math.abs(this.targetX - this.x) < 1 && Math.abs(this.targetY - this.y) < 1) this.setNewTarget();
    if (this.alpha < 1) this.alpha += 0.01;
  };
  Glow.prototype.draw = function (ctx) {
    ctx.globalAlpha = this.alpha;
    var grad = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, this.radius);
    grad.addColorStop(0, this.color);
    grad.addColorStop(1, 'transparent');
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
    ctx.fill();
    ctx.globalAlpha = 1;
  };

  customElements.define('sea-mint-grid-glow', class extends HTMLElement {
    connectedCallback() {
      if (this._started) return;
      this._started = true;
      this.style.cssText = 'display:block;position:absolute;inset:0;overflow:hidden;pointer-events:none;';
      var canvas = document.createElement('canvas');
      canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;opacity:0.6;';
      this.appendChild(canvas);
      var ctx = canvas.getContext('2d');
      var self = this;
      var glows = [];
      var raf;

      function dims() {
        var r = self.getBoundingClientRect();
        return { w: Math.max(1, r.width), h: Math.max(1, r.height) };
      }
      function resize() {
        var d = dims();
        canvas.width = d.w;
        canvas.height = d.h;
        glows = [];
        for (var i = 0; i < GLOW_COUNT; i++) glows.push(new Glow(d.w, d.h));
      }
      function drawGrid() {
        ctx.strokeStyle = GRID_COLOR;
        ctx.lineWidth = 1;
        for (var x = 0; x < canvas.width; x += GRID_SIZE) { ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, canvas.height); ctx.stroke(); }
        for (var y = 0; y < canvas.height; y += GRID_SIZE) { ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(canvas.width, y); ctx.stroke(); }
      }
      function animate() {
        raf = requestAnimationFrame(animate);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalCompositeOperation = 'source-over';
        drawGrid();
        ctx.globalCompositeOperation = 'lighter';
        glows.forEach(function (g) { g.setBounds(canvas.width, canvas.height); g.update(); g.draw(ctx); });
        ctx.globalCompositeOperation = 'source-over';
      }

      resize();
      animate();
      this._onResize = resize;
      window.addEventListener('resize', this._onResize);
    }
    disconnectedCallback() {
      if (this._onResize) window.removeEventListener('resize', this._onResize);
    }
  });
})();
