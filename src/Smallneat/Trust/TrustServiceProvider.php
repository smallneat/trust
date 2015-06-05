<?php namespace Smallneat\Trust;

use Illuminate\Support\ServiceProvider;

/**
 * Class TrustServiceProvider
 *
 * @package Smallneat\Trust
 */
class TrustServiceProvider extends ServiceProvider
{
  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Called to boot the package
   */
  public function boot()
  {
    $this->publishes(
      [
        __DIR__ . '/../../migrations/2015_02_14_000000_setup_roles_permissions_tables.php' => base_path(
          sprintf(
            '/database/migrations/%s_setup_roles_permissions_tables.php',
            date('Y_m_j_His')
          )
        ),
      ],
      'migrations'
    );
    $this->publishes(
      [
        __DIR__ . '/../../config/trust.php' => config_path('trust.php')
      ],
      'config'
    );
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__ . '/../../config/trust.php',
      'trust'
    );
  }
}
