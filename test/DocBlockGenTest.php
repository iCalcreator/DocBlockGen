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

use Exception;
use PHPUnit\Framework\TestCase;

class DocBlockGenTest extends TestCase
{
    const SUMMARY = 'This is a top (short) description';
    const DESCR1  = 'This is a longer description';
    const DESCR2a = 'This is another longer description';
    const DESCR2b = 'with more info on the next row';
    const DESCR2  = [
        '',
        self::DESCR2a,
        self::DESCR2b,
        ''
    ];
    const PARAMETER = 'parameter';

    const TEXTS = [
        self::SUMMARY,
        self::DESCR1,
        self::DESCR2b,
        DocBlockGen::PARAM_T,
        DocBlockGen::STRING_PT,
        DocBlockGen::INT_PT,
        self::PARAMETER,
        DocBlockGen::RETURN_T,
        DocBlockGen::ARRAY_PT,
        DocBlockGen::PACKAGE_T,
        __NAMESPACE__
    ];

    /**
     * @test
     */
    public function keyValueMgrTest1() {
        $code = $this->keyValueMgrProcess(
            DocBlockGen::factory( DocBlockGen::AUTHOR_T, 'Kjell-Inge Gustafsson' )
                ->setIndent()
                ->setEol( PHP_EOL )
        );
        $this->keyValueMgrTester( $code );
    }

    /**
     * @test
     */
    public function keyValueMgrTest2() {
        $code = $this->keyValueMgrProcess(
            DocBlockGen::init( PHP_EOL, '' )
                ->setTag( DocBlockGen::AUTHOR_T, 'Kjell-Inge Gustafsson' )
        );
        $this->keyValueMgrTester( $code );

        echo $code . PHP_EOL;
    }

    /**
     * @param DocBlockGen $dbg
     * @return string
     */
    public function keyValueMgrProcess( DocBlockGen $dbg ) {

        return $dbg->setSummary( self::SUMMARY )

            // set longer description (string)
            ->setDescription( self::DESCR1 )

            // set another longer description (array)
            ->setDescription( self::DESCR2 )

            ->setTag(
                DocBlockGen::PARAM_T,
                [ DocBlockGen::STRING_PT, DocBlockGen::INT_PT ],
                self::PARAMETER
            )

            ->setTag( DocBlockGen::RETURN_T, DocBlockGen::ARRAY_PT )

            ->setTag( DocBlockGen::PACKAGE_T, __NAMESPACE__ )

            ->toString();
    }

    /**
     * @param string $code
     */
    public function keyValueMgrTester( $code ) {

        $this->assertStringEndsWith( PHP_EOL, $code);

        foreach( self::TEXTS as $text ) {
            $this->assertNotFalse(
                strpos( $code, (string) $text ),
                'text NOT found : ' . $text
            );
        }
    }

    /**
     * @test
     */
    public function keyValueMgrTest3() {
        try {
            DocBlockGen::factory( DocBlockGen::class );
            $this->assertTrue( false );
        }
        catch( Exception $e ) {
            $this->assertTrue( true );
        }
    }

    /**
     * @test
     */
    public function keyValueMgrTest4() {
        $this->assertTrue( DocBlockGen::isValidTag( DocBlockGen::PARAM_T ));
        $this->assertFalse( DocBlockGen::isValidTag( DocBlockGen::class ) );
    }

}
