(function(){
    const html = document.documentElement;
    const btn = document.getElementById('themeToggle');
    const cur = localStorage.getItem('theme') || 'light';
    if (cur==='dark') html.setAttribute('data-bs-theme','dark');
    btn && (btn.textContent = (html.getAttribute('data-bs-theme')==='dark' ? '☀️ Light' : '🌙 Dark'));
    btn && btn.addEventListener('click', () => {
      const isDark = html.getAttribute('data-bs-theme')==='dark';
      html.setAttribute('data-bs-theme', isDark?'light':'dark');
      localStorage.setItem('theme', isDark?'light':'dark');
      btn.textContent = isDark ? '🌙 Dark' : '☀️ Light';
    });
  
    const copyBtn = document.getElementById('copyLinkBtn');
    if(copyBtn){
      copyBtn.addEventListener('click', async () => {
        const id = copyBtn.dataset.id;
        const url = `${location.origin}/post.php?id=${encodeURIComponent(id)}`;
        try{ await navigator.clipboard.writeText(url); copyBtn.textContent='Copied!'; setTimeout(()=>copyBtn.textContent='Copy link',1500);}catch(e){ alert('Gagal menyalin link'); }
      });
    }
  })();