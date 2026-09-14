/* Bright Care — Aligners landing page engine (GPU transforms, rAF) */
(function () {
  'use strict';
  var d = document;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  function clamp(v, a, b){ return v < a ? a : (v > b ? b : v); }

  /* ── Premium smooth / slow scrolling (Lenis) ── */
  if (window.Lenis && !reduce){
    var lenis = new Lenis({ lerp: 0.07, wheelMultiplier: 0.85, smoothWheel: true, smoothTouch: false });
    (function lraf(time){ lenis.raf(time); requestAnimationFrame(lraf); })(0);
    d.querySelectorAll('a[href^="#"]').forEach(function(a){
      a.addEventListener('click', function(e){
        var id = a.getAttribute('href');
        if (id.length > 1){ var t = d.querySelector(id); if (t){ e.preventDefault(); lenis.scrollTo(t, { offset: -70, duration: 1.6 }); } }
      });
    });
  }

  /* ── Cinematic scroll stage ── */
  var stage = d.querySelector('[data-stage]');
  var al    = stage && stage.querySelector('[data-stage-aligner]');
  var bar   = d.querySelector('[data-stage-bar]');
  var caps  = stage ? [].slice.call(stage.querySelectorAll('[data-cap]')) : [];
  var raf = 0;
  function frame(){
    raf = 0;
    if (!stage || !al) return;
    var vw = innerWidth, vh = innerHeight;
    var r = stage.getBoundingClientRect();
    var total = stage.offsetHeight - vh;
    var p = clamp(-r.top / total, 0, 1);
    var x   = (p - 0.5) * vw * 0.46;
    var y   = Math.sin(p * Math.PI * 2) * 28;
    var rotY= -30 + p * 540;                 // 3D spin
    var rotZ= Math.sin(p * Math.PI * 2) * 12;
    var sc  = 0.82 + Math.sin(p * Math.PI) * 0.46;
    al.style.transform = 'translate3d(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px,0) rotateY(' + rotY.toFixed(1) + 'deg) rotateZ(' + rotZ.toFixed(1) + 'deg) scale(' + sc.toFixed(3) + ')';
    if (bar) bar.style.transform = 'scaleX(' + p.toFixed(3) + ')';
    var n = caps.length;
    if (n){ var idx = Math.min(n - 1, Math.floor(p * n)); caps.forEach(function(c, i){ c.classList.toggle('on', i === idx); }); }
  }
  function onScroll(){ if (!raf) raf = requestAnimationFrame(frame); }

  /* ── Hero: living aligner (idle float + cursor 3D tilt) ── */
  var wrap = d.querySelector('[data-tilt]');
  var el   = d.querySelector('[data-tilt-el]');
  var tx = 0, ty = 0, cx = 0, cy = 0, hovering = false;
  function heroLoop(now){
    cx += (tx - cx) * 0.07; cy += (ty - cy) * 0.07;
    var fl  = Math.sin(now / 1500) * 16;
    var spin= Math.sin(now / 2800) * 6;
    el.style.transform = 'translateY(' + fl.toFixed(1) + 'px) rotateX(' + (-cy * 13).toFixed(2) + 'deg) rotateY(' + (cx * 18 + spin).toFixed(2) + 'deg)';
    requestAnimationFrame(heroLoop);
  }

  if (reduce){
    caps.forEach(function(c){ c.classList.add('on'); });
  } else {
    addEventListener('scroll', onScroll, { passive: true });
    addEventListener('resize', onScroll);
    frame();
    if (wrap && el){
      wrap.addEventListener('pointermove', function(e){
        var r = wrap.getBoundingClientRect();
        tx = clamp((e.clientX - r.left) / r.width - 0.5, -0.5, 0.5) * 2;
        ty = clamp((e.clientY - r.top) / r.height - 0.5, -0.5, 0.5) * 2;
        hovering = true;
      });
      wrap.addEventListener('pointerleave', function(){ tx = 0; ty = 0; hovering = false; });
      requestAnimationFrame(heroLoop);
    }
  }

  /* ── Floating particle field (hero) ── */
  var pc = d.querySelector('[data-particles]');
  if (pc && !reduce){
    var ctx = pc.getContext('2d'), parts = [], DPR = Math.min(devicePixelRatio || 1, 2);
    function size(){ var r = pc.getBoundingClientRect(); pc.width = Math.max(1, r.width*DPR); pc.height = Math.max(1, r.height*DPR); }
    size(); addEventListener('resize', size);
    var N = 60;
    for (var i=0;i<N;i++) parts.push({
      x:Math.random(), y:Math.random(), r:Math.random()*2.2+0.6,
      sx:(Math.random()-0.5)*0.00035, sy:-(Math.random()*0.0006+0.00015),
      a:Math.random()*0.5+0.18, tw:Math.random()*6.28, teal:Math.random()<0.55
    });
    (function draw(t){
      var w = pc.width, h = pc.height; ctx.clearRect(0,0,w,h);
      for (var i=0;i<parts.length;i++){
        var p = parts[i]; p.x += p.sx; p.y += p.sy;
        if (p.y < -0.06){ p.y = 1.06; p.x = Math.random(); }
        if (p.x < -0.02) p.x = 1.02; if (p.x > 1.02) p.x = -0.02;
        var tw = Math.sin(t/750 + p.tw)*0.5 + 0.5;
        var alpha = p.a*(0.35 + tw*0.65), px = p.x*w, py = p.y*h, pr = p.r*DPR*(0.8 + tw*0.6)*4;
        var col = p.teal ? '127,224,212' : '255,255,255';
        var g = ctx.createRadialGradient(px,py,0,px,py,pr);
        g.addColorStop(0,'rgba('+col+','+alpha+')'); g.addColorStop(1,'rgba('+col+',0)');
        ctx.fillStyle = g; ctx.beginPath(); ctx.arc(px,py,pr,0,6.2832); ctx.fill();
      }
      requestAnimationFrame(draw);
    })(0);
  }
})();
