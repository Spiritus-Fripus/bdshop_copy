let btns = document.querySelectorAll(".btn_delete");

btns.forEach((btn) => {
  btn.addEventListener("click", () => {
    document
      .getElementById("modal-confirm")
      .setAttribute("href", "delete.php?id=" + btn.getAttribute("data-id"));
    document.getElementById("modal-delete").style.display = "block";
  });
});

document.getElementById("modal-cancel").addEventListener("click", () => {
  document.getElementById("modal-delete").style.display = "none";
});
