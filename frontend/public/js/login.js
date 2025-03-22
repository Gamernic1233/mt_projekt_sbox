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
        response = await fetch("http://localhost:8000/login", {
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
                localStorage.setItem("username", data['user']);
                localStorage.setItem("token", data['token']);
                document.cookie = `token=${data['token']}; path=/; samesite=strict`; //  secure;
                //window.open('/prihlaseny', '_blank');
                window.location.href = "/";

            } else {
                console.log("error msg: " + data["message"])
                error_msg.innerHTML = data["message"];
                success_msg.innerHTML = "";
            }
        });
    }
});