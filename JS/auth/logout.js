import { apiLogOut } from "../api/userApi.js";
window.apiLogOut = function() {
    apiLogOut()
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = "../HTML/auth.html";
            } else {
                console.log(result.success);
            }
        })
}