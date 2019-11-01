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

class LoginTest extends TestCase
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
    public function testShowUsermanagementList(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admins = factory(Admin::class, 15)->create();
        $this->assertEquals(15, count($admins));

        /** @var TestResponse $response */
        $response = $this->actingAs($admins[0], 'admin')->followingRedirects()->get('/admin/usermanagement');
        /** @var Admin $admin */
        foreach($admins as $admin){
            $response->assertSee($admin->name);
            $response->assertSee($admin->email);
        }

        $this->flushSession();
    }

    /**
     * @test
     */
    public function testPagination(): void
    {
        $account = 45;
        $pageSize = 15;
        $this->startSession();
        /** @var array $admins */
        $admins = factory(Admin::class, $account)->create();
        $this->assertEquals($account, count($admins));

        /** @var TestResponse $response */
        $response = $this->actingAs($admins[0], 'admin')->followingRedirects()->get('/admin/usermanagement');

        for($i = 2; $i < $account/$pageSize; $i++) {
            $response->assertSee($this->baseUrl . '/admin/usermanagement?page=' . $i);
        }

        $response = $this->followingRedirects()->get('/admin/usermanagement?page=' . $account/$pageSize);
        $response->assertStatus(200);

        for($i = 0; $i < count($admins); $i++){
            if($i < 30){
                $response->assertDontSee($admins[$i]->email);
            }else{
                $response->assertSee($admins[$i]->name);
            }
        }

        $this->flushSession();
    }
}
