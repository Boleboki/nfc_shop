import { url } from "../helpers/helper.js";

export class CartManager {
  static async add(productId, quantity) {
    try {
      const response = await fetch(url("/korpa"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ productId, quantity }),
      });
      return await response.json();
    } catch (error) {
      console.error("Error adding product in cart: ", error);
      throw error;
    }
  }

  static async remove(productId) {
    try {
      const response = await fetch(url(`/korpa/remove/${productId}`), {
        method: "DELETE",
      });
      return await response.json();
    } catch (error) {
      console.error("Error removing product from cart: ", error);
      throw error;
    }
  }
}
