<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

  /*

    CREATE TABLE agents (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(191) NOT NULL,
  title VARCHAR(191) NULL,
  image VARCHAR(255) NULL,
  mobile VARCHAR(32) NULL,
  email VARCHAR(191) NOT NULL,
  username VARCHAR(64) NOT NULL,
  password VARCHAR(255) NOT NULL,
  can_property_list BOOLEAN NOT NULL DEFAULT FALSE,
  required_approval BOOLEAN NOT NULL DEFAULT TRUE,
  listings_limit INT UNSIGNED NOT NULL DEFAULT 0,
  api_key VARCHAR(191) NULL,
  api_secret VARCHAR(191) NULL,
  accesses JSON NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY ux_agents_username (username),
  UNIQUE KEY ux_agents_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    */
  protected $table = 'agents';

  protected $fillable = [
    'name',
    'title',
    'image',
    'mobile',
    'email',
    'username',
    'password',
    'can_property_list',
    'required_approval',
    'listings_limit',
    'api_key',
    'api_secret',
    'accesses',
  ]; // required for create() mass assignment [web:120]

  protected function casts(): array
  {
    return [
      'can_property_list' => 'boolean',
      'required_approval' => 'boolean',
      'accesses'          => 'array', // JSON <-> array
      'agents' => 'array', // JSON <-> array

    ];
  }
}
