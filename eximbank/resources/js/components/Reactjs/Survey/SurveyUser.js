import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import Axios from 'axios';
import serialize from 'form-serialize';
import { Card } from 'antd';

const SurveyUser = ({ text }) => {
    let navigate = useNavigate();
    const { id } = useParams();
    const queryParams = new URLSearchParams(window.location.search)
    const courseId = queryParams.get("courseId")
    const courseType = queryParams.get("courseType")
    const trainingProgramId = queryParams.get("trainingProgramId")
    const [item, setItem] = useState('');
    const [profile, setProfile] = useState('');
    const [loading, setLoading] = useState(true);
    const [categories, setCategories] = useState('');
    const [save, setSave] = useState('');
    const [send, setSend] = useState('');

    const datepicker = () => {
        $('.question-datepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });
    }

    $(".sortable_type_sort").sortable({
        update : function () {
            $('input.answer-item-sort').each(function(idx) {
                $(this).val(idx + 1);
            });
        }
    });

    $(".sortable_type_sort").disableSelection();

    const saveFrom = () => {
        setSave(1);
    }

    const sendFrom = () => {
        setSend(1);
    }

    const handleSubmit = async (e) => {
        $('input[name=course_id]').val(courseId);
        $('input[name=course_type]').val(courseType);
        $('input[name=trainingProgramId]').val(trainingProgramId);

        if (send == 1) {
            $('input[name=send]').val(1);
            var btn = $('#send');
            var btnText = btn.html();
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> ' + btnText);
        }
        if (save == 1) {
            var btn = $('#save_form');
            var btnText = btn.html();
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> ' + btnText);
        }
        e.preventDefault();
        const form = e.currentTarget
        const body = serialize(form, {hash: true, empty: true})
        console.log('submitted!', body)
        try {
            const items = await Axios.post(`/save-survey-user/`,body)
            .then((response) => {
                console.log(response);
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    if(courseId) {
                        window.location.href = response.data.redirect_url
                    } else {
                        navigate(response.data.redirect_url);
                    }
                } else {
                    btn.prop("disabled", false).html(btnText);
                }
            })
        } catch (error) {
            btn.prop("disabled", false).html(btnText);
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/get-survey-user/${id}/${courseId}/${courseType}`)
                .then((response) => {
                    if(response.data.url_edit){
                        navigate(response.data.url_edit);
                    }
                    setItem(response.data.item),
                    setProfile(response.data.profile),
                    setCategories(response.data.categories),
                    setLoading(false),
                    datepicker()
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    const isCheckRadio = (id) => {
        $('#image_'+id).closest('.item-answer').find('img').attr("src", "/images/btn_radio.png");

        $('#image_'+id).attr("src", "/images/btn_radio_check.png");

        $('#image_'+id).closest('.item-answer').find('.text_answer').prop('hidden', true);
        $('#image_'+id).closest('.item-answer').find('.text_answer').val('');

        $('#text_answer_'+id).prop('hidden', false);
    }

    const isCheckRadioMatrix = (id) => {
        $('#image_'+id).closest('tr').find('img').attr("src", "/images/btn_radio.png");

        $('#image_'+id).attr("src", "/images/btn_radio_check.png");
    }

    const isCheckCheckbox = (id) => {
        const checkImg = $('#image_'+id).attr("src");
        if(checkImg == "/images/btn_checkbox_check.png"){
            $('#image_'+id).attr("src", "/images/btn_checkbox.png");
            $('#text_answer_'+id).prop('hidden', true);
            $('#text_answer_'+id).val('');
        }else{
            $('#image_'+id).attr("src", "/images/btn_checkbox_check.png");
            $('#text_answer_'+id).prop('hidden', false);
        }

    }

    const isCheckRadioRank = (ans_key, ques_id) => {
        $('#ques_rank_'+ques_id).find('img').attr("src", "/images/heart_1.png");

        for(var i = 0; i <= ans_key; i++){
            $('#ques_rank_'+ques_id+ ' .img_'+i).attr("src", "/images/heart_check.png");
        }
    }

    return (
        <div className="container-fluid sa4d25" id="survey_list">
             <div className="row" id='first-info-user'>
                <div className="col-6 col-md-3">
                    <img src={ item.image } className="w-100" />
                </div>
                <div className="col-6 col-md-9">
                    <div className="header_right">
                        <a className="opts_account">
                            <img src={ profile.image } />
                        </a>
                        <div className='name_user'>{profile.full_name}</div>
                        <div className='name_user'>{text.user_code}: {profile.code}</div>
                        <div className='name_user'>Email: {profile.email}</div>
                        <div className='name_user'>{text.unit}: {profile.unit_name}</div>
                    </div>
                </div>
                <div className="col-12 p-0 text-center mt-3" id="second-name-survey">
                    <p>{ text.welcome_to_survey } <br/> {item.name}</p>
                </div>
            </div>
            <div className="">
                <form onSubmit={handleSubmit} id="form_submit">
                    <input type="hidden" name="survey_user_id" defaultValue=""/>
                    <input type="hidden" name="survey_id" defaultValue={item.id}/>
                    <input type="hidden" name="template_id" defaultValue={item.template_id}/>
                    <input type="hidden" name="course_id" defaultValue=""/>
                    <input type="hidden" name="course_type" defaultValue=""/>
                    <input type="hidden" name="trainingProgramId" defaultValue=""/>

                    <div className="certi_form mt-3">
                        <div className="all_ques_lest">
                            {
                                loading ? (
                                    <div className='row'>
                                        <div className="col-12 ajax-loading text-center m-5">
                                            <div className="spinner-border" role="status">
                                                <span className="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                ) : (
                                    categories.map((category) => (
                                        <div key={category.id}>
                                            <input type="hidden" name="user_category_id[]" defaultValue=""/>
                                            <input type="hidden" name="category_id[]" defaultValue={category.id}/>
                                            <input type="hidden" name={`category_name[ ${ category.id } ]`} defaultValue={ category.name }/>

                                            <div className="ques_item mb-3">
                                                <h3 className="mb-0">{ category.nameStr }</h3>
                                                <hr className="mt-1" />
                                            </div>
                                            {
                                                category.questions.map((question, index) => (
                                                    <div key={question.id}>
                                                        <input type="hidden" name={`user_question_id[ ${category.id} ][]`} defaultValue=""/>
                                                        <input type="hidden" name={`question_id[ ${category.id} ][]`} defaultValue={ question.id }/>
                                                        <input type="hidden" name={`question_code[ ${category.id} ][ ${question.id} ]`} defaultValue={ question.code }/>
                                                        <input type="hidden" name={`question_name[ ${category.id} ][ ${question.id} ]`} defaultValue={ question.name }/>
                                                        <input type="hidden" name={`type[ ${category.id} ][ ${question.id} ]`} defaultValue={ question.type }/>
                                                        <input type="hidden" name={`multiple[ ${category.id} ][ ${question.id} ]`} defaultValue={ question.multiple }/>

                                                        <div className="ques_item mb-2">
                                                            <div className="ques_title ml-1 mb-1">
                                                                <span className="text_white_night">{ index + 1 }. {question.name}</span>
                                                            </div>
                                                            {(() => {
                                                                if (question.type == "essay") {
                                                                    return (
                                                                        <div className="ui search focus">
                                                                            <div className="ui form swdh30">
                                                                                <div className="field">
                                                                                    <textarea className="w-100" rows="3"
                                                                                        name={`answer_essay[ ${category.id} ][ ${question.id} ]`}
                                                                                        placeholder={text.content}
                                                                                    />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == "dropdown") {
                                                                    return (
                                                                        <div className="ui form">
                                                                            <div className="grouped fields item-answer">
                                                                                <select defaultValue=""
                                                                                    name={`answer_essay[ ${category.id} ][ ${question.id} ]`}
                                                                                    className="form-control select2"
                                                                                    data-placeholder={text.choose_answer}
                                                                                >
                                                                                    <option value=""></option>
                                                                                    {
                                                                                        question.answers.map((answer) => (
                                                                                            <option key={answer.id} value={ answer.id }>{ answer.name }</option>
                                                                                        ))
                                                                                    }
                                                                                </select>
                                                                                {
                                                                                    question.answers.map((answer) => (
                                                                                        <div key={answer.id}>
                                                                                            <input type="hidden"
                                                                                                name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                defaultValue=""
                                                                                            />
                                                                                            <input type="hidden"
                                                                                                name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                defaultValue={answer.id}
                                                                                            />
                                                                                            <input type="hidden"
                                                                                                name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                defaultValue={ answer.code }
                                                                                            />
                                                                                            <input type="hidden"
                                                                                                name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                defaultValue={ answer.name }
                                                                                            />
                                                                                            <input type="hidden"
                                                                                                name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                defaultValue={ answer.is_text}
                                                                                            />
                                                                                            <input type="hidden"
                                                                                                name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                defaultValue={ answer.is_row }
                                                                                            />
                                                                                        </div>
                                                                                    ))
                                                                                }
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == "time") {
                                                                    return (
                                                                        <div className="ui form">
                                                                            <div className="grouped fields item-answer">
                                                                                <input type="text"
                                                                                    name={`answer_essay[ ${category.id} ][ ${question.id} ]`}
                                                                                    className="form-control question-datepicker w-auto"
                                                                                    placeholder="ngày / tháng / năm"
                                                                                    defaultValue=""
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == 'matrix' || question.type == 'matrix_text') {
                                                                    return (
                                                                        <div className="ui form">
                                                                            <div className="grouped fields item-answer">
                                                                                <table className="tDefault table table-bordered table-responsive">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            {
                                                                                                question.answer_row_col ? (
                                                                                                    <th>
                                                                                                        <input type="hidden"
                                                                                                            name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue=""
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue={question.answer_row_col.id}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_code[ ${category.id} ][ ${question.id} ][${question.answer_row_col.id}]`}
                                                                                                            defaultValue= {question.answer_row_col.code}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_name[ ${category.id} ][ ${question.id} ][${question.answer_row_col.id}]`}
                                                                                                            defaultValue={question.answer_row_col.name}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_text[ ${category.id} ][ ${question.id} ][${question.answer_row_col.id}]`}
                                                                                                            defaultValue={question.answer_row_col.is_text}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_row[ ${category.id} ][ ${question.id} ][${question.answer_row_col.id}]`}
                                                                                                            defaultValue={question.answer_row_col.is_row}
                                                                                                        />
                                                                                                        {question.answer_row_col.name}
                                                                                                    </th>
                                                                                                ) : (
                                                                                                    <th>#</th>
                                                                                                )
                                                                                            }
                                                                                            {
                                                                                                question.cols.map((answer_col) => (
                                                                                                    <th key={answer_col.id} className='text-center'>
                                                                                                        <input type="hidden"
                                                                                                            name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue=""
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue={answer_col.id}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer_col.id} ]`}
                                                                                                            defaultValue={answer_col.code}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer_col.id} ]`}
                                                                                                            defaultValue={answer_col.name}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer_col.id} ]`}
                                                                                                            defaultValue={answer_col.is_text}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer_col.id} ]`}
                                                                                                            defaultValue={answer_col.is_row}
                                                                                                        />
                                                                                                       { answer_col.name }
                                                                                                    </th>
                                                                                                ))
                                                                                            }
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        {
                                                                                            question.rows.map((answer_row) => (
                                                                                                <tr key={answer_row.id}>
                                                                                                    <th className='text_white_night'>
                                                                                                        <input type="hidden"
                                                                                                            name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue=""
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                            defaultValue={answer_row.id}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ]`}
                                                                                                            defaultValue={answer_row.code} />
                                                                                                        <input type="hidden"
                                                                                                            name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ]`}
                                                                                                            defaultValue={answer_row.name}
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ]`}
                                                                                                            defaultValue={answer_row.is_text }
                                                                                                        />
                                                                                                        <input type="hidden"
                                                                                                            name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ]`}
                                                                                                            defaultValue={answer_row.is_row }
                                                                                                        />
                                                                                                        {answer_row.name}
                                                                                                    </th>
                                                                                                    {
                                                                                                        question.cols.map((answer_col) => (
                                                                                                            <th className="text-center" key={answer_col.id}>
                                                                                                                {
                                                                                                                    answer_col.matrix_anser_code && (
                                                                                                                        <input type="hidden"
                                                                                                                            name={`answer_matrix_code[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ][ ${answer_col.id} ]`}
                                                                                                                            defaultValue={ answer_col.matrix_anser_code.code }
                                                                                                                        />
                                                                                                                    )
                                                                                                                }
                                                                                                                {
                                                                                                                    question.type == 'matrix' ? (
                                                                                                                        <>
                                                                                                                            <input type={question.multiple != 1 ? 'radio' : 'checkbox'}
                                                                                                                                name={`check_answer_matrix[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ][]`}
                                                                                                                                tabIndex="0"
                                                                                                                                className="hidden"
                                                                                                                                defaultValue={ answer_col.id }
                                                                                                                                id={`is_check_${answer_row.id}_${answer_col.id}`}
                                                                                                                                hidden
                                                                                                                            />
                                                                                                                            <label htmlFor={`is_check_${answer_row.id}_${answer_col.id}`} >
                                                                                                                            {
                                                                                                                                question.multiple != 1 ? (
                                                                                                                                    <img src="/images/btn_radio.png"
                                                                                                                                        className="img-choise"
                                                                                                                                        id={`image_is_check_${answer_row.id}_${answer_col.id}`}
                                                                                                                                        onClick={() => isCheckRadioMatrix(`is_check_${answer_row.id}_${answer_col.id}`)}
                                                                                                                                    />
                                                                                                                                ) : (
                                                                                                                                    <img src="/images/btn_checkbox.png"
                                                                                                                                        className="img-choise"
                                                                                                                                        id={`image_is_check_${answer_row.id}_${answer_col.id}`}
                                                                                                                                        onClick={() => isCheckCheckbox(`is_check_${answer_row.id}_${answer_col.id}`)}
                                                                                                                                    />
                                                                                                                                )
                                                                                                                            }
                                                                                                                            </label>
                                                                                                                        </>
                                                                                                                    ) : (
                                                                                                                        <textarea rows="1"
                                                                                                                            name={`answer_matrix[ ${category.id} ][ ${question.id} ][ ${answer_row.id} ][]`}
                                                                                                                            className="form-control w-100"
                                                                                                                        />
                                                                                                                    )
                                                                                                                }
                                                                                                            </th>
                                                                                                        ))
                                                                                                    }
                                                                                                </tr>
                                                                                            ))
                                                                                        }
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == 'rank') {
                                                                    return (
                                                                        <div className="ui form">
                                                                            <div className="grouped fields item-answer">
                                                                                <table className="tDefault table table-responsive" id={`ques_rank_${question.id}`}>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                        {
                                                                                            question.answers.map((answer, ans_key) => (
                                                                                                <th className="text-center  border-top-0" key={answer.id}>
                                                                                                    <input type="hidden"
                                                                                                        name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                        defaultValue=""
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                        defaultValue={answer.id}
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.code }
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.name }
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.is_text}
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.is_row }
                                                                                                    />
                                                                                                    <>
                                                                                                        <input type='radio'
                                                                                                            name={`answer_essay[ ${category.id} ][ ${question.id} ]`}
                                                                                                            tabIndex="0"
                                                                                                            className="hidden"
                                                                                                            defaultValue={ answer.id }
                                                                                                            id={`is_check_${answer.id}`}
                                                                                                            hidden
                                                                                                        />
                                                                                                        <label htmlFor={`is_check_${answer.id}`} className="text_white_night">
                                                                                                            <img src="/images/heart_1.png"
                                                                                                                className={`img-choise img_${ans_key} w-50px`}
                                                                                                                onClick={() => isCheckRadioRank(ans_key,question.id)}
                                                                                                            /> <br/>
                                                                                                            { answer.name }
                                                                                                        </label>
                                                                                                    </>
                                                                                                </th>
                                                                                            ))
                                                                                        }
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == 'rank_icon') {
                                                                    return (
                                                                        <div className="ui form">
                                                                            <div className="grouped fields item-answer">
                                                                                <table className="tDefault table table-responsive" id={`ques_rank_icon_${question.id}`}>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                        {
                                                                                            question.answers.map((answer, ans_key) => (
                                                                                                <th className="text-center  border-top-0" key={answer.id}>
                                                                                                    <input type="hidden"
                                                                                                        name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                        defaultValue=""
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                                        defaultValue={answer.id}
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.code }
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.name }
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.is_text}
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.is_row }
                                                                                                    />
                                                                                                    <input type="hidden"
                                                                                                        name={`answer_icon[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        defaultValue={ answer.icon }
                                                                                                    />
                                                                                                    <>
                                                                                                        <input type='radio'
                                                                                                            name={`answer_essay[ ${category.id} ][ ${question.id} ]`}
                                                                                                            tabIndex="0"
                                                                                                            className="hidden"
                                                                                                            defaultValue={ answer.id }
                                                                                                            id={`is_check_${answer.id}`}
                                                                                                            hidden
                                                                                                        />
                                                                                                        <label htmlFor={`is_check_${answer.id}`} >
                                                                                                            <img src="/images/btn_radio.png"
                                                                                                                className="img-choise mr-1"
                                                                                                                id={`image_is_check_${answer.id}`}
                                                                                                                onClick={() => isCheckRadio(`is_check_${answer.id}`)}
                                                                                                            />
                                                                                                        </label>
                                                                                                        <label htmlFor={`is_check_${answer.id}`} onClick={() => isCheckRadio(`is_check_${answer.id}`)} className="text_white_night">
                                                                                                            <span className='answer_rank_icon' id={`icon_${answer.id}`}>{ answer.icon }</span>
                                                                                                            <br/>
                                                                                                            { answer.name }
                                                                                                        </label>
                                                                                                    </>
                                                                                                </th>
                                                                                            ))
                                                                                        }
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                } else if (question.type == 'sort') {
                                                                    return (
                                                                        <div className="ui form ml-3">
                                                                            <ul className="grouped fields item-answer sortable_type_sort">
                                                                            {
                                                                                question.answers.map((answer, num_sort) => (
                                                                                    <div key={answer.id}>
                                                                                        <input type="hidden"
                                                                                            name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                            defaultValue=""
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                            defaultValue={answer.id}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.code}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.name}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.is_text}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.is_row}
                                                                                        />

                                                                                        <li className="field fltr-radio m-0">
                                                                                            <div className="ui">
                                                                                                <div className="form-inline mb-1">
                                                                                                    <span className="mr-1 text_white_night"> { answer.name }</span>
                                                                                                    <input type="text"
                                                                                                        name={`text_answer[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                        className="answer-item-sort form-control w-5"
                                                                                                        defaultValue={ num_sort + 1 }
                                                                                                    />
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                    </div>
                                                                                ))
                                                                            }
                                                                            </ul>
                                                                        </div>
                                                                    )
                                                                } else {
                                                                    return (
                                                                        <div className="ui form ml-3">
                                                                            <ul className="grouped fields item-answer">
                                                                            {
                                                                                question.answers.map((answer) => (
                                                                                    <div key={answer.id}>
                                                                                        <input type="hidden"
                                                                                            name={`user_answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                            defaultValue=""
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_id[ ${category.id} ][ ${question.id} ][]`}
                                                                                            defaultValue={answer.id}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_code[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.code}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`answer_name[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.name}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`is_text[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.is_text}
                                                                                        />
                                                                                        <input type="hidden"
                                                                                            name={`is_row[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                            defaultValue={answer.is_row}
                                                                                        />

                                                                                        {
                                                                                            question.type == 'text' && (
                                                                                                <div className="field fltr-radio m-0">
                                                                                                    <div className="ui">
                                                                                                        <div className="input-group d-flex align-items-center mb-1">
                                                                                                            <span className="mr-1 text_white_night">{ answer.name }</span>
                                                                                                            <textarea rows="1"
                                                                                                                name={`text_answer[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                                className="form-control w-auto"
                                                                                                            />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            )
                                                                                        }
                                                                                        {
                                                                                            (question.type == 'number' || question.type == 'percent') && (
                                                                                                <div className="field fltr-radio m-0">
                                                                                                    <div className="ui">
                                                                                                        <div className="form-inline mb-1">
                                                                                                            <span className="mr-1 text_white_night">{ answer.name }</span>
                                                                                                            <input type="text"
                                                                                                                name={`text_answer[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                                className="form-control w-5"
                                                                                                            />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            )
                                                                                        }
                                                                                        {
                                                                                            question.type == 'choice' && (
                                                                                                <div className="field fltr-radio m-0">
                                                                                                    <div className="ui mb-2">
                                                                                                        {
                                                                                                            question.multiple != 1 ? (
                                                                                                                <>
                                                                                                                    <input type="radio"
                                                                                                                        name={`is_check[ ${category.id} ][ ${question.id} ]`}
                                                                                                                        id={`is_check_${answer.id}`}
                                                                                                                        tabIndex="0"
                                                                                                                        className="hidden"
                                                                                                                        defaultValue={ answer.id }
                                                                                                                        hidden
                                                                                                                    />
                                                                                                                    <label htmlFor={`is_check_${answer.id}`} >
                                                                                                                        <img src="/images/btn_radio.png"
                                                                                                                            className="img-choise"
                                                                                                                            id={`image_is_check_${answer.id}`}
                                                                                                                            onClick={() => isCheckRadio(`is_check_${answer.id}`)}
                                                                                                                        />
                                                                                                                    </label>
                                                                                                                    <label htmlFor={`is_check_${answer.id}`}
                                                                                                                            onClick={() => isCheckRadio(`is_check_${answer.id}`)}
                                                                                                                            className="mb-0 ml-1 text_white_night">
                                                                                                                            { answer.name }
                                                                                                                    </label>
                                                                                                                </>
                                                                                                            ) : (
                                                                                                                <>
                                                                                                                    <input type="checkbox"
                                                                                                                        name={`is_check[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                                        id={`is_check_${answer.id}`}
                                                                                                                        tabIndex="0"
                                                                                                                        className="hidden"
                                                                                                                        defaultValue={ answer.id }
                                                                                                                        hidden
                                                                                                                    />
                                                                                                                        <label htmlFor={`is_check_${answer.id}`} >
                                                                                                                        <img src="/images/btn_checkbox.png"
                                                                                                                            className="img-choise"
                                                                                                                            id={`image_is_check_${answer.id}`}
                                                                                                                            onClick={() => isCheckCheckbox(`is_check_${answer.id}`)}
                                                                                                                        />
                                                                                                                    </label>
                                                                                                                    <label htmlFor={`is_check_${answer.id}`}
                                                                                                                        onClick={() => isCheckCheckbox(`is_check_${answer.id}`)}
                                                                                                                        className="mb-0 ml-1 text_white_night">
                                                                                                                            { answer.name }
                                                                                                                    </label>
                                                                                                                </>
                                                                                                            )
                                                                                                        }
                                                                                                        {
                                                                                                            answer.is_text == 1 && (
                                                                                                                <input type="text"
                                                                                                                    name={`text_answer[ ${category.id} ][ ${question.id} ][ ${answer.id} ]`}
                                                                                                                    className="form-control text_answer" hidden
                                                                                                                    id={`text_answer_is_check_${answer.id}`}
                                                                                                                />
                                                                                                            )
                                                                                                        }
                                                                                                    </div>
                                                                                                </div>
                                                                                            )
                                                                                        }
                                                                                    </div>
                                                                                ))
                                                                            }
                                                                            </ul>
                                                                        </div>
                                                                    )
                                                                }
                                                            })()}
                                                        </div>
                                                    </div>
                                                ))
                                            }
                                        </div>
                                    ))
                                )
                            }
                            <hr />
                            {
                                !loading && item.more_suggestions != 0 && item.type == 1 && (
                                    <>
                                        <span>{text.other_suggest}</span>
                                        <div className="row">
                                            <div className="col-sm-12">
                                                <textarea className="w-100 form-control" name="more_suggestions" rows="5" placeholder={text.content}></textarea>
                                            </div>
                                        </div>
                                    </>
                                )
                            }
                            <div className="card-footer text-center">
                                <Link to="/survey-react" className="btn">{text.close}</Link>
                                {
                                    item.type == 1 && (
                                        <button type="submit" id='save_form' onClick={saveFrom} className="btn"><i className="fa fa-save"></i> {text.save}</button>
                                    )
                                }
                                <button type="submit" onClick={sendFrom} id="send" className="btn"><i className="fa fa-location-arrow"></i> {text.send}</button>
                                <input type="hidden" name="send" value="0" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default SurveyUser;
