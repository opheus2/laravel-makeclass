#Laravel Make Class
### Features

- Create a php class from the command for any directory in the root name-space;
- Store a custom path as an alias and use alias instead of full path.
- Create interface | class | enum | trait.
- Create final and abstract classes
- Create class with a declare strict_type

### Installation
```bash
composer require opheus2/laravel-makeclass
```

###Usages
> File is created in root name-space. E.b App
```bash
php artisan make:class {name}
```
Default type is class.

------------

> File is created in test folder in root name-space. E.b App/Test
```bash
php artisan make:class Test\{name}
```
------------ 
> Create an interface type of class
```bash
php artisan make:class {name} -i
					or
php artisan make:class {name} --type interface
```
------------ 

|  Available types | flags|
| ------------ | 
|  class | (default no flag) |
|   interface | (-i or --interface) |
|   trait | (-T or --trait) |
|   enum | (-e or --enum) |

------------
> Using custom path with alias
```bash
php artisan make:class {name} -i -p="Domains/Services/" --alias="ape"
					or
php artisan make:class {name} -i -p=Domain\Services\ --alias=ape
					or
php artisan make:class {name} -i -p Domain\Services\ --alias ape
```
**Please note:** You always need to add a trailing slash at the end for it to work properly.
I would surely fix that soon. 

Then you can use alias as path
```bash
php artisan make:class {name} -i -p="ape"
```
This would use the same old/saved path for subsquent file names

------------
> Using modifiers
```bash
php artisan make:class {name} -fx
```
This would creat a final class with declare strict_types at the top
You can use the -x flag to always add the strict type to any class type

------------

|  Available modfiers | flags|
| ------------ | 
|  strict | (-x or --strict) |
|   final | (-f or --final) |
|   abstract | (-a or --abstract) |

For more info you can do 
```bash
php artisan make:class --help
```

####Thank you

###TODO
- [ ] Add check for trailing slash and auto fix
