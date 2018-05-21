# Phpx

Documentation can be found at [preprocess.io](https://preprocess.io#phpx).

## Motivation

I wanted to write a custom compiler, and this syntax appeals to me. It's not a replacement for Javascript, or even a very good implementation. But, I like it. That's all that counts.

If you want to write component-based code, without writing Javascript; then this might work for you. 

## Getting started

- Clone the repository
- Install the Compoer dependencies
- Run the tests _or_ execute the example scripts

This should work on PHP 7.2 (where it was developed and tested). Make an issue if it's not working on 7.2 _or_ if you'd like to see things added.

## What does it render?

The goal was to decouple the compiler from the renderers. Once the Pre\Phpx\Html renderer moves out, you'll need to import it into your project or create a custom `render` function. The Pre\Phpx\Html `render` function looks like this:

```php
public function render($name, $props = null)
{
    $props = $this->propsFrom($props);

    if ($function = globalFunctionMatching($name)) {
        return call_user_func($function, $props);
    }

    if ($class = globalClassMatching($name)) {
        return (new $class($props))->render();
    }

    // render HTML from a list of allowed elements...

    return $this->format("...");
}
```

This means you can define your own namespaced functions and classes, and use them in the Pre\Phpx\Html `render` function, like:

```php
namespace Example\Application;

// don't forget to define or import render
// everywhere you use HTML-in-PHP syntax
use function Pre\Phpx\Html\render;

function MyForm($props) {
    return (
        <form>
            {$props->showLabel ? <label htmlFor={"email"}>Email</label> : null}
            <input type={"text"} name={"email"} id={"email"} />
        </form>
    );
}

// ...later

print render(<Example.Application.MyForm />);
```

If you'd prefer to have your components automatically prefixed, define your own `render` function, You can extend the Pre\Phpx\Html `render` function:

```php
namespace Example\Application;

use function Pre\Phpx\classMatching;
use function Pre\Phpx\functionMatching;
use function Pre\Phpx\Html\render as renderHtml;
use function Pre\Phpx\Html\propsFrom;

define("NAMESPACES", [
    // remove this if you only have
    // namespaced classes and functions
    "global",

    // classes and functions will
    // be loaded from this namespace
    "Example\\Application",
]);

function render($name, $props = null)
{
    $props = propsFrom($props);

    if ($function = functionMatching(NAMESPACES, $name)) {
        return call_user_func($function, $props);
    }

    if ($class = classMatching(NAMESPACES, $name)) {
        return (new $class($props))->render();
    }

    return renderHtml($name, $props);
}

// ...later

print render(<MyForm />);
```

You can define components as functions or classes:

```php
function MyForm($props) {
    // render things...
}

// ...or

class MyForm
{
    public function __construct($props)
    {
        // ...store and manipulate the props
    }

    public function render()
    {
        // render things...
    }
}
```

Since you can define a custom render function, you can decide what your components are allowed to return. HTML-in-PHP syntax is converted to the following form:

```php
use function Pre\Phpx\Html\Example\render;

function MyForm($props) {
    return (
        <form>
            {$props->showLabel ? <label htmlFor={"email"}>Email</label> : null}
            <input type={"text"} name={"email"} id={"email"} />
        </form>
    );
}

// ...becomes

use function Pre\Phpx\Html\Example\render;

function MyForm($props)
{
    return render("form", [
        "children" => [
            $props->showLabel ?
                render("label", [
                    "htmlFor" => "email",
                    "children" => "Email",
                ]) :
                null,
            render("input", [
                "type" => "text",
                "name" => "email",
                "id" => "email",
            ]),
        ],
    ]);
}
```

You decide what your components return and what your `render` function does. The Pre\Phpx\Html `render` function outputs strings of HTML, and expects components rendered by it to return strings (so that they can be concatenated in an array of children).

> Returning `null` will also work.

## Roadmap

- [ ] Support HTML tag warnings and rewriting
- [ ] Move HTML renderer to its own library
- [ ] Set up StyleCI and Travis
- [ ] Write more tests
- [ ] Experiment with other renderers
- [ ] Document like it's 1999
