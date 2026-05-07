<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class DateTimeLineFormatter extends LineFormatter
{
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = 'Y-m-d H:i:s',
        bool $allowInlineLineBreaks = true,
        bool $ignoreEmptyContextAndExtra = true,
        bool $includeStacktraces = true,
    ) {
        parent::__construct(
            $format,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra,
            $includeStacktraces,
        );
    }

    public function format(LogRecord $record): string
    {
        $formatted = parent::format($record);
        $prefix = '['.$this->formatDate($record->datetime).'] ';

        return preg_replace('/(\r\n|\r|\n)(?!$)/', '$1'.$prefix, $formatted) ?? $formatted;
    }
}
