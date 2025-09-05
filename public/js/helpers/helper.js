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

export const showAlert = (message, type = "success") => {
  const alertBox = document.getElementById("alertBox");
  alertBox.innerHTML = `
  <div class="alert alert-${type} alert-dismissible fade show" role="alert">
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
`;

  setTimeout(() => {
    const alert = alertBox.querySelector(".alert");
    if (alert) {
      alert.classList.remove("show");
      alert.remove();
    }
  }, 3000);
};
