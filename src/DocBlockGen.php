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

use InvalidArgumentException;

/**
 * Class DocBlockGen
 *
 * @package Kigkonsult\DocBlockGen
 * @link https://phpdoc.org
 */
final class DocBlockGen implements DocBlockGenInterface
{
    /**
     * Defaults
     *
     * @var string
     */
    private static $EOL     = PHP_EOL;
    private static $INDENTS = '    ';

    /**
     * Row template
     *
     * @var string
     */
    private static $TMPLROW1 = '%s * %s';

    /**
     * @var array
     */
    private static $TAGLIST = [
        self::API_T,
        self::AUTHOR_T,
        self::CATEGORY_T,
        self::COPYRIGHT_T,
        self::DEPRECATED_T,
        self::EXAMPLE_T,
        self::FILESOURCE_T,
        self::GLOBAL_T,
        self::IGNORE_T,
        self::INHERITDOC_T,
        self::INTERNAL_T,
        self::LICENCE_T,
        self::LINK_T,
        self::METHOD_T,
        self::PACKAGE_T,
        self::PARAM_T,
        self::PROPERTY_T,
        self::PROPERTY_READ_T,
        self::PROPERTY_WRITE_T,
        self::RETURN_T,
        self::SEE_T,
        self::SINCE_T,
        self::SOURCE_T,
        self::SUBPACKAGE_T,
        self::THROWS_T,
        self::TODO_T,
        self::USES_T,
        self::USED_BY_T,
        self::VAR_T,
        self::VERSION_T,
    ];

    /**
     * @var string
     */
    private $eol = null;

    /**
     * @var string
     */
    private $indents = null;

    /**
     * @var string
     */
    private $summary = null;

    /**
     * @var string[]|string[][]
     */
    private $description = [];

    /**
     * Contains tags, *( tagName => *( tagDirectivs ))
     *
     * @var string[][]
     */
    private $tags = [];

    /**
     * DocBlockGen constructor
     *
     * @param string $eol
     * @param string $indents
     */
    public function __construct( $eol = null, $indents = null ) {
        $this->setEol(( is_null( $eol )        ? self::$EOL : $eol ));
        $this->setIndent(( is_null( $indents ) ? self::$INDENTS :  $indents ));
    }

    /**
     * Class factory method, opt set tag
     *
     * @param string $tagName
     * @param string|array $tagType
     * @param string $tagText
     * @param string $tagComment
     * @param string $tagExt
     * @return static
     */
    public static function factory(
        $tagName = null,
        $tagType = null,
        $tagText = null,
        $tagComment = null,
        $tagExt = null
    ) {
        $self = new self();
        if( ! empty( $tagName )) {
            $self->setTag( $tagName, $tagType, $tagText, $tagComment, $tagExt );
        }
        return $self;
    }

    /**
     * Class factory method, opt set eol/indent
     *
     * @param string $eol
     * @param string $indents
     * @return static
     */
    public static function init( $eol = null, $indents = null ) {
        return new static( $eol, $indents );
    }

    /**
     * Return code as array (with NO eol at line endings)
     */
    public function toArray() {
        $code = $this->initCode( $addEmptyRow );
        if( ! empty( $this->summary )) {
            $this->summaryToArray( $code, $addEmptyRow );
        }
        if( ! empty( $this->description )) {
            $this->descriptionToArray( $code, $addEmptyRow );
        }
        if( ! empty( $this->tags )) {
            $this->tagsToArray( $code, $addEmptyRow );
        }
        return $this->exitCode( $code );
    }

    /**
     * Return code as string (with eol at line endings)
     */
    public function toString() {
        return implode( $this->eol, $this->toArray()) . $this->eol;
    }

    /**
     * Init code
     *
     * @param       $addEmptyRow
     * @return array
     */
    private function initCode( & $addEmptyRow ) {
        static $INTRO = '';
        static $START = '%s/**';
        $addEmptyRow  = false;
        return [ $INTRO, sprintf( $START, $this->indents ) ];
    }

    /**
     * End up code code
     *
     * @param array $code
     * @return array
     */
    private function exitCode( $code ) {
        static $END = '%s */';
        $code[]     = sprintf( $END, $this->indents );
        foreach( $code as $codeIx => $row ) {
            $code[$codeIx] = self::nullByteClean( $row );
        }
        return $code;
    }

    /**
     * Add summary to code
     *
     * @param array $code
     * @param bool  $addEmptyRow
     */
    private function summaryToArray( & $code, & $addEmptyRow ) {
        $code[]      = sprintf( self::$TMPLROW1, $this->indents, $this->summary );
        $addEmptyRow = true;
    }

