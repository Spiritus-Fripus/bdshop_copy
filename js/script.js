let cpt = 1;
document.getElementById("btn").addEventListener("click", function () {
  if (cpt == 1) {
    let ul = document.createElement("ul");
    ul.setAttribute("id", "list");
    document.body.append(ul);
  }
  let li = document.createElement("li");
  li.innerText = cpt++;
  document.getElementById("list").append(li);
});
