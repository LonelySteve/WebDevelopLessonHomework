<?php
header('Content-Type:application/json');

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

if (usort($students, "compare_func")) {
    echo json_encode(["code" => 0, "data" => $students]);
} else {
    echo json_encode(["code" => -1]);
}
