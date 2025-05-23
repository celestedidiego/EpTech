<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarExporter\Tests\Fixtures;

class FooSerializable implements \Serializable
{
    private $foo;

    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function serialize(): string
    {
        return serialize([$this->getFoo()]);
    }

    public function unserialize($str)
    {
        [$this->foo] = unserialize($str);
    }
}
