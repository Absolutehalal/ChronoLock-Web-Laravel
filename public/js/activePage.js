// const activePage = window.location.pathname;
// const navLinks = document.querySelectorAll('aside a').forEach(link => {
//   if(link.href.includes(`${activePage}`)){
//     link.classList.add('active');
//     // console.log(`${activePage}`); => for displaying pathname in console.log
//   }
// })
const activePage = window.location.pathname;
const navLinks = document.querySelectorAll('aside a:not(.app-brand a)');

navLinks.forEach(link => {
  if (link.href.includes(activePage)) {
    link.classList.add('active');
  }
});