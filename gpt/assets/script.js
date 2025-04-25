function toggleTheme() {
  const html = document.documentElement;
  const btn = document.getElementById("themeToggle");

  if (html.getAttribute("data-theme") === "dark") {
    html.setAttribute("data-theme", "light");
    btn.innerText = "🌙 Dark Mode";
  } else {
    html.setAttribute("data-theme", "dark");
    btn.innerText = "☀️ Light Mode";
  }

  // Simpan preferensi
  localStorage.setItem("theme", html.getAttribute("data-theme"));
}

// Saat halaman pertama kali dibuka, ambil preferensi
window.onload = function () {
  const saved = localStorage.getItem("theme") || "dark";
  const html = document.documentElement;
  const btn = document.getElementById("themeToggle");

  html.setAttribute("data-theme", saved);
  btn.innerText = saved === "dark" ? "☀️ Light Mode" : "🌙 Dark Mode";
};
