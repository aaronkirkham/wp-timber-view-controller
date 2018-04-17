## WordPress Timber View Controller
If you're like me and hate writing 2 files to render a twig template for WordPress (e.g. page.php and page.twig) then this library is for you. It follows the WordPress Template Hierarchy so you can also create files such as `single-{post_type}.twig`.

### How to use
Using wp-timber-view-controller is easy. Place the following code inside your themes functions.php:

```php
require_once( __DIR__ . '/vendor/autoload.php' );

new Timber\Timber;
new Timber\ViewController;
```

and that's it. Your Twig templates will be automatically rendered just as if they were PHP files.

If you need to add variables to the Timber context, there are handy filters available for that.

### Filter: tvc_global_context
The tvc_global_context filter is fired for all templates rendered through Timber. If you need to add global variables to all Twig templates, this is the place to do it.

```php
add_filter( 'tvc_global_context', function( $ctx ) {
  $ctx['my_variable'] = 'Hello world!';
  return $ctx;
});
```

### Filter: tvc_%TEMPLATE%_context
The tvc_%TEMPLATE%_context filter (where %TEMPLATE% is the current template name) is only fired when the specific template is rendered. This is handy if you want to get posts from WordPress on specific pages.

```php
add_filter( 'tvc_single_context', function( $ctx ) {
  $ctx['post'] = new TimberPost();
  return $ctx;
});
```

In this example, the **post** variable will be available on all pages which are rendered using the single(.php) template.