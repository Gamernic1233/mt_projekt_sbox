document.getElementById("registration-button").addEventListener("click", async function(event){
    event.preventDefault();
    var username = document.getElementById("registracia_input_username").value;
    var email = document.getElementById("registracia_input_email").value;
    var password = document.getElementById("registracia_input_password").value;
    var password_confirm = document.getElementById("registracia_password_input_confirm").value;
    var error_msg = document.getElementById("error_msg");
    var success_msg = document.getElementById("success_msg");

    console.log(username);

    console.log(username + ' ' + email + ' ' + password + ' ' + password_confirm);

    if(username === "" || email === "" || password === "" || password_confirm === ""){
        error_msg.innerHTML = "Please fill in all fields";
        success_msg.innerHTML = "";
    } else if(password !== password_confirm){
        error_msg.innerHTML = "Passwords do not match";
        success_msg.innerHTML = "";
    } else {
        response = await fetch("http://127.0.0.1:8000/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                email: email,
                username: username,
                password: password,
                repeat_password: password_confirm
            })
        }).then(response => response.json())
        .then(data => {
            console.log(data);
            if(data["status"] === "success"){
                error_msg.innerHTML = "";
                success_msg.innerHTML = data["message"];
                console.log("Pred uložením do LocalStorage:", localStorage.getItem("username"));
                localStorage.setItem("username", username);
                localStorage.setItem("token", data['token']);  // Uloženie tokenu do LocalStorage
                console.log("Po uložení do LocalStorage:", localStorage.getItem("token"));
                window.location.href = "/";
            } else {
                error_msg.innerHTML = data["message"];
                success_msg.innerHTML = "";
            }
        });
    }
});