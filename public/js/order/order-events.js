import { showAlert } from "../helpers/helper.js";
import { OrderUI } from "./OrderUI.js";
import { OrderManager } from "./OrderManager.js";
import { Errors } from "../helpers/errors.js";

export function initializeOrderEvents() {
  const adminOrderListContainer = document.querySelector(
    "#adminOrderListContainer"
  );

  const statusFilter = document.querySelector("#statusFilter");

  async function loadAndRenderOrdersTable() {
    if (!adminOrderListContainer) return;
    const row = adminOrderListContainer.querySelector(".row");

    const data = await OrderManager.getAll();
    OrderUI.status = statusFilter.value ?? "all";
    OrderUI.renderTable(row, data);
  }

  loadAndRenderOrdersTable();

  adminOrderListContainer?.addEventListener("click", async (e) => {
    if (e.target.classList.contains("change-status")) {
      const orderCard = e.target.closest(".order-card");
      const badge = orderCard.querySelector("#badgeStatus");
      const mainChangeStatusBtn = orderCard.querySelector(
        "#mainChangeStatusBtn"
      );
      const status = e.target.dataset.status;
      const orderId = e.target.dataset.id;
      const response = await OrderManager.changeStatus(orderId, status);
      if (!response.success) {
        showAlert(response.error || "Greška", "danger");
        return;
      }
      OrderUI.changeBadgeStatus(badge, status);
      OrderUI.changeButtonStatusText(mainChangeStatusBtn, status);
      OrderUI.removeIfDifferentStatus(orderCard, status);
      OrderUI.updateDataStatus(orderId, status);
    }
  });
  statusFilter?.addEventListener("change", () => {
    OrderUI.changeStatusAndRender(statusFilter.value);
  });
}
