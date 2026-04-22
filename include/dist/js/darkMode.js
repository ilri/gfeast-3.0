var checkbox = document.querySelector('input[name=theme]');

checkbox.addEventListener('change', function () {
    if (this.checked) {
        trans()
        document.documentElement.setAttribute('data-theme', 'dark')
    } else {
        trans()
        document.documentElement.setAttribute('data-theme', 'light')
    }
})

let trans = () => {
    document.documentElement.classList.add('transition');
    window.setTimeout(() => {
        document.documentElement.classList.remove('transition')
    }, 1000)
}


// function hideImg() {
//     var x = document.getElementById("hideImg");
//     if (x.style.display === "none") {
//         x.style.display = "block";
//     } else {
//         x.style.display = "nonw";
//     }
// }