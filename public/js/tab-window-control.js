$(function() {
    login_button = document.getElementById("ingresar");
    if (login_button) {
        set_window_name();
    }
   
    else{
        check_new_window_tab()
    }
});

function set_window_name(){
    window.name = "singleWindow";
}

function check_new_window_tab(){
    if (window.name != "singleWindow") {
        
    }
}

