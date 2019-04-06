// 定义id常量
const username_edit_id = "username-edit";
const username_edit_hint_id = "username-edit-hint";
const password_edit_id = "password-edit";
const password_edit_hint_id = "password-edit-hint";
const registered_users_table_id = "registered-users-table";
const submit_btn_id = "submit-btn";
// 用户密码字典
user_dict = {
    "root": "pwd123456"
};

function Operation() {

}

function JQueryOperation() {

}

function JavaScriptOperation() {

}

/**
 * 注册用户
 * @param {string} username 用户名
 * @param {string} password 密码
 */
function register_user(username, password) {
    // 注册新用户
    user_dict[username] = password;
    alert("注册成功！");
}

Operation.prototype.check_username = function (username) {
    if (username.length === 0) {
        this.update_hint(username_edit_hint_id, "用户名不能为空！");
    } else if (username.length > 10) {
        this.update_hint(username_edit_hint_id, "用户名过长！");
    } else if (user_dict.hasOwnProperty(username)) {
        this.update_hint(username_edit_hint_id, "该用户已注册！");
    } else {
        this.clear_hint(username_edit_hint_id);
        return username;
    }
};

Operation.prototype.check_password = function (password) {
    if (password.length === 0) {
        this.update_hint(password_edit_hint_id, "密码不能为空！");
    } else if (password.length > 0 && password.length < 7) {
        this.update_hint(password_edit_hint_id, "密码过短！");
    } else if (password.length > 16) {
        this.update_hint(password_edit_hint_id, "密码过长！");
    } else if (password.match(/(^[0-9]+$)|(^[a-zA-Z]+$)/g)) {
        this.update_hint(password_edit_hint_id, "密码不能是纯数字或纯字母组合！");
    } else if (password.length > 0 && !password.match(/^[\w!@#$%^&*?\(\)]{7,16}$/g)) {
        this.update_hint(password_edit_hint_id, "密码中含有非法字符！");
    } else {
        this.clear_hint(password_edit_hint_id);
        return password;
    }
};

Operation.prototype.submit = function () {
    var usr = this.check_username();
    var pwd = this.check_password();
    if (usr !== undefined && pwd !== undefined) {
        this.clear_hint(username_edit_hint_id);
        this.clear_hint(password_edit_hint_id);
        register_user(usr, pwd);
        this.add_table_info(usr, pwd);
    }
};

JQueryOperation.prototype = new Operation();

JQueryOperation.prototype.clear_hint = function (id) {
    $("#" + id).css("display", "none");
};

JQueryOperation.prototype.update_hint = function (id, message) {
    $("#" + id).css("display", "block").text(message);
};

JQueryOperation.prototype.check_username = function () {
    var username = $("#" + username_edit_id).val().trim();
    return Operation.prototype.check_username.call(this, username);
};

JQueryOperation.prototype.check_password = function () {
    var password = $("#" + password_edit_id).val().trim();
    return Operation.prototype.check_password.call(this, password);
};

JQueryOperation.prototype.add_table_info = function (username, password) {
    new_tr = $("<tr></tr>").append(
        $("<td></td>").text(username),
        $("<td></td>").text(password)
    );
    $("#" + registered_users_table_id + " tbody").append(new_tr);
};

JQueryOperation.prototype.ready = function () {
    console.log("正使用JQuery对DOM进行操作");

    $(document).ready(
        $("#" + username_edit_id).keyup(this.check_username.bind(this)),
        $("#" + password_edit_id).keyup(this.check_password.bind(this)),
        // 提交按钮的点击事件回调
        $("#" + submit_btn_id).click(this.submit.bind(this))
    );
};

JQueryOperation.prototype.unbind = function () {
    console.log("JQuery:解除事件绑定...");

    $(document).unbind();
    $("#" + username_edit_id).unbind();
    $("#" + password_edit_id).unbind();
    $("#" + submit_btn_id).unbind();
};

JavaScriptOperation.prototype = new Operation();

JavaScriptOperation.prototype.clear_hint = function (id) {
    document.getElementById(id).style.display = "none";
};

JavaScriptOperation.prototype.update_hint = function (id, message) {
    var elem = document.getElementById(id);
    elem.style.display = "block";
    elem.innerText = message;
};

JavaScriptOperation.prototype.check_username = function () {
    var username = document.getElementById(username_edit_id).value.trim();
    return Operation.prototype.check_username.call(this, username);
};

JavaScriptOperation.prototype.check_password = function () {
    var password = document.getElementById(password_edit_id).value.trim();
    return Operation.prototype.check_password.call(this, password);
};

JavaScriptOperation.prototype.add_table_info = function (username, password) {
    var tr = document.getElementById(registered_users_table_id)
        .getElementsByTagName("tbody")[0]
        .appendChild(document.createElement("tr"));
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    td1.innerText = username;
    td2.innerText = password;
    tr.appendChild(td1);
    tr.appendChild(td2);
};

JavaScriptOperation.prototype.ready = function () {
    console.log("正使用JavaScript对DOM进行操作");

    document.getElementById(username_edit_id).onkeyup = this.check_username.bind(this);
    document.getElementById(password_edit_id).onkeyup = this.check_password.bind(this);
    document.getElementById(submit_btn_id).onclick = this.submit.bind(this);
};

JavaScriptOperation.prototype.unbind = function () {
    console.log("JavaScript:解除事件绑定...");

    var username_edit = document.getElementById(username_edit_id);
    var password_edit = document.getElementById(password_edit_id);
    var submit_btn = document.getElementById(submit_btn_id);

    username_edit.onkeyup = null;
    password_edit.onkeyup = null;
    submit_btn.onclick = null;
};

