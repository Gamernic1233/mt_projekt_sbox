document.getElementById("login_button").addEventListener("click", async function(event){
    event.preventDefault();
    var username = document.getElementById("login_input_username").value;
    var password = document.getElementById("login_input_password").value;
    var error_msg = document.getElementById("error_msg");
    var success_msg = document.getElementById("success_msg");

    if(username === "" || password === ""){
        error_msg.innerHTML = "Please fill in all fields";
        success_msg.innerHTML = "";
    } else {
        response = await fetch("http://127.0.0.1:8000/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if(data["message"] === "Login successful"){
                error_msg.innerHTML = "";
                success_msg.innerHTML = data["message"];
                localStorage.setItem("username", username);
                window.location.href = "./prihlaseny.html";

            } else {
                error_msg.innerHTML = data["message"];
                success_msg.innerHTML = "";
            }
        });
    }
});