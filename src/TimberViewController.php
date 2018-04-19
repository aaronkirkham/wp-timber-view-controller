<?php

namespace Timber;

// make sure timber is available
if ( ! class_exists( 'Timber\Timber' ) ) {
  exit;
}

class ViewController {
  private $_template_location = '';

  function __construct( $template_location = 'views' ) {
    $this->_template_location = untrailingslashit( $template_location );

    // register actions
    add_action( 'template_redirect', array( $this, 'run' ) );
  }

  /**
   * Run controller and figure out which file to render
   */
  public function run() {
    // if we are using woocommerce, and this is a woocommerce related page, skip it
    if ( $this->is_using_woocommerce() && is_woocommerce() ) {
      return;
    }

    $templates = ( new \Brain\Hierarchy\Hierarchy() )->getTemplates();
    
    // get the global timber context
    $ctx = Timber::get_context();

    // try load each template
    foreach ( $templates as $template ) {
      $path = get_template_directory() . "/{$this->_template_location}/{$template}.twig";

      // does the current template file exists?
      if ( file_exists( $path ) ) {
        // apply the global context filter
        $ctx = apply_filters( 'tvc_global_context', $ctx );
        
        // apply the per-template filter
        $ctx = apply_filters( "tvc_{$template}_context", $ctx );

        // apply filters and render the template
        Timber::render( $path, $ctx );
        exit();
      }
    }
  }

  /**
   * Helper function to figure out if this site is running woocommerce
   */
  public function is_using_woocommerce() {
    return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
  }
}