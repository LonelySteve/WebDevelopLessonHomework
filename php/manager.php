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
    header("Content-type:text/json");
    if ($data === null) {
        return json_encode(array("code" => $code, "msg" => $msg));
    }
    return json_encode(array("code" => $code, "msg" => $msg, "data" => $data));
}

function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
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
        $this->file_abspath = $file_abspath;
        $this->type = filetype($file_abspath);
        $this->name = basename($file_abspath);
        $this->size = filesize($file_abspath);
        $this->upload_time = filectime($file_abspath);
    }
}

class DirInfo
{
    public $name;

    protected $dir_abspath;

    function __construct($dir_abspath)
    {
        $this->dir_abspath = $dir_abspath;
        $this->name = basename($dir_abspath);
    }
}

class WalkableDir
{
    protected $current_workdir;

    function __construct($current_workdir)
    {
        $this->current_workdir = $current_workdir;
    }

    protected function _get_realpath($relative_path)
    {
        return $this->upload_dir_path . DIRECTORY_SEPARATOR . $relative_path;
    }

    protected function _query($filter_rules = null)
    {
        $files_arr = array_diff(scandir($this->current_workdir), array(".", ".."));
        if (is_array($filter_rules)) {
            foreach ($filter_rules as $rule) {
                $files_arr = preg_grep($rule, $files_arr);
            }
        } else {
            $files_arr = preg_grep($filter_rules, $files_arr);
        }
        $result_arr = array(
            "files" => array(),
            "dirs" => array()
        );
        foreach ($files_arr as $value) {
            $abs_path = $this->_get_realpath($value);
            if (is_dir($abs_path)) {
                $info = new DirInfo($abs_path);
                $result_arr["dirs"][] = $info;
            } else {
                $info = new FileInfo($abs_path);
                $result_arr["files"][] = $info;
            }
        }
        return $result_arr;
    }
}

class ActionResult extends WalkableDir
{
    public $code;
    public $msg;
    public $data;
    public $successCount;
    public $failureCount;
    public $failureData;

    function __construct($code, $msg, $current_workdir, $successCount, $failureCount, $failureData)
    {
        WalkableDir::__construct($current_workdir);
        $this->code = $code;
        $this->msg = $msg;
        $this->data = null;
        $this->successCount = $successCount;
        $this->failureCount = $failureCount;
        $this->failureData = $failureData;
    }

    function apply_action_error($code, $msg)
    {
        $this->code = $code;
        $this->msg = $msg;
    }

    function clear_action_error($when_not_failure_data = true)
    {
        if ($when_not_failure_data) {
            if (!$failureData) {
                $this->code = 0;
                $this->msg = "ok!";
            }
        }
        $this->code = 0;
        $this->msg = "ok!";
    }

    function append_failure_item($code, $msg)
    {
        $this->failureData[] = array("code" => $code, "msg" => $msg);
    }

    function flush_data()
    {
        $this->data = $this->_query();
    }
}

// 管理器
// 1. 增加文件（处理上传的文件）
// 2. 增加文件夹
// 3. 删除文件、文件夹
// 4. 查询某路径下的文件列表
class Manager extends WalkableDir
{
    protected $upload_dir_path;
    protected $current_workdir;

    function __construct($upload_dir_path, $current_workdir)
    {
        $cwd = $this->_get_realpath($current_workdir);
        if (!is_dir($cwd)) {
            throw new Exception("The specified working directory does not exist!", -1);
        }
        $this->upload_dir_path = $upload_dir_path;
        $this->current_workdir = $current_workdir;
    }

    // 由于老师机器上安装的是5.2的PHP，因此不能写匿名函数来简化以下逻辑，rua！

