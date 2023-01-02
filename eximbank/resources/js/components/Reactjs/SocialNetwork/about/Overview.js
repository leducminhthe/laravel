import React, { useState, useEffect } from 'react';
import {
    PlusCircleOutlined,  
} from '@ant-design/icons';
import { DatePicker, Checkbox, Input, Button, Dropdown, Menu } from 'antd';
import ModalType from '../component/ModalType';
import Axios from 'axios';
import { useParams } from 'react-router-dom';    

const Overview = ({ auth, listFriend }) => {
    const { userId } = useParams();
    const { TextArea } = Input;
    const [company, setCompany] = useState('');
    const [position, setPosition] = useState('');
    const [cityWork, setCityWork] = useState('');
    const [description, setDescription] = useState('');
    const [workNow, setWorkNow] = useState(true);
    const [showAddWorkPlace, setShowAddWorkPlace] = useState(false);
    const [isModalWorkPlaceVisible, setIsModalWorkPlaceVisible] = useState(false);
    const [typeWorkPlace, setTypeWorkPlace] = useState(1);
    const [yearStartWork, setYearStartWork] = useState('');
    const [yearEndWork, setYearEndWork] = useState('');
    const [loadingWorkPlace, setLoadingWorkPlace] = useState(true);
    const [dataWorkPlace, setDataWorkPlace] = useState(false);
    const [idWorkPlace, setIdWorkPlace] = useState('');

    const [isModalUniversityVisible, setIsModalUniversityVisible] = useState(false);
    const [yearStartUniversity, setYearStartUniversity] = useState('');
    const [yearEndUniversity, setYearEndUniversity] = useState('');
    const [showAddUniversity, setShowAddUniversity] = useState(false);
    const [university, setUniversity] = useState('');
    const [descriptionUniversity, setDescriptionUniversity] = useState('');
    const [graduateUniversity, setGraduateUniversity] = useState(true);
    const [typeUniversity, setTypeUniversity] = useState(1);
    const [idUniversity, setIdUniversity] = useState('');
    const [dataUniversity, setDataUniversity] = useState(false);
    const [loadingUniversity, setLoadingUniversity] = useState(true);

    const [yearStartSchool, setYearStartSchool] = useState('');
    const [yearEndSchool, setYearEndSchool] = useState('');
    const [loadingSchool, setLoadingSchool] = useState(true);
    const [dataSchool, setDataSchool] = useState(false);
    const [idSchool, setIdSchool] = useState('');
    const [typeSchool, setTypeSchool] = useState(1);
    const [isModalSchoolVisible, setIsModalSchoolVisible] = useState(false);
    const [graduateSchool, setGraduateSchool] = useState(true);
    const [showAddSchool, setShowAddSchool] = useState(false);
    const [school, setSchool] = useState('');
    const [descriptionSchool, setDescriptionSchool] = useState('');

    const [city, setCity] = useState('');
    const [showAddCity, setShowAddCity] = useState(false);
    const [dataCity, setDataCity] = useState(false);
    const [idCity, setIdCity] = useState('');
    const [typeCity, setTypeCity] = useState(1);
    const [isModalCityVisible, setIsModalCityVisible] = useState(false);
    const [loadingCity, setLoadingCity] = useState(true);

    const [showAddCountry, setShowAddCountry] = useState(false);
    const [country, setCountry] = useState('');
    const [dataCountry, setDataCountry] = useState(false);
    const [idCountry, setIdCountry] = useState('');
    const [typeCountry, setTypeCountry] = useState(1);
    const [isModalCountryVisible, setIsModalCountryVisible] = useState(false);
    const [loadingCountry, setLoadingCountry] = useState(true);

    const config = {     
        headers: { 'content-type': 'multipart/form-data' }
    }
    
    const add = (type) => {
        if(type == 1) {
            setShowAddWorkPlace(!showAddWorkPlace)
        } else if (type == 2) {
            setShowAddSchool(!showAddSchool)
        } else if (type == 3) {
            setShowAddUniversity(!showAddUniversity)
        } else if (type == 4) {
            setShowAddCity(!showAddCity)
        } else {
            setShowAddCountry(!showAddCountry)
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

    const onChangeYearWorkStartHandle = (date, dateString) => {
        setYearStartWork(dateString)
    }

    const onChangeYearWorkEndHandle = (date, dateString) => {
        setYearEndWork(dateString)
    }

    const yearStartSchoolHandle = (date, dateString) => {
        setYearStartSchool(dateString)
    }

    const yearEndSchoolHandle = (date, dateString) => {
        setYearEndSchool(dateString)
    }

    const yearStartUniversityHandle = (date, dateString) => {
        setYearStartUniversity(dateString)
    }

    const yearEndUniversityHandle = (date, dateString) => {
        setYearEndUniversity(dateString)
    }

    // NƠI LÀM VIỆC
    const showModalWorkPlace = () => {
        setIsModalWorkPlaceVisible(true);
    };
    const handleOkWorkPlace = () => {
        setIsModalWorkPlaceVisible(false);
    };
    const handleCancelWorkPlace = () => {
        setIsModalWorkPlaceVisible(false);
    };
    const saveWorkPlace = async () => {
        try {
            const items = await Axios.post(`/save-work-place`,{ company, position, cityWork, description, workNow, yearStartWork, yearEndWork, typeWorkPlace, idWorkPlace })
            .then((response) => {
                setLoadingWorkPlace(true)
                fetchDataWorkPlace()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const deleteWorkPlace = async () => {
        try {
            const items = await Axios.post(`/delete-work-place`,{ idWorkPlace })
            .then((response) => {
                setCompany('')
                setPosition('')
                setCityWork('')
                setDescription('')
                setWorkNow(true)
                setYearStartWork('')
                setYearEndWork('')
                setTypeWorkPlace(1)
                setIdWorkPlace('')
                setDataWorkPlace(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const fetchDataWorkPlace = async () => {
        try {
            const items = await Axios.get(`/data-work-place/${userId}/0`)
            .then((response) => {
                if(response.data.work_place) {
                    setCompany(response.data.work_place.company)
                    setPosition(response.data.work_place.position)
                    setCityWork(response.data.work_place.city)
                    setDescription(response.data.work_place.description)
                    setWorkNow(response.data.work_place.status == 1 ? true : false)
                    setYearStartWork(response.data.work_place.year_start)
                    setYearEndWork(response.data.work_place.year_end)
                    setTypeWorkPlace(response.data.work_place.type)
                    setIdWorkPlace(response.data.work_place.id)
                    setDataWorkPlace(true)
                    setShowAddWorkPlace(false)
                }
                setLoadingWorkPlace(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    //////////

    // TRƯỜNG HỌC
    const showModalSchool = () => {
        setIsModalSchoolVisible(true);
    };
    const handleOkSchool = () => {
        setIsModalSchoolVisible(false);
    };
    const handleCancelSchool = () => {
        setIsModalSchoolVisible(false);
    };
    const saveSchool = async () => {
        const data = new FormData() 
        data.append('name', school)
        data.append('description', descriptionSchool)
        data.append('graduate', graduateSchool)
        data.append('yearStart', yearStartSchool)
        data.append('yearEnd', yearEndSchool)
        data.append('type', typeSchool)
        data.append('id', idSchool)
        data.append('type_study', 0)
        try {
            const items = await Axios.post(`/save-study`, data, config)
            .then((response) => {
                setLoadingSchool(true)
                fetchDataSchool()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const deleteSchool = async () => {
        let id = idSchool
        try {
            const items = await Axios.post(`/delete-study`,{ id })
            .then((response) => {
                setSchool('')
                setDescriptionSchool('')
                setGraduateSchool(true)
                setYearStartSchool('')
                setYearEndSchool('')
                setTypeSchool('')
                setIdSchool('')
                setDataSchool(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const fetchDataSchool = async () => {
        try {
            const items = await Axios.get(`/data-study/${userId}/0/0`)
            .then((response) => {
                if(response.data.study) {
                    setSchool(response.data.study.name)
                    setDescriptionSchool(response.data.study.description)
                    setGraduateSchool(response.data.study.status == 1 ? true : false)
                    setYearStartSchool(response.data.study.year_start)
                    setYearEndSchool(response.data.study.year_end)
                    setTypeSchool(response.data.study.type)
                    setIdSchool(response.data.study.id)
                    setDataSchool(true)
                    setShowAddSchool(false)
                }
                setLoadingSchool(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    /////////////

    // CAO ĐẲNG/ĐẠI HỌC
    const showModalUniversity = () => {
        setIsModalUniversityVisible(true);
    };
    const handleOkUniversity = () => {
        setIsModalUniversityVisible(false);
    };
    const handleCancelUniversity = () => {
        setIsModalUniversityVisible(false);
    };
    const saveUniversity = async () => {
        const data = new FormData() 
        data.append('name', university)
        data.append('description', descriptionUniversity)
        data.append('graduate', graduateUniversity)
        data.append('yearStart', yearStartUniversity)
        data.append('yearEnd', yearEndUniversity)
        data.append('type', typeUniversity)
        data.append('id', idUniversity)
        data.append('type_study', 1)
        try {
            const items = await Axios.post(`/save-study`, data, config)
            .then((response) => {
                setLoadingUniversity(true)
                fetchDataUniversity()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const deleteUniversity = async () => {
        let id = idCity
        try {
            const items = await Axios.post(`/delete-study`,{ id })
            .then((response) => {
                setUniversity('')
                setDescriptionUniversity('')
                setGraduateUniversity(true)
                setYearStartUniversity('')
                setYearEndUniversity('')
                setTypeUniversity('')
                setIdUniversity('')
                setDataUniversity(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const fetchDataUniversity = async () => {
        try {
            const items = await Axios.get(`/data-study/${userId}/1/0`)
            .then((response) => {
                if(response.data.study) {
                    setUniversity(response.data.study.name)
                    setDescriptionUniversity(response.data.study.description)
                    setGraduateUniversity(response.data.study.status == 1 ? true : false)
                    setYearStartUniversity(response.data.study.year_start)
                    setYearEndUniversity(response.data.study.year_end)
                    setTypeUniversity(response.data.study.type)
                    setIdUniversity(response.data.study.id)
                    setDataUniversity(true)
                    setShowAddUniversity(false)
                }
                setLoadingUniversity(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    ///////////

    // THÀNH PHỐ
    const showModalCity = () => {
        setIsModalCityVisible(true);
    };
    const handleOkCity = () => {
        setIsModalCityVisible(false);
    };
    const handleCancelCity = () => {
        setIsModalCityVisible(false);
    };
    const saveCity = async () => {
        try {
            const items = await Axios.post(`/save-city`,{ city, typeCity, idCity })
            .then((response) => {
                setLoadingCity(true)
                fetchDataCity()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const deleteCity = async () => {
        try {
            const items = await Axios.post(`/delete-city`,{ idCity })
            .then((response) => {
                setCity('')
                setTypeCity(1)
                setIdCity('')
                setDataCity(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const fetchDataCity = async () => {
        try {
            const items = await Axios.get(`/data-city/${userId}/0`)
            .then((response) => {
                if(response.data.city) {
                    setCity(response.data.city.city)
                    setTypeCity(response.data.city.type)
                    setIdCity(response.data.city.id)
                    setDataCity(true)
                    setShowAddCity(false)
                }
                setLoadingCity(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    ///////////

    // QUÊ QUÁN
    const showModalCountry = () => {
        setIsModalCountryVisible(true);
    };
    const handleOkCountry = () => {
        setIsModalCountryVisible(false);
    };
    const handleCancelCountry = () => {
        setIsModalCountryVisible(false);
    };
    const saveCountry = async () => {
        try {
            const items = await Axios.post(`/save-country`,{ country, typeCountry, idCountry })
            .then((response) => {
                setLoadingCountry(true)
                fetchDataCountry()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const deleteCountry = async () => {
        try {
            const items = await Axios.post(`/delete-country`,{ idCountry })
            .then((response) => {
                setCountry('')
                setTypeCountry(1)
                setIdCountry('')
                setDataCountry(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    const fetchDataCountry = async () => {
        try {
            const items = await Axios.get(`/data-country/${userId}/0`)
            .then((response) => {
                if(response.data.country) {
                    setCountry(response.data.country.country)
                    setTypeCountry(response.data.country.type)
                    setIdCountry(response.data.country.id)
                    setDataCountry(true)
                    setShowAddCountry(false)
                }
                setLoadingCountry(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    ///////////

    useEffect(() => {
        fetchDataWorkPlace()
        fetchDataSchool()
        fetchDataCity()
        fetchDataUniversity()
        fetchDataCountry()
    }, [])

    const menu = (
        <Menu>
            <Menu.Item key="0">
                <div onClick={(e) => add(1)}>
                    <i className="far fa-edit mr-2"></i><span>Chỉnh sửa nơi làm việc</span>
                </div>
            </Menu.Item>
            <Menu.Item key="1">
                <div onClick={deleteWorkPlace}>
                    <i className="far fa-trash-alt mr-2"></i><span>Xóa nơi làm việc</span>
                </div>
            </Menu.Item>
        </Menu>
    );

    const menuSchool = (
        <Menu>
            <Menu.Item key="0">
                <div onClick={(e) => add(2)}>
                    <i className="far fa-edit mr-2"></i><span>Chỉnh sửa trường học</span>
                </div>
            </Menu.Item>
            <Menu.Item key="1">
                <div onClick={deleteSchool}>
                    <i className="far fa-trash-alt mr-2"></i><span>Xóa trường học</span>
                </div>
            </Menu.Item>
        </Menu>
    );

    const menuUniversity = (
        <Menu>
            <Menu.Item key="0">
                <div onClick={(e) => add(3)}>
                    <i className="far fa-edit mr-2"></i><span>Chỉnh sửa Cao đẳng/Đại học</span>
                </div>
            </Menu.Item>
            <Menu.Item key="1">
                <div onClick={deleteUniversity}>
                    <i className="far fa-trash-alt mr-2"></i><span>Xóa Cao đẳng/Đại học</span>
                </div>
            </Menu.Item>
        </Menu>
    );

    const menuCity = (
        <Menu>
            <Menu.Item key="0">
                <div onClick={(e) => add(4)}>
                    <i className="far fa-edit mr-2"></i><span>Chỉnh sửa tỉnh/thành phố hiện tại</span>
                </div>
            </Menu.Item>
            <Menu.Item key="1">
                <div onClick={deleteCity}>
                    <i className="far fa-trash-alt mr-2"></i><span>Xóa tỉnh/thành phố hiện tại</span>
                </div>
            </Menu.Item>
        </Menu>
    );

    const menuCountry = (
        <Menu>
            <Menu.Item key="0">
                <div onClick={(e) => add(5)}>
                    <i className="far fa-edit mr-2"></i><span>Chỉnh sửa quê quán</span>
                </div>
            </Menu.Item>
            <Menu.Item key="1">
                <div onClick={deleteCountry}>
                    <i className="far fa-trash-alt mr-2"></i><span>Xóa quê quán</span>
                </div>
            </Menu.Item>
        </Menu>
    );
    
    return (
        <div className='row wrraped_overview'>
            <div className="col-12 wrraped_add_work_place mb-3">
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
                                <DatePicker defaultValue={yearStartWork ? moment(yearStartWork, 'YYYY') : ''} onChange={onChangeYearWorkStartHandle} picker="year" allowClear placeholder='Năm'/>
                                {
                                    workNow == false && (
                                        <>
                                            <span className='mx-2'>Đến</span>
                                            <DatePicker defaultValue={yearEndWork ? moment(yearEndWork, 'YYYY') : ''} onChange={onChangeYearWorkEndHandle} picker="year" allowClear placeholder='Năm'/>
                                        </>
                                    ) 
                            }
                            </div>
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <ModalType isModalVisible={isModalWorkPlaceVisible} 
                                        handleOk={handleOkWorkPlace} 
                                        handleCancel={handleCancelWorkPlace} 
                                        setType={setTypeWorkPlace} 
                                        type={typeWorkPlace} 
                                        onClickShowModal={showModalWorkPlace}
                                        listFriend={listFriend}
                                    />
                                </div>
                                <div className="col-6 text-right">
                                    <Button onClick={(e) => add(1)}>
                                        Hủy
                                    </Button>
                                    {
                                        loadingWorkPlace ? (
                                            <Button className='ml-2' type="primary" loading>
                                                Lưu
                                            </Button>
                                        ) : (
                                            <Button className='ml-2' type="primary" onClick={saveWorkPlace}>
                                                Lưu
                                            </Button>
                                        )
                                    }
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    !loadingWorkPlace && (
                        <>
                        {
                            dataWorkPlace ? (
                                <>
                                {
                                    showAddWorkPlace == false && (
                                        <div className='row wrapped_info'>
                                            <div className="col-1 pr-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            </div>
                                            <div className='col-9 pl-1'>
                                                <p className='mb-0'>{company}</p>
                                                <p>{yearStartWork} {yearEndWork ? (' đến '+ yearEndWork) : ' đến nay'}</p>
                                            </div>
                                            <div className="col-1">
                                                <span>
                                                    {
                                                        typeWorkPlace == 1 ? (
                                                            <i className="fas fa-globe-asia"></i>
                                                        ) : typeWorkPlace == 2 ? (
                                                            <i className="fas fa-user-friends"></i>
                                                        ) : (
                                                            <i className="fas fa-lock"></i>
                                                        )
                                                    }
                                                </span>
                                            </div>
                                            <div className="col-1 pl-0 icon_setting">
                                                <Dropdown overlay={menu} placement="bottomRight" trigger={['click']}>
                                                    <i className="fas fa-ellipsis-h cursor_pointer" onClick={e => e.preventDefault()}></i>
                                                </Dropdown>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            ) : (
                                <>
                                    {
                                        showAddWorkPlace == false && (
                                            <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(1)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm nơi làm việc</span>
                                            </div>
                                        )
                                    }
                                </>
                            )
                        }
                        </>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_school mb-3">
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
                                <DatePicker defaultValue={yearStartSchool ? moment(yearStartSchool, 'YYYY') : ''} onChange={yearStartSchoolHandle} picker="year" allowClear placeholder='Năm'/>
                                <span className='mx-2'>Đến</span>
                                <DatePicker defaultValue={yearEndSchool ? moment(yearEndSchool, 'YYYY') : ''} onChange={yearEndSchoolHandle} picker="year" allowClear placeholder='Năm'/>
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
                                    <ModalType isModalVisible={isModalSchoolVisible} 
                                        handleOk={handleOkSchool} 
                                        handleCancel={handleCancelSchool} 
                                        setType={setTypeSchool} 
                                        type={typeSchool} 
                                        onClickShowModal={showModalSchool}
                                        listFriend={listFriend}
                                    />
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(2)}>
                                        Hủy
                                    </Button>
                                    {
                                        loadingSchool ? (
                                            <Button className='ml-2' type="primary" loading>
                                                Lưu
                                            </Button>
                                        ) : (
                                            <Button className='ml-2' type="primary" onClick={saveSchool}>
                                                Lưu
                                            </Button>
                                        )
                                    }
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    !loadingSchool && (
                        <>
                        {
                            dataSchool ? (
                                <>
                                {
                                    showAddSchool == false && (
                                        <div className='row wrapped_info'>
                                            <div className="col-1 pr-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            </div>
                                            <div className='col-9 pl-1'>
                                                <p className='mb-0'>{school}</p>
                                                <p>Niên khóa {yearStartSchool} - {yearEndSchool}</p>
                                            </div>
                                            <div className="col-1">
                                                <span>
                                                    {
                                                        typeSchool == 1 ? (
                                                            <i className="fas fa-globe-asia"></i>
                                                        ) : typeSchool == 2 ? (
                                                            <i className="fas fa-user-friends"></i>
                                                        ) : (
                                                            <i className="fas fa-lock"></i>
                                                        )
                                                    }
                                                </span>
                                            </div>
                                            <div className="col-1 pl-0 icon_setting">
                                                <Dropdown overlay={menuSchool} placement="bottomRight" trigger={['click']}>
                                                    <i className="fas fa-ellipsis-h cursor_pointer" onClick={e => e.preventDefault()}></i>
                                                </Dropdown>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            ) : (
                                <>
                                    {
                                        showAddSchool == false && (
                                            <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm trường trung học</span>
                                            </div>
                                        )
                                    }
                                </>
                            )
                        }
                        </>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_university mb-3">
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
                                <DatePicker defaultValue={yearStartUniversity ? moment(yearStartUniversity, 'YYYY') : ''} onChange={yearStartUniversityHandle} picker="year" allowClear placeholder='Năm'/>
                                <span className='mx-2'>Đến</span>
                                <DatePicker defaultValue={yearEndUniversity ? moment(yearEndUniversity, 'YYYY') : ''} onChange={yearEndUniversityHandle} picker="year" allowClear placeholder='Năm'/>
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
                                    <ModalType isModalVisible={isModalUniversityVisible} 
                                        handleOk={handleOkUniversity} 
                                        handleCancel={handleCancelUniversity} 
                                        setType={setTypeUniversity} 
                                        type={typeUniversity} 
                                        onClickShowModal={showModalUniversity}
                                        listFriend={listFriend}
                                    />
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(3)}>
                                        Hủy
                                    </Button>
                                    {
                                        loadingUniversity ? (
                                            <Button className='ml-2' type="primary" loading>
                                                Lưu
                                            </Button>
                                        ) : (
                                            <Button className='ml-2' type="primary" onClick={saveUniversity}>
                                                Lưu
                                            </Button>
                                        )
                                    }
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    !loadingUniversity && (
                        <>
                        {
                            dataUniversity ? (
                                <>
                                {
                                    showAddUniversity == false && (
                                        <div className='row wrapped_info'>
                                            <div className="col-1 pr-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            </div>
                                            <div className='col-9 pl-1'>
                                                <p className='mb-0'>{university}</p>
                                                <p>Niên khóa {yearStartUniversity} - {yearEndUniversity}</p>
                                            </div>
                                            <div className="col-1">
                                                <span>
                                                    {
                                                        typeUniversity == 1 ? (
                                                            <i className="fas fa-globe-asia"></i>
                                                        ) : typeUniversity == 2 ? (
                                                            <i className="fas fa-user-friends"></i>
                                                        ) : (
                                                            <i className="fas fa-lock"></i>
                                                        )
                                                    }
                                                </span>
                                            </div>
                                            <div className="col-1 pl-0 icon_setting">
                                                <Dropdown overlay={menuUniversity} placement="bottomRight" trigger={['click']}>
                                                    <i className="fas fa-ellipsis-h cursor_pointer" onClick={e => e.preventDefault()}></i>
                                                </Dropdown>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            ) : (
                                <>
                                    {
                                        showAddUniversity == false && (
                                            <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(3)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm trường Cao đẵng/Đại học</span>
                                            </div>
                                        )
                                    }
                                </>
                            )
                        }
                        </>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_city mb-3">
                {
                    showAddCity == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={city}
                                onChange={(e) => setCity(e.target.value)}
                                placeholder="Tỉnh/Thành phố hiện tại" 
                            />
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <ModalType isModalVisible={isModalCityVisible} 
                                        handleOk={handleOkCity} 
                                        handleCancel={handleCancelCity} 
                                        setType={setTypeCity} 
                                        type={typeCity} 
                                        onClickShowModal={showModalCity}
                                        listFriend={listFriend}
                                    />  
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(4)}>
                                        Hủy
                                    </Button>
                                    {
                                        loadingCity ? (
                                            <Button className='ml-2' type="primary" loading>
                                                Lưu
                                            </Button>
                                        ) : (
                                            <Button className='ml-2' type="primary" onClick={saveCity}>
                                                Lưu
                                            </Button>
                                        )
                                    }
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    !loadingCity && (
                        <>
                        {
                            dataCity ? (
                                <>
                                {
                                    showAddCity == false && (
                                        <div className='row wrapped_info'>
                                            <div className="col-1 pr-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            </div>
                                            <div className='col-9 pl-1'>
                                                <p className='mb-0'>Sống tại {city}</p>
                                            </div>
                                            <div className="col-1">
                                                <span>
                                                    {
                                                        typeCity == 1 ? (
                                                            <i className="fas fa-globe-asia"></i>
                                                        ) : typeCity == 2 ? (
                                                            <i className="fas fa-user-friends"></i>
                                                        ) : (
                                                            <i className="fas fa-lock"></i>
                                                        )
                                                    }
                                                </span>
                                            </div>
                                            <div className="col-1 pl-0 icon_setting">
                                                <Dropdown overlay={menuCity} placement="bottomRight" trigger={['click']}>
                                                    <i className="fas fa-ellipsis-h cursor_pointer" onClick={e => e.preventDefault()}></i>
                                                </Dropdown>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            ) : (
                                <>
                                    {
                                        showAddCity == false && (
                                            <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(4)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm tỉnh/thành phố hiện tại</span>
                                            </div>
                                        )
                                    }
                                </>
                            )
                        }
                        </>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_country mb-3">
                {
                    showAddCountry == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={country}
                                onChange={(e) => setCountry(e.target.value)}
                                placeholder="Quê quán" 
                            />
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <ModalType isModalVisible={isModalCountryVisible} 
                                        handleOk={handleOkCountry} 
                                        handleCancel={handleCancelCountry} 
                                        setType={setTypeCountry} 
                                        type={typeCountry} 
                                        onClickShowModal={showModalCountry}
                                        listFriend={listFriend}
                                    />
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(5)}>
                                        Hủy
                                    </Button>
                                    {
                                        loadingCountry ? (
                                            <Button className='ml-2' type="primary" loading>
                                                Lưu
                                            </Button>
                                        ) : (
                                            <Button className='ml-2' type="primary" onClick={saveCountry}>
                                                Lưu
                                            </Button>
                                        )
                                    }
                                </div>
                            </div>
                        </div>
                    )
                }
                {
                    !loadingCountry && (
                        <>
                        {
                            dataCountry ? (
                                <>
                                {
                                    showAddCountry == false && (
                                        <div className='row wrapped_info'>
                                            <div className="col-1 pr-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            </div>
                                            <div className='col-9 pl-1'>
                                                <p className='mb-0'>Đến từ {country}</p>
                                            </div>
                                            <div className="col-1">
                                                <span>
                                                    {
                                                        typeCountry == 1 ? (
                                                            <i className="fas fa-globe-asia"></i>
                                                        ) : typeCountry == 2 ? (
                                                            <i className="fas fa-user-friends"></i>
                                                        ) : (
                                                            <i className="fas fa-lock"></i>
                                                        )
                                                    }
                                                </span>
                                            </div>
                                            <div className="col-1 pl-0 icon_setting">
                                                <Dropdown overlay={menuCountry} placement="bottomRight" trigger={['click']}>
                                                    <i className="fas fa-ellipsis-h cursor_pointer" onClick={e => e.preventDefault()}></i>
                                                </Dropdown>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            ) : (
                                <>
                                    {
                                        showAddCountry == false && (
                                            <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(5)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm quê quản</span>
                                            </div>
                                        )
                                    }
                                </>
                            )
                        }
                        </>
                    )
                }
            </div>
        </div>
    );
};

export default Overview;