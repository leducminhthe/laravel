import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Input, DatePicker, Empty, Select, Tooltip } from 'antd';
import {
    SearchOutlined,
    UserOutlined
} from '@ant-design/icons';

const Quiz = ({text}) => {
    const { Option } = Select;
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [search, setSearch] = useState('');
    const [type, setType] = useState('');
    const [quizTypes, setQuizTypes] = useState([]);

    const selectHandel = (e) => {
        e ? setType(e) : setType('');
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value)
    }

    const changeDateFrom = (date, dateString) => {
        setDateFrom(dateString);
    }

    const changeDateTo = (date, dateString) => {
        setDateTo(dateString);
    }

    useEffect(() => {
        const fetchDataQuizType = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-quiz-type`)
                .then((response) => {
                    setQuizTypes(response.data.quiz_types)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataQuizType();
    }, []);

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-quiz?dateFrom=${dateFrom}&dateTo=${dateTo}&search=${search}&type=${type}`)
            .then((response) => {
                setData(response.data.quizs),
                setLoading(false),
                countDowntQuiz();
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
    }, [dateFrom, dateTo, search, type]);

    const pad = (num, size) => {
        var s = "000000000" + num;
        return s.substr(s.length - size);
    }

    const countDowntQuiz = () => {
        $(".quiz_id").each(function() {
            var id = $(this).val();
            var time_quiz = $('.time_quiz_'+id).val();
            var check_date_do_quiz = $('.check_date_do_quiz_'+id).val();
            if (time_quiz == 0) {
                // if (check_date_do_quiz == 0) {
                //     $('.count_down_'+id).html($('.date_to_quiz_'+id).val());
                // } else {
                    var now = new Date(moment(new Date()).format("YYYY-MM-DD HH:mm:ss"));
                    setInterval(function () {
                        var quiz_id = id;
                        var count_down = $('.count_downt_quiz_'+quiz_id).val();
                        var count_time = moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                        now.setSeconds(now.getSeconds() + 1);

                        var distance = new Date(count_down) - now;

                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / (1000));

                        var time = pad(hours, 2) + ':' + pad(minutes, 2) + ':' + pad(seconds, 2) +'s';
                        $('.count_down_'+quiz_id).html(time);
                        if(count_time == count_down) {
                            location.reload();
                        }
                    }, 1000);
                // }
            } else {
                $('.count_down_'+id).html('00:00');
            }
        });

        $('#list-quiz').on('click', '.notify-goquiz', function () {
            show_message('Kỳ thi chưa tới giờ', 'warning');
        });
    }

    return (
        <div id="quiz-list">
            <div className="container-fluid">
                <div className="row mb-3">
                    <div className="col-md-12">
                        <div className="row m-0">
                            <Input className="col-12 col-md-3 mr-1 mb-1"
                                placeholder={text.enter_quiz}
                                prefix={<SearchOutlined />}
                                onPressEnter={(e) => handleKeypress(e)}
                            />
                            <DatePicker placeholder={text.start_date} className="col-12 col-md-2 mr-1 mb-1" onChange={changeDateFrom} />
                            <DatePicker placeholder={text.end_date} className="col-12 col-md-2 mr-1 mb-1" onChange={changeDateTo} />
                            <Select className="col-12 col-md-2 mb-1"
                                showSearch
                                allowClear
                                placeholder={text.choose_type_quiz}
                                onChange={selectHandel}
                                filterOption={(input, option) =>
                                    option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                }
                            >
                            {
                                quizTypes.map((quizType) => (
                                    <Option key={quizType.id} value={quizType.id}>{quizType.name}</Option>
                                ))
                            }
                            </Select>
                        </div>
                    </div>
                </div>
                <br />
                <div className="row" id="list-quiz">
                {
                    loading ? (
                        <div className="col-12 ajax-loading text-center mb-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div>
                    ) : (
                    <>
                        {
                            data.length > 0 ? (
                            <>
                            {
                                data.map((quiz) => (
                                    <div key={quiz.id} className="col-md-4">
                                        <div className="card mb-3">
                                            <div className="card-header">
                                                <div className="row">
                                                    <div className="col-3">
                                                        <img src={ quiz.icon_quiz } alt="" className="w-100"/>
                                                    </div>
                                                    <div className="quiz_card_name col-9">
                                                        <div className="quiz_p_name mb-0">
                                                            <Tooltip placement="bottom" color={'#2ecffc'} title={ quiz.quiz_name }>
                                                                <span>{ quiz.quiz_name }</span>
                                                            </Tooltip>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="img_quiz">
                                                <img className="card-img-top" src={ quiz.image }/>
                                                <div className="show_count_register">
                                                    <div className='count_register'>
                                                        <UserOutlined /> <span className='ml-1'>{ quiz.count_quiz_user } {text.student}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="card-body text-success">
                                                <input type="hidden" className="quiz_id" defaultValue={ quiz.id }/>
                                                <input type="hidden" className={`time_quiz_${quiz.id}`} defaultValue={ quiz.time_quiz }/>
                                                <input type="hidden" className={`count_downt_quiz_${quiz.id}`} defaultValue={ quiz.count_downt }/>
                                                <input type="hidden" className={`check_date_do_quiz_${quiz.id}`} defaultValue={ quiz.check_date_do_quiz }/>
                                                <input type="hidden" className={`date_to_quiz_${quiz.id}`} defaultValue={ quiz.date_to_quiz }/>
                                                <p className="card-text">{text.start}: { quiz.start_date }</p>
                                                <p className="card-text">{text.end}: { quiz.end_date ? quiz.end_date : '' }</p>
                                                <p className="card-text">{text.status}:
                                                    <span dangerouslySetInnerHTML={{ __html: quiz.status }}></span>
                                                </p>
                                            </div>
                                            <div className="card-footer bg-transparent">
                                                <div className="row">
                                                    <div className="col-5">
                                                        <div className="row">
                                                            <div className="col-5 icon_timestop">
                                                                <img src={ quiz.iconWatch } alt="" width="25px" height="25px"/>
                                                            </div>
                                                            <div className={`col-7 count_down_time_${quiz.id} pl-0 time_text_count_down`}>
                                                                <p className={`count_down_${quiz.id}`}></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="go_quiz col-7" dangerouslySetInnerHTML={{ __html: quiz.link }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            }
                            </>
                            ) : (
                                <div className='col-12 mb-4'>
                                    <Empty />
                                </div>
                            )
                        }
                    </>
                    )
                }
                </div>
            </div>
        </div>
    );
};

export default Quiz;
