<?php


namespace App\Exceptions;


use Throwable;

class NeedRedirectException extends BaseException
{
    protected $redirect_url;

    public function __construct($redirect_url, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->redirect_url = $redirect_url;
        parent::__construct($message, $code, $previous);
    }

    public function get_redirect_url()
    {
        return $this->redirect_url;
    }

    public function redirect()
    {
        header("location: " . $this->redirect_url);
        exit();
    }
}