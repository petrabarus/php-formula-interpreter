<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FormulaInterpreter\Command;

/**
 * Description of FunctionParser
 *
 * @author mathieu
 */
class FunctionCommand implements CommandInterface
{
    protected $callable;

    protected $argumentCommands = [];

    public function __construct($callable, $argumentCommands = [])
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException();
        }

        $this->callable = $callable;

        foreach ($argumentCommands as $argumentCommand) {
            if (!($argumentCommand instanceof CommandInterface)) {
                throw new \InvalidArgumentException();
            }
        }

        $reflection = new \ReflectionFunction($this->callable);
        if (sizeof($argumentCommands) < $reflection->getNumberOfRequiredParameters()) {
            throw new \FormulaInterpreter\Exception\NotEnoughArgumentsException();
        }

        $this->argumentCommands = $argumentCommands;
    }

    public function run()
    {
        $arguments = [];
        foreach ($this->argumentCommands as $command) {
            $arguments[] = $command->run();
        }

        return call_user_func_array($this->callable, $arguments);
    }

    public function getParameters()
    {
        return array_filter(
                array_reduce(
                    array_map(function ($command) {
                        return $command->getParameters();
                    }, $this->argumentCommands),
                    function ($x, $y) {
                        return array_merge($x, $y);
                    },
                    []
                )
            );
    }
}
