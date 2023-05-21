function registration() {
    fetch(`${window.location.origin}/api/v1/registration`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            login: document.querySelector("#login").value,
            password: document.querySelector("#password").value,
            repeatPassword: document.querySelector("#repeatPassword").value
        })
    }).then(function (response) {
        return response.json().then(function (resp) {
            setTimeout(function (resp) {
                if (resp["response"] === "OK") {
                    window.location = "/";
                } else {
                    if (resp["response"] === "User already exists") {
                        resp["response"] = "Пользователь уже существует";
                    }
                    alert(resp["response"]);
                }
            }, 10, resp);
        });
    });
}

function login() {
    fetch(`${window.location.origin}/api/v1/login`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            login: document.querySelector("#login").value,
            password: document.querySelector("#password").value
        })
    }).then(function (response) {
        return response.json().then(function (resp) {
            setTimeout(function (resp) {
                if (resp["response"] === "Password and repeat password do not match") {
                    resp["response"] = "Пароль и повтор пароля не совпадают";
                }
                if (resp["response"] === "Вы успешно вошли в аккаунт") {
                    window.location = "/";
                } else {
                    alert(resp["response"]);
                }
            }, 10, resp);
        });
    });
}

function logout() {
    fetch(`${window.location.origin}/api/v1/logout`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (!isAdminPanel()) {
                location.reload();
            } else {
                window.location = '/';
            }
        });
    });
}

function isAdminPanel() {
    return window.location.pathname.startsWith("/admin");
}

function isAdmin() {
    fetch(`${window.location.origin}/api/v1/isAdmin`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (resp["response"] === "admin") {
                if (window.location.pathname === "/admin") {
                    window.location = '/admin/services';
                }
            } else if (window.location.pathname === "/admin" || window.location.pathname.startsWith("/admin")) {
                window.location = '/';
            }
        });
    });
}

function isAuthorized() {
    fetch(`${window.location.origin}/api/v1/isAuthorized`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (!isAdminPanel()) {
                let button = document.querySelector("#authButton");
                if (resp["response"]) {
                    button.innerHTML = "<button onclick='logout()'>Выйти</button>";
                } else {
                    button.innerHTML = "Войти";
                    button.href = "/login";
                }
            } else {
                if (!resp["response"]) {
                    window.location = '/';
                }
            }
        })
    });
}

function addOrder(service) {
    fetch(`${window.location.origin}/api/v1/addOrder`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: document.querySelector("#orderName").value,
            phone: document.querySelector("#orderPhone").value,
            service: service
        })
    }).then(function(response) {
        return response.json().then(function(resp) {
            alert(resp["response"]);
            if (resp["response"] === "Заявка успешно отправлена") {
                location.reload();
            }
        });
    });
}

function getOrders() {
    fetch(`${window.location.origin}/api/v1/getOrders`, {
        method: "POST"
    }).then(function(response) {
        return response.json().then(function(resp) {
            if (!resp["response"]) {
                Object.keys(resp).map(function (key) {
                    createOrder(resp[key].name, resp[key].phone, resp[key].service);
                });
            }
        });
    });
}

function createOrder(name, phone, service) {
    let order = document.createElement("div");
    order.classList.add("col-lg-4", "col-md-6", "col-12");
    let appealsBlockDiv = document.createElement("div");
    appealsBlockDiv.classList.add("appeals-block");

    let firstAppealsDiv = document.createElement("div");
    let fioHeaderH1 = document.createElement("h1");
    fioHeaderH1.innerText = "ФИО";
    let fioP = document.createElement("p");
    fioP.innerText = name;
    firstAppealsDiv.appendChild(fioHeaderH1);
    firstAppealsDiv.appendChild(fioP);

    let secondAppealsDiv = document.createElement("div");
    let phoneHeaderH1 = document.createElement("h1");
    phoneHeaderH1.innerText = "Номер телефона";
    let phoneP = document.createElement("p");
    phoneP.innerText = phone;
    secondAppealsDiv.appendChild(phoneHeaderH1);
    secondAppealsDiv.appendChild(phoneP);

    let thirdAppealsDiv = document.createElement("div");
    let serviceHeaderH1 = document.createElement("h1");
    serviceHeaderH1.innerText = "Услуга";
    let serviceP = document.createElement("p");
    serviceP.innerText = service;
    thirdAppealsDiv.appendChild(serviceHeaderH1);
    thirdAppealsDiv.appendChild(serviceP);

    appealsBlockDiv.appendChild(firstAppealsDiv);
    appealsBlockDiv.appendChild(secondAppealsDiv);
    appealsBlockDiv.appendChild(thirdAppealsDiv);
    order.appendChild(appealsBlockDiv);
    document.querySelector("#orders").appendChild(order);
}

function addFeedback() {
    fetch(`${window.location.origin}/api/v1/addFeedback`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: document.querySelector("#feedbackName").value,
            phone: document.querySelector("#feedbackPhone").value,
            question: document.querySelector("#feedbackQuestion").value
        })
    }).then(function(response) {
        return response.json().then(function(resp) {
            alert(resp["response"]);
            if (resp["response"] === "Обращение успешно отправлено") {
                location.reload();
            }
        });
    });
}

function getFeedbacks() {
    fetch(`${window.location.origin}/api/v1/getFeedbacks`, {
        method: "POST"
    }).then(function(response) {
        return response.json().then(function(resp) {
            if (!resp["response"]) {
                Object.keys(resp).map(function (key) {
                    createFeedback(resp[key].name, resp[key].phone, resp[key].question);
                });
            }
        });
    });
}

function createFeedback(name, phone, question) {
    let feedback = document.createElement("div");
    feedback.classList.add("col-lg-4", "col-md-6", "col-12");
    let appealsBlockDiv = document.createElement("div");
    appealsBlockDiv.classList.add("appeals-block");

    let firstAppealsDiv = document.createElement("div");
    let fioHeaderH1 = document.createElement("h1");
    fioHeaderH1.innerText = "ФИО";
    let fioP = document.createElement("p");
    fioP.innerText = name;
    firstAppealsDiv.appendChild(fioHeaderH1);
    firstAppealsDiv.appendChild(fioP);

    let secondAppealsDiv = document.createElement("div");
    let phoneHeaderH1 = document.createElement("h1");
    phoneHeaderH1.innerText = "Номер телефона";
    let phoneP = document.createElement("p");
    phoneP.innerText = phone;
    secondAppealsDiv.appendChild(phoneHeaderH1);
    secondAppealsDiv.appendChild(phoneP);

    let thirdAppealsDiv = document.createElement("div");
    let aboutHeaderH1 = document.createElement("h1");
    aboutHeaderH1.innerText = "Описание";
    let questionP = document.createElement("p");
    questionP.innerHTML = question;
    thirdAppealsDiv.appendChild(aboutHeaderH1);
    thirdAppealsDiv.appendChild(questionP);

    appealsBlockDiv.appendChild(firstAppealsDiv);
    appealsBlockDiv.appendChild(secondAppealsDiv);
    appealsBlockDiv.appendChild(thirdAppealsDiv);
    feedback.appendChild(appealsBlockDiv);
    document.querySelector("#feedbacks").appendChild(feedback);
}