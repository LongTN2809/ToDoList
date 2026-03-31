import { validateForm } from "./validator.js";
import { showFormError } from "./validator.js";
import { apiRegister } from "../api/userApi.js";
import { clearFieldErrorForm } from "./validator.js";
let form = document.querySelector("#registerForm");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    let formData = new FormData(form);
    let data = Object.fromEntries(formData.entries()); // formData.entries() trả về 1 iterator , Object.fromEntries dùng iterator đó để duyệt
    if (!validateForm(form , data)) return;

    // Call API
    apiRegister(data)
        .then(response => response.json())
        .then(result => {
            if(result.success){
                window.location.href = "../../TO_DO_LIST_PROJECT/HTML/index.html"
            }else{
            showFormError(result.msg);
            }
        })

})

clearFieldErrorForm(form);