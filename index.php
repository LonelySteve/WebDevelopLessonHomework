<?php
$head =<<<___
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="utf-8">
    <title>作业六</title>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <style>
        body {
            background: url("https://api.dujin.org/bing/1920.php") fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }
    
        .panel {
            text-align: center;
            color: white;
            background-color: rgba(101, 203, 255, 0.8);
            border-radius: 6px;
            width: 320px;
            margin: 0 auto;
        }
    
        .btn {
            width: 100%;
            height: 40px;
            font-size: 20px;
            outline: none;
            color: white;
            background-color: #42a5f5;
            border: none;
            border-radius: 6px;
            margin-top: 10px;
        }
    
        .btn:hover {
            background-color: #64b5f6;
        }
    
        .btn:active {
            background-color: #2196f3;
        }
    
        .stagger-table tbody {
            /* 不允许HTML吞掉空格 */
            white-space: pre
        }
    
        .stagger-table {
            border-collapse: collapse;
        }
    
        .stagger-table th,
        .stagger-table td {
            border: 1px solid white;
            text-align: center;
            padding: 10px;
        }
    
        .stagger-table tr:nth-child(odd) {
            background-color: #42a5f5;
        }
    
        .stagger-table tr:nth-child(even) {
            background-color: #90caf9;
        }
    
        .stagger-table th {
            background-color: #1976d2;
        }
    
        .sort-flag {
            cursor: pointer;
        }
    
        .sort-flag::after {
            content: "-";
            margin-left: 10px;
        }
    
        .ascending::after {
            content: "↑";
        }
    
        .descending::after {
            content: "↓";
        }
    </style>
</head>

<body>
<div class="panel">
        <h2>班级成绩表</h2>
        <table id="class-grade-table" class="stagger-table" style="width:100%;">
            <thead>
                <th id="h-stu-num" class="sort-flag">学号</th>
                <th id="h-stu-name" class="sort-flag">姓名</th>
                <th id="h-stu-grade" class="sort-flag">成绩</th>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
___;

$script =<<<___
 <script>
        function get_sort_rule_flag(id) {
            var th = $("#" + id);
            if (th.hasClass("ascending"))
                return 1;
            else if (th.hasClass("descending"))
                return -1;
            else
                return 0;
        }

        function update_tables() {
            // 移除表格主体
            $("#class-grade-table tbody").children().remove();
            // TODO 支持调整排序优先级
            sort_priority = [0, 1, 2];
            // 获取排序规则要求
            sort_rules = {
                0: get_sort_rule_flag("h-stu-num"),
                1: get_sort_rule_flag("h-stu-name"),
                2: get_sort_rule_flag("h-stu-grade")
            }
            // 异步请求获取排序后的数据
            $.post("index.php", {
                "sort_rules": sort_rules,
                "sort_priority": sort_priority
            }, function(data, status) {
                if (data.code == 0) {
                    data.data.forEach(stu => {
                        var tr = $("<tr></tr>");
                        $("#class-grade-table tbody").append(tr);
                        stu.forEach(info => {
                            var td = $("<td></td>");
                            td.text(info);
                            tr.append(td);
                        });
                    });
                } else {
                    alert("未知错误，错误码：" + data.code);
                }

            }, "json");
        }

        $(document).ready(function() {
            update_tables();
            $(".sort-flag").click(function() {
                // 切换上下箭头
                var this_ = $(this);
                if (this_.hasClass("ascending"))
                    this_.removeClass("ascending").addClass("descending");
                else if (this_.hasClass("descending"))
                    this_.removeClass("descending");
                else
                    this_.addClass("ascending");
                update_tables();
            })
        });
    </script>
___;


if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $classes = [
        [
            ["2017001", "江路1", 18],
            ["2017002", "江路2", 19],
            ["2017003", "江路3", 20],
        ],
        [
            ["2017004", "江路4", 18],
            ["2017005", "江路5", 19],
            ["2017006", "江路6", 20],
        ],
        [
            ["2017007", "江路7", 18],
            ["2017008", "江路8", 19],
            ["2017009", "江路9", 20],
        ]
    ];
    echo $head;
    foreach ($classes as $i => $class) {
        echo '<div class="panel">' . "\n";
        echo "<h2>" . ($i + 1) . "班的名单" . "</h3>" . "\n";
        echo '<table class="stagger-table" style="width:100%;">' . "\n";
        echo "<thead>" . "\n";
        echo "\t" . "<th>学号</th>" . "\n";
        echo "\t" . "<th>姓名</th>" . "\n";
        echo "\t" . "<th>年龄</th>" . "\n";
        echo "</thead>" . "\n";
        echo "<tbody>" . "\n";
        foreach ($class as $person) {
            echo "<tr>" . "\n";
            foreach ($person as $value) {
                echo "\t" . "<td>" . $value . "</td>" . "\n";
            }
            echo "</tr>" . "\n";
        }
        echo "</tbody>" . "\n";
        echo "</table>" . "\n";
        echo "</div>" . "\n";
    }
    echo $script;
    echo "</body>";

    echo "</html>";
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $students = [
        ["201701", "江路1", "100"],
        ["201702", "江路2", "80"],
        ["201703", "江路3", "130"],
        ["201704", "江路1", "120"],
        ["201705", "江路1", "80"],
        ["201706", "江路4", "50"],
        ["201707", "江路1", "120"],
        ["201708", "江路6", "80"],
        ["201709", "江路1", "100"],
        ["201710", "江路1", "80"],
        ["201711", "江路9", "110"]
    ];

    function compare_func($a, $b)
    {
        $sort_priority = $_POST["sort_priority"]; // 这期望是一个优先级的数组 ，例如 [0,1,2] 表示名字最优先排序，其次是学号，再其次是成绩
        $sort_rules = $_POST["sort_rules"];  // 排序规则，期望是一个字典，例如 {0:-1,1:0,2:1} 其中键值对分别表示一维数组的下标和排序方式（大于0为升序，小于0为降序，等于0不参与排序）
        $result = 0;
        for ($i = 0; $i < 3 && $result === 0; $i++) {
            $rule = $sort_rules[$i];
            if ($rule == 0)
                continue; // 该维度不参与排序
            else {
                $result = strnatcmp($a[$sort_priority[$i]], $b[$sort_priority[$i]]);
                if ($rule < 0) {
                    $result = -$result;
                }
            }
        }
        return $result;
    }

    header('Content-Type:application/json');
    if (usort($students, "compare_func")) {
        echo json_encode(["code" => 0, "data" => $students]);
    } else {
        echo json_encode(["code" => -1]);
    }

}

?>

