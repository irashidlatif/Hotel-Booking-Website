function get_users() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    document.getElementById("users-data").innerHTML = this.responseText;
  };
  xhr.send("get_users");
}

function toggle_status(id, val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (this.responseText == 1) {
      alert("success", "Status toggled!");
      get_users();
    } else {
      alert("success", "Server Down!");
    }
  };
  xhr.send("toggle_status=" + id + "&value=" + val);
}

function search_user(username) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    document.getElementById("users-data").innerHTML = this.responseText;
  };
  xhr.send("search_user&name=" + username);
}

window.onload = function () {
  get_users();
};
