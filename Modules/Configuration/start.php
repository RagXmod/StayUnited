<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces and Routes
|--------------------------------------------------------------------------
|
| When your module starts, this file is executed automatically. By default
| it will only load the module's route file. However, you can expand on
| it to load anything else from the module, such as a class or view.
|
*/

// if (!app()->routesAreCached()) {
//     require __DIR__ . '/Http/routes.php';
// }

/*
|--------------------------------------------------------------------------
| Register Namespaces and Routes
|--------------------------------------------------------------------------
|
| When your module starts, this file is executed automatically. By default
| it will only load the module's route file. However, you can expand on
| it to load anything else from the module, such as a class or view.
|
*/
if ( !function_exists('dcmConfig') )
{

	function dcmConfig($key = null, $returnValueRightAway = true) {

		try {
			if(DB::connection()->getDatabaseName()) {
				$configModel = app(\Modules\Configuration\Eloquent\Repositories\ConfigurationRepositoryEloquent::class);
	
				if ( $key ) {
					$configResult = $configModel->findByIdentifier( $key );
	
					if($returnValueRightAway === true)
						return $configResult['value'] ?? null;
				}
				return $configModel->getAllConfigurations();
			}
		} catch (Exception $e) {
			return null;
		}
		return null;
	}
}