    protected function _upload($result, $params)
    {
        $files_arr = $params[0];
        $name = $params[1];

        $result->apply_action_error(-100, "Upload error!");

        $files_arr = rearrange($files_arr[$name]);
        foreach ($files_arr as $file) {
            // 判断当前文件是否有上传错误
            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    // 移动上传的文件至当前工作目录
                    $safe_name = realpath($file["name"]);
                    if (!move_uploaded_file($file["tmp_name"], $this->_get_realpath($safe_name))) {
                        $result->append_failure_item(-1, $file["name"] . "Failed to move temporary upload file!");
                    }
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $result->append_failure_item(-2, $file["name"] . "No file sent!");
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $result->append_failure_item(-3, $file["name"] . "Exceeded filesize limit!");
                    break;
                default:
                    $result->append_failure_item(-4, $file["name"] . "Unknown errors!");
                    break;
            }
        }
        $result->clear_action_error();
    }

    protected function _new_dir($result, $params)
    {
        $names = $params[0];
        $result->apply_action_error(-200, "Failed to create new folder!");
        foreach ($names as $name) {
            $new_dir_path = $this->_get_realpath($name);
            if (!is_dir($new_dir_path)) {
                $result->append_failure_item(-1, "Directory already exists!");
            }
            if (!mkdir($new_dir_path, 0777, true)) {
                $result->append_failure_item(-2, "Failed to create new folder!");
            }
        }
        $result->clear_action_error();
    }

    protected function _rm($result, $params)
    {
        $names = $params[0];
        $result->apply_action_error(-300, "Failed to remove the item!");
        foreach ($names as $name) {
            $target_path = $this->_get_realpath($name);
            if (!(is_dir($target_path) ? delTree($target_path) : unlink($target_path))) {
                $result->append_failure_item(-1, "Remove " . $name . " failed!");
            }
        }
        $result->clear_action_error();
    }

    protected function _download($result, $params)
    {
        $names = $params[0];
        $result->apply_action_error(-400, "The file is missing!");
        foreach ($names as $name) {
            $target_path = $this->_get_realpath($name);

            if (!file_exists($target_path)) {
                $result->append_failure_item(-404, "File not found:" . $name);
            }
        }
        $result->clear_action_error();
        if ($result->code) {
            return $result;
        }

        foreach ($names as $name) {
            $target_path = $this->_get_realpath($name);
            $file_size = filesize($target_path);
            //以只读和二进制模式打开文件
            $file = fopen($target_path, "rb");

            //告诉浏览器这是一个文件流格式的文件
            Header("Content-type: application/octet-stream");
            //请求范围的度量单位
            Header("Accept-Ranges: bytes");
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header("Accept-Length: " . $file_size);
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header("Content-Disposition: attachment; filename=" . $name);

            //读取文件内容并直接输出到浏览器
            echo fread($file, $file_size);
            fclose($file);
        }
    }

    protected function wrapper($callback, $params)
    {
        $result = new ActionResult(0, "ok!", $this->current_workdir, 0, 0, array());
        $callback($result, $params);
        $result->flush_data();
        return $result;
    }

    function upload($params)
    {
        return $this->wrapper($this->_upload, $params);
    }

    function new_dir($params)
    {
        return $this->wrapper($this->_new_dir, $params);
    }

    function rm($params)
    {
        return $this->wrapper($this->_rm, $params);
    }

    function query($params)
    {
        return $this->wrapper($this->_query, $params);
    }

    function download($params)
    {
        $result = $this->wrapper($this->_download, $params);
        if ($result->code) {
            return $result;
        }
    }
}


// ===================主体逻辑===================


# 上传文件夹路径
const upload_dir_path = "upload";

try {
    $m = new Manager(upload_dir_path, $_POST["cwd"]);
} catch (\Throwable $th) {
    exit(std_jsonify($th->getCode(), $th->getMessage()));
}

switch ($_POST["action"]) {
    // 每种操作统一返回一个结果数组，这个数组的json结构类似于：
    // {
    //      "code":"0", // 操作失败的时候，code 为非0值
    //      "msg":"ok!", // 消息，出错为出错消息描述
    //      "data":[],    // 无论是否成功或失败，这将是一个当前工作目录的文件数组（当cwd出错时，这将是一个空数组）
    //      "successCount":5, // 成功操作的元素数量
    //      "failureCount":0, // 操作失败的元素数量
    //      "failureData":[]  // 失败的元素数组，如果操作没有失败，这将是一个空数组
    // }
    case 'upload':
    case 'download':
    case 'rm':
    case 'mkdir':
    case 'query':
        try {
            $result = $m->$_POST["action"]($_POST["params"]);
            if ($result) {
                exit(json_encode($result));
            }
        } catch (\Throwable $th) {
            exit(std_jsonify($th->getCode(), $th->getMessage()));
        }
        break;
    default:
        exit(std_jsonify(-1, "Illegal Action!"));
}
