<?php

namespace Oro\Bundle\LayoutBundle\Tests\Unit\Layout\Extension\Generator;

use CG\Generator\PhpClass;
use CG\Generator\PhpMethod;
use CG\Core\DefaultGeneratorStrategy;

use Oro\Component\Layout\Loader\Generator\VisitContext;
use Oro\Component\Layout\Loader\Generator\Extension\ImportLayoutUpdateVisitor;

class ImportLayoutUpdateVisitorTest extends \PHPUnit_Framework_TestCase
{
    // @codingStandardsIgnoreStart
    public function testVisit()
    {

        $condition = new ImportLayoutUpdateVisitor();
        $phpClass = PhpClass::create('ImportedLayoutUpdate');
        $visitContext = new VisitContext($phpClass);

        $method = PhpMethod::create('testMethod');

        $condition->startVisit($visitContext);
        $visitContext->getUpdateMethodWriter()->writeln('echo 123;');
        $condition->endVisit($visitContext);

        $method->setBody($visitContext->getUpdateMethodWriter()->getContent());
        $phpClass->setMethod($method);
        $strategy = new DefaultGeneratorStrategy();
        $this->assertSame(
<<<CLASS
use Oro\Component\Layout\ImportLayoutManipulator;

class ImportedLayoutUpdate implements \Oro\Component\Layout\LayoutUpdateImportInterface
{
    private \$parentLayoutUpdate;
    private \$import;
    private \$applicable = false;

    public function testMethod()
    {
        if (null === \$this->import) {
            throw new \RuntimeException('Missing import configuration for layout update');
        }

        if (\$this->parentLayoutUpdate instanceof Oro\Component\Layout\IsApplicableLayoutUpdateInterface
            && !\$this->parentLayoutUpdate->isApplicable()) {
            return;
        }

        \$this->applicable = true;

        \$layoutManipulator  = new ImportLayoutManipulator(\$layoutManipulator, \$this->import);
        echo 123;
    }

    public function setParentUpdate(\Oro\Component\Layout\ImportsAwareLayoutUpdateInterface \$parentLayoutUpdate)
    {
        \$this->parentLayoutUpdate = \$parentLayoutUpdate;
    }

    public function setImport(\Oro\Component\Layout\Model\LayoutUpdateImport \$import)
    {
        \$this->import = \$import;
    }

    public function isApplicable()
    {
        return \$this->applicable;
    }

    public function getImport()
    {
        return \$this->import;
    }
}
CLASS
        ,
        $strategy->generate($visitContext->getClass()));
    }
    //codingStandardsIgnoreEnd
}
