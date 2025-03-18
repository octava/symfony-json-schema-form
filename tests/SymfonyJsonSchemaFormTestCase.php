<?php

namespace Octava\SymfonyJsonSchemaForm\Tests;

use Octava\SymfonyJsonSchemaForm\Form\Extension\AddSymfonyJsonSchemaFormExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Contracts\Translation\TranslatorInterface;

class SymfonyJsonSchemaFormTestCase extends TestCase
{
    protected ?FormFactoryInterface $factory = null;

    /**
     * @var TranslatorInterface&MockObject|null
     */
    protected ?TranslatorInterface $translator = null;

    protected function setUp(): void
    {
        $ext = new AddSymfonyJsonSchemaFormExtension();
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->addTypeExtensions([$ext])
            ->getFormFactory();

        $this->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
    }
}
