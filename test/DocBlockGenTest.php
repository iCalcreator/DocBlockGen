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
    /**
     * @test
     */
    public function keyValueMgrTest1() {
        $code = DocBlockGen::factory( DocBlockGen::AUTHOR_T, 'Kjell-Inge Gustafsson' )
            ->setIndent()
            ->setEol( PHP_EOL )

            ->setSummary( 'This is a top (short) description' )

            // set longer description (string)
            ->setDescription( 'This is a longer description' )

            // set another longer description (array)
            ->setDescription(
                [
                    '',
                    'This is another longer description',
                    'with more info on the next row',
                    ''
                ]
            )

            ->setTag(
                DocBlockGen::PARAM_T,
                [ DocBlockGen::STRING_PT, DocBlockGen::INT_PT ],
                'parameter'
            )

            ->setTag( DocBlockGen::RETURN_T, DocBlockGen::ARRAY_PT )

            ->setTag( DocBlockGen::PACKAGE_T, __NAMESPACE__ )

            ->toString();

        $this->assertStringEndsWith( PHP_EOL, $code);

    }

    /**
     * @test
     */
    public function keyValueMgrTest2() {
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
    public function keyValueMgrTest3() {
        $this->assertTrue( DocBlockGen::isValidTag( DocBlockGen::PARAM_T ));
        $this->assertFalse( DocBlockGen::isValidTag( DocBlockGen::class ) );
    }

}
