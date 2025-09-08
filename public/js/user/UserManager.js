import { url } from "../helpers/helper.js";
export class UserManager {
  static async add(data) {
    try {
      const response = await fetch(url(`/admin/users`), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Error creating user: ", err);
      throw err;
    }
  }

  static async edit(userId, data) {
    try {
      // Slanje PUT zahteva sa JSON telom koje sadrži nove podatke korisnika
      const response = await fetch(url(`/admin/users/${userId}`), {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      // Provera uspešnosti odgovora
      if (!response.ok) {
        const errorText = await response.text(); // Dohvatanje poruke greške sa servera
        console.error("Server error text:", errorText);
        throw new Error("Server error text: " + errorText);
      }
      // Parsiranje i vraćanje JSON odgovora
      return await response.json();
    } catch (err) {
      console.error("Error editing user: ", err);
      throw err; // Prosleđivanje greške višem sloju
    }
  }

  static async updatePassword(userId, data) {
    try {
      const response = await fetch(url(`/admin/users/${userId}`), {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text:", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Error updating password: ", err);
      throw err;
    }
  }

  static async delete(userId) {
    try {
      const response = await fetch(url(`/admin/users/${userId}`), {
        method: "DELETE",
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Error deleting user: ", err);
      throw err;
    }
  }
}
