var toggle = document.querySelector('.nav-toggle');
var nav = document.querySelector('.site-nav');
if (toggle && nav) {
  toggle.onclick = function () {
    nav.classList.toggle('open');
  };
}
