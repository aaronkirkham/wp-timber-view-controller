<?php

namespace Timber;

// make sure timber is available
if ( ! class_exists( 'Timber\Timber' ) ) {
  exit;
}

class ViewController {
  function __construct() {
    // register actions
    add_action( 'template_redirect', array( $this, 'run' ) );
  }

  /**
   * Run controller and figure out which file to render
   */
  public function run() {
    // get the template hierarchy and the primary directory
    $templates = ( new \Brain\Hierarchy\Hierarchy() )->getTemplates();
    $template_directory = trailingslashit( get_template_directory() ) . Timber::$dirname;
    
    // if the post/page requires a password, only use the password template
    // so we don't leak any private pages.
    if ( post_password_required() ) {
      $templates = array( 'password' );
    }

    // try load each template
    foreach ( $templates as $template ) {
      $path = sprintf( '%s/%s.twig', $template_directory, $template );

      // does the current template file exists?
      if ( file_exists( $path ) ) {
        // apply the per-template context filter
        $context = apply_filters( "timber_context--{$template}", Timber::get_context() );

        // apply filters and render the template
        Timber::render( $path, $context );
        exit();
      }
    }

    // no templates found. if we are in debug mode, show some info
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
      echo '<div style="border:5px solid red;padding:5px;">';

      echo sprintf( '<h1>%s</h1>', __( 'WP Timber View Controller Error', 'wp-timber-view-controller' ) );
      echo sprintf( '<p>%s</p>', __( 'We were unable to locate a suitable template to render. Below is a list of templates we tried to find (in hierarchical order).', 'wp-timber-view-controller' ) );

      echo '<ul>';
      foreach ( $templates as $template ) {
        echo sprintf( '<li>%s.twig</li>', $template );
      }
      echo '</ul>';

      echo sprintf( '<p><strong>%s</strong>: %s</p>', __( 'Template path', 'wp-timber-view-controller' ), $template_directory );
      
      echo sprintf( '<pre><i>%s</i></pre>', __( 'You are viewing this error because you have WP_DEBUG enabled inside wp-config.php.', 'wp-timber-view-controller' ) );

      echo '</div>';
    }
  }
}