<?php

namespace Guym4c\TwigProps;

use Guym4c\PropTypes\Checker\ChainableType;
use Guym4c\PropTypes\PropTypes;
use voku\helper\UTF8;

class TwigPropTypes extends PropTypes {

    public static function instanceOf(string $className): ChainableType {

        $className = UTF8::str_replace('.', '\\', $className);

        if (UTF8::substr($className, 0, 1) !== '\\') {
            $className = "\\{$className}";
        }

        return parent::instanceOf($className);
    }

}