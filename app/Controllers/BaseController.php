<?php

namespace App\Controllers;

use App\Helpers\Utils;
use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

helper('cookie');
/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    protected string $host = 'http://localhost:8080';
    protected BaseConnection $db;

    protected int $per_page = 30;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // <MEMO> config 로 App:class 를 줘도, 비워서 default로 해도 \Config\Session 의 영향을 받음
        // $this->session = \Config\Services::session(config(\Config\App::class));
        $this->session = \Config\Services::session();

//        //initialize key
//        // Get a hex-encoded representation of the key:
//        $encoded = bin2hex(\CodeIgniter\Encryption\Encryption::createKey(32));
//
//        // Put the same value with hex2bin(),
//        // so that it is still passed as binary to the library:
//        $key = hex2bin($encoded);
//        ServerLogger::log($encoded);
    }

    /**
     * header 에 데이터 load 할 때 resource file 들의 참조를 규격화하는 기능
     * @param string $type
     * @param array|string $urls
     * @return string
     */
    private function generateAssetStatement(string $type, array|string $urls): string
    {
        $result = "";
        $getter = null;
        switch ($type) {
            case 'css' :
                $getter = function ($url) {
                    if (Utils::startsWith($url, '//') || Utils::startsWith($url, 'http')) {
                        return "<link href=\"" . $url . "\" type=\"text/css\" rel=\"stylesheet\"/>";
                    }
                    $url .= Utils::endsWith($url, '.css') ? '' : '.css';
                    $path = "/asset/css" . (Utils::startsWith($url, '/') ? '' : '/') . $url;
                    return "<link href=\"" . $path . "\" type=\"text/css\" rel=\"stylesheet\"/>";
                };
                break;
            case 'js':
            case 'javascript':
                $getter = function ($url) {
                    if (Utils::startsWith($url, '//') || Utils::startsWith($url, 'http')) {
                        return "<script src=\"" . $url . "\" type=\"text/javascript\"></script>";
                    }
                    $url .= Utils::endsWith($url, '.js') ? '' : '.js';
                    $path = "/asset/js" . (Utils::startsWith($url, '/') ? '' : '/') . $url;
                    return "<script src=\"" . $path . "\" type=\"text/javascript\"></script>";
                };
                break;
        }
        if (!$getter) "";
        if (is_array($urls)) {
            foreach ($urls as $url) {
                $result .= $getter($url) . "\n";
            }
        } else {
            $result .= $getter($urls) . "\n";
        }
        return $result;
    }

    /**
     * header 출력 시에 공통적으로 load 될 데이터 호출 기능
     * @param array $input
     * @param array $initData
     * @return array
     */
    protected function loadDataForHeader(array $input, array $initData = []): array
    {
        $data = array_merge([
            'is_login' => $this->session->is_login,
            'user_id' => $this->session->user_id,
            'user_type' => $this->session->user_type,
            'is_admin' => $this->session->is_admin,
        ], $initData);
        if (isset($input['css'])) {
            $data['css'] = $this->generateAssetStatement("css", $input['css']);
        }
        if (isset($input['js'])) {
            $data['js'] = $this->generateAssetStatement("js", $input['js']);
        }
        return $data;
    }

    /**
     * body 출력 시에 공통적으로 load 될 데이터 호출 기능
     * @return array
     */
    protected function getViewData(): array
    {
        // js 에서도 사용자의 기본 상태를 알기 위해 cookie 에도 매번 refresh
        set_cookie('is_login', $this->session->is_login, httpOnly: false);
        set_cookie('user_id', $this->session->user_id, httpOnly: false);
        set_cookie('user_type', $this->session->user_type, httpOnly: false);
        set_cookie('is_admin', $this->session->is_admin, httpOnly: false);
        return [
            'is_login' => $this->session->is_login,
            'user_id' => $this->session->user_id,
            'user_type' => $this->session->user_type,
            'is_admin' => $this->session->is_admin,
        ];
    }
}
