document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("mail").addEventListener("change", function () {
    let formData = new FormData();
    console.log(this.value);
    formData.append("mail", this.value);
    fetch("check-mail.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.text();
      })
      .then((data) => {
        console.log(data);
        if (data != "0") {
          alert("Déja inscrit");
        }
      });
  });
});
