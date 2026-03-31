let nameInp;
let passInp;
let passConfirm;

function showFieldError(input, message) {
  let groupParent = input.closest(".form-group");
  let errMess = groupParent.querySelector(".field-error");
  errMess.classList.add("error");
  errMess.textContent = message;
  input.classList.add("invalid");
}

function clearFieldError(input) {
  let groupParent = input.closest(".form-group");
  let errMess = groupParent.querySelector(".field-error");
  // Clear red border
  input.classList.remove("invalid");
  // Clear error message
  errMess.textContent = "";
  errMess.classList.remove("error");
}

export function showFormError(message) {
  let formMsg = document.querySelector("#form-message");
  console.log(formMsg);
  console.log(message);
  formMsg.textContent = message;
}

export function validateForm(form, data) {
  nameInp = form.querySelector("#name");
  passInp = form.querySelector("#password");
  passConfirm = form.querySelector("#passwordConfirm");

  let hasErr = false;

  if (!data.username.trim()) {
    showFieldError(nameInp, "Please enter your username!");
    hasErr = true;
  }

  if (!data.password) {
    showFieldError(passInp, "Please enter your password!");
    hasErr = true;
  } else if (data.password.length < 6) {
    showFieldError(passInp, "Please enter at least 6 characters!");
    hasErr = true;
  }

  if (
    passConfirm &&
    data.passConfirm !== undefined &&
    data.passConfirm !== data.password
  ) {
    showFieldError(passConfirm, "Password confirm is invalid!");
    hasErr = true;
  }

  return !hasErr;
}

export function clearFieldErrorForm(form) {

  nameInp = form.querySelector("#name");
  passInp = form.querySelector("#password");
  passConfirm = form.querySelector("#passwordConfirm");

  nameInp.addEventListener("input", () => {
    clearFieldError(nameInp);
  });

  passInp.addEventListener("input", () => {
    clearFieldError(passInp);
  });

  if (passConfirm) {
    passConfirm.addEventListener("input", () => {
      clearFieldError(passConfirm);
    });
  }
}
