## WordPress Timber View Controller
If you're like me and hate writing 2 files to render a twig template for WordPress (e.g. page.php for context and page.twig for markup) then this library is for you. It follows the WordPress Template Hierarchy so you can also create files such as `single-{post_type}.twig`.

### How to use
Using wp-timber-view-controller is easy.

`composer require aaronkirkham/wp-timber-view-controller`

Then place the following code inside your themes functions.php:

```php
require_once( __DIR__ . '/vendor/autoload.php' );

new Timber\Timber;
new Timber\ViewController;
```

and that's it. Your Twig templates will be automatically rendered.

This library uses the internal `Timber::$dirname` variable to locate your templates (default folder is `views`). If you want your files to live in a different folder, you must overwrite this.

```php
// look inside /templates/ instead of /views/
Timber::$dirname = 'templates';
```

If you need to add variables to the Timber context, there are handy filters available for that.

### Filter: timber_context--%TEMPLATE%
The `timber_context--%TEMPLATE%` filter (where %TEMPLATE% is the current template name) is fired when the specific template is rendered. This is handy if you want to get posts from WordPress on specific pages.

```php
add_filter( 'timber_context--404', function( $ctx ) {
  $ctx['message'] = '404 - Not Found';
  return $ctx;
});
```

```php
add_filter( 'timber_context--single', function( $ctx ) {
  $ctx['post'] = new \Timber\Post();
  return $ctx;
});
```

In the above examples, the **message** variable will be available on all pages which are rendered using the 404.twig template, and the **post** variable will be available on all single.twig templates.

### FYI
If you want to add data into the context for every template, you should use the `timber_context` filter which is fired by Timber.

```php
add_filter( 'timber_context', function( $ctx ) {
  $ctx['foo'] = 'bar';
  return $ctx;
});
```

The variable **foo** is now available on all templates.

### White screen?

If your twig templates are not rendered or you just have a white screen, you should turn on WP_DEBUG inside wp-config.php to view a hierarchical list of templates that wp-timber-view-controller tried to render. Double check the displayed template path and file names are correct.