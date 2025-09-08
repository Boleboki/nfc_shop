import { UserUI } from "./UserUI.js";
import { UserManager } from "./UserManager.js";
import { showAlert } from "../helpers/helper.js";
import { Errors } from "../helpers/errors.js";
export function initializeUserEvents() {
  const adminUsersListContainer = document.querySelector(
    "#adminUsersListContainer"
  );
  const deleteUserModal = document.querySelector("#deleteUserModal");
  const userCreateForm = document.querySelector("#userCreateForm");
  const resetPasswordModal = document.querySelector("#resetPasswordModal");
  const editUserForm = document.querySelector("#editUserForm");

  adminUsersListContainer?.addEventListener("click", (e) => {
    if (e.target.id === "deleteUserBtn") {
      const row = e.target.closest("tr");
      UserUI.openUserDeleteModal(row);
    }
  });
  deleteUserModal?.addEventListener("click", async (e) => {
    if (e.target.id === "confirmDeleteBtn") {
      if (!UserUI.selectedUserId) return;
      try {
        const data = await UserManager.delete(UserUI.selectedUserId);
        if (!data.success) {
          showAlert(data.error || "Greška", "danger");
          return;
        }
        UserUI.removeUserAndCloseModal();
        showAlert(data.message, "success");
      } catch (err) {
        console.error("Greška prilikom brisanja:", err);
        showAlert("Greška u komunikaciji sa serverom", "danger");
      }
    }
  });

  userCreateForm?.addEventListener("click", async (e) => {
    if (e.target.id === "addUserBtn") {
      try {
        Errors.removeAllErrors();
        const data = UserUI.collectUserCreateForm();

        const response = await UserManager.add(data);
        if (!response.success) {
          if (response.error) {
            showAlert(response.error || "Greška", "danger");
            return;
          }

          for (const [field, messages] of Object.entries(response.errors)) {
            const errorField = document
              .getElementById(field)
              .parentElement.querySelector(".error-messages");
            Errors.displayErrors(errorField, messages);
          }
          return;
        }

        if (response.redirect) {
          window.location.href = response.redirect;
        }
        showAlert(response.message, "success");
      } catch (err) {
        console.error("Greška prilikom brisanja:", err);
        showAlert("Greška u komunikaciji sa serverom", "danger");
      }
    }
  });
  resetPasswordModal?.addEventListener("click", async (e) => {
    if (e.target.id === "confirmResetBtn") {
      if (!e.target.dataset.id) return;
      try {
        Errors.removeAllErrors(resetPasswordModal);
        const data = UserUI.collectUserPassword();
        const response = await UserManager.updatePassword(
          e.target.dataset.id,
          data
        );
        if (!response.success) {
          if (response.error) {
            showAlert(response.error, "danger");
            return;
          }

          for (const [field, messages] of Object.entries(response.errors)) {
            const errorField = document
              .getElementById(field)
              .parentElement.querySelector(".error-messages");
            Errors.displayErrors(errorField, messages);
          }
          return;
        }

        UserUI.closeResetPasswordModal();
        showAlert(response.message, "success");
      } catch (err) {
        console.error("Greška prilikom brisanja:", err);
        showAlert("Greška u komunikaciji sa serverom", "danger");
      }
    }
  });

  editUserForm?.addEventListener("click", async (e) => {
    if (e.target.id === "editUserBtn") {
      if (!e.target.dataset.id) return;
      try {
        Errors.removeAllErrors(editUserForm);
        const data = UserUI.collectUserEditForm();
        const response = await UserManager.edit(e.target.dataset.id, data);
        if (!response.success) {
          if (response.error) {
            showAlert(response.error, "danger");
            return;
          }

          for (const [field, messages] of Object.entries(response.errors)) {
            const errorField = document
              .getElementById(field)
              .parentElement.querySelector(".error-messages");
            Errors.displayErrors(errorField, messages);
          }
          return;
        }

        showAlert(response.message, "success");
      } catch (err) {
        console.error("Greška prilikom brisanja:", err);
        showAlert("Greška u komunikaciji sa serverom", "danger");
      }
    }
  });
}
