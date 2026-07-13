/**
 * Tecnoprism Talent — homepage interactions.
 * Ported from the design prototype's React Component to plain JS. Since
 * markup is server-rendered (not mounted lazily by React), every element
 * this file touches already exists in the DOM by the time it runs — no
 * polling/retry loops are needed here, unlike the prototype.
 */
(function () {
  'use strict';

  var root = document.documentElement;

  function cssVar(name, fallback) {
    var v = getComputedStyle(root).getPropertyValue(name).trim();
    return v || fallback;
  }
  function palette() {
    return [cssVar('--p1', '#94ffd1'), cssVar('--p2', '#6bf5ff'), cssVar('--p3', '#ffffff')];
  }
  function hexToArr(hex) {
    hex = (hex || '#808080').replace('#', '');
    if (hex.length === 3) hex = hex.split('').map(function (c) { return c + c; }).join('');
    var n = parseInt(hex, 16);
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
  }
  function readJSON(id) {
    var el = document.getElementById(id);
    if (!el) return null;
    try { return JSON.parse(el.textContent); } catch (e) { return null; }
  }

  /* -----------------------------------------------------------
   * Scroll reveal — fade+rise once, on the way down only.
   * --------------------------------------------------------- */
  function initReveal() {
    var els = document.querySelectorAll('[data-reveal]');
    if (!els.length) return;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });
    els.forEach(function (el) { io.observe(el); });

    // Fallback for elements starved of an IO callback (rAF-heavy pages).
    var vh = window.innerHeight;
    setInterval(function () {
      els.forEach(function (el) {
        if (el.style.opacity === '1') return;
        var r = el.getBoundingClientRect();
        if (r.top < vh * 0.92 && r.bottom > 0) {
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        }
      });
    }, 1200);
  }

  /* -----------------------------------------------------------
   * Capabilities accordion
   * --------------------------------------------------------- */
  function initAccordion() {
    document.querySelectorAll('.tp-accordion__q').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = btn.closest('.tp-accordion__item');
        var open = item.classList.contains('is-open');
        item.closest('.tp-accordion').querySelectorAll('.tp-accordion__item').forEach(function (other) {
          other.classList.remove('is-open');
          other.querySelector('.tp-accordion__q').setAttribute('aria-expanded', 'false');
          other.querySelector('.tp-accordion__sign').textContent = '+';
        });
        if (!open) {
          item.classList.add('is-open');
          btn.setAttribute('aria-expanded', 'true');
          item.querySelector('.tp-accordion__sign').textContent = '–';
        }
      });
    });
  }

  /* -----------------------------------------------------------
   * Kinetic testimonial roller ("Chosen by")
   * --------------------------------------------------------- */
  function initKinetic() {
    var nm = document.getElementById('tpKinName');
    var mt = document.getElementById('tpKinMetric');
    var ds = document.getElementById('tpKinDesc');
    var dots = Array.prototype.slice.call(document.querySelectorAll('[data-tp-dot]'));
    var clients = readJSON('tpKineticData');
    if (!nm || !clients || !clients.length) return;

    var i = 0;
    var roll = function (el, txt) {
      if (!el) return;
      el.textContent = txt;
      if (el.animate) {
        el.animate([{ transform: 'translateY(75%)', opacity: 0 }, { transform: 'translateY(0)', opacity: 1 }],
          { duration: 420, easing: 'cubic-bezier(.2,.85,.2,1)' });
      }
    };
    var show = function () {
      var c = clients[i];
      var ink = cssVar('--ink', '#16483B');
      roll(nm, c.name); roll(mt, c.metric); roll(ds, c.desc);
      dots.forEach(function (d, k) { d.style.background = k === i ? ink : '#E4E8EF'; d.style.width = k === i ? '26px' : '8px'; });
    };
    show();
    setInterval(function () { i = (i + 1) % clients.length; show(); }, 2000);
  }

  /* -----------------------------------------------------------
   * Testimonial ribbon — WebGL diagonal color-band sweep.
   * Shipped defaults (no live tweak panel in production).
   * --------------------------------------------------------- */
  function initRibbon() {
    var cv = document.getElementById('tpRibbon');
    if (!cv) return;
    var gl = cv.getContext('webgl', { antialias: true, premultipliedAlpha: false, preserveDrawingBuffer: true });
    if (!gl) return;
    var vsrc = 'attribute vec2 a_pos; void main(){ gl_Position = vec4(a_pos, 0.0, 1.0); }';
    var fsrc = 'precision highp float;\n' +
      'uniform vec2 u_res; uniform float u_time; uniform float u_angle; uniform float u_width; uniform float u_lines;\n' +
      'uniform vec3 u_col[5];\n' +
      'vec3 ramp(float t){ t=clamp(t,0.0,1.0)*4.0; float bw=0.09; vec3 c=u_col[0];\n' +
      '  c=mix(c,u_col[1],smoothstep(1.0-bw,1.0+bw,t));\n' +
      '  c=mix(c,u_col[2],smoothstep(2.0-bw,2.0+bw,t));\n' +
      '  c=mix(c,u_col[3],smoothstep(3.0-bw,3.0+bw,t));\n' +
      '  c=mix(c,u_col[4],smoothstep(4.0-bw,4.0+bw,t));\n' +
      '  return c; }\n' +
      'void main(){\n' +
      '  vec2 uv = gl_FragCoord.xy / u_res.xy;\n' +
      '  vec2 p = uv; p.x *= u_res.x / u_res.y; float aspect = u_res.x / u_res.y;\n' +
      '  float t = u_time * 0.16;\n' +
      '  float ang = u_angle + 0.06 * sin(t * 0.5);\n' +
      '  vec2 dir = vec2(cos(ang), sin(ang)); vec2 nrm = vec2(dir.y, -dir.x);\n' +
      '  vec2 pivot = vec2(aspect * 0.66, -0.32);\n' +
      '  float d = dot(p - pivot, nrm);\n' +
      '  float width = u_width + 0.04 * sin(t * 0.8);\n' +
      '  float inner = 0.12 + 0.05 * sin(t * 0.6);\n' +
      '  float g = (d - inner) / width;\n' +
      '  vec3 col = ramp(g);\n' +
      '  float lines = 1.0 - u_lines * (0.5 - 0.5 * sin(g * 40.0));\n' +
      '  col *= lines;\n' +
      '  float aa = 0.006;\n' +
      '  float mask = smoothstep(0.0, aa, g) * (1.0 - smoothstep(1.0 - aa, 1.0, g));\n' +
      '  vec3 outc = mix(vec3(0.0), col, mask);\n' +
      '  gl_FragColor = vec4(outc, mask);\n' +
      '}';
    var compile = function (type, src) {
      var sh = gl.createShader(type); gl.shaderSource(sh, src); gl.compileShader(sh);
      if (!gl.getShaderParameter(sh, gl.COMPILE_STATUS)) console.error('ribbon shader error:', gl.getShaderInfoLog(sh));
      return sh;
    };
    var prog = gl.createProgram();
    gl.attachShader(prog, compile(gl.VERTEX_SHADER, vsrc));
    gl.attachShader(prog, compile(gl.FRAGMENT_SHADER, fsrc));
    gl.linkProgram(prog); gl.useProgram(prog);
    gl.enable(gl.BLEND); gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);
    var buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    var loc = gl.getAttribLocation(prog, 'a_pos');
    gl.enableVertexAttribArray(loc); gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);
    var uRes = gl.getUniformLocation(prog, 'u_res'), uTime = gl.getUniformLocation(prog, 'u_time');
    var uAngle = gl.getUniformLocation(prog, 'u_angle'), uWidth = gl.getUniformLocation(prog, 'u_width'), uLines = gl.getUniformLocation(prog, 'u_lines');
    var uCol = gl.getUniformLocation(prog, 'u_col');
    var resize = function () {
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      var w = Math.max(1, Math.floor(cv.clientWidth * dpr)), h = Math.max(1, Math.floor(cv.clientHeight * dpr));
      if (cv.width !== w || cv.height !== h) { cv.width = w; cv.height = h; gl.viewport(0, 0, w, h); }
    };
    // Shipped motion: speed 2.85, angle 0.92, spread 1.9 (widened +50% per the
    // design brief), line detail 0.14.
    var motion = { speed: 2.85, angle: 0.92, spread: 1.9, lines: 0.14 };
    var clock = 0, last = performance.now();
    var loop = function () {
      var now = performance.now();
      clock += (now - last) / 1000 * motion.speed; last = now;
      resize();
      var pal = palette();
      var flat = [];
      for (var i = 0; i < 5; i++) { var a = hexToArr(pal[i % pal.length]); flat.push(a[0] / 255, a[1] / 255, a[2] / 255); }
      gl.uniform2f(uRes, cv.width, cv.height);
      gl.uniform1f(uTime, clock);
      gl.uniform1f(uAngle, motion.angle);
      gl.uniform1f(uWidth, motion.spread);
      gl.uniform1f(uLines, motion.lines);
      gl.uniform3fv(uCol, new Float32Array(flat));
      gl.drawArrays(gl.TRIANGLES, 0, 3);
      requestAnimationFrame(loop);
    };
    resize();
    loop();
  }

  /* -----------------------------------------------------------
   * Global delivery — dotted world map + sequenced comet beams.
   * Beam chain: India -> USA & Singapore, then USA -> Canada & Colombia,
   * then Canada -> Kuwait & Colombia -> Brazil, then Kuwait -> Dubai.
   * All beams retract together, then the cycle repeats. Starts only once
   * scrolled into view.
   * --------------------------------------------------------- */
  function initMap() {
    var cv = document.getElementById('tpMap');
    var wrap = document.getElementById('tpMapWrap');
    var locations = readJSON('tpMapData');
    if (!cv || !wrap || !locations || !locations.length) return;
    var ctx = cv.getContext('2d');
    var DPR = Math.min(window.devicePixelRatio || 1, 2);
    var cw = 0, ch = 0;
    var fit = function () {
      var r = wrap.getBoundingClientRect();
      if (r.width === cw && r.height === ch) return;
      cw = r.width; ch = r.height;
      cv.width = Math.max(1, cw * DPR); cv.height = Math.max(1, ch * DPR);
      ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
    };
    fit();
    if (window.ResizeObserver) { new ResizeObserver(fit).observe(wrap); }

    var landDots = null;
    var fallback = [];
    for (var la = -55; la <= 78; la += 3.2) for (var lo = -180; lo < 180; lo += 3.2) fallback.push({ lon: lo, lat: la });

    fetch('https://unpkg.com/world-atlas@2.0.2/land-110m.json').then(function (r) { return r.json(); }).then(function (topo) {
      var tr = topo.transform, sc = tr.scale, tl = tr.translate;
      var arcs = topo.arcs.map(function (arc) {
        var x = 0, y = 0;
        return arc.map(function (p) { x += p[0]; y += p[1]; return [x * sc[0] + tl[0], y * sc[1] + tl[1]]; });
      });
      var ringFromArcs = function (idxs) {
        var pts = [];
        idxs.forEach(function (ai) {
          var a = ai >= 0 ? arcs[ai] : arcs[~ai].slice().reverse();
          if (pts.length) a = a.slice(1);
          pts = pts.concat(a);
        });
        return pts;
      };
      var rings = [], bboxes = [];
      var geom = topo.objects.land;
      if (geom.geometries) geom = geom.geometries[0];
      geom.arcs.forEach(function (poly) { poly.forEach(function (ring) { rings.push(ringFromArcs(ring)); }); });
      rings = rings.map(function (ring) {
        var out = [], off = 0, prev = null;
        ring.forEach(function (p) {
          var x = p[0] + off;
          if (prev !== null) {
            if (x - prev > 180) { off -= 360; x -= 360; }
            else if (prev - x > 180) { off += 360; x += 360; }
          }
          prev = x;
          out.push([x, p[1]]);
        });
        return out;
      });
      rings.forEach(function (ring) {
        var x0 = 9999, y0 = 9999, x1 = -9999, y1 = -9999;
        ring.forEach(function (p) { if (p[0] < x0) x0 = p[0]; if (p[0] > x1) x1 = p[0]; if (p[1] < y0) y0 = p[1]; if (p[1] > y1) y1 = p[1]; });
        bboxes.push([x0, y0, x1, y1]);
      });
      var parity = function (lon, lat) {
        var c = false;
        for (var r = 0; r < rings.length; r++) {
          var b = bboxes[r];
          if (lon < b[0] - 0.01 || lon > b[2] + 0.01 || lat < b[1] || lat > b[3]) continue;
          var ring = rings[r];
          for (var i2 = 0, j2 = ring.length - 1; i2 < ring.length; j2 = i2++) {
            var xi = ring[i2][0], yi = ring[i2][1], xj = ring[j2][0], yj = ring[j2][1];
            if (((yi > lat) !== (yj > lat)) && (lon < (xj - xi) * (lat - yi) / (yj - yi) + xi)) c = !c;
          }
        }
        return c;
      };
      var inside = function (lon, lat) { return parity(lon, lat) || parity(lon + 360, lat) || parity(lon - 360, lat); };
      var out = [];
      for (var la2 = -56; la2 <= 84; la2 += 1.3) {
        for (var lo2 = -180; lo2 < 180; lo2 += 1.3) { if (inside(lo2, la2)) out.push({ lon: lo2, lat: la2 }); }
      }
      if (out.length > 100) landDots = out;
    }).catch(function () {});

    var P = function (lon, lat) {
      var ppd = cw / 360;
      var latMid = 16;
      return { x: (lon + 180) * ppd, y: ch / 2 - (lat - latMid) * ppd };
    };

    var t0 = performance.now(), seen = false;
    var vio = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting && !seen) { seen = true; t0 = performance.now(); vio.disconnect(); } });
    }, { threshold: 0.35 });
    vio.observe(wrap);

    // Indices into `locations`: 0 India, 1 USA, 2 Canada, 3 Brazil,
    // 4 Colombia, 5 Dubai, 6 Kuwait, 7 Singapore (see tp_default('tp_global')).
    var routes = [
      [0, 1, 0], [0, 7, 0],
      [1, 2, 1], [1, 4, 1],
      [2, 6, 2], [4, 3, 2],
      [6, 5, 3],
    ];
    var beamDur = 1.5, hold = 2, pause = 1, slots = 4;
    var drawTime = slots * beamDur;
    var T = drawTime + hold + beamDur + pause;
    var ease = function (x) { return x < 0 ? 0 : x > 1 ? 1 : x * x * (3 - 2 * x); };
    var qpt = function (p0, cx, cy, p1, a) {
      var ia = 1 - a;
      return { x: ia * ia * p0.x + 2 * ia * a * cx + a * a * p1.x, y: ia * ia * p0.y + 2 * ia * a * cy + a * a * p1.y };
    };

    var draw = function () {
      var mvr = cv.getBoundingClientRect();
      if (mvr.bottom < -200 || mvr.top > window.innerHeight + 200) { requestAnimationFrame(draw); return; }
      fit();
      if (cw < 2 || ch < 2) { requestAnimationFrame(draw); return; }
      var t = seen ? (performance.now() - t0) / 1000 : 0;
      var pal = palette();
      ctx.clearRect(0, 0, cw, ch);
      var dots = landDots || fallback;
      var mixToWhite = function (hex, amt) {
        var a = hexToArr(hex);
        return 'rgb(' + Math.round(a[0] + (255 - a[0]) * amt) + ',' + Math.round(a[1] + (255 - a[1]) * amt) + ',' + Math.round(a[2] + (255 - a[2]) * amt) + ')';
      };
      ctx.fillStyle = mixToWhite(pal[0], 0.45);
      ctx.globalAlpha = 0.7;
      for (var i = 0; i < dots.length; i++) { var d = P(dots[i].lon, dots[i].lat); ctx.fillRect(d.x, d.y, 1.8, 1.8); }
      ctx.globalAlpha = 1;

      var pts = locations.map(function (l) { return P(parseFloat(l.lon), parseFloat(l.lat)); });

      var cyc2 = t % T;
      for (var j = 0; j < routes.length; j++) {
        var src = pts[routes[j][0]], dst = pts[routes[j][1]];
        if (!src || !dst) continue;
        var frac;
        if (cyc2 < drawTime + hold) frac = ease((cyc2 - routes[j][2] * beamDur) / beamDur);
        else frac = 1 - ease((cyc2 - drawTime - hold) / beamDur);
        if (frac <= 0.001) continue;
        var dist = Math.hypot(dst.x - src.x, dst.y - src.y);
        var mx = (src.x + dst.x) / 2;
        var my = Math.min(src.y, dst.y) - dist * 0.22 - 18;
        var grad = ctx.createLinearGradient(src.x, src.y, dst.x, dst.y);
        grad.addColorStop(0, pal[0]); grad.addColorStop(1, pal[1]);
        ctx.strokeStyle = grad;
        ctx.lineWidth = 2.2;
        ctx.lineCap = 'round';
        ctx.globalAlpha = 0.85;
        ctx.beginPath();
        var seg = 32;
        var q0 = qpt(src, mx, my, dst, 0);
        ctx.moveTo(q0.x, q0.y);
        for (var s = 1; s <= seg; s++) { var qq = qpt(src, mx, my, dst, frac * s / seg); ctx.lineTo(qq.x, qq.y); }
        ctx.stroke();
        if (frac < 1) {
          var hp = qpt(src, mx, my, dst, frac);
          ctx.fillStyle = pal[1];
          ctx.beginPath(); ctx.arc(hp.x, hp.y, 3.4, 0, 7); ctx.fill();
        }
      }
      ctx.lineCap = 'butt';
      ctx.globalAlpha = 1;

      ctx.font = '600 16px "Libre Franklin", sans-serif';
      var lofs = {
        Kuwait: [-62, -34], Dubai: [-58, 30],
        India: [66, -42], USA: [-48, -34], Canada: [56, -34], Brazil: [50, 28],
        Colombia: [-64, 26], Singapore: [66, 26],
      };
      var pill = function (txt, cx2, cy2, hq2) {
        var w = ctx.measureText(txt).width + 30, h = 34, r2 = h / 2;
        var x = cx2 - w / 2, y = cy2 - h / 2;
        ctx.beginPath();
        ctx.moveTo(x + r2, y);
        ctx.arcTo(x + w, y, x + w, y + h, r2);
        ctx.arcTo(x + w, y + h, x, y + h, r2);
        ctx.arcTo(x, y + h, x, y, r2);
        ctx.arcTo(x, y, x + w, y, r2);
        ctx.closePath();
        ctx.fillStyle = hq2 ? pal[0] : 'rgba(255,255,255,0.94)';
        ctx.shadowColor = 'rgba(0,0,0,0.35)'; ctx.shadowBlur = 10; ctx.shadowOffsetY = 2;
        ctx.fill();
        ctx.shadowColor = 'transparent'; ctx.shadowBlur = 0; ctx.shadowOffsetY = 0;
        ctx.fillStyle = '#08170F';
        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(txt, cx2, cy2 + 1);
        ctx.textAlign = 'start'; ctx.textBaseline = 'alphabetic';
      };
      for (var k = 0; k < locations.length; k++) {
        var pp = pts[k];
        var ph = ((t * 0.5 + k * 0.13) % 1);
        ctx.globalAlpha = 0.6 * (1 - ph);
        ctx.lineWidth = 1.5; ctx.strokeStyle = pal[0];
        ctx.beginPath(); ctx.arc(pp.x, pp.y, 4 + ph * 12, 0, 7); ctx.stroke();
        ctx.globalAlpha = 1;
        ctx.fillStyle = pal[0];
        ctx.beginPath(); ctx.arc(pp.x, pp.y, locations[k].hq ? 5.5 : 4, 0, 7); ctx.fill();
        ctx.fillStyle = '#08170F';
        ctx.beginPath(); ctx.arc(pp.x, pp.y, locations[k].hq ? 2.2 : 1.5, 0, 7); ctx.fill();
      }
      for (var k2 = 0; k2 < locations.length; k2++) {
        if (parseInt(locations[k2].hq, 10)) continue;
        var pq = pts[k2]; var o = lofs[locations[k2].name] || [0, -30];
        pill(locations[k2].name, pq.x + o[0], pq.y + o[1], false);
      }
      for (var k3 = 0; k3 < locations.length; k3++) {
        if (!parseInt(locations[k3].hq, 10)) continue;
        var pq2 = pts[k3]; var o2 = lofs[locations[k3].name] || [0, -30];
        pill(locations[k3].name, pq2.x + o2[0], pq2.y + o2[1], true);
      }
      requestAnimationFrame(draw);
    };
    draw();
  }

  /* -----------------------------------------------------------
   * CTA band — breathing radial glow.
   * --------------------------------------------------------- */
  function initCtaGlow() {
    var el = document.getElementById('tpCtaGlow');
    if (!el) return;
    var startingGap = 120, breathingRange = 6, animationSpeed = 0.03;
    var width = startingGap, dir = 1;
    var loop = function () {
      if (width >= startingGap + breathingRange) dir = -1;
      if (width <= startingGap - breathingRange) dir = 1;
      width += dir * animationSpeed;
      var pal = palette();
      var inkDeep = cssVar('--ink-deep', '#0E332B');
      var ink = cssVar('--ink', '#16483B');
      var stops = [inkDeep + ' 28%', ink + ' 46%', pal[1] + ' 62%', pal[2] + ' 80%', pal[0] + ' 100%'].join(', ');
      el.style.background = 'radial-gradient(' + width + '% ' + (width + 40) + '% at 50% 4%, ' + stops + ')';
      requestAnimationFrame(loop);
    };
    loop();
  }

  /* -----------------------------------------------------------
   * Bento case-study — WebGL fluid ripple background (mouse/touch reactive).
   * --------------------------------------------------------- */
  function initBentoFx() {
    var cv = document.getElementById('tpBentoFx');
    var sec = document.getElementById('tpBentoSec');
    if (!cv || !sec) return;
    var gl = cv.getContext('webgl', { antialias: true, alpha: false, premultipliedAlpha: false });
    if (!gl) return;
    var RES = 192;
    var cur = new Float32Array(RES * RES), prev = new Float32Array(RES * RES);
    var tex8 = new Uint8Array(RES * RES);
    var vsrc = 'attribute vec2 a_pos; void main(){ gl_Position = vec4(a_pos, 0.0, 1.0); }';
    var fsrc = 'precision mediump float;\n' +
      'uniform float u_time; uniform vec2 u_res; uniform float u_speed; uniform float u_wstr;\n' +
      'uniform vec3 u_c1; uniform vec3 u_c2; uniform vec3 u_c3; uniform vec3 u_bg;\n' +
      'uniform sampler2D u_water;\n' +
      'float th(float x){ float x2=x*x; return clamp(x*(27.0+x2)/(27.0+9.0*x2),-1.0,1.0); }\n' +
      'void main(){\n' +
      '  vec2 r=u_res; vec2 FC=gl_FragCoord.xy;\n' +
      '  vec2 sp=(FC*2.0-r)/r;\n' +
      '  float wh=(texture2D(u_water,FC/r).r-0.5)*4.0;\n' +
      '  float wi=clamp(wh*u_wstr,-0.8,0.8);\n' +
      '  vec2 p=sp*1.35;\n' +
      '  float t=u_time*u_speed+wi*2.0;\n' +
      '  float w=0.45*sin(p.x*1.8+t*0.6)+0.3*sin(p.y*2.4-t*0.45)+0.2*sin((p.x+p.y)*3.1+t*0.8);\n' +
      '  float l=p.y*0.9+p.x*0.25+w+wi*0.6;\n' +
      '  float p1=0.5+0.5*th(1.6*sin(l*2.2+t));\n' +
      '  float p2=0.5+0.5*th(1.6*sin(l*2.2+t+1.3));\n' +
      '  float p3=0.5+0.5*th(1.6*sin(l*2.2+t+2.6));\n' +
      '  float inten=0.7+wi*0.4;\n' +
      '  vec3 col=(p1*u_c1+p2*u_c2+p3*u_c3)*0.55*inten;\n' +
      '  float a=clamp(max(p1,max(p2,p3)),0.0,1.0)*0.6;\n' +
      '  gl_FragColor=vec4(mix(u_bg,col,a),1.0);\n' +
      '}';
    var mk = function (type, src) {
      var sh = gl.createShader(type); gl.shaderSource(sh, src); gl.compileShader(sh);
      if (!gl.getShaderParameter(sh, gl.COMPILE_STATUS)) console.error('bento fx shader:', gl.getShaderInfoLog(sh));
      return sh;
    };
    var prog = gl.createProgram();
    gl.attachShader(prog, mk(gl.VERTEX_SHADER, vsrc));
    gl.attachShader(prog, mk(gl.FRAGMENT_SHADER, fsrc));
    gl.linkProgram(prog); gl.useProgram(prog);
    var buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    var locp = gl.getAttribLocation(prog, 'a_pos');
    gl.enableVertexAttribArray(locp); gl.vertexAttribPointer(locp, 2, gl.FLOAT, false, 0, 0);
    var wtex = gl.createTexture();
    gl.bindTexture(gl.TEXTURE_2D, wtex);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
    var U = function (n) { return gl.getUniformLocation(prog, n); };
    var damping = 0.913, tension = 0.02;
    var resize = function () {
      var dpr = Math.min(window.devicePixelRatio || 1, 1.5);
      var w = Math.max(1, Math.floor(cv.clientWidth * dpr)), h = Math.max(1, Math.floor(cv.clientHeight * dpr));
      if (cv.width !== w || cv.height !== h) { cv.width = w; cv.height = h; gl.viewport(0, 0, w, h); }
    };
    var ripple = function (nx, ny, strength) {
      var tx = Math.floor(nx * RES), ty = Math.floor((1 - ny) * RES);
      var rad = 7;
      for (var i = -rad; i <= rad; i++) for (var j = -rad; j <= rad; j++) {
        var d2 = i * i + j * j;
        if (d2 > rad * rad) continue;
        var px = tx + i, py = ty + j;
        if (px < 1 || px >= RES - 1 || py < 1 || py >= RES - 1) continue;
        var dd = Math.sqrt(d2);
        prev[py * RES + px] += Math.cos((dd / rad) * Math.PI * 0.5) * strength * (1 - dd / rad) * 0.5;
      }
    };
    var lastMx = 0, lastMy = 0, thr = 0;
    var onMove = function (e) {
      var now = performance.now();
      if (now - thr < 16) return;
      thr = now;
      var r = cv.getBoundingClientRect();
      var x = e.clientX - r.left, y = e.clientY - r.top;
      if (x < 0 || y < 0 || x > r.width || y > r.height) return;
      var dx = x - lastMx, dy = y - lastMy;
      var dist = Math.sqrt(dx * dx + dy * dy);
      if (dist > 2) { ripple(x / r.width, y / r.height, Math.min(dist / 30, 1.2)); lastMx = x; lastMy = y; }
    };
    var onClick = function (e) {
      var r = cv.getBoundingClientRect();
      var x = e.clientX - r.left, y = e.clientY - r.top;
      if (x < 0 || y < 0 || x > r.width || y > r.height) return;
      ripple(x / r.width, y / r.height, 3);
    };
    sec.addEventListener('pointermove', onMove, { passive: true });
    sec.addEventListener('click', onClick, { passive: true });
    var t0b = performance.now();
    setTimeout(function () { ripple(0.5, 0.5, 2); }, 400);
    var step = function () {
      for (var i = 1; i < RES - 1; i++) {
        var row = i * RES;
        for (var j = 1; j < RES - 1; j++) {
          var idx = row + j;
          var v = (prev[idx - RES] + prev[idx + RES] + prev[idx - 1] + prev[idx + 1]) / 2 - cur[idx];
          v = v * damping + prev[idx] * (1 - damping);
          v += (0 - prev[idx]) * tension;
          cur[idx] = Math.max(-2, Math.min(2, v * 0.99));
        }
      }
      var tmp = cur; cur = prev; prev = tmp;
      for (var k = 0; k < RES * RES; k++) tex8[k] = Math.max(0, Math.min(255, (prev[k] * 0.25 + 0.5) * 255));
      gl.bindTexture(gl.TEXTURE_2D, wtex);
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.LUMINANCE, RES, RES, 0, gl.LUMINANCE, gl.UNSIGNED_BYTE, tex8);
    };
    var loop = function () {
      var vr = cv.getBoundingClientRect();
      if (vr.bottom < -200 || vr.top > window.innerHeight + 200) { requestAnimationFrame(loop); return; }
      resize();
      step();
      var pal = palette();
      var c = function (hex) { var a = hexToArr(hex); return [a[0] / 255, a[1] / 255, a[2] / 255]; };
      var c1 = c(pal[0]), c2 = c(pal[1]), c3 = c(pal[2]);
      var bgHex = hexToArr(pal[0]);
      var bg = [bgHex[0] + (6 - bgHex[0]) * 0.92, bgHex[1] + (10 - bgHex[1]) * 0.92, bgHex[2] + (8 - bgHex[2]) * 0.92];
      gl.uniform1f(U('u_time'), (performance.now() - t0b) * 0.001);
      gl.uniform2f(U('u_res'), cv.width, cv.height);
      gl.uniform1f(U('u_speed'), 1.1);
      gl.uniform1f(U('u_wstr'), 0.55);
      gl.uniform3f(U('u_c1'), c1[0], c1[1], c1[2]);
      gl.uniform3f(U('u_c2'), c2[0], c2[1], c2[2]);
      gl.uniform3f(U('u_c3'), c3[0], c3[1], c3[2]);
      gl.uniform3f(U('u_bg'), bg[0] / 255, bg[1] / 255, bg[2] / 255);
      gl.drawArrays(gl.TRIANGLES, 0, 3);
      requestAnimationFrame(loop);
    };
    resize();
    loop();
  }

  function init() {
    initReveal();
    initAccordion();
    initKinetic();
    initRibbon();
    initMap();
    initCtaGlow();
    initBentoFx();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
