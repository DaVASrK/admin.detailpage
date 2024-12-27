<?php

namespace DVK\Admin\DetailPage\Cli;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateDetailPageCommand extends Command
{
    const TEMPLATES_DIR = __DIR__ . '/../../templates';
    const DEFAULT_TEMPLATE = 'standart';
    const DEFAULT_DIRECTORY = 'php_interface/include/dvk';

    protected function configure(): void
    {
        $this
            ->setName('dvk:admin.detailpage.create')
            ->setDescription('Creates custom editor page for iblock element page.')
            ->setHelp('This command create custom editor page for iblock element page.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                        'filename', InputArgument::REQUIRED, 'Filename of custom editor page.'
                    ),
                    new InputOption(
                        'path', 'p', InputOption::VALUE_REQUIRED,
                        'File location path relative to the directory local/', 'php_interface/include/dvk'
                    ),
                    new InputOption(
                        'template', 't', InputOption::VALUE_REQUIRED,
                        'Template name', 'standart'
                    ),
                    new InputOption(
                        'empty', 'e', InputOption::VALUE_NONE,
                        'Create page with empty template'
                    ),
                ])
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $filename = $input->getArgument('filename');
            $templateName = $this->getTemplateName($input);
            $outputPath = $this->getOutputPath($input);
            $templatePath = self::TEMPLATES_DIR . '/' . $templateName . '.php';


            if (!file_exists($templatePath)) {
                $io->error('Error: template "' . $templateName . '" not found');
                $io->note('Available templates:');
                $io->listing($this->getTemplateList());
                return Command::FAILURE;
            }

            $outputFilePath = $outputPath . '/' . $filename . '.php';

            if (file_exists($outputFilePath)) {
                $io->error('Error: output file "' . $filename . '.php" with this name already exists in the directory "' . $outputPath . '"');
            }

            $templateContent = File::getFileContents($templatePath);
            File::putFileContents($outputFilePath, $templateContent);
        } catch (\Exception $e) {
            $io->error('Exception: '.$e->getMessage().PHP_EOL.$e->getTraceAsString());
        }

        $io->info('File was successfully created');

        return Command::SUCCESS;
    }


    protected function getTemplateName(InputInterface $input): string
    {
        $templateName = self::DEFAULT_TEMPLATE;
        if ($input->getOption('empty')) {
            $templateName = 'empty';
        } elseif ($input->getOption('template')) {
            $templateName = $input->getOption('template');
        }

        return $templateName;
    }

    protected function getOutputPath(InputInterface $input): string
    {
        $outputPath = Application::getDocumentRoot() . '/local/' . self::DEFAULT_DIRECTORY;
        if ($input->getOption('path')) {
            $outputPath = Application::getDocumentRoot() . '/local/' . $input->getOption('path');
        }

        return $outputPath;
    }

    protected function getTemplateList(): array
    {
        $templates = [];
        $directory = new \Bitrix\Main\IO\Directory(self::TEMPLATES_DIR);

        foreach ($directory->getChildren() as $child) {
            if ($child->getExtension() == 'php') {
                $templates[] = str_replace('.php', '', $child->getName());
            }
        }

        return $templates;
    }
}
