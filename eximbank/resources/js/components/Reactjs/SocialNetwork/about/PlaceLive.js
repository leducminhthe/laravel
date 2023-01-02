import React, { useState, useEffect } from 'react';
import {
    PlusCircleOutlined,  
} from '@ant-design/icons';
import { DatePicker, Input, Button, Select } from 'antd';

const PlaceLive = () => {
    const { Option } = Select;
    const [showAddCity, setShowAddCity] = useState(false);
    const [city, setCity] = useState('');
    const [showAddCountry, setShowAddCountry] = useState(false);
    const [country, setCountry] = useState('');
    const [showAddOldCity, setShowAddOldCity] = useState(false);
    const [oldCity, setOldCity] = useState('');
    const [yearMove, setYearMove] = useState('');
    const [monthMove, setMonthMove] = useState('');
    const [dateMove, setDateMove] = useState('');
    
    const add = (type) => {
        if (type == 1) {
            setShowAddCity(!showAddCity)
        } else if (type == 2) {
            setShowAddCountry(!showAddCountry)
        } else {
            setShowAddOldCity(!showAddOldCity)
        }
    }

    const onChangeHandleYear = (date, dateString) => {
        console.log(date, dateString);
        setYearMove(dateString)
    }

    const handleChangeMonth = (value) => {
        console.log(`selected ${value}`);
        setMonthMove(value)
    }

    const handleChangeDate = (value) => {
        console.log(`selected ${value}`);
        setDateMove(value)
    }

    var months = [];
    for (let index = 1; index <= 12; index++) {
        months.push(index);
    }
    var dates = [];
    for (let index = 1; index <= 31; index++) {
        dates.push(index);
    }

    return (
        <div className='row wrraped_overview'>
            <h3 className='ml-3'><strong>Nơi từng sống</strong></h3>
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
                    showAddCity == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(1)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm tỉnh/thành phố hiện tại</span>
                        </div>
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
                    showAddCountry == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(2)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm quê quản</span>
                        </div>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_old_city mb-3">
                {
                    showAddOldCity == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={oldCity}
                                onChange={(e) => setOldCity(e.target.value)}
                                placeholder="Thành phố" 
                            />
                            <div className='choose_year mb-2 row'>
                                <div className="col-3 d_flex_align">
                                    <span>Ngày chuyển đi</span>
                                </div>
                                <div className="col-2 p-1">
                                    <DatePicker onChange={onChangeHandleYear} picker="year" allowClear placeholder='Năm'/>
                                </div>
                                <div className="col-2 p-1">
                                {
                                    yearMove && (
                                        <Select className='w-100' placeholder="Tháng" onChange={handleChangeMonth}>
                                            {
                                                months.map((month,key) => (
                                                    <Option key={key} value={month}>{ month }</Option>
                                                ))
                                            }
                                        </Select>
                                    )
                                }
                                </div>
                                <div className="col-2 p-1">
                                {
                                    monthMove && (
                                        <Select className='w-100' placeholder="Ngày" onChange={handleChangeDate}>
                                            {
                                                dates.map((date,key) => (
                                                    <Option key={key} value={date}>{ date }</Option>
                                                ))
                                            }
                                        </Select>
                                    )
                                }
                                </div>
                            </div>
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
                    showAddOldCity == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(3)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm nơi làm việc</span>
                        </div>
                    )
                }
            </div>
        </div>
    );
};

export default PlaceLive;