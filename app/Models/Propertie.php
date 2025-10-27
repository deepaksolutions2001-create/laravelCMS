<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propertie extends Model
{
  /*

    CREATE TABLE properties (
  id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  type              VARCHAR(50)      NOT NULL,
  title             VARCHAR(255)     NOT NULL,
  description       TEXT             NULL,
  unit_number       VARCHAR(50)      NULL,
  street_number     VARCHAR(50)      NULL,
  street_name       VARCHAR(255)     NULL,
  city              VARCHAR(120)     NULL,
  state             VARCHAR(120)     NULL,
  postal_code       VARCHAR(20)      NULL,
  bedrooms          INT UNSIGNED     NULL,
  bathrooms         DECIMAL(3,1)     NULL,
  car_spaces        INT UNSIGNED     NULL,
  land_size         DECIMAL(10,2)    NULL,
  land_size_type    VARCHAR(20)      NULL,
  images            JSON             NULL,
  videos            JSON             NULL,
  floor_plan_images JSON             NULL,
  documents         JSON             NULL,
  documents_name    VARCHAR(255)     NULL,
  map_url           VARCHAR(2048)    NULL,
  inspection        JSON             NULL,
  agents            JSON             NULL,
  status            VARCHAR(50)      NULL,
  contract          VARCHAR(50)      NULL,
  min_price         BIGINT UNSIGNED  NULL,
  max_price         BIGINT UNSIGNED  NULL,
  created_at        TIMESTAMP NULL DEFAULT NULL,
  updated_at        TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    */
  protected $table = 'properties';


  protected $fillable = [
    'user_id',
    'type',
    'status',
    'title',
    'description',
    'unit_number',
    'street_number',
    'street_name',
    'city',
    'state',
    'postal_code',
    'bedrooms',
    'bathrooms',
    'car_spaces',
    'land_size',
    'land_size_type',
    'images',
    'videos',
    'floor_plan_images',
    'documents',
    'documents_name',
    'map_url',
    'inspection',
    'agents',
    'contract',
    'min_price',
    'max_price',
  ];

  protected $casts = [
    'images'            => 'array',
    'videos'            => 'array',
    'floor_plan_images' => 'array',
    'documents'         => 'array',
    'inspection'        => 'array',
    'agents'            => 'array',
    'min_price'         => 'integer',
    'max_price'         => 'integer',
  ];

  
}
