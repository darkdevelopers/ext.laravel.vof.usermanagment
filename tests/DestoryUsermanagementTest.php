<?php
/**
 * @author     Marco Schauer <marco.schauer@darkdevelopers.de.de>
 * @copyright  2019 Marco Schauer
 */

namespace Vof\Usermanagment\Test;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;
use Vof\Admin\AdminFacade;
use Vof\Admin\AdminServiceProvider;
use Vof\Admin\Models\Admin;

class DestoryUsermanagementTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../../ext.laravel.vof.admin/src/database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../../ext.laravel.vof.admin/src/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../../../../database/migrations/2014_10_12_000000_create_users_table.php');
        /** @var string baseUrl */
        $this->baseUrl = "http://vof.local";
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication(): Application
    {
        $app = require dirname(__DIR__) . '/../../../bootstrap/app.php';

        $app->make(Kernel::class)
            ->bootstrap();

        return $app;
    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [AdminServiceProvider::class];
    }

    /**
     * Load package alias
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Admin' => AdminFacade::class,
        ];
    }

    /**
     * @test
     */
    public function testDeleteUsermanagament(): void
    {
        $this->startSession();
        /** @var Admin $admin */
        $admin = factory(Admin::class)->create();
        $adminAccount = factory(Admin::class)->make();
        /** @var TestResponse $response */
        $response = $this->actingAs($adminAccount, 'admin')->followingRedirects()->get('/admin/usermanagement');
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);

        $response = $this->actingAs($adminAccount, 'admin')->followingRedirects()->delete('/admin/usermanagement/1', [
            '_token' => $this->app['session']->token(),
        ],[
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->actingAs($adminAccount, 'admin')->followingRedirects()->get('/admin/usermanagement');
        $response->assertDontSee($admin->email);

        $this->flushSession();
    }
}
