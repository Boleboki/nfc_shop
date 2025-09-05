import { CartManager } from "./CartManager.js";
import { CartUI } from "./CartUI.js";
import { CheckoutUI } from "../checkout/CheckoutUI.js";
import { showCartModal } from "../components/modal.js";
import { onClickIfExists } from "../helpers/helper.js";

export function initializeCartEvents() {
  const container = document.querySelector("#cart-products");

  if (container) {
    container.addEventListener("click", async (event) => {
      const target = event.target;
      const row = target.closest("tr");

      if (target.closest(".remove-product-btn")) {
        const productId = target.getAttribute("data-id");
        const sum = parseFloat(
          row.querySelector(".product-prices-all span").textContent
        );
        const quantity = parseInt(
          row.querySelector(".product-quantity").textContent
        );
        CartUI.updateTotal(-sum);
        CartUI.removeRow(row);

        if (CartUI.isEmpty()) CartUI.showEmptyMessage();
        CartUI.updateCartIcon(-quantity);
        CheckoutUI.removeProduct(productId);
        await CartManager.remove(productId);
      }

      if (target.closest(".increase-btn")) {
        const productId = target.getAttribute("data-id");
        const price = parseFloat(
          row.querySelector(".product-prices").textContent
        );
        const name = row.querySelector(".product-name").textContent;
        CartUI.updateProductTotal(row, price);
        CartUI.updateTotal(price);
        CartUI.updateQuantity(row, 1);
        CartUI.updateCartIcon(1);
        CheckoutUI.addProduct({ productId, name, price });
        await CartManager.add(productId, 1);
      }

      if (target.closest(".decrease-btn")) {
        const productId = target.getAttribute("data-id");
        const price = parseFloat(
          row.querySelector(".product-prices").textContent
        );
        const quantity = parseInt(
          row.querySelector(".product-quantity").textContent
        );
        if (quantity <= 1) return;

        CartUI.updateProductTotal(row, -price);
        CartUI.updateTotal(-price);
        CartUI.updateQuantity(row, -1);
        CartUI.updateCartIcon(-1);
        CheckoutUI.decreaseQuantity(productId);
        await CartManager.add(productId, -1);
      }
    });
  }
}
