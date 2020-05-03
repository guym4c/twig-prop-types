# `twig-prop-types`

React prop types, ported to Twig.

## Installation
With Composer:
```bash
composer require guym4c/twig-prop-types
```

## Usage
This package makes [guym4c/prop-types](https://github.com/guym4c/prop-types-php) accessible in Twig. For documentation on how to use the prop types themselves, refer to that package.

*Props* in the Twig sense refers to the current context. You can use the `only` keyword in Twig to stop the context scope being inherited.

```php
$twigEnv->addExtension(new Guym4c\TwigProps\PropTypesExtension($twigEnv, $bypass));
```

You probably want to pass the bool `$bypass` to the constructor to prevent the validator from running in production, to avoid any performance issues.

To validate your props, put the following at the top of a template file:

```twig
{{
    props({
        optionalNumber: PropTypes.number(),
        aString: PropTypes.string().isRequired(),
        anObject: PropTypes.instanceof('\DateTime'),
        heey: PropTypes.shape({
            maca: PropTypes.string().isRequired(),
            rena: PropTypes.string().isRequired(),
        }),
    })
}}
```

The validator will ignore variables in the context that begin with an underscore.

### Twig-specific differences
* When giving a fully-qualified classname when checking for an instance type, you may use dots (`.`) instead of backslashes (`\`) to simplify string escaping issues, and the leading backslash or dot is optional.

### Advanced
You may pass additional properties to the constructor:

```php
public function __construct(
    Twig\Environment $view,
    string $typesGlobal = 'PropTypes',
    array $exclude = [],
    bool $allowExtraProperties = false
) {
```

|Param                   |Value   |
|------------------------|---|
|`Twig\Environment $view`| The Twig environment that will be rendering the templates   |
|`string $typesGlobal`   | The name of the `PropTypes` Twig global (optional) |
|`array $exclude`        | Additional Twig variable names to exclude, such as Twig globals you have set yourself. Variables starting with an underscore, and the types global, are automagically excluded.|
| `bool $allowExtraProperties` | By default, the validator will throw an error if extra properties are in the context. Set this to `true` to disable this.|


