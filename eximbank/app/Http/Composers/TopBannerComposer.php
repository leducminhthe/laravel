<?php


namespace App\Http\Composers;
use Composer\Config;
use Illuminate\Contracts\View\View;
use Modules\User\Entities\User;
use App\Models\SliderPosition;

class TopBannerComposer
{
    protected  $sliders;
    protected  $tabs;
    public function __construct()
    {
        $this->tabs = request()->segment(1);
        $this->getSlideer();
    }

    public function compose(View $view)
    {
        $view->with('sliders',$this->sliders)->with('tabs',$this->tabs);

    }
    public function getSlideer()
    {
        if ($this->tabs == '') {
            $slider_postion = SliderPosition::where('value', 'dashboard')->pluck('slider_id')->toArray();
        } else if($this->tabs == 'all-course') {
            $tab_2 = request()->segment(2);
            if ($tab_2 == 1) {
                $slider_postion = SliderPosition::where('value', 'course_online')->pluck('slider_id')->toArray();
            } else if ($tab_2 == 2) {
                $slider_postion = SliderPosition::where('value', 'course_offline')->pluck('slider_id')->toArray();
            } else {
                $slider_postion = SliderPosition::where('value', 'my_course')->pluck('slider_id')->toArray();
            }
        } else if ($this->tabs == 'user') {
            $tab_2 = request()->segment(2);
            if($tab_2 == 'info') {
                $slider_postion = SliderPosition::where('value', $tab_2)->pluck('slider_id')->toArray();
            } else if (in_array($tab_2, ['roadmap', 'training-by-title', 'my-career-roadmap', 'subjectregister'])) {
                $slider_postion = SliderPosition::where('value', 'menu_roadmap')->pluck('slider_id')->toArray();
            } else if (in_array($tab_2, ['trainingprocess', 'student-cost', 'quizresult'])) {
                $slider_postion = SliderPosition::where('value', 'menu_trainingprocess')->pluck('slider_id')->toArray();
            } else {
                $slider_postion = SliderPosition::where('value', 'menu_another')->pluck('slider_id')->toArray();
            }
        } else {
            $slider_postion = SliderPosition::where('value', $this->tabs)->pluck('slider_id')->toArray();
        }
        \App\Models\Slider::addGlobalScope(new \App\Scopes\CompanyScope());
        if(!empty($slider_postion)) {   
            $this->sliders = \App\Models\Slider::where('type',1)->whereIn('id',$slider_postion)->where('status', 1)->get();
        } else {
            $this->sliders = \App\Models\Slider::where('type',1)->where('location','all')->where('status', 1)->get();
        }
    }
}








