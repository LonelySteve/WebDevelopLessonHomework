<?php


namespace App\Cores;


use App\Http\Request;

class InfoCore extends Core
{
    public function __construct()
    {
        $this->filters += [
            (new \App\Filters\InputFilter())
                ->default("type", (new \App\Validators\DataValidator())->in_array(["hint", "error"]), "hint")
                ->default("message", (new \App\Validators\StringDataValidator()), "未定义")
                ->default("time", (new \App\Validators\NumberDataValidator())->min(0)->max(15), 5)
                ->default("go_url", (new \App\Validators\StringDataValidator()))
        ];
    }

    public function main(Request $request)
    {
        $input = $request->get_input();
        if ($input["type"] == "hint") {
            $title = "提示";
        } else {
            $title = "错误";
        }

        return [
            "__title__" => "留言板 - $title",
            "type" => $input["type"],
            "time" => $input["time"],
            "message" => $input["message"],
            "go_url" => $input["go_url"]
        ];
    }
}