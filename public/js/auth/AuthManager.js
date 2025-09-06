import { url } from "../helpers/helper.js";

export class AuthManager {
  static async login(username, password) {
    try {
      const response = await fetch(url("/admin/login"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password }),
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Greška u komunikaciji sa serverom", err);
      throw err;
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
      console.error("Greška u komunikaciji sa serverom", err);
      throw err;
    }
  }
}
