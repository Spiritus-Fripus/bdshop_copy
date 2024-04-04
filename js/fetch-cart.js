let btns = document.querySelectorAll(".card_button");
btns.forEach(function (btn) {
  btn.addEventListener("click", function () {
    const formData = new FormData();
    formData.append("id", btn.getAttribute("data-id"));

    fetch("/cart/add-to-cart.php", {
      headers: {
        accept: "application/json",
      },
      method: "POST",
      body: formData,
    })
      .then(function (response) {
        return response.json();
      })

      .then(function (data) {
        document.getElementById("div_cart").innerHTML = "";

        data.forEach(function (cart) {
          let ul = document.createElement("ul");
          ul.setAttribute("id", "list-produit");
          document.getElementById("div_cart").append(ul);
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
            const formData = new FormData();
            formData.append("id", btn_remove.getAttribute("data-id"));
            fetch("/cart/remove-from-cart.php", {
              headers: {
                accept: "application/json",
              },
              method: "POST",
              body: formData,
            }).then(function () {
              if (cart.cart_quantity <= 1) {
                ul.remove();
              } else {
                cart.cart_quantity -= 1;
                quantity.innerText = "Quantité : " + cart.cart_quantity;
              }
            });
          });
        });

        let btn_del = document.createElement("button");
        btn_del.setAttribute("data-id", data.cart_customer_id);
        btn_del.innerText = "cart del";
        document.getElementById("div_cart").append(btn_del);

        btn_del.addEventListener("click", function () {
          const formData = new FormData();
          formData.append("id", btn_del.getAttribute("data-id"));

          fetch("/cart/delete-cart.php", {
            headers: {
              accept: "application/json",
            },
            method: "POST",
            body: formData,
          }).then(function () {
            document.getElementById("div_cart").innerHTML = "";
          });
        });
      });
  });
});
