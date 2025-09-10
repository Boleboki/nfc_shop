import { url } from "../helpers/helper.js";

export class CheckoutManager {
  static async create(data) {
    try {
      const res = await fetch(url("/checkout"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      if (!res.ok) {
        throw new Error(`Server error: ${res.status}`);
      }
      return await res.json();
    } catch (error) {
      console.error("Checkout error: ", error);
      throw error;
    }
  }
}
