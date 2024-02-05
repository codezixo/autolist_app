<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use \App\Service\Bitrix24;
use \Bitrix24\SDK\Core\Credentials\ApplicationProfile;
use \Bitrix24\SDK\Core\Credentials\AccessToken;
use Bitrix24\Exceptions\Bitrix24TokenIsInvalidException;

class B24ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Bitrix24::class, function ($app) {
            return new Bitrix24($app->request);
        });}

    /**
     * Bootstrap any application services.
     */
    public function boot(Bitrix24 $obB24App)
    {
        $this->publishes([
            __DIR__ . '/../config/bitrix24.php' => config_path('bitrix24.php'),
        ]);

        $log = new Logger('bitrix24');
        $log->pushHandler(new StreamHandler(storage_path() . '/logs/bitrix24.log', Logger::DEBUG));

        $arParams = config('bitrix24');

        if (!$arParams) {
            return false;
        }

        $obB24App->newApp(
            $log,
            new ApplicationProfile(
                $arParams['B24_APPLICATION_ID'],
                $arParams['B24_APPLICATION_SECRET'],
                new \Bitrix24\SDK\Core\Credentials\Scope( $arParams['B24_APPLICATION_SCOPE'] )
            ),
            $arParams['DOMAIN']
        );

        // $obB24App->setRedirectUri(url($arParams['REDIRECT_URI']));

        // try {
        //     $refreshToken = settings('b24_refresh_token');
        //
        //     if ($refreshToken) {
        //         // access key expired
        //         if (time() >= (settings('b24_expires', 0) - 300)) {
        //             $obB24App->setRefreshToken($refreshToken);
        //             $result = $obB24App->getNewAccessToken();
        //             $obB24App->setAccessToken($result['access_token']);
        //
        //             // save new settings
        //             // Bitrix::saveSettings($result); // TODO: !! // BUG: !!
        //         }
        //
        //         // from DB
        //         $obB24App->setMemberId(settings('b24_member_id'));
        //         $obB24App->setAccessToken(settings('b24_access_token'));
        //     }
        // // } catch (QueryException $e) {
        // } catch (Bitrix24TokenIsInvalidException $e) {
        //     var_dump($e);
        // }
    }

    public function provides()
    {
        return [Bitrix24::class];
    }
}
