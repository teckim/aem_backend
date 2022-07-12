<?php
use Illuminate\Support\Facades\Storage;

function testHelper()
{
  return 'test success';
}

function IDExists($model, $id)
{
  // query the database and return a boolean
  return $model::where('id', $id)->exists();
}

function generateID($model, $length = 10)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $id = '';
  for ($i = 0; $i < $length; $i++) {
    $id .= $characters[mt_rand(0, $charactersLength - 1)];
  }

  if (IDExists($model, $id)) {
    return generateID($model, $length);
  }
  return $id;
}

function generateImageName($path ,$ext, $length = 10)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $name = '';
  for ($i = 0; $i < $length; $i++) {
    $name .= $characters[mt_rand(0, $charactersLength - 1)];
  }

  $name .= $ext;

  if (file_exists($path . $name)) {
    return generateImagePath($path, $ext, $length);
  }
  return $name;
}
