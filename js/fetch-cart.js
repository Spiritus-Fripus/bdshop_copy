let btns = document.querySelectorAll(".add_cart");

document.addEventListener("DOMContentLoaded", () => {
  btns.forEach((btn) => {
    btn.addEventListener("click", function () {
      fetch("add-to-cart.php?id=" + btn.getAttribute("data-id"))
        .then((response) => {
          return response.text();
        })
        .then((data) => {
          document.getElementById("divCart").innerHTML = data;
          console.log(data);
        });
    });
  });
});
