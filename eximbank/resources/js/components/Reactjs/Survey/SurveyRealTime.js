import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import Axios from 'axios';
import { Card } from 'antd';

const SurveyRealTime = ({text}) => {
    let navigate = useNavigate();
    const { id } = useParams();
    const { type } = useParams();
    const [item, setItem] = useState('');
    const [profile, setProfile] = useState('');
    const [question, setQuestion] = useState('');
    const [loading, setLoading] = useState(true);
    const [save, setSave] = useState('');
    const [send, setSend] = useState('');
    const [answers, setAnswers] = useState([]);
    const [userAnswer, setUserAnswer] = useState(0);

    const saveFrom = () => {
        setSave(1);
    }

    const sendFrom = () => {
        setSend(1);
    }

    const chooseAnswer = (id, multiple, list_answer) => {
        setUserAnswer(1);
        if(multiple == 1) {
            var index = answers.indexOf(id)
            if (index !== -1) {
                answers.splice(index, 1);
                setAnswers([...answers]);
            } else {
                setAnswers([...answers, id])
            }
            var newArray = answers;
        } else {
            var newArray = answers;
            newArray = newArray.filter(item => list_answer.indexOf(item) === -1);
            newArray.push(id);
            setAnswers(newArray)
        }
    }

    useEffect(() => {
        if (userAnswer == 1) {
            const userAnswerSurvey = async () => {
                try {
                    var survey_id = $("input[name='survey_id']").val();
                    const items = await Axios.post(`/save-survey-answer-online`,{ answers, survey_id })
                } catch (error) {
                    console.error("Error: " + error.message);
                }
            }
    
            userAnswerSurvey();
        }
    }, [answers]);

    const handleSubmit = async (e) => {
        if (send == 1) {
            $('input[name=send]').val(1);
            const btn = $('#send');
            const btnText = btn.html();
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> ' + btnText);
        }
        if (save == 1) {
            const btn = $('#save_form');
            const btnText = btn.html();
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> ' + btnText);
        }
        e.preventDefault();
        try {
            var survey_id = $("input[name='survey_id']").val();
            const items = await Axios.post(`/save-survey-online`,{ send, survey_id })
            .then((response) => {
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    navigate('/survey-react');
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            if(type == 0) {
                try {
                    const items = await Axios.get(`/get-survey-online/${id}`)
                    .then((response) => {
                        setItem(response.data.item),
                        setProfile(response.data.profile),
                        setQuestion(response.data.questions),
                        setLoading(false)
                    })
                } catch (error) {
                    console.error("Error: " + error.message);
                }
            } else {
                try {
                    const items = await Axios.get(`/edit-survey-user-online/${id}`)
                    .then((response) => {
                        setItem(response.data.item),
                        setProfile(response.data.profile),
                        setQuestion(response.data.questions),
                        setAnswers(response.data.user_answers),
                        setLoading(false)
                    })
                } catch (error) {
                    console.error("Error: " + error.message);
                }
            }
            
        }

        fetchDataItem();
    }, []);
    
    return (
        <div className="container-fluid sa4d25" id="survey_online">
            <div className="fcrse_2">
                <div className="_14d25">
                    <div className="row">
                        <div className="col-md-12">
                            {/* <h2 className="st_title"><i className="uil uil-apps"></i>
                                <Link to="/survey-react">{text.survey}</Link>
                                <i className="uil uil-angle-right"></i>
                                <span className="font-weight-bold">{item.name}</span>
                            </h2> */}
                            <Card title={item.name}>
                                <p>
                                    {profile.full_name} ({profile.code})
                                    </p>
                                <p>
                                    {text.title}: {profile.title_name}
                                </p>
                                <p>
                                    {text.unit}: {profile.unit_name}
                                </p>
                            </Card>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-12 col-md-12">
                            <form onSubmit={handleSubmit} id="form_submit">
                                <input type="hidden" name="survey_user_id" defaultValue=""/>
                                <input type="hidden" name="survey_id" defaultValue={item.id}/>
                                <input type="hidden" name="template_id" defaultValue={item.template_id}/>

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
                                                <div className="wrapped_all">
                                                    {
                                                        question.map((question) => (
                                                            <div key={question.id} className="row mx-0 mb-2">
                                                                <div className="col-12 title mb-1">
                                                                    <h3>{ question.question }</h3>
                                                                </div>
                                                                <div className="col-12">
                                                                    {
                                                                        question.answers.map((answer) => (
                                                                            <div key={answer.id}>
                                                                            {
                                                                                item.send == 0 || type == 0 ? (
                                                                                    <div className={`wrapped_answer ${ answers.includes(answer.id) ? 'choose_answer' : null}`} onClick={(e) => chooseAnswer(answer.id, question.multiple, question.all_answer)}>
                                                                                        { answer.answer }
                                                                                    </div>
                                                                                ) : (
                                                                                    <div className={`wrapped_answer ${ answers.includes(answer.id) ? 'choose_answer' : null}`}>
                                                                                        { answer.answer }
                                                                                    </div>
                                                                                )
                                                                            }
                                                                            </div>
                                                                            
                                                                        ))
                                                                    }
                                                                </div>
                                                            </div>
                                                        ))
                                                    }
                                                </div>
                                            )
                                        }
                                        {
                                            (item.send == 0 || type == 0) && (
                                                <div className="card-footer text-center">
                                                    <Link to="/survey-react" className="btn">{text.close}</Link>
                                                    <button type="submit" id='save_form' onClick={saveFrom} className="btn"><i className="fa fa-save"></i> {text.save}</button>
                                                    <button type="submit" onClick={sendFrom} id="send" className="btn"><i className="fa fa-location-arrow"></i> {text.send}</button>
                                                    <input type="hidden" name="send" value="0" />
                                                </div>
                                            ) 
                                        }
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default SurveyRealTime;
