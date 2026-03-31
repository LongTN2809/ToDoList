import { validateForm } from "./validator.js";
import { showFormError } from "./validator.js";
import { apiLogin } from "../api/userApi.js";
import { clearFieldErrorForm } from "./validator.js";
let form = document.querySelector("#loginForm");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log(form);
    let formData = new FormData(form);
    let data = Object.fromEntries(formData.entries()); // formData.entries() trả về 1 iterator , Object.fromEntries dùng iterator đó để duyệt 
    if (!validateForm(form , data)) return;

    // Call API
    apiLogin(data)
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