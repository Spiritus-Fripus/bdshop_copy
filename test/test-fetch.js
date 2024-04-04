/*
let btns = document.querySelectorAll(".card_button");

btns.forEach(function (btn) {
  btn.addEventListener("click", AddToCart(btn));
});

function AddToCart(btn) {
  fetch("add-to-cart.php?id=" + btn.getAttribute("data-id"))
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      document.getElementById("div_cart").innerHTML = "";
      data.forEach(function (cart) {
        let ul = CreateProductList();
        let btn_remove = CreateRemoveBtn(cart);
        let { id, name, quantity, price } = CreateProductListElements(cart);

        ul.append(id, name, quantity, price, btn_remove);

        btn_remove.addEventListener(
          "click",
          RemoveFromCart(li, cart, quantity, btn_remove),
        );
      });
    });
}

function CreateProductList() {
  let li = document.createElement("li");
  li.setAttribute("id", "produit");
  document.getElementById("div_cart").append(li);

  let ul = document.createElement("ul");
  ul.setAttribute("id", "list-produit");
  li.append(ul);

  return ul;
}

function CreateRemoveBtn(cart) {
  let btn_remove = document.createElement("button");
  btn_remove.setAttribute("data-id", cart.product_id);
  btn_remove.innerText = "Remove";

  return btn_remove;
}

function CreateProductListElements(cart) {
  let id = document.createElement("li");
  let name = document.createElement("li");
  let quantity = document.createElement("li");
  let price = document.createElement("li");

  id.innerText = "Référence produit : " + cart.product_id;
  name.innerText = "Nom : " + cart.product_name;
  quantity.innerText = "Quantité : " + cart.cart_quantity;
  price.innerText = "Prix : " + cart.product_price + "€";

  return { id, name, quantity, price };
}

function RemoveFromCart(li, cart, quantity, btn) {
  fetch("remove-from-cart.php?id=" + btn.getAttribute("data-id")).then(() => {
    if (cart.cart_quantity <= 0) {
      li.remove();
    } else {
      cart.cart_quantity -= 1;
      quantity.innerText = "Quantité : " + cart.cart_quantity;
    }
  });
}
*/
