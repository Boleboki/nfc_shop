import {
  showAlert,
  onClickIfExists,
  onClickIfExistsClass,
} from "./helpers/helper.js";
import { initializeCartEvents } from "./cart/cart-events.js";
import { initializeCheckoutEvents } from "./checkout/checkout-events.js";
import { initializeProductEvents } from "./product/product-events.js";
const toggler = document.querySelector(".toggle-icon");
const offcanvas = document.getElementById("offcanvasMenu");

onClickIfExists("offcanvasMenu", () => {
  toggler.classList.add("open");
});

if (offcanvas) {
  offcanvas.addEventListener("show.bs.offcanvas", () => {
    toggler.classList.add("open");
  });

  offcanvas.addEventListener("hide.bs.offcanvas", () => {
    toggler.classList.remove("open");
  });
}
const row = document.getElementById("cardRow");
if (row) {
  document.getElementById("scrollLeft").addEventListener("click", () => {
    row.scrollBy({ left: -300, behavior: "smooth" });
  });
  document.getElementById("scrollRight").addEventListener("click", () => {
    row.scrollBy({ left: 300, behavior: "smooth" });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initializeCartEvents();
  initializeProductEvents();
  initializeCheckoutEvents();
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
