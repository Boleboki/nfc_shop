import { CartManager } from "../cart/CartManager.js";
import { CartUI } from "../cart/CartUI.js";
import { CheckoutUI } from "../checkout/CheckoutUI.js";
import { showCartModal } from "../components/modal.js";
import { onClickIfExists } from "../helpers/helper.js";

export function initializeProductEvents() {
  const container = document.querySelector(".product-container");

  if (container) {
    container.addEventListener("click", async (event) => {
      if (event.target.closest("#addToCart")) {
        const productId = event.target?.getAttribute("data-id");
        if (!productId) return;
        const data = await CartManager.add(productId, 1);
        if (data) {
          showCartModal(data.product);
          CartUI.updateCartIcon(1);
        }
      }
      if (event.target.closest("#orderNow")) {
        const productId = event.target?.getAttribute("data-id");
        if (!productId) return;
        const name = container.querySelector(".product-name").textContent;
        const price = parseFloat(
          container.querySelector(".product-price").textContent
        );
        CheckoutUI.removeProduct(productId);
        CheckoutUI.addProduct({ productId, name, price });
      }
    });
  }
}
