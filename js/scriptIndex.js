function displayForm(){
    const regForm = document.getElementById("register-form");
    regForm.classList.toggle("active");
    const loginForm = document.getElementById("login-form");
    loginForm.classList.toggle("active");
}

function init(){
    const formLogin = document.getElementById("login-form");
    formLogin.addEventListener("submit", (e) => {
        const username = document.getElementById("username-login").value;
        let regExprUsername = /^[a-z0-9]{1,60}$/;
        if(!regExprUsername.test(username)){
            e.preventDefault();
            alert("username con formato non valido");
        }

        if (username == null || username == "") {
            e.preventDefault();
            alert("nessun username inserito!");
        }

        const password = document.getElementById("password-login").value;
        let regExprPassword = /^[A-Za-z0-9!@#$%^&*]{8,16}$/;
        if(!regExprPassword.test(password)){
            e.preventDefault();
            alert("password con formato non valido");
        }

        if (password == null || password == "") {
            e.preventDefault();
            alert("nessuna password inserita!");
        }
    });

    const formRegister = document.getElementById("register-form");
    formRegister.addEventListener("submit", (e) => {
        const username = document.getElementById("username-register").value;
        let regExprUsername = /^[a-z0-9]{1,60}$/;
        if(!regExprUsername.test(username)){
            e.preventDefault();
            alert("username con formato non valido");
        }
        
        if (username == null || username == "") {
            e.preventDefault();
            alert("nessun username inserito!");
        }

        const password = document.getElementById("password-register").value;
        let regExprPassword = /^[A-Za-z0-9!@#$%^&*]{8,16}$/;
        if(!regExprPassword.test(password)){
            e.preventDefault();
            alert("password con formato non valido");
        }

        if (password == null || password == "") {
            e.preventDefault();
            alert("nessuna password inserita!");
        }
    });
}

document.addEventListener("DOMContentLoaded", init);