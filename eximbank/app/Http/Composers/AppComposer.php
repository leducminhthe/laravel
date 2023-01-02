<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use App\Models\SettingExperienceNavigate;
use App\Models\TimeExperienceNavigate;
use App\Models\ObjectExperienceNavigate;
use App\Models\CountUserExperienceNavigate;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\SettingColor;

class AppComposer{
    protected $profile_view;
    protected $check_navigate;
    protected $color_button;
    protected $color_menu;
    protected $color_link;
    protected $lighter_background_color_button;
    protected $lighter_background_hover_color_button;

    public function __construct() {
        $this->getProfileView();
        $this->checkExperienceNavigate();
        $this->color();
    }

    public function compose(View $view) {
        $view->with('profile_view', $this->profile_view)
        ->with('check_navigate', $this->check_navigate)
        ->with('color_button', $this->color_button)
        ->with('color_menu', $this->color_menu)
        ->with('color_link', $this->color_link)
        ->with('lighter_background_color_button', $this->lighter_background_color_button)
        ->with('lighter_background_hover_color_button', $this->lighter_background_hover_color_button);
    }

    public function getProfileView() {
        // $user = profile()->user_id;
        // $profileView = ProfileView::where('user_id', $user)->first();
        $this->profile_view = profile();
    }

    public function checkExperienceNavigate() {
        $date = date('Y-m-d');
        $t = date('H:i');
        $get_time = '';
        $check_object = 0;
        $experience_navigate = SettingExperienceNavigate::where('start_date', '<=', $date)->where('end_date', '>=', $date)->first();
        $time_navigate = TimeExperienceNavigate::where('time_start', '<=', $t)->where('time_end', '>=', $t)->where('experience_navigate_id', @$experience_navigate->id)->first();
        if(!empty($experience_navigate) && !empty($time_navigate)) {
            $object_navigate = ObjectExperienceNavigate::where('experience_navigate_id', $experience_navigate->id)->get(['unit_id', 'title_id']);
            foreach ($object_navigate as $key => $item) {
                if (!empty($item->unit_id)) {
                    $check_object = ObjectExperienceNavigate::checkUnit($item->unit_id, $this->profile->unit_id);
                    if ($check_object == 1) {
                        break;
                    }
                } else {
                    if ($item->title_id == $this->profile->title_id) {
                        $check_object = 1;
                        break;
                    }
                }
            }
            if ((!empty($object_navigate) && $check_object == 1) || $object_navigate->isEmpty()) {
                $get_number_count = CountUserExperienceNavigate::where('experience_navigate_id', $experience_navigate->id)->where('user_id', $user)->first();
                if(!empty($get_number_count) && ($experience_navigate->total_count <= $get_number_count->number_count || $experience_navigate->date_count <= $get_number_count->date_number_count)) {
                    $this->check_navigate = 0;
                } else {
                    $this->check_navigate = $experience_navigate->id;
                }
            }
        }
    }

    public function color() {
        $colors = SettingColor::get();
        foreach ($colors as $key => $color) {
            if($color->name == 'color_button') {
                $get_color_button = $color;
                $get_lighter_background_color = $this->luminance($get_color_button->background, 0.6);
                $get_lighter_background_hover_color = $this->luminance($get_color_button->hover_background, 0.6);
                $this->color_button = $get_color_button;
                $this->lighter_background_color_button = $get_lighter_background_color;
                $this->lighter_background_hover_color_button = $get_lighter_background_hover_color;
            } else if ($color->name == 'color_menu') {
                $this->color_menu = $color;
            } else {
                $this->color_link = $color;
            }
        }
    }

    public function luminance($hex, $percent) {
        $hash = '';
        if (stristr($hex, '#')) {
            $hex = str_replace('#', '', $hex);
            $hash = '#';
        }

        $rgb = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
        for ($i = 0; $i < 3; $i++) {
            if ($percent > 0) {
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } else {
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i] = round($rgb[$i] * (1 - $positivePercent)); // round($rgb[$i] * (1-$positivePercent));
            }
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        $hex = '';
        for ($i = 0; $i < 3; $i++) {
            $hexDigit = dechex($rgb[$i]);
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            $hex .= $hexDigit;
        }
        return $hash . $hex;
    }
}
