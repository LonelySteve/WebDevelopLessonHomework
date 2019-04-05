var o = new JQueryOperation();

$(document).ready(
    $("#grade-sub-btn").click(
        function () {
            var grade = $("#grade-edit").val().trim();

            $.get("php/evaluation.php",
                {
                    grade: grade
                },
                function (data, status) {
                    if (data.code === 0) {
                        alert(data.msg);
                    } else {
                        o.update_hint("grade-edit-hint", data.msg);
                    }
                }, "json")
        }
    ),

    $("#grade-edit").keyup(
        function () {
            o.clear_hint("grade-edit-hint");
        }
    ),

    $("#login-btn").click(function () {
        var usr = $("#username-edit").val().trim();
        var pwd = $("#password-edit").val().trim();

        o.clear_hint(username_edit_hint_id);
        o.clear_hint(password_edit_hint_id);

        $.post("php/user.php",
            {
                username: usr,
                password: pwd
            },
            function (data, status) {
                if (data.code === 0) {
                    alert(data.msg);
                } else if (data.code === -1) {
                    o.update_hint(username_edit_hint_id, data.msg);
                } else if (data.code === -2) {
                    o.update_hint(password_edit_hint_id, data.msg);
                }
            }, "json")
    }),
);
