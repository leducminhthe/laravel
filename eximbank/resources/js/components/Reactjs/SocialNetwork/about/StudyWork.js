import React, { useState, useEffect } from 'react';
import {
    PlusCircleOutlined,  
} from '@ant-design/icons';
import { DatePicker, Checkbox, Input, Button } from 'antd';

const StudyWork = () => {
    const { TextArea } = Input;
    const [company, setCompany] = useState('');
    const [position, setPosition] = useState('');
    const [cityWork, setCityWork] = useState('');
    const [description, setDescription] = useState('');
    const [workNow, setWorkNow] = useState(true);
    const [showAddWorkPlace, setShowAddWorkPlace] = useState(false);
    const [showAddSchool, setShowAddSchool] = useState(false);
    const [school, setSchool] = useState('');
    const [descriptionSchool, setDescriptionSchool] = useState('');
    const [graduateSchool, setGraduateSchool] = useState('');
    const [showAddUniversity, setShowAddUniversity] = useState(false);
    const [university, setUniversity] = useState('');
    const [descriptionUniversity, setDescriptionUniversity] = useState('');
    const [graduateUniversity, setGraduateUniversity] = useState('');
    
    const add = (type) => {
        if(type == 1) {
            setShowAddWorkPlace(!showAddWorkPlace)
        } else if (type == 2) {
            setShowAddSchool(!showAddSchool)
        } else {
            setShowAddUniversity(!showAddUniversity)
        } 
    }

    const onChange = (type, e) => {
        if(type == 1) {
            setWorkNow(e.target.checked)
        } else if (type == 2) {
            setGraduateSchool(e.target.checked)
        } else {
            setGraduateUniversity(e.target.checked)
        }
    }

    const onChangeFromHandle = (date, dateString) => {
        console.log(date, dateString);
    }

    const onChangeToHandle = (date, dateString) => {
        console.log(date, dateString);
    }

    return (
        <div className='row wrraped_overview'>
            <div className="col-12 wrraped_add_work_place mb-3">
                <h3><strong>Công việc</strong></h3>
                {
                    showAddWorkPlace == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={company}
                                onChange={(e) => setCompany(e.target.value)}
                                placeholder="Công ty" 
                            />
                            <Input 
                                className='mb-2'
                                value={position}
                                onChange={(e) => setPosition(e.target.value)}
                                placeholder="Chức vụ" 
                            />
                            <Input 
                                className='mb-2'
                                value={cityWork}
                                onChange={(e) => setCityWork(e.target.value)}
                                placeholder="Thành phố/Thị xã" 
                            />
                            <TextArea
                                className='mb-2'
                                value={description}
                                onChange={(e) => setDescription(e.target.value)}
                                placeholder="Mô tả"
                                autoSize={{ minRows: 2, maxRows: 3 }}
                            />
                            <p className='mb-2'><strong>Khoảng thời gian</strong></p>
                            <Checkbox className='mb-2' checked={workNow} onChange={(e) => onChange(1,e)}>Tôi đang làm việc ở đây</Checkbox>
                            <div className='choose_year mb-2'>
                                {
                                    workNow == false && (
                                        <>
                                            <DatePicker onChange={onChangeFromHandle} picker="year" allowClear placeholder='Năm'/>
                                            <span className='mx-2'>Đến</span>
                                        </>
                                        
                                    ) 
                                }
                                <DatePicker onChange={onChangeToHandle} picker="year" allowClear placeholder='Năm'/>
                            </div>
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <Button key="back" icon={<i className="fas fa-globe-asia mr-2"></i>}>
                                        Công khai
                                    </Button>
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(1)}>
                                        Hủy
                                    </Button>
                                    <Button className='ml-2' key="submit" type="primary">
                                        Lưu
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    showAddWorkPlace == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(1)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm nơi làm việc</span>
                        </div>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_university mb-3">
                <h3><strong>Cao đẳng/Đại học</strong></h3>
                {
                    showAddUniversity == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={university}
                                onChange={(e) => setUniversity(e.target.value)}
                                placeholder="Trường học" 
                            />
                            <p className='mb-2'><strong>Khoảng thời gian</strong></p>
                            <div className='choose_year mb-2'>
                                <DatePicker onChange={onChangeFromHandle} picker="year" allowClear placeholder='Năm'/>
                                <span className='mx-2'>Đến</span>
                                <DatePicker onChange={onChangeToHandle} picker="year" allowClear placeholder='Năm'/>
                            </div>
                            <Checkbox className='mb-2' checked={graduateUniversity} onChange={(e) => onChange(3, e)}>Đã tốt nghiệp</Checkbox>
                            <TextArea
                                className='mb-2'
                                value={descriptionUniversity}
                                onChange={(e) => setDescriptionUniversity(e.target.value)}
                                placeholder="Mô tả"
                                autoSize={{ minRows: 2, maxRows: 3 }}
                            />
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <Button key="back" icon={<i className="fas fa-globe-asia mr-2"></i>}>
                                        Công khai
                                    </Button>
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(3)}>
                                        Hủy
                                    </Button>
                                    <Button className='ml-2' key="submit" type="primary">
                                        Lưu
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    showAddUniversity == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(3)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm trường Cao đẵng/Đại học</span>
                        </div>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_school mb-3">
                <h3><strong>Trường trung học</strong></h3>
                {
                    showAddSchool == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={school}
                                onChange={(e) => setSchool(e.target.value)}
                                placeholder="Trường học" 
                            />
                            <p className='mb-2'><strong>Khoảng thời gian</strong></p>
                            <div className='choose_year mb-2'>
                                <DatePicker onChange={onChangeFromHandle} picker="year" allowClear placeholder='Năm'/>
                                <span className='mx-2'>Đến</span>
                                <DatePicker onChange={onChangeToHandle} picker="year" allowClear placeholder='Năm'/>
                            </div>
                            <Checkbox className='mb-2' checked={graduateSchool} onChange={(e) => onChange(2, e)}>Đã tốt nghiệp</Checkbox>
                            <TextArea
                                className='mb-2'
                                value={descriptionSchool}
                                onChange={(e) => setDescriptionSchool(e.target.value)}
                                placeholder="Mô tả"
                                autoSize={{ minRows: 2, maxRows: 3 }}
                            />
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <Button key="back" icon={<i className="fas fa-globe-asia mr-2"></i>}>
                                        Công khai
                                    </Button>
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(2)}>
                                        Hủy
                                    </Button>
                                    <Button className='ml-2' key="submit" type="primary">
                                        Lưu
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    showAddSchool == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(2)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm trường trung học</span>
                        </div>
                    )
                }
            </div>
        </div>
    );
};

export default StudyWork;