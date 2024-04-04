let div = document.getElementById("div_test");

const formData = new FormData();
formData.append("id", div.getAttribute("data-id"));
fetch("/test/test-fetch.php", {
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
    data.forEach(function (element) {
      let ul = document.createElement("ul");
      document.getElementById("div_test").append(ul);
      let firstname = document.createElement("li");
      firstname.innerText = element.customer_firstname;
      let name = document.createElement("li");
      name.innerText = element.customer_lastname;
      ul.append(firstname);
      ul.append(name);
    });
  });
