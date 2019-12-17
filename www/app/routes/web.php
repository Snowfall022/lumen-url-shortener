<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Tuupola\Base62Proxy;

const DEFAULT_URL_CACHE_TTL = 3600;

$router->post('/make_short_url',  function (Request $request) {
    $full_url = $request->json('full_url');

    $id = DB::selectOne('
           INSERT INTO saved_url (url, created_at, updated_at) VALUES (?, NOW(), NOW())
           RETURNING id
           ', [$full_url])->id;

    Cache::put($id, $full_url, DEFAULT_URL_CACHE_TTL);

    $encoded_url = Base62Proxy::encodeInteger($id); // qwe
    $short_url = URL::to('/q/' . $encoded_url); // http://localhost:8000/q/qwe

    return response()->json([
        'full_url' => $full_url,
        'short_url' => $short_url
    ]);
}
);

$router->get('/q/{encoded_url}',  function (string $encoded_url) {
    $saved_url_id = Base62Proxy::decodeInteger($encoded_url);
    $cached_url = Cache::get($saved_url_id);
    if (isset($cached_url)) {
        return redirect($cached_url);
    }

    $saved_url = DB::selectOne('
    SELECT url FROM saved_url WHERE id = ?
    ', [$saved_url_id]);
    if (!isset($saved_url)) {
        abort(404);
    }

    return redirect($saved_url->url);
});
