<?php

namespace Orpheus\LaravelMakeClass\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Orpheus\LaravelMakeClass\Tests\TestCase;

class MakeClassTest extends TestCase
{
    use WithFaker;

    protected Filesystem $files;

    public function setUp(): void
    {
        $this->files = new Filesystem();

        parent::setUp();
    }

    /**
     * test that a plain make:class with name command works
     *
     * @return void
     */
    function test_make_class_with_name_command()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("class", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }

    public function classTypeDataProvider()
    {
        return [
            ['interface'],
            ['class'],
            ['enum'],
            ['trait'],
        ];
    }

    /**
     * test that multiple types of class created with the type command works
     * @dataProvider classTypeDataProvider
     * @return void
     */
    function test_make_different_class_with_type_command($classType)
    {
        $randomName = Str::studly(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -t {$classType}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("{$classType}", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }

    public function classModifierDataProvider()
    {
        return [
            ['abstract'],
            ['final'],
            ['strict'],
        ];
    }

    /**
     * test that multiple types of class created with different modifiers
     * @dataProvider classModifierDataProvider
     * @return void
     */
    function test_make_different_class_with_class_modifier($classType)
    {
        $randomName = Str::studly(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} --{$classType}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("{$classType}", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }

    /**
     * test that an interface class with the interface flag works
     *
     * @return void
     */
    function test_make_class_with_wrong_type()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -t Broom")->expectsOutput("Invalid class type");

        $this->assertFalse(File::exists($filePath));
    }

    /**
     * test that an interface class with the interface flag works
     *
     * @return void
     */
    function test_make_class_interface_with_flag_command()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -i")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("interface", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }

    /**
     * test that a trait class with the trait flag command works
     *
     * @return void
     */
    function test_make_class_trait_with_flag_command()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -T")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("trait", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }

    /**
     * test that a trait class with the enum flag command works
     *
     * @return void
     */
    function test_make_class_enum_with_flag_command()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -e")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("enum", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
    }


    /**
     * test that multiple flag commands fails
     *
     * @return void
     */
    function test_make_class_with_multiple_flag_command()
    {
        $randomName = Str::camel(implode(" ", $this->faker->words(2)));
        $filePath = app_path("{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -ieT")->expectsOutput("Please use only one class flag");

        $this->assertFalse(File::exists($filePath));
    }

    /**
     * test that make:class command with appended path works
     *
     * @return void
     */
    function test_make_class_with_name_with_traditional_path_command()
    {
        $randomName = Str::studly(implode(" ", $this->faker->words(2)));
        $path = "Domains/Src/{$randomName}";
        $filePath = app_path("{$path}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$path}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("class", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
        $this->assertTrue(File::deleteDirectory(app_path("Domains")));
    }

    /**
     * test that make:class command with custom path works
     *
     * @return void
     */
    function test_make_class_with_name_with_custom_path_command()
    {
        $randomName = Str::studly(implode(" ", $this->faker->words(2)));
        $path = "Domains/Src/";
        $filePath = app_path("{$path}{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -p={$path}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("class", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
        $this->assertTrue(File::deleteDirectory(app_path("Domains")));
    }

    /**
     * test that making a class with custom path and alias gets cached
     *
     * @return void
     */
    function test_make_class_with_name_with_custom_path_and_alias()
    {
        $randomName = Str::studly(implode(" ", $this->faker->words(2)));
        $path = "Domains/Src/";
        $alias = "ape";
        $filePath = app_path("{$path}{$randomName}.php");

        $this->assertFalse(File::exists($filePath));

        $this->artisan("make:class {$randomName} -p={$path} --alias={$alias}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("class", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
        $this->assertTrue(File::deleteDirectory(app_path("Domains")));

        $this->artisan("make:class {$randomName} -p {$alias}")->assertSuccessful();

        $this->assertTrue(File::exists($filePath));
        $this->assertStringContainsString("class", $this->files->get($filePath));
        $this->assertTrue(File::delete($filePath));
        $this->assertTrue(File::deleteDirectory(app_path("Domains")));
    }
}
