<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
  function admin(){
    return redirect()->route('dashboard_admin');
  }
  function masyarakat(){
    return redirect()->route('dashboard_masyarakat');
  }
}
