function toggle_sign_log() {
  let right_box = document.querySelector("#left_box");
  let ereaser = document.querySelector("#ereaser");
  let log_in = document.getElementsByClassName("to_log_in");
  let sign_up = document.getElementsByClassName("to_sign_up");
  let duck = document.querySelector("#duck");
  let big_logo = document.querySelector("#big_logo");
  let toggle_btn = document.querySelector("#toggle_sign_log_btn");
  let sign_log_msg = document.querySelector("#sign_msg");

  toggle_btn.setAttribute("disabled", "true");

  right_box.classList.add("shrink");

  let btn_text = toggle_btn.innerHTML;
  let btn_new_text =
    btn_text == "Log In <span></span><span></span><span></span><span></span>"
      ? "Sign Up <span></span><span></span><span></span><span></span>"
      : "Log In <span></span><span></span><span></span><span></span>";
  toggle_btn.innerHTML = btn_new_text;

  let msg_text = sign_log_msg.innerHTML;
  let msg_new_text =
    msg_text == "Don't have an account yet?"
      ? "Aleready have an account?"
      : "Don't have an account yet?";
  sign_log_msg.innerHTML = msg_new_text;
  sign_msg;

  setTimeout(() => {
    ereaser.classList.add("erease");
    duck.classList.add("tumbling");
    big_logo.classList.add("logo_move");
    right_box.classList.remove("shrink");
  }, 300);

  setTimeout(() => {
    let log_count;
    let sign_count;

    for (log_count = 0; log_count < log_in.length; log_count++) {
      log_in[log_count].classList.toggle("hide");
    }
    for (sign_count = 0; sign_count < sign_up.length; sign_count++) {
      sign_up[sign_count].classList.toggle("hide");
    }
  }, 1000);

  setTimeout(() => {
    ereaser.classList.remove("erease");
    duck.classList.remove("tumbling");
    big_logo.classList.remove("logo_move");
    toggle_btn.removeAttribute("disabled");
  }, 2000);
}

let every_form = document.querySelectorAll("form");

for (i = 0; i < every_form.length; i++) {
  every_form[i].addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("index_backend.php", {
      method: "post",
      body: formData,
    })
      .then(function (response) {
        return response.text();
      })
      .then(function (text) {
        delete_all_error_msg();
        result = JSON.parse(text);

        for (let field in result) {
          if (field == "log_success") {
            let page = result[field];
            window.location.href = page;
          } else {
            let target = document.getElementById(field);
            target.innerHTML = result[field];
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
  });
}

function delete_all_error_msg() {
  let all_msg = document.querySelectorAll(".error_msg");

  for (i = 0; i < all_msg.length; i++) {
    all_msg[i].innerHTML = "";
  }
}
