<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Sub2 command.
 */
class Sub2Command extends Command
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
        $parser->addOption('sub2-option', [
            'help' => 'sub2 command option.',
        ])->addOption('sub2-choice-option', [
            'help' => 'sub2 command choices option.',
            'choices' => ['hoge', 'fuga'],
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
        $io->out('Sub2Command start.');

        $io->out(sprintf('sub2-option: %s', $args->getOption('sub2-option')));
        $io->out(sprintf('sub2-choice-option: %s', $args->getOption('sub2-choice-option')));

        $io->out('Sub2Command end.');
    }
}
