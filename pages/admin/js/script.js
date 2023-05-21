function startLoad() {
    document.querySelector(".gooey").classList.remove("hidden");
    document.body.style.overflow = "hidden";
}

function endLoad() {
    document.querySelector(".gooey").classList.add("hidden");
    document.body.style.overflow = null;
}

function registration() {
    startLoad();
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
        endLoad();
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
    startLoad();
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
        endLoad();
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
            location.reload();
        });
    });
}

function closeAddPostModal() {
    let modal = document.querySelector(".modal");
    modal.classList.remove("show");
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
    document.body.style.overflow = null;
    document.body.style.paddingRight = null;
    document.body.removeChild(document.querySelector(".modal-backdrop"));
    clearAddPostForm();
}

function clearAddPostForm() {
    document.querySelector("#postTitle").value = "";
    document.querySelector("#postBody").value = "";
    document.querySelector("#postTag").value = "security";
    document.querySelector("#postImage").value = "";
}

function addPost() {
    let date = new Date();
    let hours = date.getHours() > 9 ? date.getHours() : `0${date.getHours()}`;
    let minutes = date.getMinutes() > 9 ? date.getMinutes() : `0${date.getMinutes()}`;
    let image = document.querySelector("#postImage").files[0];
    let formData = new FormData();
    formData.append("title", document.querySelector("#postTitle").value);
    formData.append("body", document.querySelector("#postBody").value);
    formData.append("tag", document.querySelector("#postTag").value);
    formData.append("publicationTime", `${hours}:${minutes}`);
    formData.append("image", image, image.name);
    fetch(`${window.location.origin}/api/v1/addPost`, {
        method: 'POST',
        body: formData
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (resp["response"] === "Пост успешно создан") {
                startLoad();
                closeAddPostModal();
            }
            fetch(`${window.location.origin}/api/v1/getLastPostId`, {
                method: 'POST'
            }).then(function (response) {
                return response.json().then(function (resp) {
                    createPostCard(
                        formData.get("title"),
                        formData.get("tag"),
                        formData.get("publicationTime"),
                        resp["id"],
                        `/pages/post_images/${image.name}`
                    );
                    endLoad();
                })
            });
        });
    });
}

function getPosts() {
    let args = window.location.search;
    if (args.length === 0) {
        redirectToSecurityNews();
    }
    args = args.split("&");
    if (args.length > 1) {
        redirectToSecurityNews();
    }
    args[0] = args[0].substring(1, args[0].length);
    args.map(function (arg) {
        if (!(arg.startsWith("id=") || arg.startsWith("tag="))) {
            redirectToSecurityNews();
        }

        if (arg.startsWith("tag=")) {
            let tag = arg.substring(arg.indexOf("=") + 1, arg.length);
            if (["security", "administration", "social", "healthcare", "education"].indexOf(tag) === -1) {
                redirectToSecurityNews();
            }
            getPostsByTag(tag);
        } else if (arg.startsWith("id=")) {
            let id = arg.substring(arg.indexOf("=") + 1, arg.length);
            getPostById(id);
        }
    })
}

function isAdmin() {
    fetch(`${window.location.origin}/api/v1/isAdmin`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (resp["response"] === "admin") {
                document.querySelector("#createPostButton").style.display = "inline-block";
            }
        });
    });
}

function isAuthorized() {
    fetch(`${window.location.origin}/api/v1/isAuthorized`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            let button = document.querySelector("#authButton");
            if (resp["response"]) {
                button.innerHTML = "<button onclick='logout()'>Выйти</button>";
            } else {
                button.innerHTML = "Войти";
                button.href = "/login";
            }
        })
    });
}

function getPostById(id) {
    startLoad();
    fetch(`${window.location.origin}/api/v1/getPostById`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: id
        })
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (!resp["response"] && Object.keys(resp).length > 0) {
                let key = Object.keys(resp)[0];
                //let body = resp[key].body.replaceAll("\n", "<br>");
                createFullPost(resp[key].title, resp[key].body, resp[key].tag, resp[key].publication_time, resp[key].image);
            }
            endLoad();
        });
    });
}

function getPostsByTag(tag) {
    startLoad();
    let post = document.createElement("div");
    post.classList.add("row");
    post.id = "news";

    let titleNews = document.createElement("div");
    titleNews.classList.add("title-news");
    titleNews.innerHTML = `<h1>${translateTag(tag)}</h1>`;

    post.appendChild(titleNews);
    document.querySelector("#post-container").appendChild(post);

    document.title = translateTag(tag);
    document.querySelector(`#${tag}`).classList.add("active");

    fetch(`${window.location.origin}/api/v1/getPostsByTag`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            tag: tag
        })
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (!resp["response"] && Object.keys(resp).length > 0) {
                Object.keys(resp).map(function (key) {
                    let body = resp[key].body.replaceAll("\n", "<br>");
                    createPostCard(resp[key].title, resp[key].tag, resp[key].publication_time, key, resp[key].image);
                });
                endLoad();
            } else {
                endLoad();
            }
        });
    });
}

function redirectToSecurityNews() {
    window.location = "/posts/?tag=security";
    return;
}

function translateTag(tag) {
    if (tag === "security") {
        return "Безопасность";
    } else if (tag === "administration") {
        return "Администрация";
    } else if (tag === "social") {
        return "Социальная сфера";
    } else if (tag === "healthcare") {
        return "Здравоохранение";
    } else if (tag === "education") {
        return "Образование";
    }
}

function getAllPosts() {
    startLoad();
    fetch(`${window.location.origin}/api/v1/getAllPosts`, {
        method: 'POST'
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (!resp["response"] && Object.keys(resp).length > 0) {
                Object.keys(resp).map(function (key) {
                    createPostCard(resp[key].title, resp[key].tag, resp[key].publication_time, resp[key].id, resp[key].image);
                });
            }
            endLoad();
        });
    });
}

