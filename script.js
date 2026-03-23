// ── SCROLL REVEAL ──
const reveals = document.querySelectorAll('.reveal');
const obs = new IntersectionObserver((entries) => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      setTimeout(() => e.target.classList.add('visible'), i * 80);
      obs.unobserve(e.target);
    }
  });
}, { threshold: 0.1 });
reveals.forEach(el => obs.observe(el));

// ── NAV ACTIVE HIGHLIGHT ──
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-links a');
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 120) current = s.id;
  });
  navLinks.forEach(a => {
    a.style.color = a.getAttribute('href') === '#' + current ? 'var(--navy)' : '';
  });
});

// ── KIRIM PESAN KE EMAIL VIA PHP ──
function kirimPesan() {
  const nama  = document.getElementById('nama').value.trim();
  const email = document.getElementById('email').value.trim();
  const pesan = document.getElementById('pesan').value.trim();
  const btn   = document.getElementById('btn-kirim');

  if (!nama || !email || !pesan) {
    tampilNotif('error', '⚠️ Semua field wajib diisi.');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Mengirim...';

  const formData = new FormData();
  formData.append('nama', nama);
  formData.append('email', email);
  formData.append('pesan', pesan);

  fetch('send_mail.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      tampilNotif('success', '✅ Pesan berhasil terkirim! Terima kasih, ' + nama + ' 🙏');
      document.getElementById('nama').value  = '';
      document.getElementById('email').value = '';
      document.getElementById('pesan').value = '';
    } else {
      tampilNotif('error', '❌ ' + data.message);
    }
  })
  .catch(() => {
    tampilNotif('error', '❌ Terjadi kesalahan koneksi. Coba lagi.');
  })
  .finally(() => {
    btn.disabled = false;
    btn.textContent = 'Kirim Pesan →';
  });
}

// ── HELPER NOTIFIKASI ──
function tampilNotif(tipe, pesan) {
  const notif = document.getElementById('form-notif');
  notif.style.display = 'block';
  notif.textContent = pesan;
  if (tipe === 'success') {
    notif.style.background = '#ddf4e8';
    notif.style.color = '#1a7a45';
  } else {
    notif.style.background = '#fde8e8';
    notif.style.color = '#a51a1a';
  }
  setTimeout(() => { notif.style.display = 'none'; }, 5000);
}

// ── HAMBURGER MENU ──
const navToggle = document.getElementById('nav-toggle');
const navLinksList = document.getElementById('nav-links');

navToggle.addEventListener('click', () => {
  navToggle.classList.toggle('active');
  navLinksList.classList.toggle('open');
});

function tutupMenu() {
  navToggle.classList.remove('active');
  navLinksList.classList.remove('open');
}

document.addEventListener('click', (e) => {
  if (!e.target.closest('nav')) {
    navToggle.classList.remove('active');
    navLinksList.classList.remove('open');
  }
});
