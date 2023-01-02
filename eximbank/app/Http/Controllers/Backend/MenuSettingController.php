<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\MenuSetting;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Models\Categories\Titles;

class MenuSettingController extends Controller
{
    public function index() {
        return view('backend.menu_setting.index');
    }

    public function form(Request $request) {
        $model = MenuSetting::where('title_id', $request->id)->get(['menu_value', 'menu_name']);
        json_result($model);
    }

    public function save(Request $request) {
        if($request->ids) {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                MenuSetting::where('title_id', $id)->delete();
                foreach ($request->formValue as $value) {
                    $model = new MenuSetting;
                    $model->title_id = $id;
                    $model->menu_value = $value['value'];
                    $model->menu_name = $this->nameMenu($value['value']);
                    $model->save();
                }
            }
        } else {
            MenuSetting::where('title_id', $request->id)->delete();
            foreach ($request->formValue as $value) {
                $model = new MenuSetting;
                $model->title_id = $request->id;
                $model->menu_value = $value['value'];
                $model->menu_name = $this->nameMenu($value['value']);
                $model->save();
            }
        }

        json_message(trans('laother.successful_save'));
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('el_titles.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_titles.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $getMenuSetting = MenuSetting::where('title_id', $row->id)->get();
            $nameMenuSetting = '';
            if(!$getMenuSetting->isEmpty()) {
                foreach ($getMenuSetting as $menu) {
                    if($menu->menu_value == 'menu_1' || $menu->menu_value == 'menu_2' || $menu->menu_value == 'menu_3' || $menu->menu_value == 'menu_4' || $menu->menu_value == 'menu_5' || $menu->menu_value == 'library' || $menu->menu_value == 'guide') {
                        continue;
                    } 
                    $nameMenuSetting .= '<p class="menu_name_setting">'. $menu->menu_name .'</p>';
                }
            }
            $row->nameMenuSetting = $nameMenuSetting;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function updateTimeSend(Request $request) {
        $mail_code = $request->mail_code;
        $num_day = $request->num_day;

        Config::setConfig($mail_code, $num_day);

        json_message(trans('laother.successful_save'));
    }

    public function nameMenu($value)
    {
        switch ($value) {
            case 'menu_news':
                $name = 'Bản tin đào tạo';
                break;    
            case 'menu_2':
                $name = 'Khóa học';
                break;
            case 'menu_3':
                $name = 'Cộng tác';
                break;
            case 'menu_4':
                $name = 'Điễm tích lũy';
                break;
            case 'menu_1':
                $name = 'Kế hoạch của tôi';
                break;
            case 'menu_5':
                $name = 'Trợ giúp';
                break;
            case 'course_4':
                $name = 'Khóa học đánh dấu';
                break;
            case 'course_3':
                $name = 'Khóa học của tôi';
                break;
            case 'course_1':
                $name = 'Khóa học Online';
                break;
            case 'course_2':
                $name = 'Khóa học tập trung';
                break;
            case 'daily_training':
                $name = 'Video sharing';
                break;
            case 'survey':
                $name = 'Khảo sát';
                break;
            case 'rating_level':
                $name = 'Mô hình Kirkpatrick';
                break;
            case 'library':
                $name = 'Thư viện';
                break;
            case 'forum':
                $name = 'Diễn đàn';
                break;
            case 'suggest':
                $name = 'Góp ý';
                break;
            case 'topic_situation':
                $name = 'Xử lý tình huống';
                break;
            case 'coaching_teacher':
                $name = 'Coaching/Mentor';
                break;
            case 'promotion':
                $name = 'Quà tặng';
                break;
            case 'usermedal':
                $name = 'Chương trình thi đua';
                break;
            case 'usermedal_history':
                $name = 'Lịch sử huy hiệu';
                break;
            case 'user_point_history':
                $name = 'Lịch sử điểm thưởng';
                break;
            case 'my_promotion':
                $name = 'Danh sách quà tặng';
                break;
            case 'info':
                $name = 'Thông tin';
                break;
            case 'dashboard_by_user':
                $name = 'Dashboard';
                break;
            case 'calendar':
                $name = 'Lịch đào tạo';
                break;
            case 'quiz':
                $name = 'Khảo thí';
                break;
            case 'note':
                $name = 'Ghí chú của tôi';
                break;
            case 'interaction_history':
                $name = 'Lịch sử tương tác';
                break;
            case 'guide':
                $name = 'Hướng dẫn';
                break;
            case 'faq':
                $name = 'FAQ';
                break;
            case 'book':
                $name = 'Sách giấy';
                break;    
            case 'ebook':
                $name = 'Sách điện tử';
                break;    
            case 'document':
                $name = 'Tài liệu';
                break;    
            case 'Video':
                $name = 'Video';
                break;    
            case 'audio':
                $name = 'Sách nói';
                break;    
            case 'pdf':
                $name = 'PDF';
                break;    
            case 'guide_video':
                $name = 'Hướng dẫn Video';
                break;    
            case 'guide_post':
                $name = 'Bài viết';
                break; 
        }

        return $name;
    }
}
