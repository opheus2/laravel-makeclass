<?php

namespace Orpheus\LaravelMakeClass\Console;

class MakeClass extends ClassCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:class
                            {name : The name of the class}
                            {--i|interface : Create an interface}
                            {--e|enum : Create an enum}
                            {--T|trait : Create a trait}
                            {--f|final : Make the class final}
                            {--a|abstract : Make the class abstract}
                            {--x|strict : Declare strict types}
                            {--p|path= : Declare the part to be remembered}
                            {--alias= : Declare the path keyword}
                            {--t|type=class : Alternative class type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new php class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/MakeClass.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace the default namespaces
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }
}
