/* Bright Care — front-end interactions (no dependencies, ~5KB) */
(function () {
  'use strict';
  var d = document;

  // Mobile nav
  var nav = d.getElementById('nav'), toggle = d.getElementById('navToggle'), close = d.getElementById('navClose');
  var lockY = 0;
  function lockScroll(on){
    var b = d.body;
    if (on){
      lockY = window.scrollY || window.pageYOffset || 0;
      b.style.position = 'fixed'; b.style.top = (-lockY) + 'px';
      b.style.left = '0'; b.style.right = '0'; b.style.width = '100%';
      b.style.overflow = 'hidden';
    } else {
      b.style.position = ''; b.style.top = '';
      b.style.left = ''; b.style.right = ''; b.style.width = '';
      b.style.overflow = '';
      window.scrollTo(0, lockY);
    }
  }
  function setNav(o){ nav.classList.toggle('open', o); toggle.setAttribute('aria-expanded', o); lockScroll(o); }
  if (toggle) toggle.addEventListener('click', function(){ setNav(true); });
  if (close)  close.addEventListener('click', function(){ setNav(false); });
  if (nav) nav.querySelectorAll('.nav__link').forEach(function(a){ a.addEventListener('click', function(){ setNav(false); }); });

  // Sticky header + scroll progress
  var header = d.getElementById('header'), bar = d.getElementById('scrollbar');
  function onScroll(){
    header.classList.toggle('shrink', window.scrollY > 20);
    if (bar){ var hh = d.documentElement.scrollHeight - innerHeight; bar.style.transform = 'scaleX(' + (hh>0?scrollY/hh:0) + ')'; }
  }
  addEventListener('scroll', onScroll, { passive:true }); onScroll();

  // Services mega menu (desktop hover)
  var megaTrigger = d.querySelector('[data-mega]'), mega = d.getElementById('megamenu'),
      megaPanel = mega && mega.querySelector('.megamenu__panel'), megaTimer;
  function openMega(){
    if (innerWidth <= 980 || !mega) return;
    clearTimeout(megaTimer);
    if (megaPanel) megaPanel.style.top = Math.round(header.getBoundingClientRect().bottom) + 'px';
    mega.classList.add('open'); mega.setAttribute('aria-hidden', 'false');
  }
  function closeMega(now){
    if (!mega) return; clearTimeout(megaTimer);
    var go = function(){ mega.classList.remove('open'); mega.setAttribute('aria-hidden', 'true'); };
    if (now) go(); else megaTimer = setTimeout(go, 160);
  }
  if (megaTrigger && mega){
    megaTrigger.addEventListener('mouseenter', openMega);
    megaTrigger.addEventListener('mouseleave', function(){ closeMega(); });
    if (megaPanel){
      megaPanel.addEventListener('mouseenter', function(){ clearTimeout(megaTimer); });
      megaPanel.addEventListener('mouseleave', function(){ closeMega(); });
    }
    mega.querySelectorAll('[data-mega-close]').forEach(function(el){ el.addEventListener('click', function(){ closeMega(true); }); });
    mega.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', function(){ closeMega(true); }); });
    addEventListener('keydown', function(e){ if (e.key === 'Escape') closeMega(true); });
    addEventListener('scroll', function(){ if (mega.classList.contains('open')) closeMega(true); }, { passive:true });
  }

  // Scroll reveal
  var reveals = d.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window){
    var io = new IntersectionObserver(function(es){
      es.forEach(function(e,i){ if(e.isIntersecting){ var el=e.target; setTimeout(function(){ el.classList.add('in'); }, Math.min(i*60,200)); io.unobserve(el); } });
    }, { threshold:0.1, rootMargin:'0px 0px -40px 0px' });
    reveals.forEach(function(el){ io.observe(el); });
  } else reveals.forEach(function(el){ el.classList.add('in'); });

  // Counters
  function count(el){
    var t=parseFloat(el.dataset.count), suf=el.dataset.suffix||'', f=t%1!==0, dur=1400, st=performance.now();
    (function step(now){ var p=Math.min((now-st)/dur,1), v=t*(1-Math.pow(1-p,3));
      el.textContent=(f?v.toFixed(1):Math.floor(v).toLocaleString('en-IN'))+(p===1?suf:''); if(p<1)requestAnimationFrame(step); })(st);
  }
  var nums=d.querySelectorAll('.stat__num');
  if ('IntersectionObserver' in window){
    var co=new IntersectionObserver(function(es){ es.forEach(function(e){ if(e.isIntersecting){ count(e.target); co.unobserve(e.target); } }); },{threshold:0.5});
    nums.forEach(function(el){ co.observe(el); });
  } else nums.forEach(count);

  // ── Appointment modal ──────────────────────────────────────
  var modal = d.getElementById('apptModal');
  function openModal(svc){
    if(!modal) return;
    if(svc){ var sel=modal.querySelector('select[name=service]');
      if(sel) Array.prototype.forEach.call(sel.options,function(o){ if(o.value===svc||o.text===svc) sel.value=o.value; }); }
    if(nav) setNav(false);
    modal.classList.add('open'); d.body.style.overflow='hidden';
    var fn=modal.querySelector('input[name=name]'); if(fn) setTimeout(function(){ fn.focus(); },320);
  }
  function closeModal(){ if(modal){ modal.classList.remove('open'); d.body.style.overflow=''; } }

  d.addEventListener('click', function(e){
    var trig = e.target.closest('[data-book], a[href$="#book"], a[href*="/contact#book"]');
    if(trig){ e.preventDefault(); openModal(trig.getAttribute('data-service')); return; }
    if(e.target.closest('[data-close]')) closeModal();
  });
  d.addEventListener('keydown', function(e){ if(e.key==='Escape' && modal && modal.classList.contains('open')) closeModal(); });

  // ── Generic appointment-form AJAX (inline forms + modal) ────
  function bindForm(form){
    if(!form) return;
    form.addEventListener('submit', function(ev){
      ev.preventDefault();
      var note = form.querySelector('.book__note, .modal__note'),
          btn  = form.querySelector('button[type=submit]'),
          label = btn.innerHTML, isModal = form.id==='apptForm';
      if(note) note.className = isModal ? 'modal__note' : 'book__note';
      btn.disabled = true; btn.textContent = 'Sending…';
      fetch('/api/enquiry.php', { method:'POST', body:new FormData(form) })
        .then(function(r){ return r.json(); })
        .then(function(j){
          if(j.ok){
            if(note){ note.textContent = '✓ ' + (j.message || 'Thank you! We’ll be in touch.'); note.classList.add('ok'); }
            form.reset(); btn.textContent = 'Request Sent ✓';
            if(isModal) setTimeout(closeModal, 2200);
          } else {
            if(note){ note.textContent = j.error || 'Please check your details.'; note.classList.add('err'); }
            btn.disabled = false; btn.innerHTML = label;
          }
        })
        .catch(function(){ if(note){ note.textContent='Network error — please call us instead.'; note.classList.add('err'); } btn.disabled=false; btn.innerHTML=label; });
    });
  }
  bindForm(d.getElementById('bookForm'));
  bindForm(d.getElementById('apptForm'));
})();
