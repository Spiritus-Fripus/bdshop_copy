// let btns = document.querySelectorAll(".card_button");

// document.addEventListener("DOMContentLoaded", () => {
//   btns.forEach((btn) => {
//     btn.addEventListener("click", function () {
//       fetch("add-to-cart.php?id=" + btn.getAttribute("data-id"))
//         .then(function (response) {
//           return response.json();
//         })
//         .then(function (data) {
//           console.log(data);
//           document.querySelector(".div_cart").innerHTML = "";
//           data.forEach(function (cart) {
//             let ul = document.createElement("ul");
//             ul.setAttribute("id", "ul_cart");
//             document.querySelector(".div_cart").append(ul);
//             let title = document.createElement("li");
//             title.setAttribute("id", "cart_title");

//             document.getElementById("ul_cart").append(title);
//             document.getElementById("ul_cart").append(price);

//             document.getElementById("cart_title").append(cart.product_name);
//             document.getElementById("cart_price").append(cart.product_price);
//           });
//         });
//     });
//   });
// });
