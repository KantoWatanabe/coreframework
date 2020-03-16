<?php
namespace App\Application\Controllers;

use App\Core\Controller;

class Sample extends Controller
{
   /**
    * {@inheritdoc}
    */
   protected function action()
   {
      return $this->respondView('sample');
   }
}