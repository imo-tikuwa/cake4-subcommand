<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Sub1 command.
 */
class Sub1Command extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addOption('sub1-option', [
            'help' => 'sub1 command option.',
            'short' => 's',
        ])->addOption('sub1-boolean-option', [
            'help' => 'sub1 command boolean option.',
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Sub1Command start.');

        $io->out(sprintf("sub1-option: %s", $args->getOption('sub1-option')));
        $io->out(sprintf("sub1-boolean-option: %s", $args->getOption('sub1-boolean-option') ? 'true' : 'false'));

        $io->out('Sub1Command end.');
    }
}
