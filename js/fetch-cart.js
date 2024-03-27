let btns = document.querySelectorAll(".card_button");
btns.forEach(function (btn) {
  btn.addEventListener("click", function () {
    fetch("add-to-cart.php?id=" + btn.getAttribute("data-id"))
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        document.getElementById("div_cart").innerHTML = "";
        data.forEach(function (cart) {
          let li = document.createElement("li");
          li.setAttribute("id", "produit");
          document.getElementById("div_cart").append(li);
          let ul = document.createElement("ul");
          ul.setAttribute("id", "list-produit");
          li.append(ul);
          let name = document.createElement("li");
          let quantity = document.createElement("li");
          let price = document.createElement("li");
          name.innerText = cart.product_name;
          quantity.innerText = "Quantité : " + cart.cart_quantity;
          price.innerText = "Prix : " + cart.product_price + "€";
          ul.append(name);
          ul.append(quantity);
          ul.append(price);
        });
      });
  });
});
