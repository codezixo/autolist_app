<?php

namespace App\Service;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;

use \Bitrix24\SDK\Core\Credentials\ApplicationProfile;
use \Bitrix24\SDK\Core\Credentials\AccessToken;
use \Bitrix24\SDK\Core\CoreBuilder;
use \Bitrix24\SDK\Core\Credentials\Credentials;


class Bitrix24
{
    private $app = null;
    private $token = null;
    private $request = null;

    public function getApp() {
        return $this->app;
    }

    public function getTokenId() {
        $result = '';
        if (!empty($this->token)) {
            $result = $this->token->getAccessToken();
        }
        return $result;
    }

    public function newApp(
        LoggerInterface $obLogger = null,
        ApplicationProfile $appProfile,
        string $domain,
    ) {
        if (empty($this->request->input('AUTH_ID'))) return;
        $token = new \Bitrix24\SDK\Core\Credentials\AccessToken(
            (string)$this->request->input('AUTH_ID'),
            (string)$this->request->input('REFRESH_ID'),
            $this->request->input('AUTH_EXPIRES'),
        );

        $this->token = $token;

        $credentials = Credentials::createFromOAuth($token, $appProfile, $domain);

        $app = (new CoreBuilder())
            ->withLogger($obLogger)
            ->withCredentials($credentials)
            ->build();

        // $res = $app->call('app.info');
        // error_log(var_export($res->getResponseData()->getResult(), true));

        $this->app = $app;
    }

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function uploadFile(\Illuminate\Http\UploadedFile $file, $folderId) {
        $result = false;
        $b24App = $this->app;
        $baseEncodedFile = base64_encode($file->get());
        $b24result = $b24App->call('disk.folder.uploadfile',
            [
                'id' => $folderId,
                'data' => [ 'NAME' => $file->hashName()],
                'generateUniqueName' => true,
                'fileContent' => $baseEncodedFile
            ]);
        $uploadResult = $b24result->getResponseData()->getResult();
        if (!empty($uploadResult)) {
            $result = $uploadResult;
        }

        return $result;
    }


}
