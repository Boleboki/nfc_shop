import { CheckoutManager } from "./CheckoutManager.js";
import { CheckoutUI } from "./CheckoutUI.js";
import { CartUI } from "../cart/CartUI.js";
export function initializeCheckoutEvents() {
  const container = document.querySelector("#checkoutModal");

  if (container) {
    container.addEventListener("click", async (e) => {
      if (e.target.closest("#submitOrder")) {
        const name = document.querySelector("#name").value;
        const surname = document.querySelector("#surname").value;
        const phone = document.querySelector("#phone").value;
        const email = document.querySelector("#email").value;
        const city = document.querySelector("#city").value;
        const postcode = document.querySelector("#postcode").value;
        const address = document.querySelector("#address").value;
        const values = {
          name: name,
          surname: surname,
          phone: phone,
          email: email,
          city: city,
          postcode: postcode,
          address: address,
        };
        let products = [];
        document.querySelectorAll('[id^="product-"]').forEach((product) => {
          const product_id = parseInt(product.id.split("-")[1]);
          const quantity = parseInt(
            product.querySelector(".product-quantity-checkout").textContent
          );
          products.push({
            product_id: product_id,
            quantity: quantity,
          });
        });
        values.products = products;
        const data = await CheckoutManager.create(values);
        CheckoutUI.closeModal();
        CartUI.destroy();
      }
    });
  }
}
