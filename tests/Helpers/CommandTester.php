<?php

namespace MultiTenantLaravel\Tests\Helpers;

use Illuminate\Support\Facades\Artisan;

class CommandTester
{
    public $command;

    public function __construct($file)
    {
        $faker = \Faker\Factory::create();

        $this->command = \Mockery::mock($file, [$faker])->makePartial();
    }

    public function fire($command_name, $options = [])
    {
        app()['Illuminate\Contracts\Console\Kernel']->registerCommand($this->command);

        Artisan::call($command_name, $options);
    }

    public function asks($question, $answer)
    {
        $this->command->shouldReceive('ask')
            ->with($question)
            ->andReturn($answer);
    }

    public function anticipates($question, $options, $default, $answer)
    {
        $this->command->shouldReceive('anticipate')
            ->with($question, $options, $default)
            ->andReturn($answer);
    }
}
