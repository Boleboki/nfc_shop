import { showAlert } from "../helpers/helper.js";
import { AuthUI } from "./AuthUI.js";
import { AuthManager } from "./AuthManager.js";
import { Errors } from "../helpers/errors.js";

export function initializeAuthEvents() {
  const adminLoginForm = document.querySelector("#adminLoginForm");
  const adminNavbar = document.querySelector("#adminNavbar");

  adminLoginForm?.addEventListener("click", async (e) => {
    if (e.target.id === "loginBtn") {
      try {
        Errors.removeAllErrors();
        const data = AuthUI.getLoginFormData();

        const response = await AuthManager.login(data.username, data.password);

        if (!response.success) {
          if (response.error) {
            const mainErrorField = document.querySelector("#mainErrorField");
            Errors.displayErrors(mainErrorField, response.error);
            return;
          }
          for (const [field, messages] of Object.entries(response.errors)) {
            const errorField = document
              .getElementById(field)
              .parentElement.querySelector(".error-messages");
            Errors.displayErrors(errorField, messages);
          }
        }
        if (response.redirect) {
          window.location.href = response.redirect;
        }
      } catch (err) {
        console.error("Greška prilikom logina:", err);
        showAlert("Greška u komunikaciji sa serverom", "danger");
      }
    }
  });

  adminNavbar?.addEventListener("click", async (e) => {
    if (e.target.id === "logoutBtn") {
      try {
        const response = await AuthManager.logout();
        if (response.redirect) window.location.href = response.redirect;
      } catch (err) {
        showAlert("Greška u komunikaciji sa serverom: " + err, "danger");
      }
    }
  });
}
