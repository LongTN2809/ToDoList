function apiGetTasks(sortValue = "") {
    let url = "";
  if(sortValue){
    url = `../PHP/api/task.php?sortValue=${sortValue}`;
    return fetch(url) .then((response)=> response.json());
  }else{
    url = "../PHP/api/task.php";
    return fetch(url) .then((response)=> response.json());
  }
}

function apiCreateTask(data) {
    if(data){
        return fetch("../PHP/api/task.php",{
            method:"POST",
            headers:{
                "Content-type": "Application/json"
            },
            body: JSON.stringify(data)
        })
        .then((response)=> response.json());
    }else{
        console.log("Don't have data to create");
    }
}

function apiDeleteTask(id) {
   if(id){
    return fetch(`../PHP/api/task.php?id=${id}`,{
        method: "DELETE",
    })
    .then((response)=>response.json());
   }else{
    console.log("Don't have id to delete");
   }
}

function apiPatchTask(id, data) {
    console.log(id , data);
   if(id && data){
    return fetch(`../PHP/api/task.php?id=${id}`,{
        method:"PATCH",
        headers:{
            "Content-type": "Application/json"
        },
        body: JSON.stringify(data)
    })
   }
}

