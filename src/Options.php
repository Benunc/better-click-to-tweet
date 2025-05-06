<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles retrieving plugin options.
 */
class Options
{
    /**
     * Get a plugin option.
     *
     * @param string $key The option key.
     * @param mixed $default The default value if the option is not found.
     * @return mixed The option value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        // You might add prefixing logic here later if needed
        // e.g., $prefixed_key = 'bctt_' . $key;
        return get_option($key, $default);
    }

    /**
     * Update a plugin option.
     *
     * @param string $key The option key.
     * @param mixed $value The value to set.
     * @return bool True if the value was updated, false otherwise.
     */
    public function update(string $key, mixed $value): bool
    {
        return update_option($key, $value);
    }

     /**
      * Add a plugin option. Only adds if it doesn't exist.
      *
      * @param string $key The option key.
      * @param mixed $value The value to set.
      * @return bool True if the option was added, false otherwise.
      */
     public function add(string $key, mixed $value): bool
     {
         return add_option($key, $value);
     }

      /**
       * Delete a plugin option.
       *
       * @param string $key The option key.
       * @return bool True if the option was deleted, false otherwise.
       */
      public function delete(string $key): bool
      {
          return delete_option($key);
      }
} 