import { url } from "../helpers/helper.js";

export class OrderUI {
  static colorStatusMap = {
    pending: "bg-warning text-dark",
    processing: "bg-info text-dark",
    shipped: "bg-primary",
    delivered: "bg-success",
    completed: "bg-success",
    cancelled: "bg-danger",
  };

  static nextStatusMap = {
    pending: "processing",
    processing: "shipped",
    shipped: "delivered",
    delivered: "completed",
    completed: "completed",
    cancelled: "pending",
  };

  static changeBadgeStatus(badge, status) {
    const classes = this.colorStatusMap[status] || "";
    badge.className = `badge text-capitalize ${classes}`;
    badge.textContent = status;
  }

  static changeButtonStatusText(button, status) {
    const nextStatus = this.nextStatusMap[status];
    button.textContent = `Mark as ${nextStatus}`;
    button.dataset.status = nextStatus;
  }

  static removeIfDifferentStatus(card, status) {
    const nextStatus = this.nextStatusMap[status];
    if (this.status === "all" || this.status === nextStatus || !card) return;
    card.remove();
  }

  static updateDataStatus(orderId, status) {
    this.data[orderId].status = status;
    console.log("updateDataStatus", this.data);
  }

  static data = [];
  static status = null;
  static field = null;
  static formatDate(dateString) {
    const date = new Date(dateString);
    if (isNaN(date)) return ""; // fallback ako nije validan datum

    const dd = String(date.getDate()).padStart(2, "0");
    const mm = String(date.getMonth() + 1).padStart(2, "0");
    const yyyy = date.getFullYear();

    const hh = String(date.getHours()).padStart(2, "0");
    const min = String(date.getMinutes()).padStart(2, "0");

    return `${dd}.${mm}.${yyyy} ${hh}:${min}`;
  }
  static changeStatusAndRender(status) {
    this.status = status;
    this.renderTable();
  }
  static filterStatusData(data = this.data, status = this.status) {
    if (!status || !data) return data;
    const value = status.toLowerCase();
    return Object.fromEntries(
      Object.entries(data).filter(([id, order]) => {
        return order.status === value || value === "all";
      })
    );
  }

