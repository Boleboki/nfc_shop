import { showAlert, onClickIfExists, url } from "./helpers/helper.js";

export class Auth {
  static async login(username, password) {
    try {
      const response = await fetch(url("/admin/login"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password }),
      });
      return await response.json();
    } catch (err) {
      showAlert("Greška u komunikaciji sa serverom", "danger");
    }
  }

  static async logout() {
    try {
      const response = await fetch(url("/admin/logout"), {
        method: "DELETE",
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      showAlert("Greška u komunikaciji sa serverom", "danger");
    }
  }
}

onClickIfExists("loginBtn", async () => {
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;
  try {
    const data = await Auth.login(username, password);

    if (!data.success) {
      showAlert(data.error || "Došlo je do greške", "danger");
      return;
    }

    if (data.redirect) {
      window.location.href = data.redirect;
    }
  } catch (err) {
    console.error("Greška prilikom logina:", err);
    showAlert("Greška u komunikaciji sa serverom", "danger");
  }
});

onClickIfExists("logoutBtn", async () => {
  try {
    const data = await Auth.logout();
    if (data.redirect) window.location.href = data.redirect;
  } catch (err) {
    showAlert("Greška u komunikaciji sa serverom: " + err, "danger");
  }
});
