/* Admin panel — minimal interactions */
(function(){
  var burger=document.getElementById('burger'), side=document.getElementById('side');
  if(burger&&side) burger.addEventListener('click',function(){ side.classList.toggle('open'); });
  // confirm deletes
  document.querySelectorAll('[data-confirm]').forEach(function(el){
    el.addEventListener('click',function(e){
      if(!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
  // auto-slug from a title field
  document.querySelectorAll('[data-slug-source]').forEach(function(src){
    var target=document.querySelector(src.getAttribute('data-slug-source'));
    if(!target) return;
    src.addEventListener('blur',function(){
      if(target.value.trim()==='')
        target.value=src.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    });
  });
})();
