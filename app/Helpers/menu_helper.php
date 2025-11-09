<?php

if (!function_exists('is_active')) {  
  function is_active(string $segment): bool
  {
    $uri = service('uri');
    return $uri->getSegment(1) === trim($segment, '/');
  }
}
