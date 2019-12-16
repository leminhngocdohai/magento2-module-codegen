<?php

namespace Orba\Magento2Codegen\Command\Template;

use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Service\IOFactory;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\TemplateFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{
    /**
     * @var string|null
     */
    private $templateName;

    /**
     * @var Template
     */
    private $util;

    /**
     * @var IOFactory
     */
    private $ioFactory;

    /**
     * @var IO
     */
    private $io;

    /**
     * @var TemplateFile
     */
    private $templateFile;

    public function __construct(Template $util, IOFactory $ioFactory, TemplateFile $templateFile)
    {
        parent::__construct();
        $this->util = $util;
        $this->ioFactory = $ioFactory;
        $this->templateFile = $templateFile;
    }

    public function configure()
    {
        $this
            ->setName('template:info')
            ->setDescription('Show extended info of specific template.')
            ->setHelp("This command displays a description of what the template does.")
            ->addArgument(
                Template::ARG_TEMPLATE,
                InputArgument::REQUIRED,
                'The template to show description for.'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $this->io = $this->ioFactory->create($input, $output);
        $this->templateName = $this->util->getTemplateName($this->io);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->util->validateTemplate($this->templateName);
        $this->displayHeader();
        $this->displayDescription();
        $this->displayDependencies();
    }

    private function displayHeader(): void
    {
        $this->io->writeln('<comment>Template Info</comment>');
        $this->io->title($this->templateName);
    }

    private function displayDescription(): void
    {
        $description = $this->templateFile->getDescription($this->templateName);
        if ($description) {
            $this->io->text($description);
        } else {
            $this->io->text('Sorry, there is not info defined for this template.');
        }

    }

    private function displayDependencies(): void
    {
        $dependencies = $this->templateFile->getDependencies($this->templateName);
        if ($dependencies) {
            $this->io->note('DEPENDENCIES - This module will also load the following templates:');
            $this->io->text($dependencies);
        }
    }


}