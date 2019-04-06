<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="utf-8">
    <title>作业六</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

<?php
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
?>

<div class="panel">
    <h2>排序表</h2>
    <a href="sortable_table.html">
        <input type="submit" class="btn" value="点我跳转">
    </a>
</div>

</body>

</html>