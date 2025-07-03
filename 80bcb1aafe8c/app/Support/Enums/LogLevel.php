<?php

namespace App\Support\Enums;

enum LogLevel: string
{
    case ERROR = "error";
    case WARNING = "warning";
    case INFO = "info";
    case DEBUG = "debug";
}
