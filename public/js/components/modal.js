import { url } from "../helpers/helper.js";

export function showCartModal(product) {
  const modal = document.getElementById("cart-modal");
  if (!modal) return;
  modal.querySelector(".product-name").textContent = product.name;
  modal.querySelector("#cart-modal-img").src = url(`/img/${product.image_url}`);
  modal.style.display = "flex";

  document
    .getElementById("continue-shopping")
    ?.addEventListener("click", () => {
      modal.style.display = "none";
    });

  document.getElementById("go-to-cart")?.addEventListener("click", () => {
    window.location.href = "/korpa";
  });
}
