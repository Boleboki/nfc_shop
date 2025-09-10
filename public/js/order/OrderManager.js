import { url } from "../helpers/helper.js";

export class OrderManager {
  static async getAll() {
    try {
      const response = await fetch(url(`/api/admin/orders`));
      if (!response.ok) {
        const serverText = await response.text();
        console.error("Server error text: ", serverText);
        throw new Error("Server error text: " + serverText);
      }
      return await response.json();
    } catch (error) {
      console.error("Error getting all orders: ", error);
      throw error;
    }
  }
  static async changeStatus(orderId, status) {
    try {
      const response = await fetch(url(`/admin/orders/status/${orderId}`), {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ status: status }),
      });
      if (!response.ok) {
        const serverText = await response.text();
        console.error("Server error text: ", serverText);
        throw new Error("Server error text: " + serverText);
      }
      return await response.json();
    } catch (error) {
      console.error("Error changing order status: ", error);
      throw error;
    }
  }
}
