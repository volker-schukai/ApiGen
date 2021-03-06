<?php

namespace ApiGen\Tests\Templating\Filters;

use ApiGen\Configuration\Configuration;
use ApiGen\Configuration\ConfigurationOptions as CO;
use ApiGen\Templating\Filters\AnnotationFilters;
use Mockery;
use PHPUnit_Framework_TestCase;

class AnnotationFiltersTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AnnotationFilters
     */
    private $annotationFilters;


    protected function setUp()
    {
        $configurationMock = Mockery::mock(Configuration::class);
        $configurationMock->shouldReceive('getOption')->with(CO::INTERNAL)->andReturn(false);
        $configurationMock->shouldReceive('getOption')->with(CO::TODO)->andReturn(false);
        $elementResolverMock = Mockery::mock('ApiGen\Generator\Resolvers\ElementResolver');
        $this->annotationFilters = new AnnotationFilters($configurationMock, $elementResolverMock);
    }


    public function testAnnotationBeautify()
    {
        $this->assertSame('Used by', $this->annotationFilters->annotationBeautify('usedby'));
        $this->assertSame('Method', $this->annotationFilters->annotationBeautify('method'));
    }


    public function testAnnotationFilter()
    {
        $annotations = ['method' => true, 'remain' => true, 'todo' => true, 'internal' => true];
        $this->assertSame(
            ['remain' => true],
            $this->annotationFilters->annotationFilter($annotations)
        );
    }


    public function testAnnotationFilterWithCustom()
    {
        $annotations = ['remain' => true, 'otherToRemain' => true];
        $this->assertSame(
            ['otherToRemain' => true],
            $this->annotationFilters->annotationFilter($annotations, ['remain'])
        );
    }
}
