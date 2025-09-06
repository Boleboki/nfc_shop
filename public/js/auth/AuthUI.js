export class AuthUI {
  static getLoginFormData() {
    return {
      username: document.getElementById("username")?.value || "",
      password: document.getElementById("password").value || "",
    };
  }
}
