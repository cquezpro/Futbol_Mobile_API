<?php
/**
 * Created by PhpStorm.
 * User: Ing Kevin Cifuentes
 * Date: 14/04/2018
 * Time: 12:04 AM
 */

namespace App\Helpers;


use Symfony\Component\Console\Helper\ProgressBar;

class CustomProgressBar
{
    public $progressBar;

    public function __construct(ProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;

        $this->progressBar->setBarCharacter("<fg=green>\xE2\x97\x8F</>");
        $this->progressBar->setEmptyBarCharacter("<fg=red>\xE2\x97\x8F</>");
        $this->progressBar->setProgressCharacter("<fg=green>\xE2\x97\x8F</>");

        $this->progressBar->setFormat("<fg=white;bg=blue> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\nRemaining: %remaining:-1s%|%estimated:-1s%");
    }

    public function setMessage($message, $pos)
    {
        $this->progressBar->setMessage($message, $pos);
    }

    public function advance()
    {
        $this->progressBar->advance();
    }

    public function finish()
    {
        $this->progressBar->finish();
    }
}