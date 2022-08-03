document.addEventListener("DOMcontentLoaded", () => {


    /*const loginf = document.querySelector("#login");
    const registerf = document.querySelector("#register");

    document.querySelector("#reg").addEventListener("click", e => {
        e.preventDefault();
        loginf.classList.add("form_hidden");
        registerf.classList.remove("form_hidden");

        console.log("i am here");
    });

    registerf.addEventListener("submit", e => {
        e.preventDefault();
        loginf.classList.add("form_hidden");
        registerf.classList.remove("form_hidden");

        console.log("i am here");
    });*/

});


function hidef() {
    event.preventDefault();
    const loginf = document.querySelector("#login");
    const registerf = document.querySelector("#register");
    loginf.classList.add("form_hidden");
    registerf.classList.remove("form_hidden");
    //console.log("i am here");

    /*const divh = document.querySelector("#errmsg");
    divh.innerHTML = "<p></p>";*/
}