<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
  public function githubHook(Request $request) {
    $signature = $request->header('X-Hub-Signature');
    $payload = json_encode($request->all(), JSON_UNESCAPED_SLASHES);
    $pass = 'sha1=' . hash_hmac('sha1', $payload, env('GIT_WEBHOOK_KEY', 'key'));
    if($pass == $signature and $request->input('ref') == 'refs/heads/production') {
      // Update git
      chdir('../');
      ob_start();
      echo shell_exec('sudo -u pi git pull');
      echo shell_exec('php artisan clear-compiled');
      echo shell_exec('composer dump-autoload');
      echo shell_exec('php artisan optimize');
      echo ob_end_flush();
      echo "Done.";
    } else if($pass == $signature) {
      // Other branch updates
      echo "Updated branch " . explode('/', $request->input('ref'))[2];
    } else {
      // Test
      // TODO: maybe log ?
      echo "Wrong pass. Date: " . date('d.m.Y h:i:s');
    }
  }
}

