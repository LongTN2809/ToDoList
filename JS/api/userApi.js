export function apiLogin(data){
   return fetch("../PHP/api/user.php?action=login",{
    method:"POST",
    headers:{
        "Content-type": "Applicaiton/json"
    },
    body: JSON.stringify(data)
   })
}

export function apiRegister(data){
    return fetch("../PHP/api/user.php?action=register",{
    method:"POST",
    headers:{
        "Content-type": "Applicaiton/json"
    },
    body: JSON.stringify(data)
   })
}