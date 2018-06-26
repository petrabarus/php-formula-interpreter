<?php
/**
 * @author Petra Barus <petra.barus@gmail.com>
 */

namespace Tests\FormulaInterpreter\Command;

use FormulaInterpreter\Command\StringCommand;
use PHPUnit\Framework\TestCase;

/**
 * @author Petra Barus <petra.barus@gmail.com>
 */
class StringCommandTest extends TestCase
{
    
    /**
     * @dataProvider getData
     */
    public function testRun($value, $result)
    {
        $command = new StringCommand($value);
        
        $this->assertEquals($command->run(), $result);
    }
    
    public function getData()
    {
        return [
            ["2", "2"],
            ["2.2", "2.2"],
        ];
    }
    
    /**
     * @dataProvider getIncorrectValues
     */
    public function testInjectIncorrectValue($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new StringCommand($value);
        $command->run();
    }

    public function getIncorrectValues()
    {
        return [
            [new \stdClass()],
            [false],
            [[]],
        ];
    }
}
