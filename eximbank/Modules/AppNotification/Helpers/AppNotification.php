<?php

namespace Modules\AppNotification\Helpers;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Modules\AppNotification\Entities\AutoSendNotification;
use Modules\Notify\Entities\NotifyCountUser;

class AppNotification
{
    protected $add_ids;
    protected $title;
    protected $message;
    protected $url;
    protected $image;

    protected $send_limit = 20;

    public function add($user_id) {
        $this->add_ids[] = $user_id;
    }

    public function save() {
        $add_ids = User::whereIn('id', $this->add_ids)
            ->whereExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_app_device_tokens')
                    ->whereColumn('user_id', '=', 'user.id');
            })
            ->pluck('id')
            ->toArray();

        $add_ids = array_chunk($add_ids, $this->send_limit);

        foreach ($add_ids as $item) {
            $model = new AutoSendNotification();
            $model->fill([
                'user_ids' => implode(',', $item),
                'title' => $this->title,
                'message' => $this->message,
                'url' => $this->url,
                'image' => $this->image,
            ]);

            $model->save();
        }

        foreach($this->add_ids as $id){
            $notify_count_user = NotifyCountUser::where('user_id', $id)->first();
            if($notify_count_user){
                $notify_count_user->num_notify = $notify_count_user->num_notify + 1;
                $notify_count_user->save();
            }else{
                $notify_count_user = new NotifyCountUser();
                $notify_count_user->user_id = $id;
                $notify_count_user->num_notify = 1;
                $notify_count_user->save();
            }
        }
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setImage($image) {
        $this->image = $image;
    }
}
