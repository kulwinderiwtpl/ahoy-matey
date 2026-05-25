<?php

use App\Models\SpacesUser;

if (!function_exists('titleCase')) {
   function titleCase($str)
   {
      $result = "";
      $arr = [];
      $pattern = '/([;:,-.\/ X])/';
      $array = preg_split($pattern, $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

      foreach ($array as $value)
         $result .= ucwords(strtolower($value));

      return $result;
   }
}


if (!function_exists('userSpaces')) {
   function userSpaces()
   {
      return SpacesUser::where('user_id', auth()->user()->id)->with('spaces')->get();
   }
}
