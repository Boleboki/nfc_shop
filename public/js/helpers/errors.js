export class Errors {
  static displayErrors(field, messages) {
    if (!field) return;
    let ul = field.querySelector("ul");
    if (!ul) {
      ul = document.createElement("ul");
      field.appendChild(ul);
    }
    ul.innerHTML = "";
    field.classList.add("visible");
    const appendMessage = (message) => {
      const li = document.createElement("li");
      li.textContent = message;
      ul.appendChild(li);
    };

    const msgs = Array.isArray(messages) ? messages : [messages];

    msgs.forEach((message) => {
      appendMessage(message);
    });
  }

  static removeErrors(field) {
    if (!field) return;
    field.classList.remove("visible");
    const ul = field.querySelector("ul");
    if (!ul) return;
    ul.innerHTML = "";
  }

  static removeAllErrors() {
    const fields = document.querySelectorAll(".error-messages");
    fields.forEach((field) => {
      field.innerHTML = "";
    });
  }
}