function createPostCard(title, tag, publicationTime, id, image) {
    let pageTag = window.location.search;
    pageTag = pageTag.substring(pageTag.indexOf("=") + 1, pageTag.length);
    if (pageTag !== "" && pageTag !== tag) {
        return;
    }

    let divBodyNews = document.createElement("div");
    divBodyNews.classList.add("col-lg-4");
    let aBodyNews = document.createElement("a");
    aBodyNews.href = "#";
    let divBody = document.createElement("div");
    divBody.classList.add("news", "h-100");

    let divImgNews = document.createElement("div");
    divImgNews.classList.add("img-news", "m-auto");
    let imgNews = document.createElement("img");
    imgNews.alt = "image";
    imgNews.src = image;
    imgNews.width = 150;
    imgNews.height = 150;
    divImgNews.appendChild(imgNews);

    let divInfoNews = document.createElement("div");
    divInfoNews.classList.add("d-flex");
    let pTag = document.createElement("p");
    pTag.classList.add("tag");
    pTag.innerText = translateTag(tag);
    let pPublicationTime = document.createElement("p");
    pPublicationTime.classList.add("time", "ms-auto");
    pPublicationTime.innerText = publicationTime;
    divInfoNews.appendChild(pTag);
    divInfoNews.appendChild(pPublicationTime);

    let aTitle = document.createElement("a");
    aTitle.innerHTML = title;
    aTitle.classList.add("title-block");
    aTitle.href = `/posts/?id=${id}`;

    divBody.appendChild(divImgNews);
    divBody.appendChild(divInfoNews);
    divBody.appendChild(aTitle);
    aBodyNews.appendChild(divBody);
    divBodyNews.appendChild(aBodyNews);

    document.querySelector("#news").appendChild(divBodyNews);
}

function createFullPost(title, body, tag, publicationTime, image) {
    let post = document.createElement("div");
    post.classList.add("post");

    let divTitleNews = document.createElement("div");
    divTitleNews.classList.add("title-news");
    divTitleNews.innerHTML = `<h1>${title}</h1>`;

    let divNewsInfo = document.createElement("div");
    divNewsInfo.classList.add("post-tag-time", "d-flex");
    let aTag = document.createElement("a");
    aTag.href = "/posts/?tag=security";
    aTag.classList.add("tag");
    aTag.innerText = translateTag(tag);
    let pPublicationTime = document.createElement("p");
    pPublicationTime.classList.add("time", "me-auto");
    pPublicationTime.innerText = publicationTime;
    divNewsInfo.appendChild(aTag);
    divNewsInfo.appendChild(pPublicationTime);

    let divImageNews = document.createElement("div");
    divImageNews.classList.add("post-image");
    let divChildImageNews = document.createElement("div");
    divChildImageNews.classList.add("img-news", "w-100", "m-auto");
    let imgNews = document.createElement("img");
    imgNews.alt = "image";
    imgNews.src = image;
    divChildImageNews.appendChild(imgNews);
    divImageNews.appendChild(divChildImageNews);

    let divBodyNews = document.createElement("div");
    divBodyNews.classList.add("description");
    let pBodyNews = document.createElement("p");
    pBodyNews.innerText = body;
    divBodyNews.appendChild(pBodyNews);

    post.appendChild(divTitleNews);
    post.appendChild(divNewsInfo);
    post.appendChild(divImageNews);
    post.appendChild(divBodyNews);
    post.appendChild(createCommentsForm());

    let row = document.createElement("div");
    row.classList.add("row");
    row.appendChild(post);

    document.querySelector("#post-container").appendChild(row);
}

function createCommentsForm() {
    let divComments = document.createElement("div");
    divComments.classList.add("comments");

    let h1Header = document.createElement("h1");
    h1Header.innerText = "Комментарии";

    let divCreateInput = document.createElement("div");
    divCreateInput.classList.add("create-input");
    let labelLogin = document.createElement("label");
    labelLogin.for = "postBody";
    labelLogin.id = "login";
    labelLogin.innerText = "";
    let textareaComment = document.createElement("textarea");
    textareaComment.id = "postBody";
    textareaComment.name = "postBody";
    textareaComment.rows = "10";
    textareaComment.cols = "45";
    let divBtn = document.createElement("div");
    divBtn.classList.add("btn-comments", "text-end");
    let btn = document.createElement("button");
    btn.type = "button";
    btn.id = "button-send";
    btn.classList.add("btn", "ms-auto");
    btn.onclick = function() {
        if (document.querySelector("#login").innerText !== "") {
            addComment();
        }
    };
    btn.innerText = "Отправить";

    divBtn.appendChild(btn);
    divCreateInput.appendChild(labelLogin);
    divCreateInput.appendChild(textareaComment);
    divCreateInput.appendChild(divBtn);

    divComments.appendChild(h1Header);
    divComments.appendChild(divCreateInput);

    return divComments;
}

function getComments() {
    if (!window.location.search.startsWith("?id=")) {
        return;
    }
    startLoad();
    fetch(`${window.location.origin}/api/v1/getComments`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            postId: window.location.search.replace("?id=", "")
        })
    }).then(function (response) {
        return response.json().then(function (resp) {
            if (resp["login"]) {
                document.querySelector("#login").innerText = resp["login"];
            }

            if (!resp["response"] && Object.keys(resp).length > 0) {
                Object.keys(resp["comments"]).map(function (key) {
                    createComment(resp["comments"][key].login, resp["comments"][key].publication_time, resp["comments"][key].text);
                });
            }

            endLoad();
        });
    });
}