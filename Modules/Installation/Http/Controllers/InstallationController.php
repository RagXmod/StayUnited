<?php

namespace Modules\Installation\Http\Controllers;


use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\BaseController;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Artisan;
use DB;
use Exception;
use Session;
use Log;
use PDO;

class InstallationController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('installation::index');
    }

    public function getRequirements()
    {
        $requirements = $this->_requirements();
        $allLoaded    = $this->_isRequirementLoaded();

        return view('installation::requirements', compact('requirements', 'allLoaded'));
    }

    public function getPermissions()
    {
        if (! $this->_isRequirementLoaded()) {
            return redirect()->route('dcm.install.requirements');
        }

        $folders    = $this->_permissions();
        $allGranted = $this->_isPermissionsGranted();

        return view('installation::permissions', compact('folders', 'allGranted'));
    }

    public function getDatabase()
    {
        if (! $this->_isRequirementLoaded()) {
            return redirect()->route('dcm.install.requirements');
        }

        if (! $this->_isPermissionsGranted()) {
            return redirect()->route('dcm.install.permissions');
        }

        return view('installation::database');
    }

    public function startInstallation(Request $request)
    {
        if (! $this->_isRequirementLoaded()) {
            return redirect()->route('dcm.install.requirements');
        }

        if (! $this->_isPermissionsGranted()) {
            return redirect()->route('dcm.install.permissions');
        }

        $dbCredentials = $request->only('host', 'username', 'password', 'database', 'prefix');

        if (! $this->_dbCredentialsAreValid($dbCredentials)) {
            return redirect()->route('dcm.install.database')
                ->withInput(array_except($dbCredentials, 'password'))
                ->withErrors("Connection to your database cannot be established.
                Please provide correct database credentials.");
        }

        
        Session::put('install.db_credentials', $dbCredentials);

        return view('installation::installation');
    }


    public function postInstallingApplication(Request $request)
    {
        
        try {


            // forget later
            $db = Session::get('install.db_credentials');
            
            if ( !isset($db)) {
                throw new Exception('No database credentials found');
            }

            copy(base_path('.env.example'), base_path('.env'));

            $this->reloadEnv();
            
            $path = base_path('.env');
            $env = file_get_contents($path);

            $env = str_replace('_DCM_HOST_', $db['host'], $env);
            $env = str_replace('_DCM_DATABASE_', $db['database'], $env);
            $env = str_replace('_DCM_USERNAME_', $db['username'], $env);
            $env = str_replace('_DCM_PASSWORD_', $db['password'], $env);
            $env = str_replace('_DCM_PREFIX_', $db['prefix'] ?? 'dcm', $env);

            if ( $request->has('app_url')) {
                $env = str_replace('_DCM_APP_URL_', $request->get('app_url'), $env);
            }

            if ( $request->has('app_name')) {
                $env = str_replace('_DCM_APP_NAME_', str_replace(' ', '', ($request->get('app_name','Google Play App Store by Anthony Pillos'))), $env);
            }

            file_put_contents($path, $env);


            $this->_setDatabaseCredentials($db);

            // config(['app.env' => 'local']);
            config(['app.debug' => true]);

            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('migrate:reset', ['--force' => true]);
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            Artisan::call('module:seed', ['--force' => true]);
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('storage:link');

            // forget cache
            Session::forget('install.db_credentials');

            return redirect()->route('dcm.install.complete');
        } catch (Exception $e) {
            
            logger()->debug($e);
            @unlink(base_path('.env'));
            Session::forget('install.db_credentials');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            // reset afterwards.
            Artisan::call('migrate:reset');
            // create storage link
            Artisan::call('storage:link');

            return redirect()->route('dcm.install.error');
        }
    }

    public function getComplete()
    {
        return view('installation::complete');
    }

    public function getError()
    {
        return view('installation::error');
    }


    private function _dbCredentialsAreValid($credentials)
    {
        $this->_setDatabaseCredentials($credentials);
        try {

            $databaseName = config('database.connections.mysql.database');
            $charset      = config("database.connections.mysql.charset",'utf8mb4');
            $collation    = config("database.connections.mysql.collation",'utf8mb4_unicode_ci');

            $pdoArr = array_only(config('database.connections.mysql'), ['host','username','password']);
            
            $pdo = new PDO("mysql:host={$pdoArr['host']}", $pdoArr['username'], $pdoArr['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $dbname = "`".str_replace("`","``",$databaseName)."`";
            $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET $charset COLLATE $collation;");

        } catch (\Exception $e) {
            \Log::debug($e);
            return false;
        }

        return true;
    }

    /**
     * @param $credentials
     */
    private function _setDatabaseCredentials($credentials)
    {
        $default = config('database.default');
        if ( isset($credentials) ) {
            $_dbArr = array_filter([
                "database.connections.{$default}.host"     => $credentials['host'],
                "database.connections.{$default}.database" => $credentials['database'],
                "database.connections.{$default}.username" => $credentials['username'],
                "database.connections.{$default}.password" => $credentials['password'],
                "database.connections.{$default}.prefix"   => $credentials['prefix'] 
            ]);

            // to remove cache config.. before setting new config.
            DB::purge($default);
            config($_dbArr);
        }

    }

    private function reloadEnv()
    {
        (new LoadEnvironmentVariables)->bootstrap(app());
    }

    /**
     * @return array
     */
    private function _requirements()
    {
        $requirements = [
            'PHP Version (>= 7.1.3)' => version_compare(phpversion(), '7.1.3', '>='),
            'OpenSSL Extension'      => extension_loaded('openssl'),
            'PDO Extension'          => extension_loaded('PDO'),
            'PDO MySQL Extension'    => extension_loaded('pdo_mysql'),
            'Mbstring Extension'     => extension_loaded('mbstring'),
            'Tokenizer Extension'    => extension_loaded('tokenizer'),
            'XML Extension'          => extension_loaded('xml'),
            'Ctype PHP Extension'    => extension_loaded('ctype'),
            'JSON PHP Extension'     => extension_loaded('json'),
            'GD Extension'           => extension_loaded('gd'),
            'Fileinfo Extension'     => extension_loaded('fileinfo'),
        ];

        if (extension_loaded('xdebug')) {
            $requirements['Xdebug Max Nesting Level (>= 500)'] = (int)ini_get('xdebug.max_nesting_level') >= 500;
        }

        return $requirements;
    }

     /**
     * @return array
     */
    private function _permissions()
    {
        return [
            'storage/app'                => is_writable(storage_path('app')),
            'storage/framework/cache'    => is_writable(storage_path('framework/cache')),
            'storage/framework/sessions' => is_writable(storage_path('framework/sessions')),
            'storage/framework/views'    => is_writable(storage_path('framework/views')),
            'storage/logs'               => is_writable(storage_path('logs')),
            'bootstrap/cache'            => is_writable(base_path('bootstrap/cache')),
            'Base Directory'             => is_writable(base_path('')),
        ];
    }

    private function _isPermissionsGranted()
    {
        $allGranted = true;

        foreach ($this->_permissions() as $permission => $granted) {
            if ($granted == false) {
                $allGranted = false;
            }
        }

        return $allGranted;
    }


     /**
     * @return bool
     */
    private function _isRequirementLoaded()
    {
        $allLoaded = true;

        foreach ($this->_requirements() as $loaded) {
            if ($loaded == false) {
                $allLoaded = false;
            }
        }

        return $allLoaded;
    }
}
