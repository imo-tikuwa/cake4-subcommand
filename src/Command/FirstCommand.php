<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\ConsoleException;

/**
 * First command.
 */
class FirstCommand extends Command
{
    /**
     * FirstCommand のサブコマンドをここに指定する
     *
     * @var array
     */
    private $sub_commands = [
        Sub1Command::class,
        Sub2Command::class,
    ];

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        // サブコマンド名とクラス名およびオプションのマッピング作成
        $sub_commands = [];
        /** @var \Cake\Command\Command $sub_command */
        foreach ($this->sub_commands as $sub_command) {
            $sub_commands[$sub_command::defaultName()] = $sub_command;
        }
        $this->sub_commands = $sub_commands;
    }

    /**
     * @inheritDoc
     */
    public function run(array $argv, ConsoleIo $io): ?int
    {
        $this->initialize();

        $parser = $this->getOptionParser();
        try {
            // コマンドの先頭の引数がサブコマンド名のときオプションパーサーをサブコマンドのものに切り替える
            if (isset($argv[0]) && in_array($argv[0], array_keys($this->sub_commands), true)) {
                $sub_command_name = array_shift($argv);
                /** @var \Cake\Command\Command $sub_command */
                $sub_command = new $this->sub_commands[$sub_command_name]();
                $parser = $sub_command->getOptionParser();
            }

            [$options, $arguments] = $parser->parse($argv);
            $args = new Arguments(
                $arguments,
                $options,
                $parser->argumentNames()
            );
        } catch (ConsoleException $e) {
            $io->err('Error: ' . $e->getMessage());

            return static::CODE_ERROR;
        }
        $this->setOutputLevel($args, $io);

        if ($args->getOption('help')) {
            $this->displayHelp($parser, $args, $io);

            return static::CODE_SUCCESS;
        }

        if ($args->getOption('quiet')) {
            $io->setInteractive(false);
        }

        // サブコマンドが存在する場合、そちらを実行
        if (isset($sub_command)) {
            $result = $sub_command->execute($args, $io);
            if ($result === null || is_int($result)) {
                return $result;
            }
        }

        return $this->execute($args, $io);
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
            'choices' => $choices,
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('FirstCommand start.');

        $outputs = [['sub command name', 'class path', 'class options']];
        foreach ($this->sub_commands as $sub_command_name => $sub_command) {
            $sub_command_options = (new $sub_command())->getOptionParser()->options();
            $sub_command_option_names = array_map(function ($s) {
                /** @var \Cake\Console\ConsoleInputOption $s */
                return "--{$s->name()}";
            }, $sub_command_options);
            $outputs[] = [$sub_command_name, $sub_command, implode(', ', $sub_command_option_names)];
        }
        $io->helper('Table')->output($outputs);

        $io->out('FirstCommand end.');

        return static::CODE_SUCCESS;
    }
}
