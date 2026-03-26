let list = document.querySelector("#list");
// Hàm chuẩn hoá giá trị đầu vào


function createTagsLi(todo) {
    console.log(Boolean(Number(todo.completed)))
    const li = document.createElement("li");

    // ===== Checkbox container =====
    const label = document.createElement("label");
    label.classList.add("checkbox-container");

    // ===== Task container
    const info = document.createElement("div");
    info.classList.add("task-container");

    const checkBtn = document.createElement("input");
    checkBtn.type = "checkbox";
    checkBtn.checked = Boolean(Number(todo.completed));

    // ===== Task =====
    const span = document.createElement("span");
    span.classList.add("task");
    span.textContent = todo.title;
    span.innerHTML += `<i class="fa-solid fa-pen pen-icon"></i>`

    // ===== Nút xoá =====
    const delBtn = document.createElement("button");
    delBtn.classList.add("delete");
    delBtn.textContent = "DELETE";

    // ====== Date =======
    const spanDate = document.createElement("span");
    spanDate.classList.add("date");
    spanDate.textContent = formatDate(todo.create_Time);

    // Gộp các phần tử con vào cha
    label.appendChild(checkBtn);
    info.appendChild(span);
    info.appendChild(spanDate);

    // ===== Append =====
    li.appendChild(label);
    li.appendChild(info);
    li.appendChild(delBtn)
    list.appendChild(li);

    // ======== EVENT ========

    // Làm hàm xoá
    delBtn.onclick = function () {
        apiDeleteTask(todo.id)
            .then(data => {
                if (data.delete) {
                    li.remove()
                    updateStats()
                    checkTaskQuanity();
                }
            })
    }

    // PATCH (Hàm thay đổi trạng thái của task)
    li.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete") || e.target.classList.contains("task")) return

        if (checkBtn.checked) checkBtn.checked = false;
        else checkBtn.checked = true;

        const isComplete = checkBtn.checked;

        apiPatchTask(todo.id, { completed: isComplete, typeEdit: "status" })
            .then(r => r.json())
            .then((data) => {
                updateStats();
            })
    })

    // Edit title
    span.addEventListener("dblclick", (e) => {
        // Tạo input ẩn để thay đổi title
        const editInput = document.createElement("input");
        editInput.type = "text";
        editInput.classList.add("edit-title");
        editInput.placeholder = "New title";
        // Ẩn span chứa title cũ 
        span.classList.add("hidden");

        // Thêm input lên trước thời gian
        info.insertBefore(editInput, spanDate)

        editInput.focus(); // Focus vào input khi vừa double click

        editInput.addEventListener("click", (e) => { // Tránh click vào input cũng dính vào thẻ li
            e.stopPropagation()
        })

        let isEditingDone = false;

        // Xử lý input khi enter
        editInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                let newTitle = editInput.value.trim();

                if (!newTitle) return;
                span.textContent = newTitle;
                span.innerHTML += `<i class="fa-solid fa-pen pen-icon"></i>`
                span.classList.remove("hidden");

                // Kiểm tra nếu còn input thì mới xoá , tránh lỗi
                if (isEditingDone) return;
                isEditingDone = true;
                editInput.remove();

                // Edit title
                apiPatchTask(todo.id, {
                    title: newTitle,
                    typeEdit: "title"
                })
                    .then((data) => {
                        console.log(data);
                    })
            }
        })

        // Xử lý khi người dùng muốn thoát việc đổi title (blur khỏi input)
        editInput.addEventListener("blur", () => {
            if (isEditingDone) return;
            isEditingDone = true;
            span.classList.remove("hidden");
            editInput.remove();
        })
    })
}

// Hàm thêm task
function addToDo() {
    const inp = document.querySelector("#task-inp") // giá trị input
    const text = inp.value.trim() // Xoá kí tự khoảng trắng thừa

    const select = document.querySelector("#priority"); // Phân loại mức độ quan trọng của task
    const prio = select.value;
    let standarValueInp = standardizeValue(text) // Chuẩn hoá lại giá trị nhập vào

    if (!standarValueInp && !prio) {
        inp.classList.add("error");
        select.classList.add("error");
        inp.addEventListener("input", () => { // Khi nhập giá trị sẽ xoá báo lỗi
            inp.classList.remove("error");
        })

        select.addEventListener("change", () => { // Khi chọn giá trị sẽ xoá báo lỗi
            select.classList.remove("error");
        })
        return;
    } else {
        if (!standarValueInp) {
            inp.classList.add("error");
            inp.addEventListener("input", () => { // Khi nhập giá trị sẽ xoá báo lỗi
                inp.classList.remove("error");
            })
            return
        } else if (!prio) {
            select.classList.add("error");
            select.addEventListener("change", () => { // Khi chọn giá trị sẽ xoá báo lỗi
                select.classList.remove("error");
            })
            return
        }
    }


    // fetch POST ở đây...
    apiCreateTask({
        title: standarValueInp,
        completed: false,
        userId: 1,
        priority: Number(prio)
    })
        .then((data) => {
            createTagsLi(data);
            inp.value = "";
            updateStats();
            checkTaskQuanity();
            select.selectedIndex = 0;
        })
        .catch((err) => console.log(err));
}

// Thêm bằng nút enter
document.querySelector("#task-inp").addEventListener("keydown", e => {
    if (e.key === "Enter") {
        addToDo()
        checkTaskQuanity();
    }
})

// Hàm sort task
function sortTask() {
    let sortBy = document.querySelector("#sort").value;
    apiGetTasks(sortBy)
        .then((data) => {
            console.log(data);
            list.innerHTML = "";
            data.forEach(todo => {
                console.log(todo);
                createTagsLi(todo);
            });
            updateStats();
            checkTaskQuanity();
        })
        .catch((err) => console.log(err));

}

document.querySelector("#sort").addEventListener("change", () => {
    sortTask();
});

// Load task 
function loadToDo() {
    apiGetTasks()
        .then(data => {
            data.forEach(todo => createTagsLi(todo))
            updateStats()
            checkTaskQuanity();
        })
}
loadToDo();