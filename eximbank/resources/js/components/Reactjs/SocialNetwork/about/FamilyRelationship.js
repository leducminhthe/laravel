import React, { useState, useEffect } from 'react';
import {
    PlusCircleOutlined,  
} from '@ant-design/icons';
import { DatePicker, Checkbox, Input, Button, Select, AutoComplete } from 'antd';

const FamilyRelationship = ({ listFriend }) => {
    const { Option } = Select;
    const [showAddRelationship, setShowAddRelationship] = useState(false);
    const [showAddFamily, setShowAddFamily] = useState(false);
    const [school, setSchool] = useState('');
    const [showAddUniversity, setShowAddUniversity] = useState(false);
    const [showAddCity, setShowAddCity] = useState(false);
    const [showAddCountry, setShowAddCountry] = useState(false);
    const [showAddTextRelationship, setShowAddTextRelationship] = useState(false);

    const add = (type) => {
        if(type == 1) {
            setShowAddRelationship(!showAddRelationship)
            setShowAddTextRelationship(false)
        } else {
            setShowAddFamily(!showAddFamily)
        }
    }

    const handleChange = (value) => {
        if(value > 1 && value < 9) {
            setShowAddTextRelationship(true)
        } else {
            setShowAddTextRelationship(false)
        }
        console.log(`selected ${value}`);
    }

    const handleChangeFamily = (value) => {
        console.log(`selected ${value}`);
    }

    const options = [];
    useEffect(() => {
        if(listFriend.length > 0) {
            listFriend.map((friend) => {
                var option = {
                    'label': <div><img src={friend.avatar} alt="" width={'30px'}/><span className='ml-2'>{friend.user_name}</span></div>,
                    'value' : friend.user_name
                }
                return options.push(option)
            })
        }
    }, [listFriend])
    
    const onBlurRelationshipHandle = (e) => {
        console.log(e.target.value);
    }

    const onBlurFamilyHandle = (e) => {
        console.log(e.target.value);
    }

    return (
        <div className='row wrraped_overview'>
            <h3 className='ml-3'><strong>Mối quan hệ</strong></h3>
            <div className="col-12 wrraped_add_relationship mb-3">
                {
                    showAddRelationship == true && (
                        <div className='add mb-3'>
                            <Select className='w-100' defaultValue="0" onChange={handleChange}>
                                <Option value="0">Trạng thái</Option>
                                <Option value="1">Độc thân</Option>
                                <Option value="2">Hẹn hò</Option>
                                <Option value="3">Đã đính hôn</Option>
                                <Option value="4">Đã kết hôn</Option>
                                <Option value="5">Chung sống</Option>
                                <Option value="6">Chung sống có đăng ký</Option>
                                <Option value="7">Tìm hiểu</Option>
                                <Option value="8">Có mối quan hệ phức tạp</Option>
                                <Option value="9">Đã ly thân</Option>
                                <Option value="10">Đã ly hôn</Option>
                                <Option value="11">Góa</Option>
                            </Select>
                            {
                                showAddTextRelationship && (
                                    <AutoComplete
                                        className='w-100 mt-3'
                                        options={options}
                                        placeholder="Bạn đời"
                                        onBlur={onBlurRelationshipHandle}
                                        filterOption={(inputValue, option) =>
                                            option.value.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1
                                        }
                                    />
                                )
                            }
                            <p className='mt-3 mb-0'>Thay đổi sẽ không xuất hiện trong Bảng tin</p>
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
                    showAddRelationship == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(1)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm Mối quan hệ</span>
                        </div>
                    )
                }
            </div>
            <h3 className='ml-3'><strong>Thành viên trong gia đình</strong></h3>
            <div className="col-12 wrraped_add_family mb-3">
                {
                    showAddFamily == true && (
                        <div className='add mb-3'>
                            <AutoComplete
                                className='w-100 mb-3'
                                options={options}
                                placeholder="Thành viên gia đình"
                                onBlur={(e) => onBlurFamilyHandle(e)}
                                filterOption={(inputValue, option) =>
                                    option.value.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1
                                }
                            />
                            <Select placeholder="Mối quan hệ" className='w-100' onChange={handleChangeFamily}>
                                <Option value="0">Mối quan hệ</Option>
                                <Option value="1">Mẹ</Option>
                                <Option value="2">Bố</Option>
                                <Option value="3">Con trai</Option>
                                <Option value="4">Con gái</Option>
                                <Option value="5">Cháu gái</Option>
                                <Option value="6">Cháu trai</Option>
                                <Option value="7">Em gái</Option>
                                <Option value="8">Chị gái</Option>
                                <Option value="9">Em trai</Option>
                                <Option value="10">Anh trai</Option>
                                <Option value="11">Bà ngoại</Option>
                                <Option value="12">Bà nội</Option>
                                <Option value="13">Ông ngoại</Option>
                                <Option value="14">Ông nội</Option>
                                <Option value="15">Bác (chị của bố)</Option>
                                <Option value="16">Dì (em của mẹ)</Option>
                                <Option value="17">Cô (em của bố)</Option>
                                <Option value="18">Bác (anh của bố)</Option>
                                <Option value="19">Chú (em của bố)</Option>
                            </Select>
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
                    showAddFamily == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(2)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm một thành viên trong gia đình</span>
                        </div>
                    )
                }
            </div>
        </div>
    );
};

export default FamilyRelationship;