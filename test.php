<?php
interface MessageInterface {}
class MessageA implements MessageInterface {
    private $a;

    public function setA($a)
    {
        $this->a = $a;
    }

    public function getA()
    {
        return $this->a;
    }

    public function decreaseA()
    {
        --$this->a;
    }
}

$a = new MessageA();
$a->setA('123');

var_dump($a->getA());
$a->decreaseA();
var_dump($a->getA());
$a->decreaseA();
var_dump($a->getA());

