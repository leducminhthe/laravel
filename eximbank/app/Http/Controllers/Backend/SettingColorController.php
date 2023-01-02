<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\SettingColor;

class SettingColorController extends Controller
{
    public function index() {
        $color_menu = SettingColor::where('name','color_menu')->first();
        $color_button = SettingColor::where('name','color_button')->first();
        $color_link = SettingColor::where('name','color_link')->first();

        $color_online = Config::where('name','color_online')->first();
        $i_text_online = Config::where('name','i_text_online')->first();
        $b_text_online = Config::where('name','b_text_online')->first();

        $color_offline = Config::where('name','color_offline')->first();
        $i_text_offline = Config::where('name','i_text_offline')->first();
        $b_text_offline = Config::where('name','b_text_offline')->first();

        $color_title = Config::where('name','color_title')->first();
        $bg_menu = Config::where('name', 'bg_menu')->first();

        return view('backend.setting_color.form', [
            'color_online' => $color_online,
            'i_text_online' => $i_text_online,
            'b_text_online' => $b_text_online,
            'color_offline' => $color_offline,
            'i_text_offline' => $i_text_offline,
            'b_text_offline' => $b_text_offline,
            'color_title' => $color_title,
            'color_menu' => $color_menu,
            'color_button' => $color_button,
            'color_link' => $color_link,
            'bg_menu' => $bg_menu,
        ]);
    }

    public function save(Request $request) {
        $color_menu = SettingColor::firstOrNew(['name' => 'color_menu']);
        $color_menu->text = $request->text_color_menu;
        $color_menu->hover_text = $request->hover_text_color_menu;
        $color_menu->active = $request->text_color_menu_active;
        $color_menu->background = $request->background_menu;
        $color_menu->hover_background = $request->hover_background_menu;
        $color_menu->background_child = $request->background_menu_child;
        $color_menu->save();

        $color_button = SettingColor::firstOrNew(['name' => 'color_button']);
        $color_button->text = $request->color_text_button;
        $color_button->hover_text = $request->color_hover_text_button;
        $color_button->background = $request->background_button;
        $color_button->hover_background = $request->hover_background_button;
        $color_button->save();

        $color_link = SettingColor::firstOrNew(['name' => 'color_link']);
        $color_link->text = $request->color_link;
        $color_link->hover_text = $request->hover_color_link;
        $color_link->save();


        if ($request->color_online) {
            $color_online = Config::firstOrNew(['name' => 'color_online']);
            $color_online->value = $request->color_online;
            $save_color_online = $color_online->save();
        }

        $i_text_online = Config::firstOrNew(['name' => 'i_text_online']);
        $i_text_online->value = $request->input('i_text_online', 0);
        $save_i_text_online = $i_text_online->save();

        $b_text_online = Config::firstOrNew(['name' => 'b_text_online']);
        $b_text_online->value = $request->input('b_text_online', 0);
        $save_b_text_online = $b_text_online->save();

        if ($request->color_offline) {
            $color_offline = Config::firstOrNew(['name' => 'color_offline']);
            $color_offline->value = $request->color_offline;
            $save_color_offline = $color_offline->save();
        }

        $i_text_offline = Config::firstOrNew(['name' => 'i_text_offline']);
        $i_text_offline->value = $request->input('i_text_offline', 0);
        $save_i_text_offline = $i_text_offline->save();

        $b_text_offline = Config::firstOrNew(['name' => 'b_text_offline']);
        $b_text_offline->value = $request->input('b_text_offline', 0);
        $save_b_text_offline = $b_text_offline->save();

        if ($request->color_title) {
            $color_title = Config::firstOrNew(['name' => 'color_title']);
            $color_title->value = $request->color_title;
            $save_color_title = $color_title->save();
        }

        if ($request->bg_menu) {
            $bg_menu = Config::firstOrNew(['name' => 'bg_menu']);
            $bg_menu->value = $request->bg_menu;
            $save_bg_menu = $bg_menu->save();
        }

        if ($color_menu || $color_button || $save_color_online || $save_color_offline || $save_i_text_online || $save_b_text_online || $save_i_text_offline || $save_b_text_offline || $save_color_title || $save_bg_menu) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.setting_color')
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');

    }
}
