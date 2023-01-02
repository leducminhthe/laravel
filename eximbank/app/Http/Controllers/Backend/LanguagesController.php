<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\NotifyLangOfCompletedImportLanguages;
use App\Exports\LanguagesExport;
use App\Imports\ImportLanguages;
use App\Http\Controllers\Controller;
use App\Models\LanguagesType;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Languages;
use App\Models\LanguagesGroups;
use Artisan;

class LanguagesController extends Controller
{
    public function index($id = null) {

        if(!$id) {
            $lg = LanguagesGroups::first(["id"]);
            return redirect('admin-cp/languages/'.$lg->id);
        }

        $notifications = Notifications::where('notifiable_id', '=', profile()->user_id)
            ->where('notifiable_type', '=', 'App\Models\User')
            ->whereNull('read_at')
            ->get();
        \Session::forget('errors');

        $groups = LanguagesGroups::all()->toArray();
        $lang_types = LanguagesType::get(['key', 'name']);
        return view('backend.languages.index',[
            'id' => $id,
            'groups' => $groups,
            'lang_types' => $lang_types,
            'notifications' => $notifications,
        ]);
    }

    public function form($idg, $id = null) {
        $model = Languages::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->pkey : 'Thêm mới';

        $groups_name = LanguagesGroups::find($idg)->toArray();

        $lang_types = LanguagesType::get(['key', 'name']);
        return view('backend.languages.form', [
            'model' => $model,
            'id' => $idg,
            'groups_name' => $groups_name["name"],
            'page_title' => $page_title,
            'lang_types' => $lang_types,
        ]);

    }

    public function synchronize(Request $request) {
        $group_id = $request->group_id ?? 1;

        $dir = app()['path.lang'];
     //   $groups = LanguagesGroups::whereId($group_id)->get()->toArray();
        $groups = LanguagesGroups::get()->toArray();
        $types = LanguagesType::get(['key']);
        foreach ($groups as $g){
            $model = Languages::where('groups_id','=',$g["id"])->get()->toArray();
            $slug = $g["slug"];
            foreach ($types as $type){
                $dir_lang = $dir ."/".$type->key."/la".$slug.".php";

                $content = "<?php \n return[ \n";
                foreach($model as $k=>$v){
                    $pkey = preg_replace('/\s+/', ' ',$v["pkey"]);

                    if ($type->key == 'vi'){
                        if(isset($v["content"]) && $v["content"])
                            $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content"])."', \n";
                    }else{
                        if(isset($v["content_".$type->key]) && $v["content_".$type->key])
                            $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content_".$type->key])."', \n";
                    }

                }
                $content .= "];\n ?>";

                file_put_contents($dir_lang, $content);
            }
        }
//        Cache::tags('BackendMenuLeft'.app()->getLocale())->flush();
        return redirect()->route('backend.languages.group',[$group_id]);
    }

    public function syncDB2File(Request $request) {
        $dir = app()['path.lang'];
        $groups = LanguagesGroups::get()->toArray();
        $types = LanguagesType::get(['key']);
        foreach ($groups as $v) {

            $slug = $v["slug"];
            foreach ($types as $type) {
                $tkey = $type->key;
                $dir_lang = $dir . "/" . $tkey . "/la" . $slug . ".php";
                $arr = include $dir_lang;
                if(!empty($arr)) {
                    foreach ($arr as $k => $g) {
                        $model = Languages::where('groups_id', '=', $v["id"])
                            ->where('pkey', '=', $k)
                            ->first();
                        if(!empty($model)){
                            if ($tkey == 'vi') {
                                $model->content= $g;
                                }
                            else  $model->{"content_$tkey"} = $g;
                            $model->save();
                        }
                        else {
                            $model = new Languages();
                            $model->pkey= $k;
                            $model->groups_id= $v["id"];
                            if ($tkey == 'vi') {
                                $model->content= $g;
                            }
                            else  $model->{"content_$tkey"} = $g;
                            $model->save();
                        }
                    }
                }
            }
        }
        return redirect()->route('backend.languages');
    }

