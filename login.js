const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");
const backLogin = document.getElementById("backLogin");

function showRegister() {
  loginForm.classList.add("hidden");
  registerForm.classList.remove("hidden");
  backLogin.classList.remove("hidden");
}

function showLogin() {
  loginForm.classList.remove("hidden");
  registerForm.classList.add("hidden");
  backLogin.classList.add("hidden");
}