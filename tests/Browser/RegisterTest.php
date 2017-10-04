<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * Test registering a new user without first name
     *
     * @return void
     */
    public function test_registering_new_user_without_first_name()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->last_name = $faker->lastName;
        $data->email = $faker->unique()->safeEmail;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form without first_name field */
                ->type('last_name', $data->last_name)
                ->type('email', $data->email)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert form is not submitted */
                ->assertPathIs('/register');
        });
    }

    /**
     * Test registering a new user without last name
     *
     * @return void
     */
    public function test_registering_new_user_without_last_name()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->first_name = $faker->firstName();
        $data->email = $faker->unique()->safeEmail;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form without last name field */
                ->type('first_name', $data->first_name)
                ->type('email', $data->email)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert form is not submitted */
                ->assertPathIs('/register');
        });
    }

    /**
     * Test registering a new user without email address
     *
     * @return void
     */
    public function test_registering_new_user_without_email_address()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->first_name = $faker->firstName();
        $data->last_name = $faker->lastName;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form without email field */
                ->type('first_name', $data->first_name)
                ->type('last_name', $data->last_name)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert form is not submitted */
                ->assertPathIs('/register');
        });
    }

    /**
     * Test registering a new user with invalid email address
     *
     * @return void
     */
    public function test_registering_new_user_with_invalid_email_address()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->first_name = $faker->firstName();
        $data->last_name = $faker->lastName;
        $data->email = $faker->word;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form with invalid email */
                ->type('first_name', $data->first_name)
                ->type('last_name', $data->last_name)
                ->type('email', $data->email)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert form is not submitted */
                ->assertPathIs('/register');
        });
    }

    /**
     * Test registering a new user with email address that already exists in users table
     *
     * @return void
     */
    public function test_registering_new_user_with_none_unique_email()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->first_name = $faker->firstName();
        $data->last_name = $faker->lastName;
        $data->email = $faker->email;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        /** create existing user */
        $user = factory(User::class)->create([
            'email' => $data->email,
        ]);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form without email address already in table field */
                ->type('first_name', $data->first_name)
                ->type('last_name', $data->last_name)
                ->type('email', $data->email)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert form is not submitted */
                ->assertPathIs('/register')
                /** assert error message is shown */
                ->assertsee('The email has already been taken.');
        });
    }
    /**
     * Test successfully registering new user
     *
     * @return void
     */
    public function test_register_new_user()
    {
        /** new faker */
        $faker = Faker::create();
        /** set up vars needed for register user form */
        $data = collect();
        $data->first_name = $faker->firstName();
        $data->last_name = $faker->lastName;
        $data->email = $faker->unique()->safeEmail;
        $data->handle = $faker->word;
        $data->password = $faker->password(7);
        /** create team vars */
        $data->team_name = $faker->words(4);
        $this->browse(function (Browser $browser) use  ($data) {
            /** Got to register page */
            $browser->visit('/register')
                /** Assert that we can see page title */
                ->assertSee('Create New User')
                /** fill out user registration form */
                ->type('first_name', $data->first_name)
                ->type('last_name', $data->last_name)
                ->type('email', $data->email)
                ->type('handle', $data->handle)
                ->type('password', $data->password)
                ->type('password_confirmation', $data->password)
                /** submit form */
                ->press('Submit')
                /** assert we are taken to create team page */
                ->assertPathIs('/create/')
                /** assert that we see page title */
                ->assertSee('Create Team')
                /** fill in create team form */
                ->type('name', $data->team_name)
                /** submit create team from */
                ->press('Submit')
                /** wait half a second for application to load */
                ->pause(500)
                /** we are taken into the application */
                ->assertPathIs('/home');
        });
    }
}

