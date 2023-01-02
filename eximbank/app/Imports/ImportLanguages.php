<?php

namespace App\Imports;

use App\Models\Languages;
use App\Models\LanguagesGroups;
use App\Models\LanguagesType;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\ImportLanguageHasFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;

class ImportLanguages implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;
    public $imported_by;

    public function __construct(User $user)
    {
        $this->imported_by = $user;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $error = false;
        $pkey = trim($row[1]);
        $group_name = trim($row[2]);
        $note = $row[3];
        $lang_vi = trim($row[4]);

        $errors = [];
        if (empty($pkey)) {
            $errors[] = 'Dòng '. $row[0] .': Từ khóa không thể trống';
            $error = true;
        }

        if (empty($lang_vi)) {
            $errors[] = 'Dòng '. $row[0] .': Tiếng Việt không thể trống';
            $error = true;
        }

        $group = LanguagesGroups::where('name', '=', $group_name)->first();
        if (empty($group)){
            $errors[] = 'Dòng '. $row[0] .': Nhóm <b>'. $group_name .' </b> không tồn tại';
        }

        if(!empty($errors)) {
            $this->imported_by->notify(new ImportLanguageHasFailed($errors));
            return null;
        }

      //  try {
            $types = LanguagesType::get(['key']);
            
            $model = Languages::firstOrNew(['pkey' => $pkey, 'groups_id' => $group->id]);
            foreach ($types as $index => $type){
                if ($type->key == 'vi'){
                    $model->content = $lang_vi;
                }else{
                    $model->{'content_'.$type->key} = $row[($index + 4)];
                }
            }
            $model->note = $note;
            $model->groups_id = $group->id;
            $model->save();
    /*    }
        catch (\Exception $exception) {
            $this->imported_by->notify(new ImportLanguageHasFailed(['Dòng ' . $row[0] . ': ' . $exception->getMessage()]));
        }*/
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->imported_by->notify(new ImportLanguageHasFailed([$event->getException()->getMessage()]));
            },
        ];
    }
}
