<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NuxtController extends Controller
{

    /**
     * Handle the SPA request.
     */
    public function nuxtMethod(Request $request): string
    {
        // 'dist/' folder is copied by vapor (vapor.yml) to 'public/dist/', that way nuxt
        // is available on CDN

        // sw.js is copied to 'public/' folder and in config/vapor.php (serve_assets)
        // needs to be specified, cause it requires same domain.

        // Path corresponds to nuxt.config.js publicPath: process.env.ASSET_URL + "/dist/",
        // publicPath is path where nuxt is generated (plain copy to 'public/' folder later on)

        // Manifest
        // When manifest.hash.js differs (APP vs CDN), remove .nuxt and redeploy.
        if(strpos($request->path(), 'manifest') !== false) {
            $manifestName = array_reverse(explode('/', $request->path()))[0];
            return file_get_contents(asset('/dist/' . $manifestName));
        }

        // Icons
        // /pwa-icons/ folder is specified on nuxt.config.js
        if(strpos($request->path(), 'icons') !== false) {
            $iconName = array_reverse(explode('/', $request->path()))[0];
            $iconPath = asset('/dist/pwa-icons/' . $iconName);

            return new RedirectResponse($iconPath, 302, [
                'Cache-Control' => 'public, max-age=3600',
            ]);
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
    }
}
