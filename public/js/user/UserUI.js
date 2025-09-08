export class UserUI {
  static selectedUserId = null;
  static selectedListItem = null;
  static openUserDeleteModal(row) {
    if (!row || !row.dataset.id) return;
    this.selectedUserId = row.dataset.id;
    this.selectedListItem = row;
    document.getElementById("modalUser").textContent =
      row.dataset.username || "";
    const modal = new bootstrap.Modal(
      document.getElementById("deleteUserModal")
    );
    modal.show();
  }

  static removeUserAndCloseModal() {
    this.selectedListItem.remove();
    const modalElement = document.getElementById("deleteUserModal");
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
  }

  static collectUserCreateForm() {
    return {
      username: document.getElementById("username").value || "",
      password: document.getElementById("password").value || "",
      email: document.getElementById("email").value || "",
      admin: document.getElementById("admin").checked || 0,
      master: document.getElementById("master").checked || 0,
      active: document.getElementById("active").checked || 0,
    };
  }

  static collectUserEditForm() {
    return {
      username: document.getElementById("username").value || "",
      email: document.getElementById("email").value || "",
      admin: document.getElementById("admin").checked || 0,
      master: document.getElementById("master").checked || 0,
      active: document.getElementById("active").checked || 0,
    };
  }

  static collectUserPassword() {
    return {
      new_password: document.getElementById("new_password").value || "",
      confirm_password: document.getElementById("confirm_password").value || "",
    };
  }

  static closeResetPasswordModal() {
    const modalElement = document.getElementById("resetPasswordModal");
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
  }
}
