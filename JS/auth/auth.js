let lock = document.querySelector(".lock");
let password = document.querySelector("#password");
lock.addEventListener("click", () => {
    if (password.type === "password") {
        lock.classList.remove("fa-lock");
        lock.classList.add("fa-lock-open");
        password.type = "text";
    }else{
        lock.classList.add("fa-lock");
        lock.classList.remove("fa-lock-open");
        password.type = "password";
    }
})

function switchTo(page){
    if(page === "register-link"){
        document.querySelector("#loginForm").classList.add("hidden");
        document.querySelector("#registerForm").classList.remove("hidden");
    }else{
        document.querySelector("#loginForm").classList.remove("hidden");
        document.querySelector("#registerForm").classList.add("hidden");
    }
}

document.querySelectorAll(".formLink").forEach((btn)=>{
    btn.addEventListener("click" ,()=>{
        switchTo(btn.id);
    })
})



