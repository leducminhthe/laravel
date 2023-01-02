<?php
namespace Modules\Quiz\Imports;

use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionImportV2 implements ToModel, WithStartRow
{
    public $errors;
    protected $plat = 0;
    public $arr_question_multiple;

    public function __construct($category_id)
    {
        $this->errors = [];
        $this->category_id = $category_id;
        $this->arr_question_multiple = [];
    }

    public function model(array $row)
    {
        $error = false;

        $index = (int) $row[0]; //STT
        $question_code = $row[1]; //Mã câu hỏi
        $question_name = $row[2]; //Tên câu hỏi
        $difficulty = strtoupper($row[3]); //Mức độ câu hỏi. D => Dễ; TB => Trung bình; K => Khó
        /*
            Loại câu hỏi.
            1: Tự luận;
            2: Trắc nghiệm chọn 1;
            3: Trắc nghiệm chọn nhiều;
            4: Điền từ chính xác;
            5: Nối câu;
            6: Chọn từ còn thiếu;
        */
        $question_type = (int)$row[4];
        $note_question = $row[5]; //Chú thích câu hỏi
        /*
            Tính điểm trắc nghiệm chọn nhiều.
            Mặc định không nhập là chọn đúng hết mới tính điểm câu hỏi. (multiple_full_score = 1)
            Nhập dấu X là tính trên % các câu đáp án đúng. (multiple_full_score = 0)
        */
        $multiple_full_score = $row[6];
        $shuffle_answers = $row[7]; //Xáo trộn đáp án
        $feedback_question = $row[8] ?? null; //Phản hồi câu hỏi
        $correct_answer_string = in_array($question_type, [2,3]) ? strtoupper(str_replace(' ', '', $row[9])) : $row[9]; //Đáp án đúng

        $max_row = max(array_keys($row));
        $question_multiple = ($question_type == 3 ? 1: 0); //1: Chọn nhìu; 0: Chọn một

        $type_question = [
            1 => 'essay',
            2 => 'multiple-choise', // Chọn 1
            3 => 'multiple-choise', // Chọn nhiều
            4 => 'fill_in_correct',
            5 => 'matching',
            6 => 'select_word_correct',
        ];
        $arr_char = [
            'A' => 10,
            'B' => 11,
            'C' => 12,
            'D' => 13,
            'E' => 14,
            'F' => 15,
            'G' => 16,
            'H' => 17,
            'I' => 18,
            'J' => 19,
            'K' => 20,
            'L' => 21,
            'M' => 22,
            'N' => 23,
            'O' => 24,
            'P' => 25,
            'Q' => 26,
            'R' => 27,
            'S' => 28,
            'T' => 29,
            'U' => 30,
            'V' => 31,
            'W' => 32,
            'X' => 33,
            'Y' => 34,
            'Z' => 35,
        ];

        $select_word_correct = 0;
        $i_answer_correct = 0;
        $count_answer = 0;
        $percent_answer_arr = [];
        $answer_arr = [];

        //Kiểm tra câu hỏi sử dụng công thức
        if($question_code[0] == '=') {
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> không đúng dịnh dạng. Mã câu hỏi đang sử dụng công thức';
            $error = true;
        }
        if($question_name[0] == '=') {
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> không đúng dịnh dạng. Tên câu hỏi đang sử dụng công thức';
            $error = true;
        }
        if(!in_array($question_type, [1,2,3,4,5,6])){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa chọn loại câu hỏi';
            $error = true;
        }

        if($question_type > 1){
            $correct_answer_arr = explode("|",$correct_answer_string);
            if($question_type == 2){
                $i_answer_correct = $arr_char[$correct_answer_arr[0]];

                if(count($correct_answer_arr) > 1){
                    $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> là câu trắc nghiệm chọn 1. Không thể có nhiều đáp án đúng';
                    $error = true;
                }
                if($correct_answer_arr[0] && !array_key_exists($correct_answer_arr[0], $arr_char)){
                    $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> đáp án đúng '. $correct_answer_arr[0] .' không đúng định dạng ABCD';
                    $error = true;
                }

                //Kiểm tra câu hỏi không có câu trả lời nhưng lại nhập là đáp án đúng
                if(empty($row[$i_answer_correct])){
                    $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> không có đáp án '. $correct_answer_arr[0];
                    $error = true;
                }
            }
            if($question_type == 3){
                $percent_answer = (1/count($correct_answer_arr))*100;
                foreach($correct_answer_arr as $correct_answer_key => $correct_answer_item){
                    $i_answer_correct = $arr_char[$correct_answer_item];
                    $percent_answer_arr[$i_answer_correct] = $percent_answer;

                    if($correct_answer_item && !array_key_exists($correct_answer_item, $arr_char)){
                        $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> đáp án đúng '. $correct_answer_item .' không đúng định dạng ABCD';
                        $error = true;
                    }

                    //Kiểm tra câu hỏi không có câu trả lời nhưng lại nhập là đáp án đúng
                    if(empty($row[$i_answer_correct]) || strlen(trim($row[$i_answer_correct])) == 0){
                        $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> không có câu trả lời cho đáp án '. $correct_answer_item;
                        $error = true;
                    }
                }
            }
            if(in_array($question_type, [4,5,6])){
                foreach($correct_answer_arr as $correct_answer_key => $correct_answer_item){
                    $answer_arr[(10+$correct_answer_key)] = $correct_answer_item;
                }
                for($i = 10; $i <= $max_row; $i++){
                    if(isset($row[$i]) && strlen(trim($row[$i])) > 0){
                        $count_answer += 1;

                        if($question_type == 6){
                            $list_answer_arr = explode("|",$row[$i]);
                            if(!array_key_exists($answer_arr[$i]-1, $list_answer_arr)){
                                $this->errors[] = 'Câu hỏi số <b>'. $index .'</b>: đáp án đúng vị trí '.$answer_arr[$i].' không có câu trả lời';
                                $error = true;
                            }
                        }
                    }
                }
                if($count_answer != count($correct_answer_arr)){
                    $this->errors[] = 'Câu hỏi số <b>'. $index .'</b>: đáp án đúng không khớp với câu trả lời';
                    $error = true;
                }
            }
        }

        if(!$difficulty){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa nhập mức độ câu hỏi';
            $error = true;
        }

        if(!in_array($difficulty, ['D','TB','K'])){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> mức độ câu hỏi không tồn tại';
            $error = true;
        }

        if($index && (empty($question_code) || strlen(trim($question_code)) == 0)){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa nhập mã câu hỏi';
            $error = true;
        }

        if($index && (empty($question_name) || strlen(trim($question_name)) == 0)){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa nhập tên câu hỏi';
            $error = true;
        }

        $question = Question::where('code', $question_code);
        if($question->exists()){
            $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> mã câu hỏi đã tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        if($index){
            $question = new Question();
            $question->category_id = $this->category_id;
            $question->code = $question_code;
            $question->name = $question_name;
            $question->type = $type_question[$question_type];
            $question->multiple = $question_multiple;
            $question->multiple_full_score = $question_multiple == 0 ? 0 : (($multiple_full_score == 'X' || $multiple_full_score == 'x') ? 0 : 1);
            $question->answer_horizontal = 0;
            $question->note = $note_question;
            $question->status = 2;
            $question->feedback = $feedback_question;
            $question->created_by = profile()->user_id;
            $question->updated_by = profile()->user_id;
            $question->shuffle_answers = $question_type > 1 ? (($shuffle_answers == 'X' || $shuffle_answers == 'x') ? 1 : 0) : 0;
            $question->difficulty = $difficulty;
            $question->save();

            //Không phải là câu tự luận mới thêm đáp án
            if($question_type > 1){
                for($i = 10; $i <= $max_row; $i++){
                    if(isset($row[$i]) && strlen(trim($row[$i])) > 0){
                        if($question_type == 6){
                            $select_word_correct += 1;
                            $list_answer_arr = explode("|",$row[$i]);
                            foreach($list_answer_arr as $list_answer_key => $list_answer_item){
                                $answer = new QuestionAnswer();
                                $answer->question_id = $question->id;
                                $answer->title = $list_answer_item;
                                $answer->correct_answer = (($answer_arr[$i]-1) == $list_answer_key) ? 1 : 0;
                                $answer->percent_answer = 0;
                                $answer->feedback_answer = null;
                                $answer->matching_answer = null;
                                $answer->fill_in_correct_answer = null;
                                $answer->select_word_correct = $select_word_correct;
                                $answer->save();
                            }
                        }else{
                            $answer = new QuestionAnswer();
                            $answer->question_id = $question->id;
                            $answer->title = $row[$i];
                            $answer->correct_answer = ($i_answer_correct == $i) ? 1 : 0;
                            $answer->percent_answer = count($percent_answer_arr) > 0 && isset($percent_answer_arr[$i]) ? $percent_answer_arr[$i] : 0;
                            $answer->feedback_answer = null;
                            $answer->matching_answer = isset($answer_arr[$i]) && $question_type == 5 ? $answer_arr[$i] : null;
                            $answer->fill_in_correct_answer = isset($answer_arr[$i]) && $question_type == 4 ? $answer_arr[$i] : null;
                            $answer->select_word_correct = null;
                            $answer->save();
                        }
                    }
                }
            }

        }
    }

    public function startRow(): int
    {
        return 5;
    }

}
