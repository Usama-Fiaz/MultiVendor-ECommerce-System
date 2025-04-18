<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\CPU\ImageManager;
use App\Model\Notification;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{

    public function theme_setup(Request $request)
    {
        Helpers::setEnvironmentValue('WEB_THEME', $request['theme_id']);
        Toastr::success(translate('Web_theme_updated_successfully'));
        return back();
    }

    public function theme_index()
    {
        $scan = scandir(base_path('resources/themes'));
        $themes_folders = array_diff($scan, ['.', '..','.DS_Store']);

        $themes = [];
        foreach ($themes_folders as $folder){
            $info = file_exists(base_path('resources/themes/'.$folder.'/public/addon/info.php')) ? include(base_path('resources/themes/'.$folder.'/public/addon/info.php')) : [];
            $themes[$folder] = $info;
        }

        return view('admin-views.business-settings.theme-setup', compact('themes'));
    }

    public function theme_install(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme_upload' => 'required|mimes:zip'
        ]);

        if ($validator->errors()->count() > 0) {
            $error = Helpers::error_processor($validator);
            return response()->json(['status' => 'error', 'message' => $error[0]['message']]);
        }

        $file = $request->file('theme_upload');
        $filename = $file->getClientOriginalName();
        $tempPath = $file->storeAs('temp', $filename);

        $zip = new \ZipArchive();
        if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {

            $genFolderName = explode('/', $zip->getNameIndex(0))[0];
            if ($genFolderName === "__MACOSX") {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    if (strpos($zip->getNameIndex($i), "__MACOSX") === false) {
                        $getThemeFolder = explode('/', $zip->getNameIndex($i))[0];
                        break;
                    }
                }
            }
            $getThemeFolder = explode('.', $genFolderName)[0];

            $zip->extractTo(storage_path('app/temp'));
            $infoPath = storage_path('app/temp/'.$getThemeFolder.'/public/addon/info.php');

            if(File::exists($infoPath))
            {
                $extractPath = base_path('resources/themes');
                $zip->extractTo($extractPath);
                $zip->close();

                File::chmod($extractPath.'/'.$getThemeFolder.'/public/addon', 0777);
                Toastr::success(translate('theme_upload_successfully'));
                $status = 'success';
                $message = translate('theme_upload_successfully');
            }else {
                File::cleanDirectory(storage_path('app/temp'));
                $status = 'error';
                $message = translate('invalid_theme');
            }
        }else{
            $status = 'error';
            $message = translate('theme_upload_fail');
        }

        if (File::exists(base_path('resources/themes/__MACOSX'))) {
            File::deleteDirectory(base_path('resources/themes/__MACOSX'));
        }

        File::cleanDirectory(storage_path('app/temp'));

        return response()->json([
            'status' => $status,
            'message'=> $message
        ]);
    }

    public function publish(Request $request)
    {
        $theme_info = include('resources/themes/'.$request['theme'].'/public/addon/info.php');
        if ($request['theme'] != 'default' && (empty($theme_info['purchase_code']) || empty($theme_info['username']) || $theme_info['is_active'] == '0')) {
            $theme = $request['theme'];
            return response()->json([
                'flag' => 'inactive',
                'view' => view('admin-views.business-settings.partials.theme-activate-modal-data', compact('theme_info', 'theme'))->render(),
            ]);
        }

        $current_theme = theme_root_path();
        $current_theme_routes = include('resources/themes/'.$current_theme.'/public/addon/theme_routes.php');
        Helpers::setEnvironmentValue('WEB_THEME', $request['theme']);

        $reload_action = 1;
        $informationModal = '';

        if (is_file(base_path('resources/themes/'.$request['theme'].'/public/addon/theme_routes.php'))) {
            $theme_routes = include('resources/themes/'.$request['theme'].'/public/addon/theme_routes.php');
            $reload_action = 0;
            $informationModal = view('admin-views.business-settings.partials.theme-information-modal-data', compact('current_theme', 'theme_info', 'theme_routes', 'current_theme_routes'))->render();
        }

        return response()->json([
            'reload_action' => $reload_action,
            'informationModal' => $informationModal,
        ]);
    }

    public function activation(Request $request): Redirector|RedirectResponse|Application
    {
        $remove = ["http://", "https://", "www."];
        $url = str_replace($remove, "", url('/'));
        $full_data = include('resources/themes/'.$request['theme'].'/public/addon/info.php');

        $post = [
            base64_decode('dXNlcm5hbWU=') => $request['username'],
            base64_decode('cHVyY2hhc2Vfa2V5') => $request['purchase_code'],
            base64_decode('ZG9tYWlu') => $url,
        ];

        $response = Http::post(base64_decode('aHR0cHM6Ly9jaGVjay42YW10ZWNoLmNvbS9hcGkvdjEvZG9tYWluLXJlZ2lzdGVy'), $post)->json();
        $status = base64_decode($response['active']) ?? 1;

        if((int)$status){
            $full_data['is_active'] = 1;
            $full_data['username'] = $request['username'];
            $full_data['purchase_code'] = $request['purchase_code'];
            $str = "<?php return " . var_export($full_data, true) . ";";
            file_put_contents(base_path('resources/themes/'.$request['theme'].'/public/addon/info.php'), $str);

            Toastr::success(translate('activated_successfully'));
        }else{
            Toastr::error(translate('activation failed'));
        }
        return back();
    }

    public function delete_theme(Request $request){
        $theme = $request->theme;

        if(theme_root_path() == $theme){
            return response()->json([
                'status' => 'error',
                'message'=> translate("can't_delete_the_active_theme")
            ]);
        }
        $full_path = base_path('resources/themes/'.$theme);

        if(File::deleteDirectory($full_path)){
            return response()->json([
                'status' => 'success',
                'message'=> translate('theme_delete_successfully')
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message'=> translate('theme_delete_fail')
            ]);
        }

    }

    function getDirectories(string $path): array
    {
        $directories = [];
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item == '..' || $item == '.')
                continue;
            if (is_dir($path . '/' . $item))
                $directories[] = $item;
        }
        return $directories;
    }

    public function notify_all_the_sellers(Request $request)
    {
        $status = 0;
        $message = translate('Notification_Sent_to_All_Sellers_Fail');

        try {
            $notification = new Notification;
            $notification->sent_by = 'admin';
            $notification->sent_to = 'seller';
            $notification->title = 'Theme Changed to '.ucwords(str_replace('_',' ',theme_root_path()));
            $notification->description = 'Theme Changed Description, time - '.Carbon::now();

            if ($request->has('image')) {
                $notification->image = ImageManager::upload('notification/', 'webp', $request->file('image'));
            } else {
                $notification->image = 'null';
            }

            $notification->status             = 1;
            $notification->notification_count = 1;
            $notification->save();

            Helpers::send_push_notif_to_topic($notification, 'six_valley_seller');

            $status = 1;
            $message = translate('Notification_Sent_to_All_Sellers');
        } catch (\Throwable $th) {

        }

        return response()->json([
            'status' => $status,
            'message'=> $message,
        ]);
    }
}
