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
$twigEnv->addExtension(new Guym4c\TwigProps\PropTypesExtension(
    $twigEnv, 
    $bypass,
    $typesGlobal,
    $exclude,
    $allowExtraProperties,
));
```

### Extension constructor parameters
* `$twigEnv`: the current Twig environment
* `$bypass`: a flag to prevent the validator from running in production, to avoid any performance issues. (This will not prevent default prop values being set.)
* `$typesGlobal` (optional): a custom name for the `PropTypes` global
* `$exclude` (optional): context variables to exclude from validation. Variables prefixed with an underscore `_` are automatically excluded
* `$allowExtraProperties` (optional): Whether prop validation should fail if unexpected unexcluded extra props are found in the context (false by default)

## Validation

To validate your props, put the following at the top of a template file:

```twig
{{
    props({
        optionalNumber: PropTypes.number(),
        aString: PropTypes.string().isRequired(),
        anObject: PropTypes.instanceof('An.Instance.Of.This.Class'),
        heey: PropTypes.shape({
            maca: PropTypes.string().isRequired(),
            rena: PropTypes.string().isRequired(),
        }),
    })
}}
```

### Twig-specific differences
When giving a fully-qualified classname when checking for an instance type, you may use dots instead of backslashes to simplify string escaping issues, and the leading backslash or dot is optional.

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

## Default values
If required, you must set default values of required props after the `props()` call to ensure that required props fail correctly if not provided.

```twig
{{
    props({
        someProp: PropTypes.string(),
    })  
    ~
    defaults({
        someProp: 'foo',
    })
}}
```
The `props()` and `defaults()` calls are separated by the Twig concatenate operator (`~`) so that they may be called in the same `{{` output block `}}` without throwing an error. Both of these functions never return a value, so it is safe to do so.
