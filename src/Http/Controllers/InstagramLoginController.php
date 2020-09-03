<?php

Namespace NineteenSquared\Instagram\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NineteenSquared\Instagram\InstagramApi;
use Statamic\Http\Controllers\CP\CpController;

class InstagramLoginController extends CpController
{
    /**
     * @param InstagramApi $instagram
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(InstagramApi $instagram)
    {
        $this->authorize('setup Instagram');

        $status = 'NOT_CONFIGURED';
        if (config('statamic.instagram.appId') && config('statamic.instagram.appSecret')) {
            $status = 'NOT_CONNECTED';
        }
        if ($instagram->getUserProfile()) {
            $status = 'CONNECTED';
        }

        return view('nineteen-ig::index', [
            'status' => $status,
            'userProfile' => $instagram->getUserProfile(),
            'tokenExpireDate' => $instagram->getExpireDate(),
            'logoutUrl' => route('statamic.cp.nineteen-ig.logout'),
            'loginUrl' => $instagram->instagram_basic_display->getLoginUrl(),
        ]);

    }

    /**
     * @param InstagramApi $instagram
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function logout(InstagramApi $instagram)
    {
        $this->authorize('setup Instagram');

        $instagram->logout();

        return redirect(route('statamic.cp.nineteen-ig.index'));
    }

    /**
     * @param InstagramApi $instagram
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function callback(InstagramApi $instagram, Request $request)
    {
        $this->authorize('setup Instagram');

        try {
            // Get the OAuth callback code
            $code = $request->get('code');
            $instagram->saveAccessTokenFromCallbackCode($code);

        } catch (\Exception $exception) {
            Log::error($exception);
        }

        return redirect(route('statamic.cp.nineteen-ig.index'));
    }
}
