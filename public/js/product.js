import { showAlert, onClickIfExists } from "./helpers/helper.js";

export class ProductManager {
  // static addProduct(username, password, isAdmin) {
  //   return fetch('/users', {
  //     method: 'POST',
  //     headers: { 'Content-Type': 'application/json' },
  //     body: JSON.stringify({ username, password, isAdmin })
  //   }).then(res => res.json());
  // }
  // static editProduct(user, username, password, isAdmin){
  //   return fetch(`/users/${user}`, {
  //     method: 'PATCH',
  //     headers: { 'Content-Type': 'application/json' },
  //     body: JSON.stringify({ username, password, isAdmin })
  //   }).then(res => res.json());
  // }
  static async delete(product) {
    try {
      const response = await fetch(`/admin/products/${product}`, {
        method: "DELETE",
      });
      return await response.json();
    } catch (err) {
      showAlert("Greška u komunikaciji sa serverom", "danger");
    }
  }
}

let selectedUserId = null;
let selectedListItem = null;

document.querySelectorAll(".delete-btn").forEach((button) => {
  button.addEventListener("click", (e) => {
    selectedUserId = e.currentTarget.getAttribute("data-id");
    selectedListItem = e.currentTarget.closest("tr");
    document.getElementById("modalUser").textContent =
      e.currentTarget.getAttribute("data-name");

    const modal = new bootstrap.Modal(
      document.getElementById("deleteUserModal")
    );
    modal.show();
  });
});

onClickIfExists("confirmDeleteBtn", async () => {
  if (!selectedUserId) return;
  try {
    const data = await ProductManager.delete(selectedUserId);
    if (data.success) {
      showAlert(data.message, "success");
      selectedListItem.remove();

      const modalElement = document.getElementById("deleteUserModal");
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    } else {
      showAlert(data.error, "danger");
    }
  } catch (err) {
    console.error("Greška prilikom brisanja:", err);
    showAlert("Greška u komunikaciji sa serverom", "danger");
  }
});
