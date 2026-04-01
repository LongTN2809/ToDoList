let locks = document.querySelectorAll(".lock");
locks.forEach((lock) => {
  lock.addEventListener("click", () => {
    let passwordInput = lock.previousElementSibling;
    if (passwordInput.type === "password") {
      lock.classList.remove("fa-lock");
      lock.classList.add("fa-lock-open");
      passwordInput.type = "text";
    } else {
      lock.classList.add("fa-lock");
      lock.classList.remove("fa-lock-open");
      passwordInput.type = "password";
    }
  });
});

function switchTo(page) {
  if (page === "register-link") {
    document.querySelector("#loginForm").classList.add("hidden");
    document.querySelector("#registerForm").classList.remove("hidden");
  } else {
    document.querySelector("#loginForm").classList.remove("hidden");
    document.querySelector("#registerForm").classList.add("hidden");
  }
}

// Lấy tất cả các nút submit và phân biệt theo id
document.querySelectorAll(".formLink").forEach((btn) => {
  btn.addEventListener("click", () => {
    switchTo(btn.id);
  });
});
