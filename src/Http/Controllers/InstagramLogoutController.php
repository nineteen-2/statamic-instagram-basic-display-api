<?php

namespace NineteenSquared\Instagram\Http\Controllers;

use Illuminate\Http\Request;
use NineteenSquared\Instagram\InstagramApi;
use Statamic\Http\Controllers\CP\CpController;

class InstagramLogoutController extends CpController
{
    public function __invoke(InstagramApi $instagram, Request $request)
    {
        $this->authorize('setup Instagram');

        $instagram->logout();

        return redirect(route('statamic.cp.nineteen-ig.index'));
    }
}
