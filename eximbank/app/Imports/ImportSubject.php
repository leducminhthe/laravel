<?php

namespace App\Imports;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\Unit;
use App\Notifications\ImportSubjectHasFailed;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Models\Profile;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserRole;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportSubject implements ToModel, WithStartRow
{
    public $imported_by;
    public $unit_id;
    public $errors;

    public function __construct($user, $user_role)
    {
        $this->imported_by = $user;
        $this->errors = [];
        $this->unit_id = session()->get('user_unit') ?? (Profile::getUnitId() ?? null);
        $this->user_role = $user_role;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;
        $ctdt_code = trim($row[1]);
        $ctdt_name = trim($row[2]);
        $level_subject_code = trim($row[3]);
        $level_subject_name = trim($row[4]);
        $subject_code = trim($row[5]);
        $subject_name = trim($row[6]);

        if (empty($ctdt_code)) {
            $this->errors[] = 'Dòng '. $row[0] .': Mã Chủ đề không thể trống';
            $error = true;
        }

        if (empty($ctdt_name)) {
            $this->errors[] = 'Dòng '. $row[0] .': Tên Chủ đề không thể trống';
            $error = true;
        }

        if (empty($level_subject_code)) {
            $this->errors[] = 'Dòng '. $row[0] .': Mã Mảng nghiệp vụ không thể trống';
            $error = true;
        }

        if (empty($level_subject_name)) {
            $this->errors[] = 'Dòng '. $row[0] .': Tên Mảng nghiệp vụ không thể trống';
            $error = true;
        }

        if (empty($subject_code)) {
            $this->errors[] = 'Dòng '. $row[0] .': Mã Chuyên đề không thể trống';
            $error = true;
        }

        if (empty($subject_name)) {
            $this->errors[] = 'Dòng '. $row[0] .': Tên Chuyên đề không thể trống';
            $error = true;
        }

        if($error) {
            // $this->imported_by->notify(new ImportSubjectHasFailed($errors));
            return null;
        }

        // kiểm tra có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin()){
            if($this->user_role->type == 'group-child') {
                $getArray = Unit::getArrayChild($this->user_role->code);
                array_push($getArray, $this->user_role->unit_id);
            }
        }

        $training_program = TrainingProgram::firstOrNew(['code' => $ctdt_code]);
        $training_program->code = $ctdt_code;
        $training_program->name = $ctdt_name;
        if($training_program->exists){
            $training_program->updated_by = $this->imported_by->id;
            if(!Permission::isAdmin()) {
                if($this->user_role->type == 'group-child') {
                    if(!in_array($training_program->unit_by, $getArray)) {
                        $this->errors[] = 'Dòng '. $row[0] .': Chủ đề Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                } else {
                    if($training_program->unit_by != $this->user_role->unit_id) {
                        $this->errors[] = 'Dòng '. $row[0] .': Chủ đề Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                }
            }
        } else{
            $training_program->updated_by = $this->imported_by->id;
            $training_program->created_by = $this->imported_by->id;
        }
        $training_program->unit_by = $this->unit_id;

        if ($training_program->save()) {
            $level_subject = LevelSubject::firstOrNew(['code' => $level_subject_code]);
            if($level_subject->exists){
                $level_subject->updated_by = $this->imported_by->id;
                if(!Permission::isAdmin()) {
                    if($this->user_role->type == 'group-child') {
                        if(!in_array($level_subject->unit_by, $getArray)) {
                            $this->errors[] = 'Dòng '. $row[0] .': Mảng nghiệp vụ Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                            return null;
                        }
                    } else {
                        if($level_subject->unit_by != $this->user_role->unit_id) {
                            $this->errors[] = 'Dòng '. $row[0] .': Mảng nghiệp vụ Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                            return null;
                        }
                    }
                }
            } else{
                $level_subject->updated_by = $this->imported_by->id;
                $level_subject->created_by = $this->imported_by->id;
            }
            $level_subject->unit_by = $this->unit_id;
            $level_subject->code = $level_subject_code;
            $level_subject->name = $level_subject_name;
            $level_subject->status = 1;

            if ($level_subject->save()){
                $subject = Subject::firstOrNew(['code' => $subject_code]);
                if($subject->exists) {
                    $subject->updated_by = $this->imported_by->id;
                    if(!Permission::isAdmin()) {
                        if($this->user_role->type == 'group-child') {
                            if(!in_array($subject->unit_by, $getArray)) {
                                $this->errors[] = 'Dòng '. $row[0] .': Chuyên đề Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                                return null;
                            }
                        } else {
                            if($subject->unit_by != $this->user_role->unit_id) {
                                $this->errors[] = 'Dòng '. $row[0] .': Chuyên đề Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                                return null;
                            }
                        }
                    }
                } else {
                    $subject->updated_by = $this->imported_by->id;
                    $subject->created_by = $this->imported_by->id;
                }

                $subject->unit_by = $this->unit_id;
                $subject->code = $subject_code;
                $subject->name = $subject_name;
                $subject->status = 1;
                $subject->level_subject_id = $level_subject->id;
                $subject->training_program_id = $training_program->id;
                $subject->save();
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         ImportFailed::class => function(ImportFailed $event) {
    //             $this->imported_by->notify(new ImportSubjectHasFailed([$event->getException()->getMessage()]));
    //         },
    //     ];
    // }
}
