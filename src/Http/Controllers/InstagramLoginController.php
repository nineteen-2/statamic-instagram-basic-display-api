<?php

namespace NineteenSquared\Instagram\Http\Controllers;

use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NineteenSquared\Instagram\InstagramApi;
use NineteenSquared\Instagram\InstagramException;
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

        $status = InstagramApi::STATUS_NOT_CONFIGURED;
        $userProfile = null;
        $error = null;
        $instagramMedias = [];

        if (config('statamic.instagram.appId') && config('statamic.instagram.appSecret')) {
            try {
                // dd($instagram);
                $userProfile = $instagram->getUserProfile();
                if (! $userProfile) {
                    $status = InstagramApi::STATUS_NOT_CONNECTED;
                } else {
                    $status = InstagramApi::STATUS_CONNECTED;
                    $instagramMedias = $instagram->getUserMedia(5);
                }
            } catch (InstagramBasicDisplayException | InstagramException | \Exception $exception) {
                $error = $exception->getMessage();
                $status = InstagramApi::STATUS_HAS_ERROR;
            }
        }

        return view('nineteen-ig::index', [
            'status' => $status,
            'error' => $error,
            'instagramMedias' => $instagramMedias,
            'userProfile' => $userProfile,
            'tokenExpireDate' => $instagram->getExpireDate(),
            'logoutUrl' => route('statamic.cp.nineteen-ig.logout'),
            'loginUrl' => $instagram->instagram_basic_display->getLoginUrl(),
        ]);
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
