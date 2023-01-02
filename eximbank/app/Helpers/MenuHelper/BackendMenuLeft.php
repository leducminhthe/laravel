<?php

namespace App\Helpers\MenuHelper;

use App\Models\Permission;
use App\Models\User;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use TorMorten\Eventy\Facades\Events as Eventy;
use App\Models\Categories\Area;
use App\Models\UnitName;
use App\Models\AreaName;

class BackendMenuLeft
{
    public static function render()
    {
        $menu = new BackendMenuLeft();
        return $menu->view();
    }

    public function view()
    {
        return view('backend.menu.item', [
            'items' => $this->items(),
        ]);
    }

    protected function items()
    {
        $check_permission_super_admin = Permission::isSuperAdmin();
        $check_permission_organization = User::canPermissionTrainingOrganization();
        $check_permission_report = User::canPermissionReport();
        $check_unit_manager = Permission::isUnitManager();
        $language = \App::getLocale();

        $unit[] = [
            'name' => trans('lacategory.unit_structure'),
            'url' => route('backend.category.unit_name',  false),
            'icon' => asset('images/svg-backend/svgexport-101.svg'),
            'permission' => $check_permission_super_admin,
        ];

        $unitNames = UnitName::get();
        foreach ($unitNames as $key => $unitName) {
            if($unitName->level == 0) {
                $icon = asset('images/svg-backend/svgexport-101.svg');
            } else if ($unitName->level == 1) {
                $icon = asset('images/svg-backend/svgexport-102.svg');
            } else if ($unitName->level == 2) {
                $icon = asset('images/svg-backend/svgexport-103.svg');
            } else if ($unitName->level == 3) {
                $icon = asset('images/svg-backend/svgexport-104.svg');
            } else if ($unitName->level == 4) {
                $icon = asset('images/svg-backend/svgexport-105.svg');
            } else if ($unitName->level == 5) {
                $icon = asset('images/svg-backend/svgexport-106.svg');
            } else if ($unitName->level == 6) {
                $icon = asset('images/svg-backend/svgexport-107.svg');
            } else {
                $icon = asset('images/svg-backend/crowd.svg');
            }
            $unit[] = [
                'name' => $language == 'vi' ? $unitName->name : $unitName->name_en,
                'url' => route('backend.category.unit', ['level' => $unitName->level], false),
                'icon' => $icon,
                'permission' => userCan('category-unit'),
            ];
        }

        $area[] = [
            'name' => trans('lacategory.area_structure'),
            'url' => route('backend.category.area_name',  false),
            'icon' => asset('images/svg-backend/svgexport-109.svg'),
            'permission' => $check_permission_super_admin,
        ];

        $areaNames = AreaName::get();
        foreach ($areaNames as $key => $areaName) {
            if($areaName->level == 1) {
                $icon = asset('images/svg-backend/svgexport-109.svg');
            } else if ($areaName->level == 2) {
                $icon = asset('images/svg-backend/svgexport-110.svg');
            } else if ($areaName->level == 3) {
                $icon = asset('images/svg-backend/svgexport-111.svg');
            } else if ($areaName->level == 4) {
                $icon = asset('images/svg-backend/svgexport-112.svg');
            } else if ($areaName->level == 5) {
                $icon = asset('images/svg-backend/svgexport-113.svg');
            } else {
                $icon = asset('images/svg-backend/destinations.svg');
            }
            $area[] = [
                'name' => $language == 'vi' ? $areaName->name : $areaName->name_en,
                'url' => route('backend.category.area', ['level' => $areaName->level], false),
                'icon' => $icon,
                'permission' => userCan('category-area'),
            ];
        }


        $iconReport = asset('images/svg-backend/svgexport-27.svg');
        $trainingActivity = $this->trainingActivityReport();
        $count = 0;
        foreach ($trainingActivity as $key => $training) {
            $count += 1;
            $training_activity[] = [
                'name' => $count. '/ '. $training,
                'url' => route('module.report_new.review', ['id' => $key], false),
                'icon' => $iconReport,
                'permission' => userCan('report-'.(str_replace('BC', '', $key))) || User::isRoleLeader(),
            ];
        }

        $quizManagerReport = $this->quizManagerReport();
        $count_quiz_manager = 0;
        foreach ($quizManagerReport as $key => $quiz) {
            if(userCan('report-'.(str_replace('BC', '', $key)))) {
                $count_quiz_manager += 1;
                $quiz_manager[] = [
                    'name' => $count_quiz_manager. '/ '. $quiz,
                    'url' => route('module.report_new.review', ['id' => $key], false),
                    'icon' => $iconReport,
                    'permission' => userCan('report-'.(str_replace('BC', '', $key))),
                ];
            }
        }

        $costReport = $this->costReport();
        $count_cost = 0;
        foreach ($costReport as $key => $cost) {
            if(userCan('report-'.(str_replace('BC', '', $key)))) {
                $count_cost += 1;
                $cost_report[] = [
                    'name' => $count_cost. '/ '. $cost,
                    'url' => route('module.report_new.review', ['id' => $key], false),
                    'icon' => $iconReport,
                    'permission' => userCan('report-'.(str_replace('BC', '', $key))),
                ];
            }
        }

        $otherReport = $this->otherReport();
        $count_other = 0;
        foreach ($otherReport as $key => $other) {
            if(userCan('report-'.(str_replace('BC', '', $key)))) {
                $count_other += 1;
                $other_report[] = [
                    'name' => $count_other. '/ '. $other,
                    'url' => route('module.report_new.review', ['id' => $key], false),
                    'icon' => $iconReport,
                    'permission' => userCan('report-'.(str_replace('BC', '', $key))),
                ];
            }
        }

        $user = Auth::user();
//        return Cache::tags('BackendMenuLeft'.app()->getLocale())->rememberForever('BackendMenuLeft::items' . $user->id. User::getUserRole(), function () use ($user, $unit, $training_activity, $quiz_manager, $cost_report, $other_report) {
            $items = [
                'Thống kê' => [
                    'id' => '1',
                    'name' => trans('lamenu.summary'),
                    'url' =>  route('module.dashboard', [], false),
                    'icon' => asset('images/svg-backend/svgexport-1.svg'),
                    'permission' =>  User::isRoleManager(),
                    'url_name' => 'dashboard',
                    'url_child' => [],
                ],
                'Thống kê Trưởng đơn vị' => [
                    'id' => '1',
                    'name' => trans('lamenu.summary'),
                    'url' =>  route('module.dashboard_unit', [], false) ,
                    'icon' => asset('images/svg-backend/svgexport-1.svg'),
                    'permission' => User::isRoleLeader() ,
                    'url_name' => 'dashboard',
                    'url_child' => [],
                ],
                'Thống kê giảng viên' => [
                    'id' => '1',
                    'name' => trans('lamenu.summary'),
                    'url' => route('module.dashboard_teacher', [], false),
                    'icon' => asset('images/svg-backend/svgexport-1.svg'),
                    'permission' => User::isRoleTeacher(),
                    'url_name' => 'dashboard-teacher',
                    'url_child' => [],
                ],
                //ĐÀO TẠO
                'learning_opening' => [
                    'id' => '2',
                    'permission' => $check_permission_organization,
                    'name' => trans('lamenu.training_activity'),
                    'icon' => asset('images/svg-backend/svgexport-2.svg'),
                    'url' => route('module.online.management', [], false),
                    'url_name_child' => ['online','offline','training-plan','course-plan','courseold','trainingroadmap','training-by-title','training-by-title-result', 'mergesubject', 'splitsubject', 'subjectcomplete', 'movetrainingprocess','subjectregister','indemnify','certificate','evaluationform', 'rating-organization', 'virtual-classroom', 'subject-type', 'capabilities-review'],
                    'items' => [
                        [
                            'name' => trans('lamenu.training_organizations'),
                            'url' => route('module.online.management', [], false),

                            'icon' => asset('images/svg-backend/svgexport-3.svg'),
                            'permission' => $check_permission_organization ,
                            'id' => 'learning_open_1',
                            'url_item_child' => ['online','offline','training-plan','course-plan','courseold', 'virtual-classroom'],
                            'item_childs' => [
                                // [
                                //     'name' => trans('backend.virtual_classroom'),
                                //     'url' => route('module.virtualclassroom.index', [], false),
                                //     'icon' => asset('images/svg-backend/svgexport-3.svg'),
                                //     'permission' => userCan('virtual-classroom'),
                                // ],
                                [
                                    'name' => trans('lamenu.online_course'),
                                    'url' => route('module.online.management', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-4.svg'),
                                    'permission' => userCan('online-course'),
                                ],
                                [
                                    'name' => trans('lamenu.offline_course'),
                                    'url' => route('module.offline.management', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-5.svg'),
                                    'permission' => userCan('offline-course'),
                                ],
                                [
                                    'name' => trans('lamenu.training_plan'),
                                    'url' => route('module.training_plan', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-6.svg'),
                                    'permission' => userCan('training-plan'),
                                ],
                                [
                                    'name' => trans('lamenu.month_elearning_plan'),
                                    'url' => route('module.course_plan.management', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-7.svg'),
                                    'permission' => userCan('course-plan'),
                                ],
                                [
                                    'name' => trans('lamenu.teacher_register'),
                                    'url' => route('backend.approve_teacher_register', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-8.svg'),
                                    'permission' => userCan('training-teacher-register'),
                                ]
                            ],
                        ],
                        [
                            'name' => trans('lamenu.training_manager'),
                            'url' => route('module.trainingroadmap', [], false),
                            'icon' => asset('images/svg-backend/svgexport-9.svg'),
                            'permission' => User::canPermissionTrainingManager(),
                            'id' => 'learning_open_2',
                            'url_item_child' => ['trainingroadmap','training-by-title','training-by-title-result', 'mergesubject', 'splitsubject', 'subjectcomplete', 'movetrainingprocess'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.trainingroadmap'),
                                    'url' => route('module.trainingroadmap', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-10.svg'),
                                    'permission' => userCan('training-roadmap'),
                                ],
                                [
                                    'name' => trans('lamenu.learning_path'),
                                    'url' => route('module.training_by_title', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-11.svg'),
                                    'permission' => userCan('training-by-title'),
                                ],
                                [
                                    'name' => trans('lamenu.learning_path_result'),
                                    'url' => route('module.training_by_title.result', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-12.svg'),
                                    'permission' => userCan('training-by-title-result'),
                                ],
                                // [
                                //     'name' => trans('lamenu.merge_subject'),
                                //     'url' => route('module.mergesubject.index', [], false),
                                //     'icon' => asset('images/svg-backend/svgexport-13.svg'),
                                //     'permission' => userCan('mergesubject'),
                                // ],
                                // [
                                //     'name' => trans('lamenu.split_subject'),
                                //     'url' => route('module.splitsubject.index', [], false),
                                //     'icon' => asset('images/svg-backend/svgexport-14.svg'),
                                //     'permission' => userCan('splitsubject'),
                                // ],
                                // [
                                //     'name' => trans('lamenu.training_completion'),
                                //     'url' => route('module.subjectcomplete.index', [], false),
                                //     'icon' => asset('images/svg-backend/svgexport-15.svg'),
                                //     'permission' => userCan('subjectcomplete'),
                                // ],
                                // [
                                //     'name' => trans('lamenu.move_training_process'),
                                //     'url' => route('module.movetrainingprocess.index', [], false),
                                //     'icon' => asset('images/svg-backend/svgexport-16.svg'),
                                //     'permission' => userCan('movetrainingprocess'),
                                // ],
                            ],
                        ],
                        [
                            'name' => trans('lamenu.subject_registered'),
                            'url' => route('subjectregister.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-17.svg'),
                            'url_name' => 'subjectregister',
                            'permission' => userCan('subjectregister'),
                        ],
                        [
                            'name' => trans('lamenu.indemnify'),
                            'url' => route('module.indemnify', [], false),
                            'icon' => asset('images/svg-backend/svgexport-18.svg'),
                            'url_name' => 'indemnify',
                            'permission' => userCan('indemnify'),
                        ],
                        [
                            'name' => 'Khung năng lực',
                            'url' => route('module.capabilities.review', [], false),
                            'icon' => asset('images/svg-backend/svgexport-18.svg'),
                            'url_name' => 'capabilities-review',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('laprofile.certificates'),
                            'url' => route('module.certificate', [], false),
                            'icon' => asset('images/svg-backend/svgexport-19.svg'),
                            'url_name' => 'certificate',
                            'permission' => User::canPermissionTrainingCert(),
                            'id' => 'learning_open_4',
                            'url_item_child' => ['certificate','subject-type','kpi-template'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.certificate'),
                                    'url' => route('module.certificate', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-20.svg'),
                                    'permission' => userCan('certificate-template'),
                                ],
                                [
                                    'name' => trans('lacategory.subject_type'),
                                    'url' => route('backend.category.subject_type', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-21.svg'),
                                    'permission' => userCan('category-subject-type')
                                ],
                                [
                                    'name' => trans('lacategory.kpi_template'),
                                    'url' => route('backend.category.kpi_tempalte', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-22.svg'),
                                    'permission' => userCan('certificate-template-kpi')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('backend.plan_app'),
                            'url' => route('module.rating.template', [], false),
                            'icon' => asset('images/svg-backend/svgexport-23.svg'),
                            'permission' => User::canPermissionTrainingRate(),
                            'id' => 'learning_open_3',
                            'url_item_child' => ['evaluationform', 'rating-organization'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.rating_template'),
                                    'url' => route('module.rating.template', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-24.svg'),
                                    'permission' => userCan('rating-template'),
                                ],
                                [
                                    'name' => trans('lamenu.kirkpatrick_model'),
                                    'url' => route('module.rating_organization', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-25.svg'),
                                    'permission' => userCan('rating-levels'),
                                ],
                                [
                                    'name' => trans('lamenu.app_plan_template'),
                                    'url' => route('module.plan_app.template', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-26.svg'),
                                    'permission' => userCan('plan-app-template'),
                                ],
                            ],
                        ],
                        [
                            'name' => trans('lamenu.training_calendar'),
                            'url' => route('backend.training_calendar', [], false),
                            'icon' => asset('images/svg-frontend/svgexport-33.svg'),
                            'url_name' => 'training-calendar',
                            'permission' => userCan('training-calendar'),
                        ],
                    ],
                ],
                // BÁO CÁO
                'report' => [
                    'id' => '3',
                    'permission' => $check_permission_report || $check_unit_manager,
                    'name' => trans('lamenu.new_report'),
                    'icon' => asset('images/svg-backend/svgexport-27.svg'),
                    'url' => route('module.online.management', [], false),
                    'url_name_child' => ['review'],
                    'items' => [
                        [
                            'name' => trans('lamenu.training_activity'),
                            'url' => route('module.report_new', [], false),
                            'icon' => asset('images/svg-backend/svgexport-3.svg'),
                            'permission' =>  $check_permission_report || $check_unit_manager,
                            'id' => 'report_1',
                            'url_item_child' => ['BC09', 'BC12', 'BC24', 'BC05', 'BC06', 'BC25', 'BC08', 'BC11', 'BC23', 'BC29', 'BC15', 'BC07', 'BC10', 'BC22'],
                            'item_childs' => $training_activity,
                        ],
                        [
                            'name' => trans('lamenu.quiz_manager'),
                            'url' => route('module.report_new', [], false),
                            'icon' => asset('images/svg-backend/svgexport-28.svg'),
                            'permission' => $check_permission_report,
                            'id' => 'report_2',
                            'url_item_child' => ['BC34', 'BC04', 'BC01', 'BC02', 'BC28'],
                            'item_childs' => $quiz_manager,
                        ],
                        [
                            'name' => trans('lamenu.cost'),
                            'url' => route('module.report_new', [], false),
                            'icon' => asset('images/svg-backend/svgexport-29.svg'),
                            'permission' => $check_permission_report,
                            'id' => 'report_3',
                            'url_item_child' => ['BC13', 'BC17', 'BC18', 'BC26', 'BC27'],
                            'item_childs' => $cost_report,
                        ],
                        [
                            'name' => trans('lamenu.other'),
                            'url' => route('module.report_new', [], false),
                            'icon' => asset('images/svg-backend/svgexport-9.svg'),
                            'permission' => $check_permission_report,
                            'id' => 'report_4',
                            'url_item_child' => ['BC14', 'BC31', 'BC32', 'BC16'],
                            'item_childs' => $other_report,
                        ],
                    ],
                ],
                //KỲ THI
                'quiz' => [
                    'id' => '4',
                    'permission' => User::canPermissionQuiz(),
                    'name' => trans('lamenu.quiz_manager'),
                    'icon' => asset('images/svg-backend/svgexport-28.svg'),
                    'url' => route('module.quiz.questionlib', [], false),
                    'name_url' => 'menu_quiz',
                    'url_name_child' => ['question-lib','quiz-template','all','data-old','dashboard','setting-alert','user-second-note','history'],
                    'items' => [
                        [
                            'name' => trans('lamenu.questionlib'),
                            'url' => route('module.quiz.questionlib', [], false),
                            'icon' => asset('images/svg-backend/svgexport-30.svg'),
                            'url_name' => 'question-lib',
                            'permission' => userCan('quiz-category-question'),
                        ],
                        [
                            'name' => trans('lamenu.quiz_structure'),
                            'url' => route('module.quiz_template.manager', [], false),
                            'icon' => asset('images/svg-backend/svgexport-31.svg'),
                            'url_name' => 'quiz-template',
                            'permission' => userCan('quiz-template')
                        ],
                        [
                            'name' => trans('lamenu.quiz_list'),
                            'url' => route('module.quiz.manager', [], false),
                            'icon' => asset('images/svg-backend/svgexport-32.svg'),
                            'url_name' => 'all',
                            'permission' => userCan('quiz')
                        ],
                        /*[
                            'name' => trans('lamenu.data_old_quiz'),
                            'url' => route('module.quiz.data_old_quiz', [], false),
                            'icon' => asset('images/svg-backend/svgexport-33.svg'),
                            'url_name' => 'data-old',
                            'permission' => userCan('quiz')
                        ],*/
                        [
                            'name' => trans('lamenu.quiz_dashboard'),
                            'url' => route('module.quiz.dashboard', [], false),
                            'icon' => asset('images/svg-backend/svgexport-34.svg'),
                            'url_name' => 'dashboard',
                            'permission' => userCan('quiz-dashboard')
                        ],
                        [
                            'name' => trans('lamenu.information_edit'),
                            'url' => route('module.quiz.user_second_note', [], false),
                            'icon' => asset('images/svg-backend/svgexport-36.svg'),
                            'url_name' => 'user-second-note',
                            'permission' => userCan('quiz')
                        ],
                        [
                            'name' => trans('latraining.history'),
                            'url' => route('module.quiz.history_user', [], false),
                            'icon' => asset('images/svg-backend/svgexport-37.svg'),
                            'permission' => userCan('quiz-history') || userCan('quiz-history-user-second'),
                            'id' => 'quiz_1',
                            'url_item_child' => ['history'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.internal_user_history'),
                                    'url' => route('module.quiz.history_user', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-37.svg'),
                                    'permission' => userCan('quiz-history')
                                ],
                                [
                                    'name' => trans('lamenu.external_user_history'),
                                    'url' => route('module.quiz.history_user_second', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-38.svg'),
                                    'permission' => userCan('quiz-history-user-second')
                                ],
                            ],
                        ],
                    ],
                ],
                //Sales Kit
                'saleskit' => [
                    'id' => '17',
                    'name' => 'Sales Kit',
                    'url' => route('module.saleskit.category', [], false),
                    'icon' => asset('images/svg-backend/svgexport-43.svg'),
                    'permission' => User::canPermissionSalesKit(),
                    'url_name' => 'saleskit',
                    'url_child' => [],
                ],
                //THƯ VIỆN
                'library' => [
                    'id' => '5',
                    'permission' => User::canPermissionLibraries(),
                    'name' => trans('lamenu.library'),
                    'icon' => asset('images/svg-backend/svgexport-39.svg'),
                    'url' => route('module.libraries.category', [], false),
                    'url_name_child' => ['libraries'],
                    'items' => [
                        [
                            'name' => trans('lamenu.category'),
                            'url' => route('module.libraries.category', [], false),
                            'icon' => asset('images/svg-backend/svgexport-40.svg'),
                            'url_name' => 'category-libraries',
                            'permission' => userCan('libraries-category'),
                        ],
                        [
                            'name' => trans('lamenu.book_register'),
                            'url' => route('module.libraries.book.register', [], false),
                            'icon' => asset('images/svg-backend/svgexport-41.svg'),
                            'url_name' => 'register',
                            'permission' => userCan('libraries-book-register'),
                        ],
                        [
                            'name' => trans('lamenu.book'),
                            'url' => route('module.libraries.book', [], false),
                            'icon' => asset('images/svg-backend/svgexport-42.svg'),
                            'url_name' => 'book',
                            'permission' => userCan('libraries-book'),
                        ],
                        [
                            'name' => trans('lamenu.ebook'),
                            'url' => route('module.libraries.ebook', [], false),
                            'icon' => asset('images/svg-backend/svgexport-43.svg'),
                            'url_name' => 'ebook',
                            'permission' => userCan('libraries-ebook'),
                        ],
                        [
                            'name' => trans('lamenu.document'),
                            'url' => route('module.libraries.document', [], false),
                            'icon' => asset('images/svg-backend/svgexport-44.svg'),
                            'url_name' => 'document',
                            'permission' => userCan('libraries-document'),
                        ],
                        [
                            'name' => trans('lamenu.audio'),
                            'url' => route('module.libraries.audiobook', [], false),
                            'icon' => asset('images/svg-backend/svgexport-45.svg'),
                            'url_name' => 'audiobook',
                            'permission' => userCan('libraries-ebook'),
                        ],
                        [
                            'name' => trans('lamenu.video'),
                            'url' => route('module.libraries.video', [], false),
                            'icon' => asset('images/svg-backend/svgexport-46.svg'),
                            'url_name' => 'video',
                            'permission' => userCan('libraries-video'),
                        ],
                        // [
                        //     'name' => 'Sales Kit',
                        //     'url' => route('module.libraries.salekit', [], false),
                        //     'icon' => asset('images/svg-backend/svgexport-43.svg'),
                        //     'url_name' => 'salekit',
                        //     'permission' => userCan('libraries-salekit'),
                        // ],
                    ],
                ],
                //TIN TỨC
                'news' => [
                    'id' => '6',
                    'permission' => User::canPermissionNews(),
                    'name' => trans('lamenu.news'),
                    'icon' => asset('images/svg-backend/svgexport-47.svg'),
                    'url' => route('module.news.category', [], false),
                    'url_name_child' => ['category-news','news','category-news-outside','news-outside'],
                    'items' => [
                        [
                            'name' => trans('lamenu.category'),
                            'url' => route('module.news.category', [], false),
                            'icon' => asset('images/svg-backend/svgexport-40.svg'),
                            'url_name' => 'category-news',
                            'permission' => userCan('news-category'),
                        ],
                        [
                            'name' => trans('lamenu.news_list'),
                            'url' => route('module.news.manager', [], false),
                            'icon' => asset('images/svg-backend/svgexport-48.svg'),
                            'url_name' => 'news',
                            'permission' => userCan('news-list'),
                        ],
                        // [
                        //     'name' => trans('lamenu.news_adv_banner'),
                        //     'url' => route('backend.advertising_photo', ['type' => 1]),
                        //     'icon' => asset('images/icon_menu_backend/news_adv_banner.png'),
                        //     'url_name' => 'advertising-photo',
                        //     'permission' => userCan('advertising-photo'),
                        // ],
                        // [
                        //     'name' => trans('lamenu.cate_news_general'),
                        //     'url' => route('module.news_outside.category', [], false),
                        //     'icon' => asset('images/svg-backend/svgexport-40.svg'),
                        //     'url_name' => 'category-news-outside',
                        //     'permission' => Permission::isAdmin() || userCan('news-outside-category'),
                        // ],
                        // [
                        //     'name' => trans('lamenu.news_list_outside'),
                        //     'url' => route('module.news_outside.manager', [], false),
                        //     'icon' => asset('images/svg-backend/svgexport-40.svg'),
                        //     'url_name' => 'news-outside',
                        //     'permission' => Permission::isAdmin() || userCan('news-outside-list'),
                        // ],
                        // [
                        //     'name' => trans('lamenu.news_general_adv_banner'),
                        //     'url' => route('backend.advertising_photo', ['type' => 0]),
                        //     'icon' => asset('images/icon_menu_backend/news_general_adv_banner.png'),
                        //     'permission' => userCan('advertising-photo'),
                        // ],
                    ],
                ],
                // TÍCH LŨY ĐIỂM THƯỞNG QUÀ TẶNG
                'study_promotion_program' => [
                    'id' => '7',
                    'permission' => User::canPermissionGiftPromotion(),
                    'name' => trans('lamenu.study_promotion_program'),
                    'icon' => asset('images/svg-backend/svgexport-49.svg'),
                    'url' => route('module.promotion.group', [], false),
                    'url_name_child' => ['promotion-group','promotion','promotion-orders','donate-points','promotion-level'],
                    'items' => [
                        [
                            'name' => trans('lamenu.promotion_category_group'),
                            'url' => route('module.promotion.group', [], false),
                            'icon' => asset('images/svg-backend/svgexport-50.svg'),
                            'url_name' => 'promotion-group',
                            'permission' => userCan('promotion-group'),
                        ],
                        [
                            'name' => trans('lamenu.promotions'),
                            'url' => route('module.promotion', [], false),
                            'icon' => asset('images/svg-backend/svgexport-51.svg'),
                            'url_name' => 'promotion',
                            'permission' => userCan('promotion'),
                        ],
                        [
                            'name' => trans('lamenu.purchase_history'),
                            'url' => route('module.promotion.orders.buy', [], false),
                            'icon' => asset('images/svg-backend/svgexport-52.svg'),
                            'url_name' => 'promotion-orders',
                            'permission' => userCan('promotion-purchase-history'),
                        ],
                        [
                            'name' => trans('lamenu.donate_points'),
                            'url' => route('backend.donate_points', [], false),
                            'icon' => asset('images/svg-backend/svgexport-53.svg'),
                            'url_name' => 'donate-points',
                            'permission' => userCan('donate-point'),
                        ],
                        [
                            'name' => trans('lamenu.learning_rank'),
                            'url' => route('module.promotion.level', [], false),
                            'icon' => asset('images/svg-backend/svgexport-54.svg'),
                            'url_name' => 'promotion-level',
                            'permission' => userCan('promotion-level'),
                        ],
                        [
                            'name' => trans('lamenu.point_history'),
                            'url' => route('module.promotion.history', [], false),
                            'icon' => asset('images/svg-backend/svgexport-55.svg'),
                            'url_name' => 'promotion-history',
                            'permission' => userCan('promotion-history'),
                        ],
                    ],
                ],
                //CHƯƠNG TRÌNH THI ĐUA (usermedal-setting)
                'usermedal-setting' => [
                    'id' => '8',
                    'name' => trans('lamenu.emulation_program'),
                    'url' => route('module.usermedal-setting.list', [], false),
                    'icon' => asset('images/svg-backend/svgexport-56.svg'),
                    'permission' => User::canPermissionMedalSetting(),
                    'url_name' => 'usermedal-setting',
                    'url_child' => [],
                ],
                //HỌC LIỆU ĐÀO TẠO VIDEO
                'training_video' => [
                    'id' => '9',
                    'permission' => User::canPermissionTrainingVideo(),
                    'name' => trans('lamenu.training_video'),
                    'icon' => asset('images/svg-backend/svgexport-57.svg'),
                    'url' => route('module.daily_training', [], false),
                    'url_name_child' => ['daily-training','score-views','score-like','score-comment'],
                    'items' => [
                        [
                            'name' => trans('lamenu.video_category'),
                            'url' => route('module.daily_training', [], false),
                            'icon' => asset('images/svg-backend/svgexport-40.svg'),
                            'url_name' => 'daily-training',
                            'permission' => userCan('daily-training'),
                        ],
                    ],
                ],
                //PHÂN QUYỀN
                'permission' => [
                    'id' => '10',
                    'permission' => User::canPermissionRule(),
                    'name' => trans('lamenu.permission'),
                    'icon' => asset('images/svg-backend/svgexport-58.svg'),
                    'url' => route('module.permission.type', [], false),
                    'url_name_child' => ['permission-type','role','approved-process','permission'],
                    'items' => [
                        [
                            'name' => trans('lamenu.permission_group'),
                            'url' => route('module.permission.type', [], false),
                            'icon' => asset('images/svg-backend/svgexport-59.svg'),
                            'url_name' => 'permission-type',
                            'permission' => userCan('permission-group'),
                        ],
                        [
                            'name' => trans('lamenu.role'),
                            'url' => route('backend.roles', [], false),
                            'icon' => asset('images/svg-backend/svgexport-60.svg'),
                            'url_name' => 'role',
                            'permission' => userCan('role'),
                        ],
                        [
                            'name' => trans('lamenu.permission_approved'),
                            'url' => route('backend.approved.process.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-61.svg'),
                            'url_name' => 'approved-process',
                            'permission' => userCan('approved-process'),
                        ],
                        [
                            'name' => trans('lamenu.unit_manager_setup'),
                            'url' => route('backend.permission.unitmanager', [], false),
                            'icon' => asset('images/svg-backend/svgexport-62.svg'),
                            'url_name' => 'permission',
                            'permission' => userCan('unit-manager-setting'),
                        ],
                    ],
                ],
//                ĐƠN VỊ
                 'units_func' => [
                     'id' => '11',
                     'name' => trans('lamenu.unit'),
                     'icon' => asset('images/svg-backend/svgexport-63.svg'),
                     'permission' => User::isRoleLeader() ,
                     'url' => route('module.training_unit.approve_course', [], false),
                     'url_name_child' => ['training-unit','course-educate-plan','quiz-educate-plan','authorized-unit', 'plan-app'],
                     'items' => [
                         [
                             'name' => trans('backend.register'),
                             'url' => route('module.training_unit.register_course', [], false),
                             'icon' => asset('images/svg-backend/svgexport-64.svg'),
                             'url_name' => 'register-course',
                             'permission' => true,
                         ],
                         [
                             'name' => trans('lamenu.approve_register'),
                             'url' => route('module.training_unit.approve_course', [], false),
                             'icon' => asset('images/svg-backend/svgexport-65.svg'),
                             'url_name' => 'approve-course',
                             'permission' =>  true,
                         ],
                         [
                             'name' => trans('lamenu.approve_student_cost'),
                             'url' => route('module.training_unit.approve_student_cost', [], false),
                             'icon' => asset('images/svg-backend/svgexport-66.svg'),
                             'url_name' => 'approve-student-cost',
                             'permission' => true,
                         ],
                        //  [
                        //      'name' => trans('lamenu.training_seft_plan'),
                        //      'url' => route('module.course_educate_plan.management', [], false),
                        //      'icon' => asset('images/svg-backend/svgexport-67.svg'),
                        //      'url_name' => 'course-educate-plan',
                        //      'permission' => true,
                        //  ],
                         [
                             'name' => trans('lamenu.quiz_plan_suggest'),
                             'url' => route('module.quiz_educate_plan_suggest', [], false),
                             'icon' => asset('images/svg-backend/svgexport-68.svg'),
                             'url_name' => 'quiz-educate-plan',
                             'permission' => true,
                         ],
                         [
                             'name' => trans('lamenu.authorized_unit_manager'),
                             'url' => route('module.authorized_unit', [], false),
                             'icon' => asset('images/svg-backend/svgexport-69.svg'),
                             'url_name' => 'authorized-unit',
                             'permission' => true,
                         ],
                         [
                             'name' => trans('lamenu.app_plan'),
                             'url' => route('module.plan_app.course', [], false),
                             'icon' => asset('images/svg-backend/svgexport-70.svg'),
                             'url_name' => 'plan-app',
                             'permission' => true,
                         ],
                     ],
                 ],
                //CÀI ĐẶT
                'setting' => [
                    'id' => '12',
                    'permission' => User::canPermissionSetting(),
                    'name' => trans('lamenu.setting'),
                    'icon' => asset('images/svg-backend/svgexport-71.svg'),
                    'url' => route('backend.setting', [], false),
                    'url_name_child' => ['config','config-email','login-image','logo','logo-outside','favicon','app-mobile','notify-send','notify-template','mail-template','mail-signature','mail-history','contact','google-map','slider','slider-outside','infomation-company','banner-login-mobile','setting-color','languages','setting-time','setting-experience-navigate','dashboard_by_user',],
                    'items' => [
                        /*[
                            'name' => trans('lasetting.generals_setting'),
                            'url' => route('backend.config', [], false),
                            'icon' => asset('images/svg-backend/svgexport-71.svg'),
                            'url_name' => 'config',
                            'permission' => userCan('config'),
                        ],*/
                        [
                            'name' => trans('lasetting.email_configuration'),
                            'url' => route('backend.config.email.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-72.svg'),
                            'url_name' => 'config-email',
                            'permission' => userCan('config-email')
                        ],
                        [
                            'name' => trans('lasetting.login_wallpaper'),
                            'url' => route('backend.login_image', [], false),
                            'icon' => asset('images/svg-backend/svgexport-73.svg'),
                            'url_name' => 'login-image',
                            'permission' => userCan('config-login-image')
                        ],
                        [
                            'name' => trans('lasetting.logo'),
                            'url' => route('backend.logo', [], false),
                            'icon' => asset('images/svg-backend/svgexport-74.svg'),
                            'url_name' => 'logo',
                            'permission' => userCan('config-logo')
                        ],
                        [
                            'name' => trans('lasetting.extenal_logo'),
                            'url' => route('backend.logo_outside', [], false),
                            'icon' => asset('images/svg-backend/svgexport-75.svg'),
                            'url_name' => 'logo-outside',
                            'permission' => userCan('config-logo')
                        ],
                        [
                            'name' => trans('lasetting.favicon'),
                            'url' => route('backend.logo.favicon', [], false),
                            'icon' => asset('images/svg-backend/svgexport-76.svg'),
                            'url_name' => 'favicon',
                            'permission' => userCan('config-favicon')
                        ],
                        [
                            'name' => trans('lasetting.app_mobile'),
                            'url' => route('backend.app_mobile', [], false),
                            'icon' => asset('images/svg-backend/svgexport-77.svg'),
                            'url_name' => 'app-mobile',
                            'permission' => userCan('config-app-mobile')
                        ],
                        [
                            'name' => trans('lasetting.notify'),
                            'url' => route('module.notify_send', [], false),
                            'icon' => asset('images/svg-backend/svgexport-78.svg'),
                            'url_name' => 'notify-send',
                            'permission' => userCan('config-notify-send')
                        ],
                        [
                            'name' => trans('lasetting.notification_template'),
                            'url' => route('module.notify.template', [], false),
                            'icon' => asset('images/svg-backend/svgexport-79.svg'),
                            'url_name' => 'notify-template',
                            'permission' => userCan('config-notify-template')
                        ],
                        [
                            'name' => trans('lasetting.mailtemplate'),
                            'url' => route('backend.mailtemplate', [], false),
                            'icon' => asset('images/svg-backend/svgexport-80.svg'),
                            'url_name' => 'mail-template',
                            'permission' => userCan('mail-template')
                        ],
                        [
                            'name' => trans('lasetting.email_signature'),
                            'url' => route('backend.mail_signature', [], false),
                            'icon' => asset('images/svg-backend/svgexport-81.svg'),
                            'url_name' => 'mail-signature',
                            'permission' => userCan('mail-template')
                        ],
                        [
                            'name' => trans('lasetting.mailhistory'),
                            'url' => route('backend.mailhistory', [], false),
                            'icon' => asset('images/svg-backend/svgexport-82.svg'),
                            'url_name' => 'mail-history',
                            'permission' => userCan('mail-template-history')
                        ],
                        [
                            'name' => trans('lasetting.contact'),
                            'url' => route('backend.contact', [], false),
                            'icon' => asset('images/svg-backend/svgexport-83.svg'),
                            'url_name' => 'contact',
                            'permission' => userCan('contact')
                        ],
                        [
                            'name' => trans('lasetting.training_position'),
                            'url' => route('backend.google.map', [], false),
                            'icon' => asset('images/svg-backend/college_map_location.svg'),
                            'url_name' => 'google-map',
                            'permission' => userCan('google-map')
                        ],
                        [
                            'name' => trans('lasetting.banner'),
                            'url' => route('backend.slider', [], false),
                            'icon' => asset('images/svg-backend/svgexport-84.svg'),
                            'url_name' => 'slider',
                            'permission' => userCan('banner')
                        ],
                        // [
                        //     'name' => trans('lasetting.extenal_banner'),
                        //     'url' => route('backend.slider_outside', [], false),
                        //     'icon' => asset('images/svg-backend/svgexport-81.svg'),
                        //     'url_name' => 'slider-outside',
                        //     'permission' => userCan('banner')
                        // ],
                        // [
                        //     'name' => trans('lasetting.company_info'),
                        //     'url' => route('backend.infomation_company', [], false),
                        //     'icon' => asset('images/svg-backend/svgexport-81.svg'),
                        //     'url_name' => 'infomation-company',
                        //     'permission' => userCan('infomation-company')
                        // ],
                        [
                            'name' => trans('lasetting.banner_login_mobile'),
                            'url' => route('backend.banner_login_mobile', [], false),
                            'icon' => asset('images/svg-backend/svgexport-85.svg'),
                            'url_name' => 'banner-login-mobile',
                            'permission' => userCan('config-login-image')
                        ],
                        [
                            'name' => trans('lasetting.button_setting_color'),
                            'url' => route('backend.setting_color', [], false),
                            'icon' => asset('images/svg-backend/svgexport-86.svg'),
                            'url_name' => 'setting-color',
                            'permission' => userCan('setting-color')
                        ],
                        [
                            'name' => trans('lasetting.languages'),
                            'url' => route('backend.languages', [], false),
                            'icon' => asset('images/svg-backend/svgexport-87.svg'),
                            'url_name' => 'languages',
                            'permission' => userCan('languages')
                        ],
                        [
                            'name' => trans('lasetting.setting_time'),
                            'url' => route('backend.setting_time', [], false),
                            'icon' => asset('images/svg-backend/svgexport-88.svg'),
                            'url_name' => 'setting-time',
                            'permission' => userCan('setting-time')
                        ],
                        [
                            'name' => trans('lamenu.experience_directed'),
                            'url' => route('backend.experience_navigate', [], false),
                            'icon' => asset('images/svg-backend/svgexport-89.svg'),
                            'url_name' => 'setting-experience-navigate',
                            'permission' => userCan('setting-experience-navigate')
                        ],
                        // [
                        //    'name' => trans('lasetting.setting_chatbot'),
                        //    'url' => route('module.botconfig'),
                        //    'icon' => asset('images/icon_setting/botconfig.png'),
                        //    'url_name' => 'botconfig',
                        //    'permission' => true
                        // ],
                        [
                            'name' => trans('lamenu.user_summary'),
                            'url' => route('backend.dashboard_by_user', [], false),
                            'icon' => asset('images/svg-backend/svgexport-90.svg'),
                            'url_name' => 'dashboard_by_user',
                            'permission' => userCan('dashboard-by-user')
                        ],
                        [
                            'name' => trans('lamenu.cached_clear'),
                            'url' => route('backend.cache', [], false),
                            'icon' => asset('images/svg-backend/svgexport-91.svg'),
                            'permission' => $check_permission_super_admin,
                        ],
                        [
                            'name' => trans('lamenu.interaction_history_clear'),
                            'url' => route('backend.interaction_history_clear', [], false),
                            'icon' => asset('images/svg-backend/trash.svg'),
                            'url_name' => 'interaction_history_clear',
                            'permission' => userCan('interaction-history-clear')
                        ],
                        [
                            'name' => trans('laother.menu_setting'),
                            'url' => route('backend.menu_setting', [], false),
                            'icon' => asset('images/svg-backend/options.svg'),
                            'url_name' => 'interaction_history_clear',
                            'permission' => userCan('menu-setting')
                        ],
                        [
                            'name' => 'Update source',
                            'url' => route('backend.update_source', [], false),
                            'icon' => asset('images/svg-backend/updated.svg'),
                            'permission' => $check_permission_super_admin,
                        ],
                    ],
                ],
                //QUYỀN GIẢNG VIÊN
                'permission_teacher' => [
                    'id' => '13',
                    'permission' => $user->isTeacher(),
                    'name' => trans('lamenu.teacher_permission'),
                    'icon' => asset('images/svg-backend/svgexport-93.svg'),
                    'url' => route('backend.category.training_teacher.list_permission', [], false),
                    'name_url' => 'menu_permission_teacher',
                    'url_name_child' => ['grading', 'list-course', 'calendar-teacher', 'history-teacher'],
                    'items' => [
                        [
                            'name' => trans('lamenu.grading'),
                            'url' => route('module.quiz.grading', [], false),
                            'icon' => asset('images/svg-backend/svgexport-94.svg'),
                            'url_name' => 'grading',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('latraining.attendance'),
                            'url' => route('backend.category.training_teacher.list_course', [], false),
                            'icon' => asset('images/svg-backend/svgexport-95.svg'),
                            'url_name' => 'list-course',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('lamenu.calendar_teacher'),
                            'url' => route('backend.category.training_teacher.calendar_teacher', [], false),
                            'icon' => asset('images/svg-backend/svgexport-96.svg'),
                            'url_name' => 'calendar-teacher',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('latraining.history_teaching'),
                            'url' => route('backend.category.training_teacher.history_teacher', [], false),
                            'icon' => asset('images/svg-backend/svgexport-97.svg'),
                            'url_name' => 'history-teacher',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('laother.sign_teach'),
                            'url' => route('backend.category.training_teacher.register_teach', [], false),
                            'icon' => asset('images/svg-backend/svgexport-65.svg'),
                            'url_name' => 'history-teacher',
                            'permission' => true,
                        ],
                        [
                            'name' => trans('latraining.all_course'),
                            'url' => route('backend.category.training_teacher.list_course_teacher', [], false),
                            'icon' => asset('images/svg-backend/svgexport-98.svg'),
                            'url_name' => 'history-teacher',
                            'permission' => true,
                        ]
                    ],
                ],
                // MENU DANH MỤC
                'menu_category' => [
                    'id' => '14',
                    'name' => trans('lamenu.category'),
                    'url' => route('backend.category', [], false),
                    'icon' => asset('images/svg-backend/svgexport-99.svg'),
                    'permission' => User::canPermissionCategory(),
                    'url_name_child' => ['category','usermedal'],
                    'items' => [
                        [
                            'name' => trans('lacategory.organize'),
                            'url' => route('backend.category.unit', ['level' => 0], false),
                            'permission' => User::canPermissionCategoryUnit(),
                            'icon' => asset('images/svg-backend/svgexport-100.svg'),
                            'id' => 'category_1',
                            'url_item_child' => ['unit'],
                            'item_childs' => $unit
                        ],
                        [
                            'name' => trans('lacategory.geographical_location'),
                            'url' => route('backend.category.area', ['level' => 1], false),
                            'icon' => asset('images/svg-backend/svgexport-108.svg'),
                            'permission' => User::canPermissionCategoryArea(),
                            'id' => 'category_2',
                            'url_item_child' => ['area'],
                            'item_childs' => $area
                        ],
                        [
                            'name' => trans('lacategory.info'),
                            'url' => route('backend.category.unit_type', [], false),
                            'icon' => asset('images/svg-backend/svgexport-114.svg'),
                            'permission' => User::canPermissionCategoryInfo(),
                            'id' => 'category_3',
                            'url_item_child' => ['unit-type','title_rank','titles','cert','position'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.unit_type'),
                                    'url' => route('backend.category.unit_type', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => userCan('category-unit-type')
                                ],
                                [
                                    'name' => trans('lacategory.title_level'),
                                    'url' => route('backend.category.title_rank', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-116.svg'),
                                    'permission' => userCan('category-title-rank')
                                ],
                                [
                                    'name' => trans('lacategory.title'),
                                    'url' => route('backend.category.titles', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-117.svg'),
                                    'permission' => userCan('category-titles')
                                ],
                                [
                                    'name' => trans('lacategory.level'),
                                    'url' => route('backend.category.cert', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-118.svg'),
                                    'permission' => userCan('category-cert')
                                ],
                                [
                                    'name' => trans('lacategory.position'),
                                    'url' => route('backend.category.position', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-119.svg'),
                                    'permission' => userCan('category-position')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('backend.program_subject'),
                            'url' => route('backend.category.training_program', [], false),
                            'icon' => asset('images/svg-backend/svgexport-120.svg'),
                            'permission' => User::canPermissionCategorySubject(),
                            'id' => 'category_4',
                            'url_item_child' => ['training-program','level-subject','subject','training-form','training-type','training-object','quiz-type'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.training_program'),
                                    'url' => route('backend.category.training_program', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-21.svg'),
                                    'permission' => userCan('category-training-program')
                                ],
                                [
                                    'name' => trans('lacategory.type_subject'),
                                    'url' => route('backend.category.level_subject', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-121.svg'),
                                    'permission' => userCan('category-level-subject')
                                ],
                                [
                                    'name' => trans('backend.subject'),
                                    'url' => route('backend.category.subject', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-122.svg'),
                                    'permission' => userCan('category-subject')
                                ],
                                [
                                    'name' => trans('lacategory.training_form'),
                                    'url' => route('backend.category.training_form', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-123.svg'),
                                    'permission' => userCan('category-training-form')
                                ],
                                [
                                    'name' => trans('lacategory.training_type'),
                                    'url' => route('backend.category.training-type', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-124.svg'),
                                    'permission' => userCan('category-training-type')
                                ],
                                [
                                    'name' => trans('lacategory.training_object_group'),
                                    'url' => route('backend.category.training-object', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-125.svg'),
                                    'permission' => userCan('category-training-object')
                                ],
                                [
                                    'name' => trans('lacategory.quiz_type'),
                                    'url' => route('module.quiz.type.manager', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-126.svg'),
                                    'permission' => userCan('category-quiz-type')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.discipline'),
                            'url' => route('backend.category.absent', [], false),
                            'icon' => asset('images/svg-backend/svgexport-127.svg'),
                            'permission' => User::canPermissionCategoryDiscipline(),
                            'id' => 'category_5',
                            'url_item_child' => ['absent','discipline','absent-reason'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.absent_type'),
                                    'url' => route('backend.category.absent', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-128.svg'),
                                    'permission' => userCan('category-absent')
                                ],
                                [
                                    'name' => trans('lacategory.violator_list'),
                                    'url' => route('backend.category.discipline', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-129.svg'),
                                    'permission' => userCan('category-discipline')
                                ],
                                [
                                    'name' => trans('lacategory.absent_reason'),
                                    'url' => route('backend.category.absent-reason', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-130.svg'),
                                    'permission' => userCan('category-absent-reason')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.cost'),
                            'url' => route('backend.category.type_cost', [], false),
                            'icon' => asset('images/svg-backend/svgexport-29.svg'),
                            'permission' => User::canPermissionCategoryCost(),
                            'id' => 'category_6',
                            'url_item_child' => ['type-cost','training-cost','student-cost','commit-month'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.fee_type'),
                                    'url' => route('backend.category.type_cost', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-131.svg'),
                                    'permission' => userCan('category-type-cost')
                                ],
                                [
                                    'name' => trans('lacategory.training_cost'),
                                    'url' => route('backend.category.training_cost', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-132.svg'),
                                    'permission' => userCan('category-training-cost')
                                ],
                                [
                                    'name' => trans('lacategory.student_cost'),
                                    'url' => route('backend.category.student_cost', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-133.svg'),
                                    'permission' => userCan('category-student-cost')
                                ],
                                [
                                    'name' => trans('lamenu.commit'),
                                    'url' => route('backend.category.commit_month', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-134.svg'),
                                    'permission' => userCan('commit-month')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.teacher'),
                            'url' => route('backend.category.training_partner', [], false),
                            'icon' => asset('images/svg-backend/svgexport-135.svg'),
                            'permission' => User::canPermissionCategoryTeacher(),
                            'id' => 'category_7',
                            'url_item_child' => ['training-partner','teacher-type','training-teacher','coaching-group','coaching-mentor-method'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.partner'),
                                    'url' => route('backend.category.training_partner', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-136.svg'),
                                    'permission' => userCan('category-partner')
                                ],
                                [
                                    'name' => trans('lacategory.teacher_type'),
                                    'url' => route('backend.category.teacher_type', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-137.svg'),
                                    'permission' => userCan('category-teacher-type')
                                ],
                                [
                                    'name' => trans('lacategory.list_teacher'),
                                    'url' => route('backend.category.training_teacher', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-138.svg'),
                                    'permission' => userCan('category-teacher')
                                ],
                                [
                                    'name' => trans('lamenu.coaching_group'),
                                    'url' => route('module.coaching_group', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-139.svg'),
                                    'permission' => userCan('coaching-group')
                                ],
                                [
                                    'name' => trans('lamenu.coaching_mentor_method'),
                                    'url' => route('module.coaching_mentor_method', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-140.svg'),
                                    'permission' => userCan('coaching-mentor-method')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.training_location'),
                            'url' => route('backend.category.province', [], false),
                            'icon' => asset('images/svg-backend/svgexport-141.svg'),
                            'permission' => User::canPermissionCategoryTrainingLocation(),
                            'id' => 'category_8',
                            'url_item_child' => ['province','district','training-location'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.province'),
                                    'url' => route('backend.category.province', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-142.svg'),
                                    'permission' => userCan('category-province')
                                ],
                                [
                                    'name' => trans('lacategory.district'),
                                    'url' => route('backend.category.district', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-143.svg'),
                                    'permission' => userCan('category-district')
                                ],
                                [
                                    'name' => trans('lacategory.training_location'),
                                    'url' => route('backend.category.training_location', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-113.svg'),
                                    'permission' => userCan('category-training-location')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.reward_points'),
                            'url' => route('module.userpoint.manager',["id"=>2], false),
                            'icon' => asset('images/svg-backend/svgexport-144.svg'),
                            'permission' => User::canPermissionCategoryUserPoint(),
                            'id' => 'category_9',
                            'url_item_child' => ['userpoint'],
                            'item_childs' => [
                                [
                                    'name' => trans('lacategory.onl_course'),
                                    'url' => route('module.userpoint.manager',["id"=>2], false),
                                    'icon' => asset('images/svg-backend/svgexport-4.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lacategory.off_course'),
                                    'url' => route('module.userpoint.manager',["id"=>3], false),
                                    'icon' => asset('images/svg-backend/svgexport-5.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lacategory.quiz'),
                                    'url' => route('module.userpoint.manager',["id"=>4], false),
                                    'icon' => asset('images/svg-backend/svgexport-28.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lacategory.library'),
                                    'url' => route('module.userpoint.manager',["id"=>6], false),
                                    'icon' => asset('images/svg-backend/svgexport-39.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lacategory.forum') ,
                                    'url' => route('module.userpoint.manager',["id"=>7], false),
                                    'icon' => asset('images/svg-backend/svgexport-145.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lamenu.training_video'),
                                    'url' => route('module.userpoint.manager',["id"=>8], false),
                                    'icon' => asset('images/svg-backend/svgexport-57.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('lamenu.news'),
                                    'url' => route('module.userpoint.manager',["id"=>9], false),
                                    'icon' => asset('images/svg-backend/svgexport-47.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                [
                                    'name' => trans('latraining.other'),
                                    'url' => route('module.userpoint.manager',["id"=>10], false),
                                    'icon' => asset('images/svg-backend/svgexport-146.svg'),
                                    'permission' => userCan('category-userpoint-item')
                                ],
                                // [
                                //     'name' => 'Coaching',
                                //     'url' => route('module.userpoint.manager',["id"=>11], false),
                                //     'icon' => asset('images/svg-backend/svgexport-146.svg'),
                                //     'permission' => userCan('category-userpoint-item')
                                // ],
                            ]
                        ],
                        [
                            'name' => trans('lacategory.competition_program'),
                            'url' => route('module.usermedal.list', [], false),
                            'icon' => asset('images/svg-backend/svgexport-147.svg'),
                            'permission' => User::canPermissionCategoryMedal(),
                            'id' => 'category_10',
                            'url_item_child' => ['usermedal'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.compete_title'),
                                    'url' => route('module.usermedal.list', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-148.svg'),
                                    'permission' => userCan('category-usermedal')
                                ],
                            ]
                        ],
                        [
                            'name' => 'Khung năng lực',
                            'url' => '',
                            'icon' => asset('images/svg-backend/svgexport-114.svg'),
                            'permission' => true,
                            'id' => 'category_11',
                            'url_item_child' => ['capabilities-category', 'capabilities-group-percent', 'capabilities-group', 'capabilities', 'capabilities-title'],
                            'item_childs' => [
                                [
                                    'name' => 'Khung năng lực (A)',
                                    'url' => route('module.capabilities.category', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => true
                                ],
                                [
                                    'name' => 'Năng lực chuyên môn (C)',
                                    'url' => route('module.capabilities', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => true
                                ],
                                [
                                    'name' => 'Nhóm phần trăm',
                                    'url' => route('module.capabilities.group_percent', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => true
                                ],
                                [
                                    'name' => 'Phân Nhóm năng lực (ASK)',
                                    'url' => route('module.capabilities.group', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => true
                                ],
                                [
                                    'name' => 'Khung năng lực theo chức danh',
                                    'url' => route('module.capabilities.title', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-115.svg'),
                                    'permission' => true
                                ],
                            ]
                        ],
                    ],
                ],
                // QUẢN LÝ
                'manager' => [
                    'id' => '15',
                    'permission' =>  User::canPermissionGeneral(),
                    'name' => trans('lamenu.management'),
                    'icon' => asset('images/svg-backend/svgexport-149.svg'),
                    'url' => route('backend.category', [], false),
                    'url_name_child' => ['user-take-leave','user','user-contact','user-secondary', 'topic-situations', 'forums', 'suggest', 'note', 'survey', 'career-roadmap', 'plan-suggest', 'model-history', 'login-history', 'log-view-course', 'faq', 'guide', 'coaching-teacher'],
                    'items' => [
                        [
                            'name' => trans('lamenu.master_data_manager'),
                            'url' => route('backend.master_data.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-150.svg'),
                            'url_name' => 'master-data',
                            'permission' => $check_permission_super_admin,
                        ],
                        [
                            'name' => trans('lamenu.situations_proccessing'),
                            'url' => route('module.topic_situations', [], false),
                            'icon' => asset('images/svg-backend/svgexport-150.svg'),
                            'url_name' => 'topic-situations',
                            'permission' => userCan('topic')
                        ],
                        [
                            'name' => trans('lamenu.user'),
                            'url' => route('module.backend.user', [], false),
                            'permission' => User::canPermissionGeneralEmployee(),
                            'icon' => asset('images/svg-backend/svgexport-151.svg'),
                            'id' => 'manager_1',
                            'url_item_child' => ['user-take-leave','user','user-contact','user-secondary'],
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.user'),
                                    'url' => route('module.backend.user', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-152.svg'),
                                    'permission' => userCan('user') || $check_unit_manager
                                ],
                                [
                                    'name' => trans('lamenu.user_take_leave'),
                                    'url' => route('module.backend.user_take_leave', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-153.svg'),
                                    'permission' => userCan('user-take-leave')
                                ],
                                [
                                    'name' => trans('lamenu.user_secondary'),
                                    'url' => route('module.quiz.user_secondary', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-155.svg'),
                                    'url_name' => 'user-secondary',
                                    'permission' => userCan('quiz-user-secondary')
                                ],
                            ]
                        ],
                        [
                            'name' => trans('lamenu.target_manager'),
                            'url' => route('module.target_manager_parent', [], false),
                            'icon' => asset('images/svg-backend/svgexport-156.svg'),
                            'url_name' => 'target-manager-parent',
                            'permission' => userCan('target-manager-parent'),
                        ],
                        [
                            'name' => trans('lamenu.forum'),
                            'url' => route('module.forum.category', [], false),
                            'icon' => asset('images/svg-backend/svgexport-145.svg'),
                            'url_name' => 'forums',
                            'permission' => userCan('forum')
                        ],
                        [
                            'name' => trans('lamenu.suggestion'),
                            'url' => route('module.suggest', [], false),
                            'icon' => asset('images/svg-backend/svgexport-157.svg'),
                            'url_name' => 'suggest',
                            'permission' => userCan('suggest')
                        ],
                        [
                            'name' => trans('lamenu.note'),
                            'url' => route('backend.note', [], false),
                            'icon' => asset('images/svg-backend/svgexport-158.svg'),
                            'url_name' => 'note',
                            'permission' => userCan('note'),
                        ],
                        [
                            'name' => trans('lamenu.survey'),
                            'url' => route('module.survey.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-159.svg'),
                            'url_name' => 'survey',
                            'permission' => userCan('survey'),
                        ],
                        [
                            'name' => trans('lamenu.career_roadmap'),
                            'url' => route('module.career_roadmap', [], false),
                            'icon' => asset('images/svg-backend/svgexport-160.svg'),
                            'url_name' => 'career-roadmap',
                            'permission' => userCan('career-roadmap'),
                        ],
                        [
                            'name' => trans('lamenu.plan_suggest'),
                            'url' => route('module.plan_suggest', [], false),
                            'icon' => asset('images/svg-backend/svgexport-161.svg'),
                            'url_name' => 'plan-suggest',
                            'permission' => userCan('plan-suggest'),
                        ],
                        [
                            'name' => trans('lamenu.schedule_task'),
                            'url' => route('module.cron', [], false),
                            'icon' => asset('images/svg-backend/svgexport-162.svg'),
                            'permission' => $check_permission_super_admin,
                        ],
                        [
                            'name' => trans('lamenu.table_manager'),
                            'url' => route('module.tablemanager.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-163.svg'),
                            'permission' => $check_permission_super_admin,
                        ],
                        [
                            'name' => trans('latraining.history'),
                            'url' => route('module.modelhistory.index', [], false),
                            'icon' => asset('images/svg-backend/svgexport-162.svg'),
                            'permission' => User::canPermissionGeneralHistory(),
                            'url_item_child' => ['model-history','login-history','log-view-course'],
                            'id' => 'manager_2',
                            'item_childs' => [
                                [
                                    'name' => trans('lamenu.modelhistory'),
                                    'url' => route('module.modelhistory.index', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-163.svg'),
                                    'permission' => userCan('model-history')
                                ],
                                [
                                    'name' => trans('lamenu.login_history'),
                                    'url' => route('backend.login-history', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-164.svg'),
                                    'permission' => userCan('login-history')
                                ],
                                [
                                    'name' => trans('lamenu.log_view_course'),
                                    'url' => route('module.log.view.course.index', [], false),
                                    'icon' => asset('images/svg-backend/svgexport-165.svg'),
                                    'permission' => userCan('log-view-course')
                                ]
                            ]
                        ],
                        [
                            'name' => 'API',
                            'url' => route('backend.manual-api', [], false),
                            'icon' => asset('images/svg-backend/svgexport-165.svg'),
                            'url_name' => 'manual-api',
                            'permission' => $check_permission_super_admin,
                        ],
                        [
                            'name' => trans('lamenu.faq'),
                            'url' => route('module.faq', [], false),
                            'icon' => asset('images/svg-backend/svgexport-166.svg'),
                            'url_name' => 'faq',
                            'permission' => userCan('FAQ'),
                        ],
                        [
                            'name' => trans('lamenu.guide'),
                            'url' => route('backend.guide', [], false),
                            'icon' => asset('images/svg-backend/svgexport-167.svg'),
                            'url_name' => 'guide',
                            'permission' => userCan('guide'),
                        ],
                        [
                            'name' => trans('lamenu.coaching_teacher'),
                            'url' => route('module.coaching.backend',[], false),
                            'icon' => asset('images/svg-backend/svgexport-168.svg'),
                            'url_name' => 'coaching-teacher',
                            'permission' => userCan('coaching-teacher')
                        ],
                        [
                            'name' => trans('lamenu.compete_title'),
                            'url' => route('module.emulation_badge.list',[], false),
                            'icon' => asset('images/svg-backend/svgexport-148.svg'),
                            'url_name' => 'emulation-badge',
                            'permission' => userCan('emulation-badge')
                        ],
                    ],
                ],
                // XEM DS NHÂN VIÊN DÀNH CHO TRƯỞNG ĐƠN VỊ
                'user_unit_manager' => [
                    'id' => '16',
                    'name' => trans('lamenu.user'),
                    'url' => route('module.backend.user', [], false),
                    'icon' => asset('images/svg-backend/svgexport-152.svg'),
                    'permission' => User::isRoleLeader(),
                    'url_name' => 'user',
                    'url_child' => [],
                ],
            ];

            // dd($items);
            return Eventy::filter('backend.menu_left', $items);
//        });
    }

    public function trainingActivityReport() {
        if(Permission::isUnitManager()){
            $training_activity = [
                'BC12' => trans('lareport.report_title_12'), // 'Thống kê chi tiết học viên theo đơn vị'
                'BC05' => trans('lareport.report_title_5'), // 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến'
                'BC15' => trans('lareport.report_title_15'), //  'Báo cáo tổng hợp kết quả theo tháp đào tạo'
                'BC07' => trans('lareport.report_title_7'), // 'Báo cáo quá trình đào tạo của nhân viên'
            ];
        }else{
            $training_activity = [
                'BC09' => trans('lareport.report_title_9'), // 'Thống kê tình hình đào tạo nhân viên tân tuyển'
                'BC12' => trans('lareport.report_title_12'), // 'Thống kê chi tiết học viên theo đơn vị'
                'BC24' => trans('lareport.report_title_24'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo đơn vị'
                'BC05' => trans('lareport.report_title_5'), // 'Báo cáo học viên tham gia khóa học tập trung / trực tuyến'
                'BC06' => trans('lareport.report_title_6'), // 'Danh sách học viên của đơn vị theo chuyên đề'
                'BC25' => trans('lareport.report_title_25'), // 'Tổng hợp tình hình tham gia đào tạo các khóa E-Learning theo chuyên đề'
                'BC08' => trans('lareport.report_title_8'), // 'Tổng hợp tình hình tổ chức các khóa học nội bộ và bên ngoài'
                'BC11' => trans('lareport.report_title_11'), //  'Thống kê Giảng viên Đào tạo (Nội bộ & bên ngoài) theo Tháng / Quý / Năm'
                'BC23' => trans('lareport.report_title_23'), // 'Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh'
                'BC29' => trans('lareport.report_title_29'), //  'Báo cáo kết quả thực hiện so với kế hoạch quý / năm'
                'BC15' => trans('lareport.report_title_15'), //  'Báo cáo tổng hợp kết quả theo tháp đào tạo'
                'BC07' => trans('lareport.report_title_7'), // 'Báo cáo quá trình đào tạo của nhân viên'
                'BC10' => trans('lareport.report_title_10'), // 'Danh sách CBNV không chấp hành nội quy đào tạo'
                //'BC22' => trans('lareport.report_title_22'), // 'Danh sách các chuyên đề gộp / tách'
                'BC33' => trans('lareport.report_title_33'), // 'Danh sách khảo sát'
                'BC35' => trans('lareport.report_title_35'), // 'BÁO CÁO TÌNH HÌNH TỔ CHỨC ĐÀO TẠO E-LEARNING/TẬP TRUNG'
                'BC36' => trans('lareport.report_title_36'), // 'BÁO CÁO TỈ LỆ HỌC PHẦN THEO LỘ TRÌNH ĐÀO TẠO'
                'BC38' => trans('lareport.report_title_38'), // 'THỐNG KÊ TẤT CẢ NHÂN VIÊN THEO KHÓA HỌC'
            ];
        }

        return $training_activity;
    }

    public function quizManagerReport() {
        $quiz_manager = [
            'BC34' => trans('lareport.report_title_34'), //Báo cáo thống kê ngân hàng câu hỏi
            'BC04' => trans('lareport.report_title_4'), // 'Báo cáo tỉ lệ trả lời đúng từng câu hỏi trong ngân hàng câu hỏi'
            'BC01' => trans('lareport.report_title_1'), // 'Báo cáo số liệu công tác khảo thi'
            'BC02' => trans('lareport.report_title_2'), // 'Báo cáo số liệu điểm thi chi tiết'
            'BC28' => trans('lareport.report_title_28'), // 'Báo cáo kết quả chi tiết theo kỳ thi'
            'BC37' => trans('lareport.report_title_37'), // 'Báo cáo kết quả chi tiết tỷ lệ trả lời câu hỏi theo kỳ thi'
        ];
        return $quiz_manager;
    }

    public function costReport() {
        $cost = [
            'BC13' => trans('lareport.report_title_13'), //  'Báo cáo chi phí đào tạo theo khu vực'
            'BC17' => trans('lareport.report_title_17'), //  'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV tân tuyển'
            'BC18' => trans('lareport.report_title_18'), // 'Danh sách xác nhận bồi hoàn chi phí đào tạo đối với CBNV có cam kết'
            'BC26' => trans('lareport.report_title_26'), // 'Báo cáo thù lao giảng viên'
            'BC27' => trans('lareport.report_title_27'), //  'Báo cáo chi phí đào tạo'
        ];
        return $cost;
    }

    public function otherReport() {
        $other = [
            'BC31' => trans('lareport.report_title_31'), //  'Báo cáo tổng giờ học của học viên'
            'BC40' => trans('lareport.report_title_40'), //  'Báo cáo chi tiết tổng giờ học của học viên theo khóa học'
            'BC32' => trans('lareport.report_title_32'), //  'Báo cáo tổng giờ học theo từng đơn vị, chức danh'
            'BC16' => trans('lareport.report_title_16'), //  'Báo cáo lịch sử giảng dạy'
            'BC41' => 'Báo cáo đánh giá khung năng lực theo chức danh', // 'Báo cáo đánh giá khung năng lực theo chức danh'
        ];
        return $other;
    }
}
