document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("container");
    const signUpButton = document.getElementById("signUp");
    const signInButton = document.getElementById("signIn");
    const showLogin = document.getElementById("Showlogin");
    const showRegister = document.getElementById("Showregister");

    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");
    const registerMessage = document.getElementById("registerMessage");
    const loginUsername = loginForm.querySelector("input[name='username']");
    const loginPassword = loginForm.querySelector("input[name='password']");

    // Slide panels
    signUpButton.onclick = () => container.classList.add("right-panel-active");
    signInButton.onclick = () => container.classList.remove("right-panel-active");

    showLogin.onclick = e => {
        e.preventDefault();
        container.classList.remove("right-panel-active");
        loginUsername.focus();
    };

    showRegister.onclick = e => {
        e.preventDefault();
        container.classList.add("right-panel-active");
    };

    // REGISTER
    registerForm.addEventListener("submit", async e => {
        e.preventDefault();

        registerMessage.textContent = "";
        const submitBtn = registerForm.querySelector(".button");
        submitBtn.disabled = true;

        try {
            const response = await fetch("register.php", {
                method: "POST",
                body: new FormData(registerForm)
            });
            const data = await response.json();

            if (data.success) {
                // Clear register form
                registerForm.reset();

                // Show success message briefly
                registerMessage.textContent = "Registration successful! Please login.";
                registerMessage.classList.add("success");

                // Slide to login panel
                container.classList.remove("right-panel-active");

                // Auto-focus username in login
                setTimeout(() => loginUsername.focus(), 600);

                // Clear message after 4 seconds
                setTimeout(() => registerMessage.textContent = "", 4000);

            } else {
                registerMessage.textContent = data.error || "Registration failed.";
                registerMessage.classList.remove("success");
            }

        } catch (err) {
            registerMessage.textContent = "Server error. Try again.";
        } finally {
            submitBtn.disabled = false;
        }
    });

    // LOGIN
    loginForm.addEventListener("submit", async e => {
        e.preventDefault();

        const loginBtn = loginForm.querySelector(".button");
        loginBtn.disabled = true;

        try {
            const response = await fetch("login.php", {
                method: "POST",
                body: new FormData(loginForm)
            });
            const data = await response.json();

            if (data.success) {
                // Redirect to home page
                window.location.href = "home.php";
            } else {
                alert(data.error || "Login failed.");
                loginPassword.value = ""; // clear password field
                loginPassword.focus();
            }

        } catch (err) {
            alert("Server error. Try again.");
        } finally {
            loginBtn.disabled = false;
        }
    });

});
//js to hadle forms submissions