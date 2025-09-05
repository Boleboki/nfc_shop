import { showAlert, onClickIfExists } from "./helpers/helper.js";

class UserManager {
  /**
   * Dodaje novog korisnika slanjem POST zahteva
   * @param {string} username - korisničko ime
   * @param {string} password - lozinka
   * @param {boolean} isAdmin - da li je korisnik admin
   * @returns {Promise<object>} odgovor sa servera u JSON formatu
   */
  static async add(username, password, isAdmin) {
    const response = await fetch("/users", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password, isAdmin }),
    });
    return await response.json();
  }

  /**
   * Izmenjuje korisnika sa zadatim ID-em slanjem PUT zahteva
   * @param {string} userId - ID korisnika
   * @param {string} username - novo korisničko ime
   * @param {string} password - nova lozinka
   * @param {boolean} isAdmin - nova admin privilegija
   * @returns {Promise<object>} odgovor sa servera u JSON formatu
   */
  static async edit(userId, username, password, isAdmin) {
    const response = await fetch(`/users/${userId}`, {
      // dodata ispravna sintaksa za template literal
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password, isAdmin }),
    });
    return await response.json();
  }

  /**
   * Briše korisnika sa zadatim ID-em slanjem DELETE zahteva
   * @param {string} userId - ID korisnika
   * @returns {Promise<object>} odgovor sa servera u JSON formatu
   */
  static async delete(userId) {
    const response = await fetch(`/users/${userId}`, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
    });
    return await response.json();
  }
}

onClickIfExists("addUserBtn", async () => {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const isAdmin = document.getElementById("admin").value;

  try {
    const data = await UserManager.add(username, password, isAdmin);
    showAlert(data.message || data.error, data.success ? "success" : "danger");
  } catch (e) {
    showAlert("Greška u dodavanju korisnika", "danger");
  }
});

onClickIfExists("editUserBtn", async () => {
  const userId = document.getElementById("editUserBtn").getAttribute("data-id");
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const isAdmin = document.getElementById("admin").value;

  try {
    const data = await UserManager.edit(userId, username, password, isAdmin);
    showAlert(data.message || data.error, data.success ? "success" : "danger");
  } catch (e) {
    showAlert("Greška u izmeni korisnika", "danger");
  }
});

let selectedUserId = null;
let selectedListItem = null;

document.querySelectorAll(".delete-btn").forEach((button) => {
  button.addEventListener("click", (e) => {
    selectedUserId = e.currentTarget.dataset.id;
    selectedListItem = e.currentTarget.closest("tr");
    document.getElementById("modalUser").textContent =
      e.currentTarget.dataset.username;
    new bootstrap.Modal(document.getElementById("deleteUserModal")).show();
  });
});

onClickIfExists("confirmDeleteBtn", async () => {
  if (!selectedUserId) return;

  try {
    const data = await UserManager.delete(selectedUserId);
    if (data.success) {
      showAlert(data.message, "danger");
      selectedListItem.remove();
      bootstrap.Modal.getInstance(
        document.getElementById("deleteUserModal")
      ).hide();
    } else {
      showAlert(data.error, "danger");
    }
  } catch (e) {
    showAlert("Greška prilikom brisanja", "danger");
  }
});
