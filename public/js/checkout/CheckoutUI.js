export class CheckoutUI {
  static updateTotal(amount) {
    const totalEl = document.querySelector("#totalPriceCheckout");
    if (!totalEl) return;
    const newTotal = parseFloat(totalEl.textContent) + amount;
    totalEl.textContent = newTotal.toFixed(2);
  }

  static recalculateTotal() {
    let sum = 0;
    document.querySelectorAll(".cart-item").forEach((item) => {
      const price = parseFloat(item.dataset.price);
      const quantity = parseInt(
        item.querySelector(".product-quantity").textContent
      );
      sum += price * quantity;
    });

    const shipping = document.querySelector("#bexShipping");
    if (shipping && shipping.checked) {
      sum += window.TROSKOVI_DOSTAVE || 0;
    }

    const totalEl = document.querySelector("#totalPrice");
    if (totalEl) {
      totalEl.textContent = sum.toFixed(2);
    }
  }

  static closeModal() {
    const modalElement = document.getElementById("checkoutModal");
    const modalInstance =
      bootstrap.Modal.getInstance(modalElement) ||
      new bootstrap.Modal(modalElement);
    modalInstance.hide();
  }

  static addProduct({ productId, name, price }) {
    const cart = document.querySelector("#cart-items");
    let item = document.querySelector(`#product-${productId}`);
    if (item) {
      const quantityEl = item.querySelector(".product-quantity-checkout");
      let quantity = parseInt(quantityEl.textContent);
      quantity++;
      quantityEl.textContent = quantity;

      const total = (price * quantity).toFixed(2);
      item.querySelector(".product-total-checkout").textContent = total;
    } else {
      const newItem = document.createElement("div");
      newItem.className =
        "d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 cart-item";
      newItem.id = `product-${productId}`;
      newItem.dataset.price = price;

      newItem.innerHTML = `
                <div>
                    <strong>${name}</strong><br>
                    <span class="product-price-checkout">${price}</span>
                     RSD × 
                    <span class="product-quantity-checkout">1</span>
                </div>
                <div>
                    <span class="product-total-checkout">${price}</span> RSD
                </div>
            `;
      // <button class="btn btn-sm btn-danger ms-2 remove-product" data-id="${productId}">×</button>

      cart.appendChild(newItem);

      // newItem.querySelector(".remove-product").addEventListener("click", () => {
      //     this.removeProduct(id);
      // });
    }

    this.updateTotal(price);
  }

  static decreaseQuantity(id) {
    const item = document.querySelector(`#product-${id}`);
    if (!item) return;

    const quantityEl = item.querySelector(".product-quantity-checkout");
    let quantity = parseInt(quantityEl.textContent);
    const price = parseFloat(
      item.querySelector(".product-price-checkout").textContent
    );

    if (quantity > 1) {
      quantity--;
      quantityEl.textContent = quantity;
      console.log(quantity, price);
      item.querySelector(".product-total-checkout").textContent = (
        price * quantity
      ).toFixed(2);
      this.updateTotal(-price);
    } else {
      this.removeProduct(id);
    }
  }

  static removeProduct(id) {
    const item = document.querySelector(`#product-${id}`);
    if (!item) return;
    const price = parseFloat(
      item.querySelector(".product-price-checkout").textContent
    );
    const quantity = parseInt(
      item.querySelector(".product-quantity-checkout").textContent
    );
    this.updateTotal(-price * quantity);
    item.remove();
  }
}
