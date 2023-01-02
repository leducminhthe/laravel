import React, { useState, useEffect } from 'react';
import {
    PlusCircleOutlined,  
} from '@ant-design/icons';
import { DatePicker, Checkbox, Input, Button, Select } from 'antd';

const ContactInfo = () => {
    const { TextArea } = Input;
    const { Option } = Select;
    const [address, setAddress] = useState([]);
    const [showAddAddress, setShowAddAddress] = useState(false);
    const [showAddweb, setShowAddweb] = useState(false);
    const [addressWeb, setAddressWeb] = useState([]);
    const [showAddSocial, setShowAddSocial] = useState(false);
    const [showAddLanguage, setShowAddLanguage] = useState(false);
    const [city, setCity] = useState('');
    const [showAddReligion, setShowAddReligion] = useState(false);
    const [numberAddressWeb, setNumberAddressWeb] = useState([1]);
    const [numberSocial, setNumberSocial] = useState([]);
    const [religion, setReligion] = useState('');
    const [descriptionReligion, setDescriptionReligion] = useState('');
    const [social, setSocial] = useState([]);
    const [socialType, setSocialType] = useState([]);

    const add = (type) => {
        if(type == 1) {
            setShowAddAddress(!showAddAddress)
        } else if (type == 2) {
            setShowAddweb(!showAddweb)
            setNumberAddressWeb([1])
        } else if (type == 3) {
            setShowAddSocial(!showAddSocial)
            setNumberSocial([])
        } else if (type == 4) {
            setShowAddLanguage(!showAddLanguage)
        } else {
            setShowAddReligion(!showAddReligion)
        }
    }

    const addMoreAddressWebHandle = () => {
        var count = numberAddressWeb.length + 1
        setNumberAddressWeb(numberAddressWeb => [...numberAddressWeb, count])
    }

    const addMoreSocialHandle = () => {
        var count = numberSocial.length + 1
        setNumberSocial(numberSocial => [...numberSocial, count])
    }

    const handleChange = (value, key) => {
        if(socialType[key]) {
            socialType[key] = value; 
        } else {
            setSocialType(socialType => [...socialType, value])
        }
    }

    const onBlurAddressWebHandle = (e, key) => {
        if(addressWeb[key]) {
            addressWeb[key] = e.target.value; 
        } else {
            setAddressWeb(addressWeb => [...addressWeb, e.target.value])
        }
    }

    const onBlurSocial = (e, key) => {
        if(social[key]) {
            social[key] = e.target.value; 
        } else {
            setSocial(social => [...social, e.target.value])
        }
    }

    return (
        <div className='row wrraped_overview'>
            <h3 className='ml-3'><strong>Thông tin liên hệ</strong></h3>
            <div className="col-12 wrraped_add_address mb-3">
                {
                    showAddAddress == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={address}
                                onChange={(e) => setAddress(e.target.value)}
                                placeholder="Địa chỉ" 
                            />
                            <Input 
                                className='mb-2'
                                value={city}
                                onChange={(e) => setCity(e.target.value)}
                                placeholder="Thành phố/Thị xã" 
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
                    showAddAddress == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(1)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm địa chỉ của bạn</span>
                        </div>
                    )
                }
            </div>
            <h3 className='ml-3'><strong>Các trang web và liên kết xã hội</strong></h3>
            <div className="col-12 wrraped_add_web mb-3">
                {
                    showAddweb == true && (
                        <div className='add mb-3'>
                            <div className='address_web'>
                                {
                                    numberAddressWeb.map((number,key) => (
                                        <Input key={key}
                                            className='mb-2'
                                            defaultValue={''}
                                            onBlur={(e) => onBlurAddressWebHandle(e, key)}
                                            placeholder="Địa chỉ trang web" 
                                        />
                                    ))
                                }
                            </div>
                            <Button onClick={addMoreAddressWebHandle} icon={<i className="fas fa-plus ml-2"></i>}>
                                Thêm một trang web
                            </Button>
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
                    showAddweb == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(2)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm một trang web</span>
                        </div>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_social mb-3">
                {
                    showAddSocial == true && (
                        <div className='add mb-3'>
                            {
                                numberSocial.map((number,key) => (
                                    <div key={key} className="d_flex_align mb-2">
                                        <Input className='mr-3 w-30' key={key}
                                            defaultValue={''}
                                            onBlur={(e) => onBlurSocial(e, key)}
                                            placeholder="Tên người dùng" 
                                        />
                                        <Select defaultValue="Instagram" onChange={(e) => handleChange(e, key)}>
                                            <Option value="Instagram">Instagram</Option>
                                            <Option value="Twitter">Twitter</Option>
                                            <Option value="YouTube">YouTube</Option>
                                            <Option value="TikTok">TikTok</Option>
                                            <Option value="Skyper">Skyper</Option>
                                        </Select>
                                    </div>
                                    
                                ))
                            }
                            <Button onClick={addMoreSocialHandle} icon={<i className="fas fa-plus ml-2"></i>}>
                                Thêm liên kết xã hội
                            </Button>
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
                    showAddSocial == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(3)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm liên kết xã hội</span>
                        </div>
                    )
                }
            </div>
            <h3 className='ml-3'><strong>Thông tin cơ bản</strong></h3>
            <div className="col-12 wrraped_add_language mb-3">
                {
                    showAddLanguage == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={city}
                                onChange={(e) => setCity(e.target.value)}
                                placeholder="Ngôn ngữ" 
                            />
                            <hr />
                            <div className='row'>
                                <div className="col-6">
                                    <Button key="back" icon={<i className="fas fa-globe-asia mr-2"></i>}>
                                        Công khai
                                    </Button>
                                </div>
                                <div className="col-6 text-right">
                                    <Button key="back" onClick={(e) => add(4)}>
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
                    showAddLanguage == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(4)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm một ngôn ngữ</span>
                        </div>
                    )
                }
            </div>
            <div className="col-12 wrraped_add_religion mb-3">
                {
                    showAddReligion == true && (
                        <div className='add mb-3'>
                            <Input 
                                className='mb-2'
                                value={religion}
                                onChange={(e) => setReligion(e.target.value)}
                                placeholder="Quan điểm tôn giáo" 
                            />
                            <TextArea
                                className='mb-2'
                                value={descriptionReligion}
                                onChange={(e) => setDescriptionReligion(e.target.value)}
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
                                    <Button key="back" onClick={(e) => add(5)}>
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
                    showAddReligion == false && (
                        <div className='cursor_pointer d_flex_align add_work_place add_new' onClick={(e) => add(5)}>
                            <PlusCircleOutlined /> <span className='ml-2'>Thêm quan điểm tôn giáo</span>
                        </div>
                    )
                }
            </div>
        </div>
    );
};

export default ContactInfo;