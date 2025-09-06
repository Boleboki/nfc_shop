export function initializeGalleryEvents() {
  const toggler = document.querySelector(".toggle-icon");
  const offcanvas = document.getElementById("offcanvasMenu");
  const row = document.getElementById("cardRow");
  offcanvas?.addEventListener("click", () => {
    toggler.classList.add("open");
  });
  if (offcanvas) {
    offcanvas.addEventListener("show.bs.offcanvas", () => {
      toggler.classList.add("open");
    });
    offcanvas.addEventListener("hide.bs.offcanvas", () => {
      toggler.classList.remove("open");
    });
  }
  if (row) {
    document.getElementById("scrollLeft").addEventListener("click", () => {
      row.scrollBy({ left: -300, behavior: "smooth" });
    });
    document.getElementById("scrollRight").addEventListener("click", () => {
      row.scrollBy({ left: 300, behavior: "smooth" });
    });
  }
}
