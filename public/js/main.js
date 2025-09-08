import { initializeCartEvents } from "./cart/cart-events.js";
import { initializeCheckoutEvents } from "./checkout/checkout-events.js";
import { initializeProductEvents } from "./product/product-events.js";
import { initializeAuthEvents } from "./auth/auth-events.js";
import { initializeGalleryEvents } from "./gallery/gallery-events.js";
import { initializeUserEvents } from "./user/user-events.js";

document.addEventListener("DOMContentLoaded", () => {
  initializeCartEvents();
  initializeProductEvents();
  initializeCheckoutEvents();
  initializeAuthEvents();
  initializeGalleryEvents();
  initializeUserEvents();
});

// onClickIfExists("addUserBtn", () => {
//   const username = document.getElementById('username').value;
//   const password = document.getElementById('password').value;
//   const isAdmin = document.getElementById('admin').value;
//   UserManager.addUser(username, password, isAdmin)
//   .then(data => {
//     if (data.success) {
//       showAlert(data.message, "success");
//     } else {
//       showAlert(data.error, "danger");
//     }
//   })
//   .catch(error => console.error('Fetch error:', error));
// });

// onClickIfExists("editUserBtn", () => {
//   const username = document.getElementById('username').value;
//   const password = document.getElementById('password').value;
//   const isAdmin = document.getElementById('admin').value;
//   const userId = document.getElementById("editUserBtn").getAttribute("data-id");
//   UserManager.editUser(userId,username, password, isAdmin)
//   .then(data => {
//     if (data.success) {
//       showAlert(data.message, "success");
//     } else {
//       showAlert(data.error, "danger");
//     }
//   })
//   .catch(error => console.error('Fetch error:', error));
// });
