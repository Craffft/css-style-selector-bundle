CSS Style Selector Bundle
=======================
This extension allows backend users to use predefined styles in articles, news, content elements, and many more. It's easy to use because it uses clear names for every style. The selected styles are then set to their corresponding CSS class, which can be styled as usual. It's really helpful if you want to set up a simple system for the end-user.

### CSS styles can be defined here

![css selector navigation](https://raw.githubusercontent.com/Craffft/css-style-selector-bundle/master/docs/css-style-selector-nav.png)

### The predefined styles can be selected here

![css selector navigation](https://raw.githubusercontent.com/Craffft/css-style-selector-bundle/master/docs/css-style-selector-preview.png)


Installation
------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require craffft/css-style-selector-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Craffft\CssStyleSelectorBundle\CraffftCssStyleSelectorBundle(),
        );

        // ...
    }

    // ...
}
```
