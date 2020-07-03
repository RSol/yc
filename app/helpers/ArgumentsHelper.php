<?php

namespace app\helpers;

use app\exceptions\NotEnoughArgumentsException;
use app\exceptions\WrongArgumentsException;

class ArgumentsHelper
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * ArgHelper constructor.
     * @param array $arguments
     * @throws NotEnoughArgumentsException
     * @throws WrongArgumentsException
     */
    public function __construct($arguments)
    {
        $this->setArguments($arguments);
    }

    /**
     * @param array $arguments
     * @throws NotEnoughArgumentsException
     * @throws WrongArgumentsException
     */
    public function setArguments($arguments)
    {
        if (!is_array($arguments)) {
            throw new WrongArgumentsException('Should be array');
        }
        array_shift($arguments);
        if (!$arguments) {
            throw new NotEnoughArgumentsException('Wrong arguments count');
        }
        $this->arguments = $arguments;
    }

    /**
     * @return mixed|null
     */
    public function getFirstArgument()
    {
        return $this->getArgumentByPosition(1);
    }

    /**
     * Human like numbers
     * @param $position
     * @return mixed|null
     */
    public function getArgumentByPosition($position)
    {
        if (array_key_exists($position - 1, $this->arguments)) {
            return $this->arguments[$position - 1];
        }
        return null;
    }
}
