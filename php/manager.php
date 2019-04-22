<?php

// The cleanest way to rearrange the $_FILES
function rearrange($arr)
{
    foreach ($arr as $key => $all) {
        foreach ($all as $i => $val) {
            $new[$i][$key] = $val;
        }
    }
    return $new;
}

function std_jsonify($code = 0, $msg = "ok!", $data = null)
{
    if ($data === null) {
        return json_encode(array("code" => $code, "msg" => $msg));
    }
    return json_encode(array("code" => $code, "msg" => $msg, "data" => $data));
}

class FileInfo
{
    public $name;
    public $type;
    public $size;
    public $upload_time;

    protected $file_abspath;

    function __construct($file_abspath)
    {
        $this->$file_abspath = $file_abspath;
    }
}

class DirInfo
{
    public $name;

    protected $dir_abspath;

    function __construct($dir_abspath)
    {
        $this->$dir_abspath = $dir_abspath;
    }
}


class FilesArray
{
    public $arr = array();
    function __construct($files_arr, $dirs_arr)
    {
        foreach ($dirs_arr as $dir) {
            $this->$arr[] = array("dir" => $dir);
        }
        foreach ($files_arr as $file) {
            $this->$arr[] = array("file" => $file);
        }
    }
}


// 管理器
// 1. 增加文件（处理上传的文件）
// 2. 增加文件夹
// 3. 删除文件、文件夹
// 4. 查询某路径下的文件列表
class Manager
{
    protected $upload_dir_path;
    protected $current_workdir;
    function __construct($upload_dir_path, $current_workdir)
    {
        $this->upload_dir_path = $upload_dir_path;
        $this->current_workdir = $current_workdir;
    }
    function get_realpath($relative_path)
    {
        return $this->$upload_dir_path . DIRECTORY_SEPARATOR . $relative_path;
    }

    function upload($files_obj)
    { }
    function mkdir($name)
    { }
    function rm($name)
    { }
    function query($filter_rule)
    { }
}

// ===================主体逻辑===================

header("Content-type:text/json");

# 上传文件夹路径
const upload_dir_path = "upload";

$cwd = get_real_upload_dir_path($_POST["cwd"]);
if (!is_dir($cwd)) {
    exit(std_jsonify(-1, "The specified working directory does not exist!"));
}
$m = Manager(upload_dir_path, $cwd);
switch ($_POST["action"]) {
    case 'upload':
        break;
    case 'download':
        break;
    case 'rm':
        break;
    case 'mkdir':
        break;
    case 'query':
        break;
    default:
        exit(std_jsonify(-1, "Illegal Action!"));
}
