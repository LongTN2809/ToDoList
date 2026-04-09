document.addEventListener("DOMContentLoaded",()=>{
    fetch("../PHP/api/user.php?action=getUser")
    .then((response)=>response.json())
    .then((result)=>{
        if(!result.success){
            window.location.href = "../../TO_DO_LIST_PROJECT/HTML/auth.html";
            console.log(result.msg);
        }else{
            document.querySelector("#hello_user").innerText = `Xin chào ${result.user.username}`;
        }
    })
})