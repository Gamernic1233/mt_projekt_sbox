const server_addres = "http://127.0.0.1:8000";

var selected_device = "";

usrnm_field = document.getElementById("usenameTop");
usrnm_field.innerHTML =  localStorage.getItem("username");

console.log(localStorage.getItem("username"));

//logout button

document.getElementById("logout_btn").addEventListener("click", async function(event){
    event.preventDefault();
    window.location.href = "./login.html"
});

//generate device buttons
function generate_device_buttons() {
    var devicesContainer = document.querySelector(".devicesContainer");
    var device_names = [];
    var username = localStorage.getItem("username");

    fetch(server_addres + "/getUserDevices?username=" + encodeURIComponent(username), {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        }
    })
    .then(response => response.json()) 
    .then(data => {
        data.forEach(element => {
            device_names.push(element); 
        });
    })
    .then(() => {

        device_names.forEach(element => {
            var device_button = document.createElement("button");
            device_button.innerHTML = element; 
            device_button.className = "buttonZariadenie";
            device_button.onclick = function() {
                document.querySelector(".deviceNameTopBar").innerHTML = element;
                selected_device = element;
            }
            devicesContainer.appendChild(device_button);
        });
    })
    .catch(error => {
        console.log("Error fetching devices:", error);
    });
}

generate_device_buttons();


//search
document.getElementById("searchButton").addEventListener("click", async function(event){
    event.preventDefault();
    var searchInput = document.querySelector(".searchInput").value;
    var devicesContainer = document.querySelector(".devicesContainer");
    var device_buttons = document.querySelectorAll(".buttonZariadenie");

    device_buttons.forEach(element => {
        if (element.innerHTML === searchInput){
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    });
});

document.getElementById("search_input_area").addEventListener("keydown", async function(event){
    real_input = document.getElementById("search_input_area").value;
    device_buttons = document.querySelectorAll(".buttonZariadenie");

    device_buttons.forEach(element => {
        if (element.innerHTML.includes(real_input)){
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    });
});

document.getElementById("search_input_area").addEventListener("keyup", async function(event){
    real_input = document.getElementById("search_input_area").value;

    if (real_input === ""){
        var device_buttons = document.querySelectorAll(".buttonZariadenie");
        device_buttons.forEach(element => {
            element.style.display = "block";
        });
    }
});
