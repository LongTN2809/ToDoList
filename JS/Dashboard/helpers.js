// Hàm update các dữ liệu về list
function updateStats() {
  const total = document.querySelectorAll("li").length;
  const done = document.querySelectorAll(
    ".checkbox-container input:checked",
  ).length;
  const left = total - done;
  document.getElementById("st-total").textContent = total;
  document.getElementById("st-done").textContent = done;
  document.getElementById("st-left").textContent = left;
}

// Hàm chỉnh định dạng ngày tháng năm
function formatDate(dateString) {
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, "0");
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const year = date.getFullYear();
  return `${day} - ${month} - ${year}`;
}

// Hàm kiểm tra nếu không còn task thì báo
function checkTaskQuanity() {
  let liQuanity = document.querySelectorAll("li").length;
  const emptyMsg = document.querySelector("#msg"); // thẻ thông báo

  if (liQuanity == 0) {
    console.log(liQuanity);
    emptyMsg.classList.remove("hidden");
  } else {
    console.log(emptyMsg);
    emptyMsg.classList.add("hidden");
  }
}

// Chuẩn hoá giá trị text
function standardizeValue(inputValue) {
  inputValue = inputValue.slice(0, 1).toUpperCase().concat(inputValue.slice(1));
  return inputValue;
}

function goToAuth() {
  window.location.href = "../../TO_DO_LIST_PROJECT/HTML/auth.html";
}
