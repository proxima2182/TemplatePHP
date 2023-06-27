<?php

namespace App\Controllers;

use App\Helpers\ServerLogger;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
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

    /**
     * links for pages
     * @var string[]
     */
    private $links = ["test" => "a", "test2" => "a"];
    /**
     * local variable for checking login
     * @var bool
     */
    protected $is_login = false;

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

    /* common functions */
    function startsWith($haystack, $needle): bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    function endsWith($haystack, $needle): bool
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    protected function generateAssetStatement(string $type, array $files): string
    {
        $result = null;
        switch ($type) {
            case 'css' :
                foreach ($files as $key => $file) {
                    $file .= $this->endsWith($file, '.css') ? '' : '.css';
                    $filename = "/asset/css" . ($this->startsWith($file, '/') ? '' : '/') . $file;
                    $result .= "<link href=\"" . $filename . "\" type=\"text/css\" rel=\"stylesheet\"/>\n";
                }
                break;
            case 'js':
            case 'javascript':
                foreach ($files as $key => $file) {
                    $file .= $this->endsWith($file, '.js') ? '' : '.js';
                    $filename = "/asset/js" . ($this->startsWith($file, '/') ? '' : '/') . $file;
                    $result .= "<script src=\"" . $filename . "\" type=\"text/javascript\"></script>\n";
                }
                break;
        }
        return $result;
    }

    function loadHeader($input_data): string
    {
        if ($this->is_login) {
            $this->session->set_userdata([
                'referrer' => '',
            ]);
        }
        $data = [
            'links' => $this->links,
            'is_login' => $this->is_login,
        ];
        if(isset($input_data['css'])) {
            $data['css'] = $input_data['css'];
        }
        if(isset($input_data['js'])) {
            $data['js'] = $input_data['js'];
        }
        return view('header', $data);
    }
}