    /**
     * Add description to code,
     *
     * Empty first or last row in (/)description part) rows are skipped.
     * If not first in the docBlock, an empty leading row is inserted.
     * Then, all but first, will have an empty leading row.
     *
     * @param array $code
     * @param bool  $addEmptyRow
     */
    private function descriptionToArray( & $code, & $addEmptyRow = false) {
        foreach( $this->description as $description ) {
            if( $addEmptyRow ) {
                $this->addEmptyRow( $code );
            }
            $lastIx = count( $description ) - 1;
            foreach((array) $description as $x => $descrPart ) {
                if(( empty( $x ) || ( $x == $lastIx )) && empty( $descrPart )) {
                    continue;
                }
                if( ! is_array( $descrPart )) {
                    $descrPart = [ $descrPart ];
                }
                foreach( $descrPart as $descrPart2 ) {
                    $code[] = sprintf( self::$TMPLROW1, $this->indents, $descrPart2 );
                }
            } // end foreach
            $addEmptyRow = true;
        }  // end foreach
    }

    /**
     * Add tags to code
     *
     * If not first in the docBlock, an empty leading row is inserted
     * TagNames are space-padded to the same length.
     *
     * @param array $code
     * @param bool  $addEmptyRow
     */
    private function tagsToArray( & $code, $addEmptyRow = false ) {
        static $DSIGN = '$';
        static $PIPE  = '|';
        static $TMPLROW2 = '%s * @%s %s %s %s %s';
        if( $addEmptyRow ) {
            $this->addEmptyRow( $code );
        }
        $padLen = 0;
        foreach( $this->tags as $tagName => $tagInfoArr ) {
            if( $padLen < strlen( $tagName )) {
                $padLen = strlen( $tagName );
            }
        }
        foreach( $this->tags as $tagName => $tagInfoArr ) {
            $isTagParam = ( self::PARAM_T == $tagName );
            foreach( $tagInfoArr as $data ) {
                if( is_array( $data[0] )) {
                    $data[0] = implode( $PIPE, $data[0] );
                }
                $theTagType = str_pad( $tagName, $padLen );
                if( $isTagParam && ( $DSIGN != substr( $data[1], 0, 1 ))) {
                    $data[1] = $DSIGN . $data[1];
                }
                $code[] = rtrim(
                    sprintf( $TMPLROW2, $this->indents, $theTagType, $data[0], $data[1], $data[2], $data[3] )
                );
            } // end foreach
        } // end foreach
    }

    /**
     * Add empty row to code
     *
     * @param array $code
     */
    private function addEmptyRow( & $code ) {
        static $EMPTY = '%s *';
        $code[] = sprintf( $EMPTY, $this->indents );
    }

    /**
     * @param string $eol
     * @return DocBlockGen
     */
    public function setEol( $eol ) {
        $this->eol = self::nullByteClean( $eol );
        return $this;
    }

    /**
     * @param string $indents
     * @return DocBlockGen
     */
    public function setIndent( $indents = null ) {
        $this->indents = self::nullByteClean( $indents );
        return $this;
    }

    /**
     * Set (header) short description, overwrite if exists
     *
     * @param string $summary
     * @return DocBlockGen
     */
    public function setSummary( $summary ) {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Set a long description, each one will have an empty leading row
     *
     * @param string|array $description
     * @return DocBlockGen
     */
    public function setDescription( $description ) {
        $this->description[] = $description;
        return $this;
    }

    /**
     * Set tag
     *
     * Args are modelled after the 'param' tag usage
     *
     * @param string $tagName
     * @param string|array $tagType
     * @param string $tagText
     * @param string $tagComment
     * @param string $tagExt
     * @return DocBlockGen
     * @throws InvalidArgumentException
     */
    public function setTag( $tagName, $tagType = null, $tagText = null, $tagComment = null, $tagExt = null ) {
        $tagName = self::assertTag( $tagName );
        if( ! isset( $this->tags[$tagName] )) {
            $this->tags[$tagName] = [];
        }
        $this->tags[$tagName][] = [ $tagType, $tagText, $tagComment, $tagExt ];
        return $this;
    }

    /**
     * Return bool true if tag is accepted
     *
     * @param string $tagName
     * @return bool
     */
    public static function isValidTag( $tagName ) {
        foreach( self::$TAGLIST as $tag ) {
            if( 0 == strcasecmp( $tagName, $tag )) {
                return true;
                break;
            }
        }
        return false;
    }

    /**
     * Assert accepted tag
     *
     * @param string $tagName
     * @return string
     * @throws InvalidArgumentException
     */
    private static function assertTag( $tagName ) {
        static $ERR1 = 'Invalid tagType %s';
        foreach( self::$TAGLIST as $tag ) {
            if( 0 == strcasecmp( $tagName, $tag )) {
                return $tag;
                break;
            }
        }
        throw new InvalidArgumentException( sprintf( $ERR1, $tagName ));
    }

    /**
     * Clean code of null bytes
     *
     * @param string $code
     * @return string
     */
    private static function nullByteClean( $code ) {
        static $SPACE0 = '';
        static $CHR0   = null;
        if( is_null( $CHR0 )) {
            $CHR0 = chr(0 );
        }
        return empty( $code ) ? $SPACE0 : str_replace( $CHR0, $SPACE0, $code );
    }

}
