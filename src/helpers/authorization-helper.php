<?php

/**
 * Authorization Helper Class
 * Manages role-based permission checks for the booking system
 */
class AuthorizationHelper
{
  // Role constants
  const ROLE_ADMIN = 1;
  const ROLE_MODERATOR = 2;
  const ROLE_HOST = 3;

  // Module access permissions
  const MODULE_DASHBOARD = 'dashboard';
  const MODULE_ROOMS = 'room';
  const MODULE_BOOKING = 'booking';
  const MODULE_PAYMENTS = 'payments';
  const MODULE_HOSTS = 'host';
  const MODULE_USERS = 'user';

  // Permission definitions
  private static $modulePermissions = [
    self::ROLE_ADMIN => [
      self::MODULE_DASHBOARD,
      self::MODULE_ROOMS,
      self::MODULE_BOOKING,
      self::MODULE_PAYMENTS,
      self::MODULE_HOSTS,
      self::MODULE_USERS
    ],
    self::ROLE_MODERATOR => [
      self::MODULE_DASHBOARD,
      self::MODULE_ROOMS,
      self::MODULE_BOOKING
    ],
    self::ROLE_HOST => [
      self::MODULE_DASHBOARD,
      self::MODULE_ROOMS,
      self::MODULE_BOOKING,
      self::MODULE_PAYMENTS
    ]
  ];

  /**
   * Check if a user role can view a specific module
   * @param int $userRole The user's role ID
   * @param string $module The module name to check
   * @return bool True if user can view the module
   */
  public static function canViewModule($userRole, $module)
  {
    if (!isset(self::$modulePermissions[$userRole])) {
      return false;
    }

    return in_array($module, self::$modulePermissions[$userRole]);
  }

  /**
   * Check if a user role can add properties
   * @param int $userRole The user's role ID
   * @return bool True if user can add properties
   */
  public static function canAddProperty($userRole)
  {
    return in_array($userRole, [self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_HOST]);
  }

  /**
   * Check if a user role can approve properties
   * @param int $userRole The user's role ID
   * @return bool True if user can approve properties
   */
  public static function canApproveProperty($userRole)
  {
    return in_array($userRole, [self::ROLE_ADMIN, self::ROLE_MODERATOR]);
  }

  /**
   * Check if a user can manage a specific property
   * @param int $userRole The user's role ID
   * @param int $userId The user's ID
   * @param int $propertyOwnerId The property owner's user ID
   * @return bool True if user can manage the property
   */
  public static function canManageProperty($userRole, $userId, $propertyOwnerId)
  {
    // Admin and moderator can manage any property
    if (in_array($userRole, [self::ROLE_ADMIN, self::ROLE_MODERATOR])) {
      return true;
    }

    // Host can only manage their own properties
    if ($userRole === self::ROLE_HOST) {
      return $userId === $propertyOwnerId;
    }

    return false;
  }

  /**
   * Check if a user role can view all data or only their own
   * @param int $userRole The user's role ID
   * @return bool True if user can view all data, False if only their own
   */
  public static function canViewAllData($userRole)
  {
    return in_array($userRole, [self::ROLE_ADMIN, self::ROLE_MODERATOR]);
  }

  /**
   * Check if properties added by this role need approval
   * @param int $userRole The user's role ID
   * @return bool True if properties need approval
   */
  public static function needsApproval($userRole)
  {
    return $userRole === self::ROLE_HOST;
  }

  /**
   * Get the initial status for a new property based on user role
   * @param int $userRole The user's role ID
   * @return int Status code (0=pending, 5=available)
   */
  public static function getInitialPropertyStatus($userRole)
  {
    // Admin and moderator properties are automatically approved
    if (in_array($userRole, [self::ROLE_ADMIN, self::ROLE_MODERATOR])) {
      return 5; // available
    }

    // Host properties need approval
    return 0; // pending
  }

  /**
   * Get all modules accessible by a role
   * @param int $userRole The user's role ID
   * @return array List of accessible modules
   */
  public static function getAccessibleModules($userRole)
  {
    return self::$modulePermissions[$userRole] ?? [];
  }

  /**
   * Get role name by ID
   * @param int $roleId The role ID
   * @return string Role name
   */
  public static function getRoleName($roleId)
  {
    $roles = [
      self::ROLE_ADMIN => 'Admin',
      self::ROLE_MODERATOR => 'Moderator',
      self::ROLE_HOST => 'Host'
    ];

    return $roles[$roleId] ?? 'Unknown';
  }
}
