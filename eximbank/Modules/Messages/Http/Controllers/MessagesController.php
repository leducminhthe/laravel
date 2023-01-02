<?php

namespace Modules\Messages\Http\Controllers;

use App\Events\MessageBot;
use App\Events\MessagePost;
use App\Events\MessageUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\BotConfig\Entities\BotConfigSuggest;
use Modules\Messages\Entities\Message;
use Modules\Online\Entities\OnlineCourse;
use App\Models\User;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('messages::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('messages::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        $message = new Message();
        $message->message = $request->input('message', '');
        $message->from = $user->id;
        $message->to = 0;
        $message->save();
        event(new MessageUser($message,$user,$request->header('X-Socket-Id')));
        return ['message' => 'Toi la bot ban can gi'];
    }

    public function botProcess(Request $request)
    {
        $user = \Auth::user();
        $message = new Message();
        $message->message = $this->getMessageBot($request->message,$request->suggest);
        $message->from = 0;
        $message->to = $user->id;
        $message->room= '0__'.$user->id;
        $message->save();
        $suggests =BotConfigSuggest::where(['parent_id'=>$request->suggest])->select('id','name','parent_id','url','type')->get();
//        event(new MessageUser($bot,$user,$request->header('X-Socket-Id'),1));
        broadcast(new MessagePost($message->load(['receiver']),$user,$request->header('X-Socket-Id')));
        return \response()->json(['message' => $message->load(['sender','receiver']),'suggests'=>$suggests]);
    }
    public function getMessageUser(Request $request){
        $room = explode('__',$request->query('room'));
        if ($room[0]==0){
            return Message::with(['sender'])
                ->where(['room'=>$request->query('room')])
                ->latest()
                ->paginate(50);
        }
        return Message::with(['sender', 'receiver'])
            ->where(['room'=>$request->query('room')])
            ->latest()
            ->paginate(50);
    }

    public function saveMessageUser(Request $request)
    {
        $user = \Auth::user();
        $receiver = (int)$request->input('receiver');
        $sender = $user->id;
        $message = new Message();
        $message->message = $request->input('message', '');
        $message->from = $sender;
        $message->to = (int)$request->input('receiver');
        $message->suggest_id = (int)$request->input('suggest');
        $message->seen=0;
        $message->room= $receiver>0? ($sender < $receiver ? $sender.'__'.$receiver : $receiver.'__'.$sender) : ('0__'.$sender);
        $message->save();
        broadcast(new MessagePost($message->load(['sender','receiver']),$user,$request->header('X-Socket-Id')));
        broadcast(new MessageUser($message->load(['sender','receiver']),$user,$request->header('X-Socket-Id')));
        return \response()->json(['message' => $message->load(['sender','receiver'])]);
    }
    private function getMessageBot($key,$suggest_id=0){
        $mesage ='Xin chào tôi có thể giúp gì cho bạn';
        $detectKey = substr($key,0,2);
        if ($suggest_id>0){
            $answer = BotConfigSuggest::find($suggest_id)->answer;
            $mesage= $answer? $answer:$mesage;
        }else {
            if (strtolower($key) == '#report') {
                $mesage = '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC01']) . '">01. Báo cáo số liệu công tác khảo thi</a><br>';
                $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC02']) . '">02. Báo cáo số liệu điểm thi chi tiết</a><br>';
                $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC03']) . '">03. Báo cáo cơ cấu đề thi</a><br>';
                $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC04']) . '">04. Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi</a><br>';
                $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC05']) . '">05. Báo cáo học viên tham gia khóa học tập trung / trực tuyến</a><br>';
                $mesage .= '<a class="text-danger" target="_blank" href="' . route('module.report_new.review', ['id' => 'BC06']) . '">06. Danh sách học viên của đơn vị theo chuyên đề</a> ';
            } elseif (strtolower($detectKey) == '#e') {
                $code = substr($key, 3);
                $online = OnlineCourse::where(['code' => $code])->first();
                if ($online)
                    $mesage = '<a class="text-danger" target="_blank" href="' . route('module.online.detail_online', ['id' => $online->id]) . '">' . $online->name . '</a>';
                else
                    $mesage = 'Không tìm thấy khóa học';
            }
        }
        return $mesage;
    }

    public function getMessageUnread()
    {
        $sub = Message::where(['to'=>profile()->user_id,'seen'=>0])->selectRaw('count(*) as new_messages, `from` as user_id')->groupBy('from');
        return User::joinSub($sub,'ms',function ($join){
            $join->on('id','=','ms.user_id');
        })->select('user.id', 'user.username','user.firstname','user.lastname','user.email','ms.new_messages')->get();
    }
    public function getSuggest(Request $request, $id=0){

        $query = BotConfigSuggest::suggestFirst()->select('id','name','url','type','parent_id');
        if ($id>0){
            $query->where('id',$id);
        }
        $query = $query->get();
        return $query;
    }

    public function saveSuggest(Request $request, $id)
    {
        $suggest = BotConfigSuggest::findOrFail($id);
        $user  = \Auth::user();
        $message = $this->save($user->id,0,$suggest->name);

        broadcast(new MessagePost($message->load(['sender','receiver']),$user,$request->header('X-Socket-Id')));
        broadcast(new MessageUser($message->load(['sender','receiver']),$user,$request->header('X-Socket-Id')));
        return \response()->json(['message' => $message->load(['sender','receiver'])]);
    }
    public function save($sender,$receiver,$message){
        $message = new Message();
        $message->message = $message;
        $message->from = $sender;
        $message->to = (int)$receiver;
        $message->seen=0;
        $message->room= $receiver>0? ($sender < $receiver ? $sender.'__'.$receiver : $receiver.'__'.$sender) : ('0__'.$sender);
        $message->save();
        return $message;
    }
}
