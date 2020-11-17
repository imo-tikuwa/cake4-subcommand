<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * First command.
 */
class FirstCommand extends Command
{
    /**
     * FirstCommand のサブコマンドをここに指定する
     * @var \Cake\Command\Command[] $sub_commands
     */
    private $sub_commands = [
        Sub1Command::class,
        Sub2Command::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        // サブコマンド名前とクラス名のマッピング作成
        $sub_commands = [];
        foreach ($this->sub_commands as $sub_command) {
            $sub_commands[$sub_command::defaultName()] = $sub_command;
        }
        $this->sub_commands = $sub_commands;
    }


    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $choices = array_keys($this->sub_commands);

        $parser = parent::buildOptionParser($parser);
        $parser->addArgument('sub_command', [
            'help' => 'サブコマンド名を入力してください',
            'required' => true,
            'choices' => $choices,
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
        $io->out('FirstCommand start.');

        // サブコマンド実行
        $this->executeCommand($this->sub_commands[$args->getArgument('sub_command')]);

        $io->out('FirstCommand end.');
    }
}
