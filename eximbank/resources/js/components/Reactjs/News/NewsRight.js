import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';    
import { Tooltip, Tabs, Button } from 'antd';
import {
    EyeOutlined,
    LikeOutlined
} from '@ant-design/icons';

import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Autoplay, Pagination, Navigation } from "swiper";
import Axios from 'axios';

const NewsRight = ({ loading , newsRight, text }) => {
    let navigate = useNavigate();
    const color = '#f3b542';
    const [newsView, setNewsView] = useState([]);
    const [newsLike, setNewsLike] = useState([]);
    const [type, setType] = useState(1);
    const { TabPane } = Tabs;
    
    const changeTab = (key) => {
        setType(key);
    }

    const moreNewsViewLike = () => {
        navigate('/news-react/news-view-like',{ state:{type: type} });
    }

    const operations = <p onClick={moreNewsViewLike} className='more_new_view_like'><span>{text.view_more} <i className="fas fa-arrow-right"></i></span></p>;

    const fetchDataNewViewLike = async () => {
        try {
            const items = await Axios.get(`/data-new-view-like/0`)
            .then((response) => {
                setNewsView(response.data.news_view),
                setNewsLike(response.data.news_like)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataNewViewLike();
    }, []);

    return (
        <div className="content_right col-md-4 col-12 pr-1">
        {
            loading ? (
                <div className='row m-5'>
                    <div className="col-12 ajax-loading text-center mb-5">
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div> 
                </div>
            ) : (
            <>
            {
                newsRight.map((cateRight, index) => (
                    <div key={cateRight.id}>
                    {
                        index == 0 ? (
                            <>
                            {
                                cateRight.news_right.length > 0 && (
                                    <div className="all_news mb-3 pt-2">
                                        <div className="row mb-1">
                                            <div className="col-6 mb-2">
                                                <h5>
                                                    <strong>
                                                        <span className='title_cate_right'>{ cateRight.name }</span>
                                                    </strong>
                                                </h5>
                                            </div>
                                            {
                                                cateRight.news_right.length >= 3 && (
                                                    <div className="col-6 py-1 text-right">
                                                        <Link to={`/news-react/cate-new/${cateRight.id}/1`}>
                                                            <p className='more_new'>
                                                                <span>{text.view_more} <i className="fas fa-arrow-right"></i></span>
                                                            </p>
                                                        </Link>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        {
                                            cateRight.news_right.map((newRight) => (
                                                <div key={newRight.id} className="row mb-3 get_new_right">
                                                    <div className="col-5 pr-0">
                                                        <div className='wrapped_new'>
                                                            <Link to={`/news-react/detail/${newRight.id}`}>
                                                                <img className="w-100" src={newRight.image} alt="" height="auto"/>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                    <div className="col-7 pl-2 pr-1 new_right">
                                                        <div className="hot_new_title_right">
                                                            <Link className="link_hot_new_title_right" to={`/news-react/detail/${newRight.id}`}>
                                                                <h6 className="mb-1">
                                                                    <p className='mb-0 time_view_new_right'>
                                                                        <span>{ newRight.created_at2 }  
                                                                            <span className='ml-2'>{ newRight.views } <EyeOutlined className='ml-1'/></span>
                                                                            <span className='ml-2'>
                                                                                { newRight.like_new }
                                                                                {
                                                                                    newRight.check_user_like ? (
                                                                                        <img src={ newRight.check_user_like } className='ml-1' width="16px" />
                                                                                    ) : (
                                                                                        <LikeOutlined className='ml-1'/>
                                                                                    )
                                                                                }  
                                                                            </span>
                                                                            {
                                                                                newRight.checkDate && (
                                                                                    <img className="icon_new" src={ newRight.checkDate } alt="" width="40px"/>
                                                                                )
                                                                            }
                                                                        </span>
                                                                    </p>
                                                                    <div className='mt-1'>
                                                                        <Tooltip placement="bottom" color={color} title={ newRight.title }>
                                                                            <strong className='title_new_right'>{ newRight.title }</strong>
                                                                        </Tooltip>
                                                                    </div>
                                                                </h6>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))
                                        }
                                    </div>
                                )
                            }
                            </>
                        ) : (
                            <>
                            {
                                cateRight.news_right.length > 0 && (
                                    <div  className="all_news mb-3 pt-2">
                                        <div className="row mb-1">
                                            <div className="col-6 pt-1">
                                                <h4>
                                                    <strong>
                                                        <span className='title_cate_right'>{ cateRight.name }</span>
                                                    </strong>
                                                </h4>
                                            </div>
                                            {
                                                cateRight.news_right.length >= 3 && (
                                                    <div className="col-6 py-1 text-right">
                                                        <Link to={`/news-react/cate-new/${cateRight.id}/1`}>
                                                            <p className='more_new'>
                                                                <span>{text.view_more} <i className="fas fa-arrow-right"></i></span>
                                                            </p>
                                                        </Link>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <Swiper
                                            slidesPerView={2}
                                            spaceBetween={10}
                                            // slidesPerGroup={3}
                                            loop={cateRight.news_right.length > 1 ? true : false}
                                            autoplay={{
                                                delay: 4500,
                                                disableOnInteraction: false,
                                            }}
                                            navigation={true}
                                            modules={[Autoplay, Pagination, Navigation]}
                                            className="mySwiper"
                                        >
                                            {
                                                cateRight.news_right.map((newRight) => (
                                                    <SwiperSlide key={newRight.id}>
                                                        <Link to={`/news-react/detail/${newRight.id}`}>
                                                            <div className='wrapped_new'>
                                                                <img className="w-100" src={newRight.image} alt="" height="auto"/>
                                                            </div>
                                                            <div className="mt-1">
                                                                <Tooltip placement="bottom" color={color} title={ newRight.title }>
                                                                    <strong className="title_new_right">
                                                                        { newRight.title }
                                                                    </strong>
                                                                </Tooltip>
                                                            </div>
                                                        </Link>
                                                        {
                                                            newRight.checkDate && (
                                                                <img className="icon_new" src={ newRight.checkDate } alt="" width="40px"/>
                                                            )
                                                        }
                                                    </SwiperSlide>
                                                ))
                                            }
                                        </Swiper>
                                    </div>
                                )
                            }
                            </>
                        )
                    }
                    </div>
                ))
            }
                <div className="row show_new_view_like">
                    <div className="col-12">
                        <Tabs tabBarExtraContent={operations} defaultActiveKey={type} onChange={changeTab}>
                            <TabPane className='row' tab={text.most_view} key="1">
                                {
                                    newsView.map((newView) => (
                                        <div key={newView.id} className="row w-100 mb-3 get_new_right mx-0">
                                            <div className="col-5 pr-0 wrapped_new">
                                                <Link to={`/news-react/detail/${newView.id}`}>
                                                    <img src={newView.image} alt="" width={'100%'} height="160px"/>
                                                </Link>
                                            </div>
                                            <div className="col-7 pl-2 pr-1 new_right">
                                                <div className="hot_new_title_right">
                                                    <Link className="link_hot_new_title_right" to={`/news-react/detail/${newView.id}`}>
                                                        <h6 className="mb-1">
                                                            <p className='mb-0 time_view_new_right'>
                                                                <span>{ newView.created_at2 }
                                                                    <span className='ml-2'>{ newView.views }<EyeOutlined className='ml-1'/></span>
                                                                    <span className='ml-2'>
                                                                        { newView.like_new }
                                                                        {
                                                                            newView.check_user_like ? (
                                                                                <img src={ newView.check_user_like } className='ml-1' width="16px" />
                                                                            ) : (
                                                                                <LikeOutlined className='ml-1'/>
                                                                            )
                                                                        } 
                                                                    </span>    
                                                                    {
                                                                        newView.checkDate && (
                                                                            <img className="icon_new" src={ newView.checkDate } alt="" width="40px"/>
                                                                        )
                                                                    }
                                                                </span>
                                                            </p>
                                                            <Tooltip placement="bottom" color={color} title={ newView.title }>
                                                                <strong className='title_new_right'>{ newView.title }</strong>
                                                            </Tooltip>
                                                        </h6>
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    )) 
                                }
                            </TabPane>
                            <TabPane className='row' tab={text.like} key="2">
                                {
                                    newsLike.map((newLike) => (
                                        <div key={newLike.id} className="row w-100 mb-3 get_new_right mx-0">
                                            <div className="col-5 pr-0 wrapped_new">
                                                <Link to={`/news-react/detail/${newLike.id}`}>
                                                    <img src={newLike.image} alt="" width={'100%'} height="160px"/>
                                                </Link>
                                            </div>
                                            <div className="col-7 pl-2 pr-1 new_right">
                                                <div className="hot_new_title_right">
                                                    <Link className="link_hot_new_title_right" to={`/news-react/detail/${newLike.id}`}>
                                                        <h6 className="mb-1">
                                                            <p className='mb-0 time_view_new_right'>
                                                                <span>{ newLike.created_at2 } 
                                                                    <span className='ml-2'>{ newLike.views } <EyeOutlined className='ml-1'/></span>
                                                                    <span className='ml-2'>
                                                                        { newLike.like_new }
                                                                        {
                                                                            newLike.check_user_like ? (
                                                                                <img src={ newLike.check_user_like } className='ml-1' width="16px" />
                                                                            ) : (
                                                                                <LikeOutlined className='ml-1'/>
                                                                            )
                                                                        } 
                                                                    </span>   
                                                                    {
                                                                        newLike.checkDate && (
                                                                            <img className="icon_new" src={ newLike.checkDate } alt="" width="40px"/>
                                                                        )
                                                                    }
                                                                </span>
                                                            </p>
                                                            <Tooltip placement="bottom" color={color} title={ newLike.title }>
                                                                <strong className='title_new_right'>{ newLike.title }</strong>
                                                            </Tooltip>
                                                        </h6>
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    )) 
                                }
                            </TabPane>
                        </Tabs>
                    </div>
                </div>
            </>
            )
        }
        </div>
    );
};

export default NewsRight;