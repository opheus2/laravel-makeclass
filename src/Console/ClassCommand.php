<?php

namespace Orpheus\LaravelMakeClass\Console;

use Illuminate\Support\Facades\Cache;
use Illuminate\Console\GeneratorCommand;

abstract class ClassCommand extends GeneratorCommand
{
    const CLASS_TYPES = ["class", "interface", "enum", "trait"];

    /**
     * The boilerplate code.
     *
     * @var string
     */
    protected $stub;

    /**
     * The boilerplate code.
     *
     * @var string
     */
    protected $path;

    /**
     * The replaceable attributes.
     *
     * @var array
     */
    protected $attributes = [
        "{{ isFinal }}",
        "{{ isAbstract }}",
        "{{ isStrict }}",
    ];

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        try {
            $this->stub = $this->files->get($this->getStub());

            $type = str_replace("=", "", $this->option("type"));

            if (!in_array(strtolower($type), self::CLASS_TYPES)) {
                throw new \Exception("Invalid class type", 1);
            }

            $options = array_filter(
                $this->options(),
                function ($value, $key) {
                    return in_array($key, self::CLASS_TYPES) && $value;
                },
                ARRAY_FILTER_USE_BOTH
            );

            if (count($options) > 1) {
                throw new \Exception("Please use only one class flag", 1);
            }

            if ($this->option("interface")) {
                $type = "interface";
            }

            if ($this->option("trait")) {
                $type = "trait";
            }

            if ($this->option("enum")) {
                if (version_compare(phpversion(), '8.1.0', '<')) {
                    $this->warn(
                        "Warning: Your current php version "
                            . phpversion() .
                            " does not support enums"
                    );
                }

                $type = "enum";
            }

            if ($path = str_replace("=", "", $this->option("path"))) {
                $name = $path . $this->getNameInput();

                $this->path = Cache::get("lmc.{$path}");

                if ($alias = str_replace("=", "", $this->option("alias"))) {
                    Cache::put("lmc.{$alias}", $name);
                }

                if (is_null($this->path)) {
                    $this->path = $name;
                }
            }

            /**
             * Make class a final class
             */
            if ($type === "class" & $this->option("final")) {
                $this->removeAttribute("{{ isFinal }}");
                $this->makeClassFinal();
            }

            /**
             * Make class an abstract class
             */
            if ($type === "class" & $this->option("abstract")) {
                $this->removeAttribute("{{ isAbstract }}");
                $this->makeClassAbstract();
            }

            /**
             * Declare strict types for the class
             */
            if ($this->option("strict")) {
                $this->removeAttribute("{{ isStrict }}");
                $this->makeClassStrict();
            }

            $this->replaceClassType($type);

            $this->clearAttributes();

            parent::handle();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Make the class type
     *
     * @param string $classType classType to be used
     *
     * @return self
     */
    protected function replaceClassType(string $classType)
    {
        $this->stub = str_replace('{{ classType }}', $classType, $this->stub);

        return $this;
    }

    /**
     * Make the class final
     *
     * @return self
     */
    protected function makeClassFinal()
    {
        $this->stub = str_replace('{{ isFinal }}', "final ", $this->stub);

        return $this;
    }

    /**
     * Make the class an abstract
     *
     * @return self
     */
    protected function makeClassAbstract()
    {
        $this->stub = str_replace('{{ isAbstract }}', "abstract ", $this->stub);

        return $this;
    }

    /**
     * Add declare strict type
     *
     * @return self
     */
    protected function makeClassStrict()
    {
        $this->stub = str_replace(
            '{{ isStrict }}',
            "\ndeclare(strict_types=1); ",
            $this->stub
        );

        return $this;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name class name and file name
     *
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->stub ?? $this->getStub();

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Remove a specific attribute from the attributes array
     *
     * @param string $attribute class type attribute
     *
     * @return self
     */
    protected function removeAttribute(string $attribute)
    {
        if (($key = array_search($attribute, $this->attributes)) !== false) {
            unset($this->attributes[$key]);
        }

        return $this;
    }

    /**
     * Remove all unused attributes
     *
     * @return self
     */
    protected function clearAttributes()
    {
        foreach ($this->attributes as $attribute) {
            $this->stub = str_replace($attribute, '', $this->stub);
        }

        return $this;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->path ?? $this->argument('name'));
    }
}
