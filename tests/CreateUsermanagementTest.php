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

class CreateUsermanagementTest extends TestCase
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
    public function testCreateUsermanagementPageLoad(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admins = factory(Admin::class)->create();

        /** @var TestResponse $response */
        $response = $this->actingAs($admins, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.create.headline') . ' | VOF Admin');
        $response->assertSee('<input type="text" id="name" name="name"');
        $response->assertSee('<input type="email" id="email" name="email"');
        $response->assertSee('<input type="password" id="password" name="password"');
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.btn-save'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testCreateUsermanagementSuccessfully(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
            'name' => $adminMake->name,
            'email' => $adminMake->email,
            'password' => 'secret12',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee($adminMake->name);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.admin-created-success'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testCreateUsermanagementUnccessfullyNameRequired(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
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
    public function testCreateUsermanagementUnccessfullyWrongEmail(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name, 'email' => 'dummy.dummy.de']);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
            'name' => $adminMake->name,
            'email' => 'dummy.dummy',
            'password' => 'secret12',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee($adminMake->name);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-is-wrong'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testCreateUsermanagementUnccessfullyEmailRequired(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
            'name' => $adminMake->name,
            'email' => '',
            'password' => 'secret1337',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee($adminMake->name);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.email-required'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testCreateUsermanagementUnccessfullyPasswordTooShort(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
            'name' => $adminMake->name,
            'email' => $adminMake->email,
            'password' => 'secr',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee($adminMake->name);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-too-short'));
        $this->flushSession();
    }

    /**
     * @test
     */
    public function testCreateUsermanagementUnccessfullyPasswordRequired(): void
    {
        $this->startSession();
        /** @var array $admins */
        $admin = factory(Admin::class)->create();
        $adminMake = factory(Admin::class)->make(['name' => $this->faker->name]);

        /** @var TestResponse $response */
        $response = $this->actingAs($admin, 'admin')->followingRedirects()->get('/admin/usermanagement/create');
        $response->assertStatus(200);

        $response = $this->post('admin/usermanagement', [
            'name' => $adminMake->name,
            'email' => $adminMake->email,
            'password' => '',
            '_token' => $this->app['session']->token(),
        ], [
            'content-type' => 'multipart/form-data',
        ]);
        $response = $this->followRedirects($response);

        $response->assertSee($adminMake->name);
        $response->assertSee(__('vof.admin.usermanagment::usermanagment.partials.create-read-update.password-required'));
        $this->flushSession();
    }
}
