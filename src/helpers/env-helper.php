<?php

/**
 * Environment Helper
 * Loads environment variables from .env file
 */

class EnvHelper
{
  private static $loaded = false;

  /**
   * Load environment variables from .env file
   */
  public static function load($filePath = null)
  {
    // Prevent loading multiple times
    if (self::$loaded) {
      return true;
    }

    // Default to .env in project root
    if ($filePath === null) {
      $filePath = __DIR__ . '/../../.env';
    }

    // Check if file exists
    if (!file_exists($filePath)) {
      error_log("Warning: .env file not found at $filePath");
      return false;
    }

    // Read and parse .env file
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      // Skip comments
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      // Parse key=value pairs
      if (strpos($line, '=') !== false) {
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Remove quotes if present
        if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
          $value = $matches[2];
        }

        // Set environment variable if not already set
        if (!array_key_exists($name, $_ENV)) {
          putenv("$name=$value");
          $_ENV[$name] = $value;
          $_SERVER[$name] = $value;
        }
      }
    }

    self::$loaded = true;
    return true;
  }

  /**
   * Get an environment variable
   * 
   * @param string $key Variable name
   * @param mixed $default Default value if not found
   * @return mixed
   */
  public static function get($key, $default = null)
  {
    // Load .env if not already loaded
    if (!self::$loaded) {
      self::load();
    }

    // Try different sources
    $value = getenv($key);
    if ($value !== false) {
      return $value;
    }

    if (isset($_ENV[$key])) {
      return $_ENV[$key];
    }

    if (isset($_SERVER[$key])) {
      return $_SERVER[$key];
    }

    return $default;
  }

  /**
   * Check if an environment variable exists
   * 
   * @param string $key Variable name
   * @return bool
   */
  public static function has($key)
  {
    return self::get($key) !== null;
  }
}

// Auto-load .env file when this helper is included
EnvHelper::load();
