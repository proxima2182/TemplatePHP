<?php

namespace App\Controllers;

use App\Helpers\ServerLogger;
use App\Helpers\Utils;
use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

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

    protected BaseConnection $db;

    /**
     * local variable for checking login
     * @var bool
     */
    protected bool $is_login = false;

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
    }

    /**view functions**/

    protected function generateAssetStatement(string $type, array|string $urls): string
    {
        $result = "";
        $getter = null;
        switch ($type) {
            case 'css' :
                $getter = function ($url) {
                    $url .= Utils::endsWith($url, '.css') ? '' : '.css';
                    $path = "/asset/css" . (Utils::startsWith($url, '/') ? '' : '/') . $url;
                    return "<link href=\"" . $path . "\" type=\"text/css\" rel=\"stylesheet\"/>";
                };
                break;
            case 'js':
            case 'javascript':
                $getter = function ($url) {
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

    protected function loadDataForHeader($input, $initData = []): array
    {
        if ($this->is_login) {
            $this->session->set_userdata([
                'referrer' => '',
            ]);
        }
        $data = array_merge([
            'is_login' => $this->is_login
        ], $initData);
        if (isset($input['css'])) {
            $data['css'] = $this->generateAssetStatement("css", $input['css']);
        }
        if (isset($input['js'])) {
            $data['js'] = $this->generateAssetStatement("js", $input['js']);
        }
        return $data;
    }

    /**API functions**/

    protected function sendEmail($address, $title, $message): bool
    {
        try {
            if (strlen($address) == 0) {
                return false;
            }
            $email = \Config\Services::email();
            $email->setFrom('no-reply@localhost');
            $email->setTo($address);
            $email->setSubject($title);
            $email->setMessage($message);
            if ($email->send()) {
                return true;
            }
        } catch (Exception $e) {
            ServerLogger::log($e);
        }
        return false;
    }
}
