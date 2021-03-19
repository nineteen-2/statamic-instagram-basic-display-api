<?php

namespace NineteenSquared\Instagram;

use Carbon\Carbon;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstagramApi
{
    const STATUS_NOT_CONFIGURED = 'NOT_CONFIGURED';
    const STATUS_NOT_CONNECTED = 'NOT_CONNECTED';
    const STATUS_HAS_ERROR = 'HAS_ERROR';
    const STATUS_CONNECTED = 'CONNECTED';

    /**
     * @var InstagramBasicDisplay
     */
    public $instagram_basic_display;

    /**
     * Instagram constructor.
     *
     * @throws \EspressoDev\InstagramBasicDisplay\InstagramBasicDisplayException
     */
    public function __construct()
    {
        if ($this->getAccessToken()) {
            try {
                $this->instagram_basic_display = new InstagramBasicDisplay($this->getAccessToken());

                return;
            } catch (\Exception $exception) {
            }
        }

        $this->instagram_basic_display = new InstagramBasicDisplay([
            'appId' => config('statamic.instagram.appId'),
            'appSecret' => config('statamic.instagram.appSecret'),
            'redirectUri' => route('statamic.cp.nineteen-ig.callback'),
        ]);
    }

    /**
     * @param string $code
     */
    public function saveAccessTokenFromCallbackCode(string $code) : void
    {
        // Get the short lived access token (valid for 1 hour)
        $token = $this->instagram_basic_display->getOAuthToken($code, true);

        // Exchange this token for a long lived token (valid for 60 days)
        $token = $this->instagram_basic_display->getLongLivedToken($token, false);

        $this->storeToken($token);
    }

    /**
     * @return string|null
     */
    public function getAccessToken() : ?string
    {
        return $this->getStoredToken('access_token');
    }

    /**
     * @return Carbon|null
     */
    public function getExpireDate() : ? Carbon
    {
        return $this->getStoredToken('expires_in') ? Carbon::parse($this->getStoredToken('expires_in')) : null;
    }

    public function refreshToken() : void
    {
        $token = $this->instagram_basic_display->refreshToken($this->getStoredToken('access_token'), false);
        $this->storeToken($token);
    }

    /**
     * @return object
     * @throws InstagramBasicDisplayException
     * @throws InstagramException
     */
    public function getUserProfile() : ? object
    {
        if (! $this->instagram_basic_display || ! $this->instagram_basic_display->getAccessToken()) {
            return null;
        }

        $userProfile = $this->instagram_basic_display->getUserProfile();

        if (isset($userProfile->error)) {
            throw new InstagramException($userProfile->error->message);
        }

        return $userProfile;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getUserMedia(int $limit = 12) : ? array
    {
        $media = $this->instagram_basic_display->getUserMedia('me', $limit);

        return collect($media->data)->map(function ($media) {
            return [
                'id' => $media->id,
                'caption' => isset($media->caption) ? $media->caption : null,
                'media_type' => $media->media_type, // Can be IMAGE, VIDEO, or CAROUSEL_ALBUM.
                'media_url' =>  $media->media_url,
                'permalink' => $media->permalink,
                'thumbnail_url' => isset($media->thumbnail_url) ? $media->thumbnail_url : null,
                'timestamp' => $media->timestamp,
                'username' => $media->username,
            ];
        })->all();
    }

    public function logout() : void
    {
        Storage::delete(self::getTokenFilename());
    }

    /**
     * @return |null
     */
    private function getStoredToken($key)
    {
        if (Storage::exists(self::getTokenFilename())) {
            try {
                $token = json_decode(Storage::get(self::getTokenFilename()), true);

                return $token[$key];
            } catch (\Exception $exception) {
                Log::alert('Instagram error : '.$exception->getMessage());
            }
        }

        return null;
    }

    /**
     * @param $token
     */
    private function storeToken($token): void
    {
        Storage::put(self::getTokenFilename(), json_encode([
            'access_token' => $token->access_token,
            'expires_in'   => Carbon::now()->addSeconds($token->expires_in)->timestamp,
            'last_update'  => Carbon::now()->timestamp,
        ]));
    }

    private static function getTokenFilename()
    {
        return config('statamic.instagram.token.days_before_expiration');
    }
}
