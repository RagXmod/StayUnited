<?php

/**
 * App\Http\Controllers\Web\Api\ApkDownload
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */


namespace App\Http\Controllers\Web\Api;

use Exception;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Facades\App\App\Facades\UserAgentFacade;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use duzun\hQuery;
use Error;

class ApkDownload
{

    /**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
    public function __construct()
    {

        $this->referer = 'https://www.google.com';

        $this->webClient = new Client([
            'headers' => [
                'User-Agent' => UserAgentFacade::random(),
                'Referer' => $this->referer,
                // 'Accept-Encoding' => 'gzip, deflate',
                // 'Accept' => 'text/html,application/xhtml+xml,application/xml'
            ],
            'allow_redirects' => ['track_redirects' => true]
        ]);
    }


    /**
    * download()
    *
    * @return void
    * @access  public
    **/
    public function download($myAppId)
    {
        $cacheKeyName = 'apkdownload_'.str_slug($myAppId);

        try {

            $responseArr  = $this->myOwnApi( $myAppId );

            if ( isset($responseArr['data'])) {

                $_data = array_filter($responseArr['data']);

                if ( !isset($_data['purchase_code']) || !isset($_data['purchase_username'])) {
                    throw new Exception('No purchase code or purchase username found.');
                }
            }

            $urlToken     = $responseArr['url'];

            $_response = cache()->remember($cacheKeyName, 60, function () use($urlToken,$responseArr) {

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    // CURLOPT_PORT => "8001",
                    CURLOPT_URL => $urlToken,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($responseArr['data'] ?? []),
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    throw new Exception($err);
                }

                if ($response) {
                    $conteResponse = json_decode($response, true);
                    return $conteResponse ?? [];

                }
            });

            if ( isset($_response['link'])) {

                $fileName = str_slug(dcmConfig('site_name')) . '_'. $myAppId . '.apk';
                $url      = $_response['link'];

                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); // required for certain browsers
                header('Content-Type: application/vnd.android.package-archive');

                header('Content-Transfer-Encoding: binary');
                header("Content-Description: File Transfer");
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                // ob_end_flush();
                readfile($url);
                return $url;
            }
            logger()->debug('[Download-Failed]: Failed to download from api source.');
            return redirect()->to('/');

        } catch (Exception $e) {
            logger()->debug($e);
            cache()->forget($cacheKeyName);
            return redirect()->to('/');
        }
    }

    private function myOwnApi( $myAppId ) {

        $purchaseCode     = dcmConfig('app_purchase_code');
        $purchaseUsername = dcmConfig('app_username');

        // // override
        // $purchaseCode     = '';
        // $purchaseUsername = '';

        return [
            'url' => env('MY_OWN_API_SERVER','https://apkdownloader.googelplayappstore.info/dcm-api'),
            'data'=> [
                'purchase_code'     => $purchaseCode,
                'purchase_username' => $purchaseUsername,
                'app_id'            => $myAppId,
            ]
        ];;
    }
}