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

class EditUsermanagementTest extends TestCase
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
    public function testEditUsermanagmentShowAndUpdate(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create(['name' => $this->faker->name]);
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);

        $response = $this->put('/admin/usermanagement/1', [
            'name' => $adminMake->name,
            'email' => $admin->email,
            'password' => 'secret12',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertStatus(200);
        $response->assertSee($adminMake->name);
        $response->assertSee($admin->email);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-created-success'));

        $this->flushSession();
    }

    /**
     * @test
     */
    public function testEditUsermanagmentNameRequired(): void
    {
        $this->startSession();
        /** @var Admin $admin */
        $admin = factory(Admin::class)->create(['name' => $this->faker->name]);
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);

        $response = $this->patch('/admin/usermanagement/1', [
            'name' => '',
            'email' => $adminMake->email,
            'password' => 'secret1337',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.username-required'));
        $this->flushSession();
    }


    /**
     * @test
     */
    public function testEditUsermanagmentWrongEmail(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name, 'email' => 'dummy.dummy.de']);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);

        $response = $this->put('/admin/usermanagement/1', [
            'name' => $adminMake->name,
            'email' => 'dummy.dummy',
            'password' => 'secret12',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-is-wrong'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testEditUsermanagmentEmailRequired(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);

        $response = $this->put('/admin/usermanagement/1', [
            'name' => $adminMake->name,
            'email' => '',
            'password' => 'secret1337',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-required'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testEditUsermanagmentPasswordTooShort(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);

        $response = $this->put('/admin/usermanagement/1', [
            'name' => $adminMake->name,
            'email' => $adminMake->email,
            'password' => 'secr',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-too-short'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testEditUsermanagmentPasswordRequired(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/1/edit');
        $response->assertStatus(200);

        $response = $this->put('/admin/usermanagement/1', [
            'name' => $adminMake->name,
            'email' => $adminMake->email,
            'password' => '',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-required'));
        $this->flushSession();
    }
}
