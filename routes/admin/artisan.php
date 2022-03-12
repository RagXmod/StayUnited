<?php
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

// iframe view
Route::get('logviewer-list', function() {
    return view('admin.dashboard.logviewer');
})->name('artisan.logviewer.list');


Route::get('logviewer-clear', function() {

    $allFiles = Storage::disk('logs')->files();
    $files = Arr::where( $allFiles , function($filename) {
        return Str::endsWith($filename,'.log');
    });

    $count = count($files);
    if(Storage::disk('logs')->delete($files)) {
        logger()->info(sprintf('Logfile Deleted %s %s!', $count, Str::plural('file', $count)));
    } else {
        logger()->error('Error in deleting log files!');
    }
    sleep(2);
    return redirect()->route('admin.dashboard.index');
})->name('artisan.logviewer.clear');




Route::get('artisan/{cmd}', function($cmd) {

    $cmdArr = explode('_', $cmd);

    foreach($cmdArr as $item) {
        $item = trim(str_replace("-",":", $item));
        $validCommands = ['cache:clear', 'optimize', 'route:cache', 'route:clear', 'view:clear', 'view:cache', 'config:cache', 'config:clear'];
        if (in_array($item, $validCommands)) {
            Artisan::call($item);
            sleep(2);
            return redirect()->route('admin.dashboard.index');
        } else {
            abort(404);
        }
    }

})->name('artisan.command');

