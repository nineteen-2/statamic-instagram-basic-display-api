<?php

namespace NineteenSquared\Instagram\Tags;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use NineteenSquared\Instagram\InstagramApi;
use Statamic\Tags\Tags;

class Instagram extends Tags
{
    /**
     * The {{ instagram }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        $cache_key = config('statamic.instagram.cache.key_prefix').'_'.$this->params->get('limit');

        try {
            if (! config('statamic.instagram.cache.duration')) {
                return $this->getMedia($this->params->get('limit') ?: config('statamic.instagram.limit'));
            }

            return Cache::remember(
                $cache_key,
                now()->addSeconds(config('statamic.instagram.cache.duration')),
                function () {
                    return $this->getMedia($this->params->get('limit') ?: config('statamic.instagram.limit'));
                }
            );
        } catch (\Exception $exception) {
            Log::alert('Instagram error : '.$exception->getMessage());

            return [];
        }
    }

    private function getMedia(int $limit)
    {
        $instagram = new InstagramApi();

        if ($instagram->getExpireDate() &&
            $instagram->getExpireDate()->diffInDays(Carbon::now()) < config('statamic.instagram.token.days_before_expiration')) {
            $instagram->refreshToken();
        }

        return $instagram->getUserMedia($limit);
    }
}
