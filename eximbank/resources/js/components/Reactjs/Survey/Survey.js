import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import 'antd/dist/antd.css';
import { Empty, DatePicker, Select } from 'antd';
import {
    UserOutlined
} from '@ant-design/icons';

const Survey = ({ text }) => {
    const { Option } = Select;
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);
    const [dateStart, setDateStart] = useState('');
    const [dateEnd, setDateEnd] = useState('');
    const [status, setStatus] = useState('');

    const selectHandel = (e) => {
        e ? setStatus(e) : setStatus('');
    }

    function setDateStartHandle(date, dateString) {
        setDateStart(dateString);
    }

    function setDateEndHandle(date, dateString) {
        setDateEnd(dateString);
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/get-survey?page=1&dateStart=${dateStart}&dateEnd=${dateEnd}&status=${status}`)
                .then((response) => {
                    setData(response.data.surveys.data),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, [dateStart, dateEnd, status]);

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/get-survey?page=${page}&dateStart=${dateStart}&dateEnd=${dateEnd}&status=${status}`)
        return res;
    };

    const fetchData = async () => {
        if (data.length > 0) {
            const dataFormServer = await fetchDataScroll();
            setData([...data, ...dataFormServer.data.surveys.data]);
            if (dataFormServer.data.surveys.data.length === 0 || dataFormServer.data.surveys.data.length.length < 6) {
                sethasMore(false);
            }
            setPage(page + 1);
        }
    };

    return (
        <div className="sa4d25" id='survey_react'>
            <div className="container-fluid">
                <div className="row">
                    <div className="col-md-12 p-0">
                        <div className="_14d25 mb-4">
                            <div className="col-md-12 mb-4">
                                <div className="ibox-content forum-container">
                                    <h2 className="st_title">
                                        <a href="/">
                                            <i className="uil uil-apps"></i>
                                            <span>{text.home_page}</span>
                                        </a>
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{text.survey}</span>
                                    </h2>
                                </div>
                            </div>
                            <div className='col-12 mb-3'>
                                <div className='row'>
                                    <div className='col-md-3 col-12'>
                                        <DatePicker className="w-100 mb-2" onChange={setDateStartHandle} placeholder={text.start_date}/>
                                    </div>
                                    <div className='col-md-3 col-12'>
                                        <DatePicker className='w-100 mb-2' onChange={setDateEndHandle} placeholder={text.end_date}/>
                                    </div>
                                    <div className="col-12 col-md-3 mb-2">
                                        <Select className='w-100'
                                            showSearch
                                            allowClear
                                            placeholder={text.status}
                                            onChange={selectHandel}
                                        >
                                            <Option value="1">{text.do_not}</Option>
                                            <Option value="2">{text.did}</Option>
                                        </Select>
                                </div>
                                </div>
                            </div>
                            <div className="col-md-12">
                                {
                                    loading ? (
                                        <div className='row'>
                                            <div className="col-12 ajax-loading text-center mb-5">
                                                <div className="spinner-border" role="status">
                                                    <span className="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                    <>
                                    {
                                        data.length > 0 ? (
                                            <InfiniteScroll className="row m-0"
                                                dataLength={data.length}
                                                next={fetchData}
                                                hasMore={hasMore}
                                                style={{ overflow: 'unset'}}
                                            >
                                            <>
                                                {
                                                    data.map(item => (
                                                        <div key={item.id} className="col-lg-3 col-md-4 p-1">
                                                            <div className="fcrse_1 library">
                                                                <div className="fcrse_img">
                                                                    <img alt={item.name} className="lazy" src={item.image}/>
                                                                        {
                                                                            item.send == 1 && (
                                                                                <>
                                                                                    <div className="course-overlay">
                                                                                        <div className="badge_seller">{text.done}</div>
                                                                                    </div>
                                                                                    <div className="show_count_register">
                                                                                        <div className='count_register'>
                                                                                            <UserOutlined /> <span className='ml-1'>{ item.count_survey_user } {text.student}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </>
                                                                            )
                                                                        }
                                                                </div>
                                                                <div className="fcrse_content">
                                                                    <label className="crse14s">{item.name}</label>
                                                                    <div className="vdtodt">
                                                                        <span className="vdt14"><span className='font-weight-bold'>{text.time}: </span><br />
                                                                            <span className='font-weight-bold'>{item.start_date}</span>
                                                                            <span> {text.to} </span>
                                                                            <span className='font-weight-bold'>{item.end_date}</span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    {(() => {
                                                                        if (item.check == 1) {
                                                                            return (
                                                                                item.check_online != 1 ? (
                                                                                    <Link to={`/survey-react/user/${item.id}`} target="_blank" className="btn btn-danger float-right">{text.take_survey}</Link>
                                                                                ) : (
                                                                                    <Link to={`/survey-react/online/${item.id}/0`} target="_blank" className="btn btn-danger float-right">{text.take_survey}</Link>
                                                                                )
                                                                            )
                                                                        } else if (item.check == 2 ) {
                                                                            return (
                                                                                <button type="button" className="btn float-right">{text.survey_end}</button>
                                                                            )
                                                                        } else if (item.check == 3) {
                                                                            return (
                                                                                item.check_online != 1 ? (
                                                                                    <Link to={`/survey-react/edit-user/${item.id}`} target="_blank" className="btn btn-danger float-right">{text.view_survey}</Link>
                                                                                ) : (
                                                                                    <Link to={`/survey-react/edit-user-online/${item.id}/1`} target="_blank" className="btn btn-danger float-right">{text.view_survey}</Link>
                                                                                )
                                                                            )
                                                                        } else if (item.check == 4) {
                                                                            return (
                                                                                <button type="button" className="btn float-right">{text.survey_not_start_yet}</button>
                                                                            )
                                                                        } else {
                                                                            return (
                                                                                item.check_online != 1 ? (
                                                                                    <Link to={`/survey-react/edit-user/${item.id}`} target="_blank" className="btn btn-danger float-right">{text.edit_survey}</Link>
                                                                                ) : (
                                                                                    <Link to={`/survey-react/edit-user-online/${item.id}/1`} target="_blank" className="btn btn-danger float-right">{text.edit_survey}</Link>
                                                                                )
                                                                            )
                                                                        }
                                                                    })()}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ))
                                                }
                                            </>
                                            </InfiniteScroll>
                                        ) : (
                                            <div className='mb-4'>
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
                </div>
            </div>
        </div>
    );
};

export default Survey;
