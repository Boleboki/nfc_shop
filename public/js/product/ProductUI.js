import { url } from "../helpers/helper.js";

export class ProductUI {
  static showUserProducts(container, data) {
    if (!container || !data) return;
    let html = "";
    data.forEach((product) => {
      html += `
        <div class="col-6 col-md-3" data-aos="zoom-in">
            <a href="${url(
              `/proizvodi/${product.url_name || ""}`
            )}" class="product-link">
            <div class="card h-100 shadow-sm text-center product-card">
                <img src="${url(
                  `/img/${product.image_url || ""}`
                )}" class="card-img-top img-fluid" alt="NFC Privezak">
                <div class="card-body">
                <h5 class="card-title">${product.name || ""}</h5>
                </div>
            </div>
            </a>
        </div>
        `;
    });
    container.innerHTML = html;
  }

  static collectProductFormData() {
    return {
      name: document.getElementById("name")?.value || "",
      description: document.getElementById("description")?.value || "",
      short_description:
        document.getElementById("short_description")?.value || "",
      price: document.getElementById("price")?.value || 0,
      stock_quantity: document.getElementById("quantity")?.value || 0,
      image_url: document.getElementById("photoPathInput")?.value || "",
    };
  }
  static selectedProductId = null;
  static selectedListItem = null;
  static openProductDeleteModal(row) {
    if (!row || !row.dataset.id) return;
    this.selectedProductId = row.dataset.id;
    this.selectedListItem = row;
    document.getElementById("modalProduct").textContent =
      row.dataset.name || "";
    const modal = new bootstrap.Modal(
      document.getElementById("deleteProductModal")
    );
    modal.show();
  }

  static removeProductAndCloseModal() {
    this.selectedListItem.remove();
    const modalElement = document.getElementById("deleteProductModal");
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
  }
}
