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
          let btn_remove = document.createElement("button");
          btn_remove.setAttribute("data-id", cart.product_id);
          let id = document.createElement("li");
          let name = document.createElement("li");
          let quantity = document.createElement("li");
          let price = document.createElement("li");

          id.innerText = "Référence produit : " + cart.product_id;
          name.innerText = "Nom : " + cart.product_name;
          quantity.innerText = "Quantité : " + cart.cart_quantity;
          price.innerText = "Prix : " + cart.product_price + "€";
          btn_remove.innerText = "Remove";

          ul.append(id);
          ul.append(name);
          ul.append(quantity);
          ul.append(price);
          ul.append(btn_remove);

          btn_remove.addEventListener("click", function () {
            fetch(
              "remove-from-cart.php?id=" + btn_remove.getAttribute("data-id"),
            ).then(function (response) {
              if (cart.cart_quantity <= 0) {
                li.remove();
              } else {
                cart.cart_quantity -= 1;
                quantity.innerText = "Quantité : " + cart.cart_quantity;
              }
            });
          });
        });
      });
  });
});
