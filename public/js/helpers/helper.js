export const onClickIfExists = (elementId, callback) => {
  const element = document.getElementById(elementId);
  if (element) {
    element.addEventListener("click", callback);
  }
};

export const onClickIfExistsClass = (classVal, callback) => {
  const element = document.querySelector(classVal);
  if (element) {
    element.addEventListener("click", callback);
  }
};

export const showAlert = (message, type = "success", time = null) => {
  const alertBox = document.getElementById("alertBox");
  if (!alertBox) return;
  alertBox.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;
  const alert = alertBox.querySelector(".alert");

  if (!time || time <= 0 || !alert) return;

  setTimeout(() => {
    alert.classList.remove("show");
    setTimeout(() => alert.remove(), 200); // fade-out pre uklanjanja
  }, time * 1000);
};

export const url = (path) => {
  // Vraća URL koji se koristi za API pozive, kombinujući osnovni URL sa prosleđenim putem
  return `${BASE_URL}${path}`;
};
