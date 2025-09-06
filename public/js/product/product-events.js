import { CartManager } from "../cart/CartManager.js";
import { CartUI } from "../cart/CartUI.js";
import { CheckoutUI } from "../checkout/CheckoutUI.js";
import { showCartModal } from "../components/modal.js";
import { showAlert } from "../helpers/helper.js";
import { ProductUI } from "../product/ProductUI.js";
import { ProductManager } from "../product/ProductManager.js";

export function initializeProductEvents() {
  const oneProductContainer = document.querySelector("#oneProductContainer");
  const userProductList = document.querySelector("#sekcijaProizvodi");
  const productEditContainer = document.querySelector("#productEditContainer");
  const adminProductsListContainer = document.querySelector(
    "#adminProductsListContainer"
  );
  const productCreateContainer = document.querySelector(
    "#productCreateContainer"
  );
  const confirmDeleteBtn = document.querySelector("#confirmDeleteBtn");

  const loadUserProducts = async () => {
    if (!userProductList) return;
    const data = await ProductManager.getAll();
    const userProductsRow = userProductList.querySelector(".row");
    ProductUI.showUserProducts(userProductsRow, data);
  };
  loadUserProducts();

  if (
    adminProductsListContainer &&
    localStorage.getItem("productUpdateMessage")
  ) {
    showAlert(localStorage.getItem("productUpdateMessage"), "success");
    localStorage.removeItem("productUpdateMessage");
  }

  adminProductsListContainer?.addEventListener("click", async (e) => {
    if (e.target.id === "deleteProductBtn") {
      const row = e.target.closest("tr");
      ProductUI.openProductDeleteModal(row);
    }
  });

  confirmDeleteBtn?.addEventListener("click", async (e) => {
    if (!ProductUI.selectedProductId) return;
    try {
      const data = await ProductManager.delete(ProductUI.selectedProductId);
      if (!data.success) {
        showAlert(data.error, "danger");
        return;
      }
      ProductUI.removeProductAndCloseModal();
      showAlert(data.message, "success");
    } catch (err) {
      console.error("Greška prilikom brisanja:", err);
      showAlert("Greška u komunikaciji sa serverom", "danger");
    }
  });

  oneProductContainer?.addEventListener("click", async (event) => {
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
      const name =
        oneProductContainer.querySelector(".product-name").textContent;
      const price = parseFloat(
        oneProductContainer.querySelector(".product-price").textContent
      );
      CheckoutUI.removeProduct(productId);
      CheckoutUI.addProduct({ productId, name, price });
    }
  });

  productEditContainer?.addEventListener("click", async (e) => {
    try {
      if (e.target.dataset.id) {
        const data = ProductUI.collectProductFormData();
        const response = await ProductManager.update(e.target.dataset.id, data);

        if (response.redirect) {
          localStorage.setItem("productUpdateMessage", response.message);
          window.location.href = response.redirect;
        }
      }
    } catch (err) {
      showAlert("Greška u komunikaciji sa serverom", "danger");
      console.error("Greška u komunikaciji sa serverom: ", err);
    }
  });

  productCreateContainer?.addEventListener("click", async (e) => {
    try {
      if (e.target.id === "addProductBtn") {
        const data = ProductUI.collectProductFormData();
        const response = await ProductManager.add(data);
        if (response.redirect) {
          localStorage.setItem("productUpdateMessage", response.message);
          window.location.href = response.redirect;
        }
      }
    } catch (err) {
      showAlert("Greška u komunikaciji sa serverom", "danger");
      console.error("Greška u komunikaciji sa serverom: ", err);
    }
  });
}
