import React, { useState, useEffect } from 'react';
import {Card, Input, Select, Empty, Modal, Button, DatePicker} from 'antd';
import Axios from 'axios';
import {
    SearchOutlined,
    ArrowRightOutlined
} from '@ant-design/icons';
import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import {Autoplay, Navigation, Pagination} from "swiper";
import {Link} from "react-router-dom";

const Promotion = ({text}) => {
    const { Option } = Select;
    const [data, setData] = useState([]);
    const [imagePoromotion, setImagePoromotion] = useState('');
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [sort, setSort] = useState('');
    const [dataUserMaxPoint, setDataUserMaxPoint] = useState([]);
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [loadingPromotion, setLoadingPromotion] = useState(false);
    const [location, setLocation] = useState('');
    const [phoneNumber, setPhoneNumber] = useState('');
    const [time, setTime] = useState('');
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [note, setNote] = useState('');
    const [idOrder, setIdOrder] = useState('');
    const { TextArea } = Input;
    const dateFormatList = ['DD/MM/YYYY', 'DD/MM/YYYY'];
    const selectHandel = (e) => {
        e ? setSort(e) : setSort('');
    }

    const promotionHandel = async () => {
        setLoadingPromotion(true)
        try {
            const items = await Axios.post(`/get-promotion-order/${idOrder}`,{ location, phoneNumber, time, dateFrom, dateTo, note})
            .then((response) => {
                if(response.data.status == 'success') {
                    setIsModalVisible(false);
                } 
                show_message(response.data.message, response.data.status);
                setLoadingPromotion(false);
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const showModal = (id) => {
        setIdOrder(id)
        setIsModalVisible(true)
    };

    const handleCancel = () => {
        setIsModalVisible(false);
    };

    const handleChangeTime = (value) => {
        setTime(value);
    }

    const onChangeDateFromHandle = (date, dateString) => {
        console.log(dateString);
        setDateFrom(dateString)
    }

    const onChangeDateToHandle = (date, dateString) => {
        setDateTo(dateString)
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-promotion?search=${search}&sort=${sort}`)
            .then((response) => {
                setData(response.data.rows),
                setImagePoromotion(response.data.image_promotion),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataUserMaxPoint = async () => {
        try {
            const items = await Axios.get(`/data-user-max-point?limit=10`)
                .then((response) => {
                    setDataUserMaxPoint(response.data.rows),
                    setImagePoromotion(response.data.image_promotion)
                })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
        fetchDataUserMaxPoint();
    }, [sort]);

    return(
        <div className="container-fluid" id='promotion'>
            <div className="row">
                <div className="col-12">
                    <div className="row m-0 pt-3">
                        <div className="col-12 ibox-content forum-container">
                            <h2 className="st_title">
                                <a href="/">
                                    <i className="uil uil-apps"></i>
                                    <span>{text.home_page}</span>
                                </a>
                                <i className="uil uil-angle-right"></i>
                                <span className="font-weight-bold">{text.promotion}</span>
                            </h2>
                        </div>
                    </div>
                    <div className="row mt-3 mb-4">
                        <div className="col-12 col-md-5">
                            <Input placeholder={text.gift_name}
                                className='w-100 mb-2'
                                prefix={<SearchOutlined/>}
                                allowClear
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                            />
                        </div>
                        <div className="col-12 col-md-3">
                            <Select className='w-100 mb-2'
                                showSearch
                                allowClear
                                placeholder={text.gift_point_filter}
                                onChange={selectHandel}
                            >
                                <Option value="1">{text.ascending}</Option>
                                <Option value="2">{text.decrease}</Option>
                            </Select>
                        </div>
                    </div>
                    </div>
                <div className="col-md-12 mb-4">
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
                                <div className="row card_promotions">
                                    {
                                        data.filter((val) => {
                                            return (
                                                val.name.toLowerCase().includes(search.toLocaleLowerCase())
                                            )
                                        }).map(item => (
                                            <div key={item.id} className='col-12 col-md-3 mb-2'>
                                                <Card
                                                    hoverable
                                                    cover={<img alt="example" src={item.images} />}
                                                >
                                                    <p className='item_name mb-1'>
                                                        <strong>{item.name}</strong>
                                                        <i className="uil uil-check-circle"></i>
                                                    </p>
                                                    <p className='mb-1'>{ item.groupname }</p>
                                                    {
                                                        (item.checkPeriod == 0 && !item.checkAmount) ? (
                                                            <button onClick={() => showModal(item.id)} className="btn btn-promotion">
                                                                <span className='change_promotion mr-1'>{text.redeem_gifts} ({item.point})</span>
                                                                <span className='mr-1 point_promotion'>{ item.point }</span>
                                                                <img className="point w-5" src={imagePoromotion} alt="" />
                                                            </button>
                                                        ) : item.checkAmount == 1 ? (
                                                            <button className="btn btn-promotion">
                                                                <span className='mr-1 point_promotion'>{text.gift_over}</span>
                                                            </button>
                                                        ) : (
                                                            <button className="btn btn-promotion">
                                                                <span className='mr-1 point_promotion'>{text.out_of_date}</span>
                                                            </button>
                                                        )
                                                    }
                                                    <div className="tut1250">
                                                        <div>
                                                            <span className="vdt15 mr-2"><strong>{text.quantity}: {item.amount}</strong></span>
                                                        </div>
                                                        <div>
                                                            <span className="vdt15"><strong>{text.period}: {item.period}</strong></span>
                                                        </div>
                                                    </div>
                                                </Card>
                                            </div>
                                        ))
                                    }
                                </div>
                            ) : (
                                <div className='mb-4'>
                                    <Empty />
                                </div>
                            )
                        }
                        <Modal className='modal_info_promotion' 
                            title={text.fill_info} 
                            visible={isModalVisible} 
                            onCancel={handleCancel}
                            footer={[
                                <Button key="submit" type="primary" loading={loadingPromotion} onClick={() => promotionHandel()}>
                                    {text.redeem_gifts}
                                </Button>,
                            ]}
                        >
                            <div className='location mb-3'>
                                <p className='mb-2'>{text.location_gift} <span className='text-danger'>*</span></p>
                                <Input placeholder={text.enter_location} onChange={(e) => setLocation(e.target.value)} allowClear/>
                            </div>
                            <div className='phone_number mb-3'>
                                <p className='mb-2'>{text.phone} <span className='text-danger'>*</span></p>
                                <Input placeholder={text.enter_phone} onChange={(e) => setPhoneNumber(e.target.value)} allowClear/>
                            </div>
                            <div className='phone_number mb-3'>
                                <p className='mb-2'>{text.receiving_period} <span className='text-danger'>*</span></p>
                                <div className='row'>
                                    <div className="col-md-4 col-12">
                                        <Select onChange={handleChangeTime} placeholder={text.choose_time}>
                                            <Option value="1">{text.morning} (6-12h)</Option>
                                            <Option value="2">{text.afternoon} (1-5h)</Option>
                                            <Option value="3">{text.evening} (6-8h)</Option>
                                        </Select>
                                    </div>
                                    <div className="col-md-8 col-12 d_flex_align">
                                        <DatePicker onChange={onChangeDateFromHandle} placeholder={text.received_date} format={dateFormatList} />
                                        <span className='mx-2'><ArrowRightOutlined /></span>
                                        <DatePicker onChange={onChangeDateToHandle} placeholder={text.received_date} format={dateFormatList} />
                                    </div>
                                </div>
                            </div>
                            <div className='note'>
                                <p className='mb-2'>{text.note}</p>
                                <TextArea rows={4} onChange={(e) => setNote(e.target.value)} allowClear/>
                            </div>
                        </Modal>
                        <div className="row">
                            <div className='col-md-10 col-7 mb-2 mt-2'>
                                <span className="font-weight-bold">Top 10 {text.student}</span> {text.high_cumulative_points}
                            </div>
                            <div className='col-md-2 col-5 text-right mb-2 mt-2'>
                                <Link to={`/promotion-react/list-user-max-point`}>
                                    <p className='btn'>
                                        <span>{text.see_detail} <i className="fas fa-arrow-right"></i></span>
                                    </p>
                                </Link>
                            </div>
                        </div>
                        {
                            dataUserMaxPoint.length > 0 ? (
                                <div className="row">
                                    <div className='col-12 mb-2 mt-2'>
                                        <Swiper
                                            slidesPerView={5}
                                            spaceBetween={10}
                                            // slidesPerGroup={3}
                                            loop={dataUserMaxPoint.length > 5 ? true : false}
                                            autoplay={{
                                                delay: 4500,
                                                disableOnInteraction: false,
                                            }}
                                            navigation={true}
                                            modules={[Autoplay, Pagination, Navigation]}
                                            className="mySwiper"
                                        >
                                            {
                                                dataUserMaxPoint.map((usermaxpoint) => (
                                                    <SwiperSlide key={usermaxpoint.user_id}>
                                                        <div className='text-center'>
                                                            <img className="w-50 rounded-circle" src={usermaxpoint.image_avatar} alt="" height="auto"/>
                                                            <p className='mt-2'>{usermaxpoint.full_name}</p>
                                                            <p>
                                                                <span className='mr-1 point_promotion'> {usermaxpoint.point} </span>
                                                                <img className="point w-5" src={imagePoromotion} alt="" />
                                                            </p>
                                                        </div>
                                                    </SwiperSlide>
                                                ))
                                            }
                                        </Swiper>
                                    </div>
                                </div>
                            ): (
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
    )
}

export default Promotion
