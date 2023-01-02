import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import { Input, Empty, Checkbox, DatePicker, Modal } from 'antd';

const Course = () => {
    const { type } = useParams();
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [courseType, setCourseType] = useState([]);
    const [courseStatus, setCourseStatus] = useState([]);
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [search, setSearch] = useState("");
    const [modalTrainingProgram, setModalTrainingProgram] = useState(false);
    const [modalLevelSubject, setModalLevelSubject] = useState(false);
    const [trainingProgram, setTrainnigProgram] = useState('');

    useEffect(() => {
        const fetchDataItem = async () => {
            try {
                const items = await Axios.get(`/api/v1/training_program`)
                .then((response) => {
                    console.log(response);
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    const showTrainingProgram = () => {
        setModalTrainingProgram(true);
    };

    const handleCancel = () => {
        setModalTrainingProgram(false);
    };
    
    
    const showLevelSubject = () => {
        setModalLevelSubject(true);
    };

    const handleCancelLevelSubject = () => {
        setModalLevelSubject(false);
    };

    const filterShow = () => {

    }

    const changeDateFrom = (date, dateString) => {
        setDateFrom(dateString);
    }

    const changeDateTo = (date, dateString) => {
        setDateTo(dateString);
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value) 
    }

    const courseTypeOptions = [
        { label: 'Khóa học online', value: '1' },
        { label: 'Khóa học tập trung', value: '2' },
        { label: 'Khóa học của tôi', value: '3' },
        { label: 'Khóa đang học', value: '4' },
        { label: 'Khóa học đánh dấu', value: '5' },
    ];

    const courseStatusOptions = [
        { label: 'Đăng ký', value: '1' },
        { label: 'Đang học', value: '2' },
        { label: 'Chờ duyệt', value: '3' },
        { label: 'Hoàn thành', value: '4' },
        { label: 'Đã kết thúc', value: '5' },
    ];

    const onChangeCourseType = (checkedValues) => {
        console.log('checked = ', checkedValues);
    }

    const onChangeCourseStatus = (checkedValues) => {
        console.log('checked = ', checkedValues);
    }
    return (
        <div className="container-fluid" id='all_course'>
            <div className="row">
                <div className="col-md-12">
                    <div className="_14d25">
                        <div className="row mb-3">
                            <div className="col-md-12">
                                <div className="ibox-content forum-container">
                                    <h2 className="st_title">
                                        <i className="uil uil-apps"></i>
                                        <Link to="">Khóa học </Link>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-12">
                                <div className="_14d25 mt-1 mb-2">
                                    <div className="row">
                                        <div className="col-6 col-md-3">
                                            <div>
                                                <h3>Khóa học</h3>
                                                <Checkbox.Group options={courseTypeOptions} 
                                                    defaultValue={courseType} 
                                                    onChange={onChangeCourseType} 
                                                />
                                            </div>
                                        </div>
                                        <div className="col-6 col-md-3 mb-2">
                                            <div>
                                                <h3>Trạng thái</h3>
                                                <Checkbox.Group options={courseStatusOptions} 
                                                    defaultValue={courseStatus} 
                                                    onChange={onChangeCourseStatus} 
                                                />
                                            </div>
                                        </div>
                                        <div className="col-6 col-md-3 mb-2">
                                            <h3>Danh mục</h3>
                                            <div className="search_training_program mb-3">
                                                <span onClick={showTrainingProgram}>Chủ đề</span>
                                            </div>
                                            <Modal title="Chủ đề" 
                                                visible={modalTrainingProgram} 
                                                onCancel={handleCancel}
                                            >
                                                <p>Some contents...</p>
                                                <p>Some contents...</p>
                                                <p>Some contents...</p>
                                            </Modal>
                                            <div className="search_level_subject">
                                                <span onClick={showLevelSubject}>Học phần</span>
                                            </div>
                                            <Modal title="Học phần" 
                                                visible={modalLevelSubject} 
                                                onCancel={handleCancelLevelSubject}
                                            >
                                                <p>124</p>
                                                <p>Some contents...</p>
                                                <p>Some contents...</p>
                                            </Modal>
                                        </div>
                                        <div className="col-6 col-md-3 mb-2">
                                            <h3>Thời gian</h3>
                                            <div className="mb-2">
                                                <div className="ui left input swdh11">
                                                    <DatePicker onChange={changeDateFrom} />
                                                </div>
                                            </div>
                                            <div className="mb-2">
                                                <div className="ui left input swdh11">
                                                    <DatePicker onChange={changeDateTo} />
                                                </div>
                                            </div>
                                            <div className="mb-2">
                                                <div className="ui left input swdh11">
                                                    <Input placeholder="Tìm kiếm" 
                                                        onPressEnter={(e) => handleKeypress(e)}  
                                                    />
                                                </div>
                                            </div>
                                            <div className="float-right filter_show">
                                                <div className="" onClick={filterShow}>
                                                    <span>Hình thức hiển thị</span>
                                                    <img src="" width="25px" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-12">
                                <div className="_14d25 mt-1">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Course;