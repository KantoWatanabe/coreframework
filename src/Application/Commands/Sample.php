<?php
namespace App\Application\Commands;

use App\Core\Command;

class Sample extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function exec()
    {
        printf($this->getArg(0) . "\n");
        printf($this->getOpt('test') . "\n");
        sleep(5);
    }
}