  static renderTable(field = this.field, data = this.data) {
    if (!field) return;
    // helper: siguran tekst
    const safeText = (value) => document.createTextNode(value ?? "");
    field.innerHTML = ""; // očisti pre renderovanja

    this.field = field;
    this.data = data;

    data = this.filterStatusData(data);

    for (const order of Object.values(data)) {
      const status = order.status ?? "pending";
      const nextStatus = this.nextStatusMap[status] ?? "processing";

      // order card
      const orderCard = document.createElement("div");
      orderCard.classList.add("col-12", "order-card");
      orderCard.dataset.status = status;

      const card = document.createElement("div");
      card.classList.add("card", "shadow-sm");

      // header
      const cardHeader = document.createElement("div");
      cardHeader.classList.add(
        "card-header",
        "d-flex",
        "justify-content-between",
        "align-items-center"
      );

      const div = document.createElement("div");

      const strong = document.createElement("strong");
      strong.appendChild(
        safeText(`${order.name ?? ""} ${order.surname ?? ""}`)
      );

      const br = document.createElement("br");

      const small = document.createElement("small");
      small.classList.add("text-muted");
      small.appendChild(
        safeText(`${order.phone_number ?? ""} | ${order.email ?? ""}`)
      );

      div.appendChild(strong);
      div.appendChild(br);
      div.appendChild(small);

      const span = document.createElement("span");
      span.className = `badge text-capitalize ${this.colorStatusMap[status]}`;
      span.id = "badgeStatus";
      span.textContent = status;

      cardHeader.appendChild(div);
      cardHeader.appendChild(span);

      // body
      const cardBody = document.createElement("div");
      cardBody.classList.add("card-body");

      const p = document.createElement("p");
      const icon = document.createElement("i");
      icon.classList.add("bi", "bi-geo-alt");

      const strongDelivery = document.createElement("strong");
      strongDelivery.appendChild(safeText(" Delivery: "));

      p.appendChild(icon);
      p.appendChild(strongDelivery);
      p.appendChild(
        safeText(
          `${order.city ?? ""}, ${order.postcode ?? ""}, ${order.address ?? ""}`
        )
      );

      cardBody.appendChild(p);

      // products
      const productsDiv = document.createElement("div");
      productsDiv.classList.add("mb-2");

      const strongProducts = document.createElement("strong");
      strongProducts.appendChild(safeText("Products:"));
      productsDiv.appendChild(strongProducts);

      (order.items ?? []).forEach((item) => {
        const itemDiv = document.createElement("div");
        itemDiv.classList.add(
          "d-flex",
          "align-items-center",
          "border",
          "rounded",
          "p-2",
          "mb-2"
        );

        const img = document.createElement("img");
        img.src = url(`/img/${item.image_url}`);
        img.alt = "Product Image";
        img.classList.add("rounded", "me-2");
        img.style.width = "50px";
        img.style.height = "50px";
        img.style.objectFit = "cover";

        const itemInfo = document.createElement("div");

        const productName = document.createElement("div");
        productName.appendChild(safeText(item.product_name ?? ""));

        const qty = document.createElement("small");
        qty.classList.add("text-muted");
        qty.appendChild(safeText(`Quantity: ${item.quantity ?? 0}`));

        itemInfo.appendChild(productName);
        itemInfo.appendChild(qty);

        itemDiv.appendChild(img);
        itemDiv.appendChild(itemInfo);

        productsDiv.appendChild(itemDiv);
      });

      cardBody.appendChild(productsDiv);

      // footer
      const cardFooter = document.createElement("div");
      cardFooter.classList.add(
        "card-footer",
        "d-flex",
        "justify-content-between",
        "align-items-center"
      );

      const smallCreated = document.createElement("small");
      smallCreated.classList.add("text-muted");
      smallCreated.appendChild(
        safeText(`Created: ${this.formatDate(order.created_at)}`)
      );

      const btnGroup = document.createElement("div");
      btnGroup.classList.add("btn-group");

      const mainBtn = document.createElement("button");
      mainBtn.classList.add("btn", "btn-sm", "btn-primary", "change-status");
      mainBtn.id = "mainChangeStatusBtn";
      mainBtn.dataset.id = order.order_id;
      mainBtn.dataset.status = nextStatus;
      mainBtn.appendChild(safeText(`Mark as ${nextStatus}`));

      const dropdownBtn = document.createElement("button");
      dropdownBtn.type = "button";
      dropdownBtn.classList.add(
        "btn",
        "btn-sm",
        "btn-primary",
        "dropdown-toggle",
        "dropdown-toggle-split"
      );
      dropdownBtn.dataset.bsToggle = "dropdown";

      const ul = document.createElement("ul");
      ul.classList.add("dropdown-menu", "dropdown-menu-end");

      [
        "pending",
        "processing",
        "shipped",
        "delivered",
        "completed",
        "cancelled",
      ].forEach((st) => {
        const li = document.createElement("li");
        const a = document.createElement("a");
        a.classList.add("dropdown-item", "change-status");
        if (st === "completed") a.classList.add("text-success");
        if (st === "cancelled") a.classList.add("text-danger");
        a.href = "#";
        a.dataset.id = order.order_id;
        a.dataset.status = st;
        a.appendChild(safeText(st.charAt(0).toUpperCase() + st.slice(1)));
        li.appendChild(a);
        ul.appendChild(li);
      });

      btnGroup.appendChild(mainBtn);
      btnGroup.appendChild(dropdownBtn);
      btnGroup.appendChild(ul);

      cardFooter.appendChild(smallCreated);
      cardFooter.appendChild(btnGroup);

      card.appendChild(cardHeader);
      card.appendChild(cardBody);
      card.appendChild(cardFooter);
      orderCard.appendChild(card);
      field.appendChild(orderCard);
    }
  }
}
