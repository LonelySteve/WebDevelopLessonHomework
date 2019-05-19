<?php

namespace JLoeve\BBS\exceptions {

    use Throwable;

    class InvalidParamException extends \InvalidArgumentException
    {
        const code = -5;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, self::code, $previous);
        }
    }

    class ParamTypeException extends \TypeError
    {
        const code = -10;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, self::code, $previous);
        }
    }

    class SyntaxErrorException extends \Exception
    {
        const code = -15;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, self::code, $previous);
        }
    }

    class UnImplementedException extends \Exception
    {
        const code = -20;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, self::code, $previous);
        }
    }

    class RuntimeErrorException extends \RuntimeException
    {
        const code = -30;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, self::code, $previous);
        }
    }

    class SqlExecuteException extends RuntimeErrorException
    {
        const code = -35;

        function __construct($message = "", Throwable $previous = null)
        {
            parent::__construct($message, $previous);
        }
    }
}