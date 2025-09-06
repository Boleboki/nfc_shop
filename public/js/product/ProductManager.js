import { showAlert, url } from "../helpers/helper.js";

export class ProductManager {
  static async getAll() {
    try {
      const response = await fetch(url("/api/products"));
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Greška u komunikaciji sa serverom: ", err);
      throw err;
    }
  }

  static async update(product_id, data) {
    try {
      const response = await fetch(url(`/admin/products/${product_id}`), {
        method: "PUT",
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
      console.error("Greška u komunikaciji sa serverom: ", err);
      throw err;
    }
  }

  static async add(data) {
    try {
      const response = await fetch(url(`/admin/products`), {
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
      console.error("Greška u komunikaciji sa serverom: ", err);
      throw err;
    }
  }

  static async delete(product_id) {
    try {
      const response = await fetch(url(`/admin/products/${product_id}`), {
        method: "DELETE",
      });
      if (!response.ok) {
        const errorText = await response.text();
        console.error("Server error text: ", errorText);
        throw new Error("Server error text: " + errorText);
      }
      return await response.json();
    } catch (err) {
      console.error("Greška u komunikaciji sa serverom: ", err);
      throw err;
    }
  }
}
