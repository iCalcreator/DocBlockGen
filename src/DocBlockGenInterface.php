<?php
/**
 * DocBlockGen generates PHP (phpdoc.org) DocBlocks
 *
 * Copyright 2020 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * Link <https://kigkonsult.se>
 * Support <https://github.com/iCalcreator/DocBlockGen>
 *
 * This file is part of DocBlockGen.
 *
 * DocBlockGen is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * DocBlockGen is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with DocBlockGen. If not, see <https://www.gnu.org/licenses/>.
 */
namespace Kigkonsult\DocBlockGen;


interface DocBlockGenInterface
{
    /**
     * PHP primitive types
     */
    const ARRAY_PT          = 'array';
    const BOOL_PT           = 'bool';
    const CALLABLE_PT       = 'callable';
    const FLOAT_PT          = 'float';
    const INT_PT            = 'int';
    const NULL_PT           = 'null';
    const RESOURCE_PT       = 'resource';
    const STRING_PT         = 'string';

    /**
     * PHP primitive types as array
     */
    const ARRAY2_PT         = '[]';
    const BOOLARRAY_PT      = 'bool[]';
    const CALLABLEARRAY_PT  = 'callable[]';
    const FLOATARRAY_PT     = 'float[]';
    const INT_ARRAYPT       = 'int[]';
    const RESOURCEARRAY_PT  = 'resource[]';
    const STRINGARRAY_PT    = 'string[]';

    /**
     * PHP keywords
     */
    const FALSE_KW          = 'false';
    const MIXED_KW          = 'mixed';
    const OBJECT_KW         = 'object';
    const SELF_KW           = 'self';
    const STATIC_KW         = 'static';
    const THIS_KW           = '$this';
    const TRUE_KW           = 'true';
    const VOID_KW           = 'void';

    /**
     * PhpDoc tags
     */
    const API_T             = 'api';
    const AUTHOR_T          = 'author';
    const CATEGORY_T        = 'category';
    const COPYRIGHT_T       = 'copyright';
    const DEPRECATED_T      = 'deprecated';
    const EXAMPLE_T         = 'example';
    const FILESOURCE_T      = 'filesource';
    const GLOBAL_T          = 'global';
    const IGNORE_T          = 'ignore';
    const INHERITDOC_T      = 'inheritDoc';
    const INTERNAL_T        = 'internal';
    const LICENCE_T         = 'license';
    const LINK_T            = 'link';
    const METHOD_T          = 'method';
    const PACKAGE_T         = 'package';
    const PARAM_T           = 'param';
    const PROPERTY_T        = 'property';
    const PROPERTY_READ_T   = 'property-read';
    const PROPERTY_WRITE_T  = 'property-write';
    const RETURN_T          = 'return';
    const SEE_T             = 'see';
    const SINCE_T           = 'since';
    const SOURCE_T          = 'source';
    const SUBPACKAGE_T      = 'subpackage';
    const THROWS_T          = 'throws';
    const TODO_T            = 'todo';
    const USES_T            = 'uses';
    const USED_BY_T         = 'used-by';
    const VAR_T             = 'var';
    const VERSION_T         = 'version';


}
