var selected_device = "";

usrnm_field = document.getElementById("logged_in_user");
usrnm_field.innerHTML =  localStorage.getItem("username");

console.log(localStorage.getItem("username"));

//logout button

document.getElementById("logout_button").addEventListener("click", async function(event){
    event.preventDefault();
    localStorage.clear();
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
    window.location.href = "./login"
});

//generate device buttons
function generate_device_buttons() {
    var devicesContainer = document.querySelector(".devices-container");
    var device_names = [];
    var username = localStorage.getItem("username");

    fetch("http://localhost:8000/getuserdevices?username=" + encodeURIComponent(username), {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${localStorage.getItem('token')}`,
        }
    })
    .then(response => response.json()) 
    .then(data => {
        data.forEach(element => {
            device_names.push(element.nazov_zariadenia); 
        });
    })
    .then(() => {

        device_names.forEach(element => {
            var device_button = document.createElement("button");
            device_button.innerHTML = element; 
            device_button.className = "device-button";
            device_button.onclick = function() {
                localStorage.setItem("device_name", element);
                document.querySelector(".device-name").innerHTML = '<span class="device-label">ZARIADENIE:</span>&nbsp;' + element;
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
    var searchInput = document.querySelector(".search-input").value;
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
    device_buttons = document.querySelectorAll(".device-button");

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
        var device_buttons = document.querySelectorAll(".device-button");
        device_buttons.forEach(element => {
            element.style.display = "block";
        });
    }
});
