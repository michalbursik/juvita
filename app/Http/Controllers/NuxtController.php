<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class NuxtController extends Controller
{
    /**
     * Handle the SPA request.
     */
    public function nuxtMethod(Request $request): string
    {
//        if($request->path() === 'sw.js') {
//            return response()->file(resource_path('sw.js'), [
//                'Content-Type' => 'application/javascript',
//                'Cache-Control' => 'public, max-age=3600',
//            ]);
//        }

        if(strpos($request->path(), 'manifest') !== false) {
            $manifestName = array_reverse(explode('/', $request->path()))[0];
            return file_get_contents(asset('/dist/_nuxt/' . $manifestName));
//            return response()->redirectTo(config('app.asset_url') . '/_nuxt/' . $manifestName,   302, [
//                'Content-Type' => 'application/json',
//                'Cache-Control' => 'public, max-age=3600',
//            ]);
        }
        if(strpos($request->path(), 'icons') !== false) {
            $iconName = array_reverse(explode('/', $request->path()))[0];
            $iconPath = asset('/dist/_nuxt/icons/' . $iconName);

            Log::debug('FILE:', [
                'path' => $iconPath,
            ]);

//            if (config('vapor.redirect_robots_txt') && $request->path() === 'robots.txt') {
                return new RedirectResponse($iconPath, 302, [
                    'Cache-Control' => 'public, max-age=3600',
                ]);
//            }


//            return response()->file(asset('/dist/_nuxt/icons/' . $iconName), [
//                'X-Vapor-Base64-Encode' => 'True',
//                'Content-Type' => 'image/png',
//                'Cache-Control' => 'public, max-age=3600',
//            ]);

//            return response()->redirectTo(asset('/dist/_nuxt/icons/' . $iconName),   302, [
//                'Content-Type' => 'image/png',
//                'Cache-Control' => 'public, max-age=3600',
//            ]);
        }

        return $this->renderNuxtPage();
    }

    /**
     * Render the Nuxt page.
     */
    protected function renderNuxtPage(): string
    {
        // In production, this will display the precompiled nuxt page.
        // In development, this will fetch and display the page from the nuxt's dev server.
        return file_get_contents(asset('dist/index.html'));
//        return (config('app.env') === 'localhost') ? file_get_contents(config('nuxt.page')) : file_get_contents(asset('_nuxt/index.html'));
    }
}
