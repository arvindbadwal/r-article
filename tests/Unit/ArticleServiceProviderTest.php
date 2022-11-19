<?php

namespace Cactus\Article\Tests\Unit;

use Cactus\Article\ArticleServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\ServiceProvider;
use Mockery;

class ArticleServiceProviderTest extends TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $config_mock;

    /**
     * @var Mockery\Mock
     */
    protected $application_mock;

    /**
     * @var ServiceProvider
     */
    protected $service_provider;

    protected function setUp(): void
    {
        $this->setUpMocks();

        $this->service_provider = new ArticleServiceProvider($this->application_mock);

        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [ArticleServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    protected function setUpMocks()
    {
        $this->config_mock = Mockery::mock(Config::class);
        $this->application_mock = Mockery::mock(Application::class, ['make' => $this->config_mock]);
    }

    /**
     * @test
     */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->service_provider);
    }

    /**
     * @test
     */
    public function it_performs_the_register_method()
    {
        // TODO:: need to perform register method test case

        $this->assertNull(null);
    }

    /**
     * @test
     */
    public function it_performs_a_boot_method()
    {
        // TODO:: need to perform boot method test case

        $this->assertNull(null);
    }
}