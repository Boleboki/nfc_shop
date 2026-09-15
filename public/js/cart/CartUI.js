export class CartUI {
  static updateTotal(priceChange) {
    const el = document.querySelector("#cart-products tfoot #total span");
    if (!el) return;
    el.textContent = parseFloat(el.textContent) + priceChange;
  }

  static updateProductTotal(row, amount) {
    const el = row.querySelector(".product-prices-all span");
    if (!el) return;
    el.textContent = parseFloat(el.textContent) + amount;
  }

  static updateQuantity(row, amount) {
    const el = row.querySelector(".product-quantity");
    if (!el) return;
    el.textContent = parseInt(el.textContent) + amount;
  }

  static removeRow(row) {
    row.remove();
  }

  static isEmpty() {
    const rows = document.querySelectorAll("#cart-products tbody tr");
    return rows.length === 0;
  }

  static destroy() {
    this.showEmptyMessage();
    this.destroyCartIcon();
    const tbody = document.querySelectorAll("#cart-products tbody");
    if (!tbody);
    tbody.innerHTML = "";
  }

  static showEmptyMessage() {
    const container = document.querySelector("#cart-container");
    container.innerHTML += `<div class="alert alert-info">Korpa je prazna.</div>`;
    document.querySelector("#cart-products")?.remove();
  }

  static destroyCartIcon() {
    const button = document.querySelector("#cartBtn");
    if (!button) return;
    const badge = button.querySelector("span");
    if (!badge) return;
    badge.remove();
  }

  static updateCartIcon(delta) {
    const cartBtn = document.querySelector("#cartBtn");
    let badge = cartBtn.querySelector("span");
    if (!badge) {
      badge = document.createElement("span");
      badge.className =
        "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary cart-counter";
      badge.textContent = "0";
      cartBtn.appendChild(badge);
    }

    let currentCount = parseInt(badge.textContent) || 0;
    let newCount = currentCount + delta;

    // Ensure count is not negative
    newCount = Math.max(newCount, 0);

    if (newCount > 0) {
      badge.textContent = newCount;
      badge.style.display = "inline";
    } else {
      badge.style.display = "none";
    }
  }
}