    public function getData($idg, Request $request) {
        $search = $request->input('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Languages::query();
        $query->select([
            'a.*',
            'b.name AS group_name',
        ]);
        $query->from('el_languages AS a');
        $query->leftJoin('el_languages_groups AS b', 'b.id', '=', 'a.groups_id');

        if ($search) {
            $query->where(function ($query)use ($search) {
                $query->orWhere('a.pkey', 'like', '%'.$search.'%');
                $query->orWhere('a.content', 'like', '%'.$search.'%');
                $query->orWhere('a.content_en', 'like', '%'.$search.'%');
            });
        }
        else {
            $query->where('a.groups_id','=',$idg);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $lang_types = LanguagesType::get(['key', 'name']);

        foreach ($rows as $row) {
            $row->edit_url = route('backend.languages.edit', ['idg' => $row->groups_id, 'id' => $row->id]);

            foreach($lang_types as $type){
                $row->{'content_'.$type->key} = $row->{'content_'.$type->key} ? $row->{'content_'.$type->key} : '';
            }
            $row->note = $row->note ? $row->note : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Languages::destroy($ids);
        json_message(trans('laother.delete_success'));
    }

    public function save($idg, Request $request) {
        $this->validateRequest([
            'pkey' => 'required',
            'content' => 'required',

        ], $request, Languages::getAttributeName());

        $model = Languages::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->groups_id = $idg;
        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.languages.group', $idg)
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');

    }

    public function saveGroup(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, LanguagesGroups::getAttributeName());

        $model = LanguagesGroups::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->slug = \Illuminate\Support\Str::slug($model->name, '_');

        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.languages.group', $model->id)
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');

    }

    public function export_file() {
        $model = Languages::all()->toArray();
        $types = LanguagesType::get(['key']);

        $result = '';

        foreach ($types as $type){
            $content = "<?php \n return[ \n";
            foreach($model as $k=>$v){
                $pkey = preg_replace('/\s+/', ' ',$v["pkey"]);

                if ($type->key == 'vi'){
                    if(isset($v["content"]) && $v["content"])
                        $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content"])."', \n";
                }else{
                    if(isset($v["content_".$type->key]) && $v["content_".$type->key])
                        $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content_".$type->key])."', \n";
                }

            }
            $content .= "];\n ?>";

            $result .= $content;
        }


        return response()->attachment('content.txt', $result);

    }

    public function import_languages(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_user_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new ImportLanguages(\Auth::user()))->queue($newfile)->chain([
                new NotifyLangOfCompletedImportLanguages(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.languages')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('backend.languages')
        ]);
    }

    public function export()
    {
        return (new LanguagesExport())->download('danh_sach_ngon_ngu_'. date('d_m_Y') .'.xlsx');
    }

    public function showModal(Request $request) {

        $model = LanguagesGroups::find($request->id);

        return view('backend.languages.addgroup', [
            'model' => $model
        ]);
    }

    public function createNew(Request $request){
        $this->validateRequest([
            'icon' => 'required',
            'key' => 'required',
            'name' => 'required',
        ], $request, LanguagesType::getAttributeName());

        $model = LanguagesType::firstOrNew(['key' => $request->key]);
        $model->fill($request->all());
        if ($request->icon){
            $model->icon = upload_image([32, 32], $request->icon);
        }
        if (empty($model)){
            $sql = "ALTER TABLE ".DB::getTablePrefix()."el_languages ADD content_".$request->key." TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;";
            \DB::statement($sql);
        }
        $model->save();

        $path = base_path().'/resources/lang/'.$request->key;
        if (!\File::exists($path)) {
            \File::makeDirectory($path, 0777, true);
        }

        $groups = LanguagesGroups::all()->toArray();
        foreach ($groups as $v) {
            $model = Languages::where('groups_id','=',$v["id"])->get()->toArray();
            $slug = $v["slug"];

            $dir = $path."/la".$slug.".php";

            $content = "<?php \n
        return[ \n";
            foreach($model as $k => $v){
                $pkey = preg_replace('/\s+/', ' ',$v["pkey"]);
                if(isset($v["content"]) && $v["content"]){
                    $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content"])."', \n";

                    Languages::where('pkey', $pkey)
                        ->update(['content_'.$request->key => $v["content"]]);
                }
            }
            $content .= "];\n
        ?>";
            file_put_contents($dir, $content);
        }

        json_result([
            'status' => 'success',
            'message' => 'Đang tạo dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
            'redirect' => route('backend.languages')
        ]);
    }

    public function gitPush(Request $request)
    {
        $output = shell_exec('git add . && git commit -m "update language" && git pull && git push origin master');
        json_message('Cập nhật thành công');
    }

}
