export class CartManager {
  static async add(productId, quantity) {
    try {
      const res = await fetch("/korpa", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ productId, quantity }),
      });
      return await res.json();
    } catch (error) {
      console.error("Greška u komunikaciji sa serverom: " + error, "danger");
    }
  }

  static async remove(productId) {
    try {
      const res = await fetch(`/korpa/remove/${productId}`, {
        method: "DELETE",
      });
      return await res.json();
    } catch (error) {
      console.error("Greška u komunikaciji sa serverom: " + error, "danger");
    }
  }
}
