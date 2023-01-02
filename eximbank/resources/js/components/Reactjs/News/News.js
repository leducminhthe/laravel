import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Axios from 'axios';
import NewsRight from './NewsRight';
import { Tooltip, Button } from 'antd';
import {
    EyeOutlined,
    LikeOutlined,
    LeftOutlined,
    RightOutlined
} from '@ant-design/icons';

const News = ({ newsRight, text }) => {
    const [parentCatesLeft, setParentCatesLeft] = useState([]);
    const [loading, setLoading] = useState(true);
    const [hotPublic1, setHotPublic1] = useState('');
    const [hotPublic2, setHotPublic2] = useState('');
    const [hotPublic3, setHotPublic3] = useState('');
    const [hotPublic4, setHotPublic4] = useState('');
    const color = '#2ecffc';
    const size = 'small';

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-news`)
                .then((response) => {
                    setHotPublic1(response.data.hot_public_sort_1),
                    setHotPublic2(response.data.hot_public_sort_2),
                    setHotPublic3(response.data.hot_public_sort_3),
                    setHotPublic4(response.data.hot_public_sort_4),
                    setParentCatesLeft(response.data.parent_cate_left),
                    setLoading(false),
                    runFun(response.data.parent_cate_left)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    const scrollRight = (id) => {
        var id = 'slide_menu_'+id
        document.getElementById(id).scrollBy(150, 0);
    }
    const scrollLeft = (id) => {
        var id = 'slide_menu_'+id
        document.getElementById(id).scrollBy(-150, 0);
    }

    const runFun = (cates) => {
        var allCateChild = document.getElementsByClassName("all_cate_child");
        if(allCateChild.length > 0){
            var widthCateChild = allCateChild[0].clientWidth;
            cates.map((parentCateLeft) => {
                var id = 'slide_menu_' + parentCateLeft.id
                var widthId = document.getElementById(id).scrollWidth;
                if (widthId > widthCateChild) {
                    $('.left_click_'+ parentCateLeft.id).show();
                    $('.right_click_'+ parentCateLeft.id).show();
                }
            })
        }
    }

    return (
        <div className="wrraped_content_new row m-0 my-2 pt-2 pb-4">
            <div className="content_left col-md-7 col-lg-8 col-12">
                {
                    loading ? (
                        <div className='row'>
                            <div className="col-12 ajax-loading text-center my-5">
                                <div className="spinner-border" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="row">
                            {
                                (hotPublic1 || hotPublic2 || hotPublic3 || hotPublic4) && (
                                    <div className="col-12 wrraped_hot_public">
                                        <div className="row mb-3">
                                            <div className="col-12 mb-2">
                                                <h3 className='parent_cate_name'>
                                                    <span>{text.featured_news.toUpperCase()}</span>
                                                </h3>
                                            </div>
                                            <div className="col-12">
                                                <div className="row m-0">
                                                    {
                                                        hotPublic1 && (
                                                            <div className="col-md-5 col-12 p-1 hot_public_1">
                                                                <Link className="" to={`/news-react/detail/${hotPublic1.id}`}>
                                                                    <div className='wrapped_new'>
                                                                        <img src={ hotPublic1.image } alt="" width="100%" height="100%"/>
                                                                        <div className="info_hotpublic p-1">
                                                                            <p className='mb-0'>{ hotPublic1.created_at2 }</p>
                                                                            <p className='title_hot_public mb-0'>{ hotPublic1.title }</p>
                                                                        </div>
                                                                    </div>
                                                                </Link>
                                                            </div>
                                                        )
                                                    }
                                                    <div className="col-md-7 col-12 p-1">
                                                        <div className="row m-0">
                                                            {
                                                                hotPublic2 && (
                                                                    <div className="col-12 pl-0 pr-0 hot_public_2">
                                                                        <Link className="" to={`/news-react/detail/${hotPublic2.id}`}>
                                                                            <div className='wrapped_new'>
                                                                                <img src={ hotPublic2.image } alt="" width="100%"/>
                                                                                <div className="info_hotpublic p-1 w-100">
                                                                                    <p className='mb-0'>{ hotPublic2.created_at2 }</p>
                                                                                    <p className='title_hot_public mb-0'>{ hotPublic2.title }</p>
                                                                                </div>
                                                                            </div>
                                                                        </Link>
                                                                    </div>
                                                                )
                                                            }
                                                            {
                                                                hotPublic3 && (
                                                                    <div className="col-6 mt-2 pl-0 pr-0 hot_public_3">
                                                                        <Link className="" to={`/news-react/detail/${hotPublic3.id}`}>
                                                                            <div className='wrapped_new'>
                                                                                <img src={ hotPublic3.image } alt="" width="100%"/>
                                                                                <div className="info_hotpublic p-1">
                                                                                    <p className='mb-0'>{ hotPublic3.created_at2 }</p>
                                                                                    <p className='title_hot_public mb-0'>{ hotPublic3.title }</p>
                                                                                </div>
                                                                            </div>
                                                                        </Link>
                                                                    </div>
                                                                )
                                                            }
                                                            {
                                                                hotPublic4 && (
                                                                    <div className="col-6 mt-2 pl-1 pr-0 hot_public_4">
                                                                        <Link className="" to={`/news-react/detail/${hotPublic4.id}`}>
                                                                            <div className='wrapped_new'>
                                                                                <img src={ hotPublic4.image } alt="" width="100%"/>
                                                                                <div className="info_hotpublic p-1">
                                                                                    <p className='mb-0'>{ hotPublic4.created_at2 }</p>
                                                                                    <p className='title_hot_public mb-0'>{ hotPublic4.title }</p>
                                                                                </div>
                                                                            </div>
                                                                        </Link>
                                                                    </div>
                                                                )
                                                            }
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )
                            }
                            <div className="col-12">
                                {
                                    parentCatesLeft && (
                                        parentCatesLeft.map((parentCateLeft) => (
                                            <div key={parentCateLeft.id} className="row mb-3">
                                                <div className="col-12 mb-2">
                                                    <h3 className='parent_cate_name'>
                                                        <Link to={`/news-react/cate-new/${parentCateLeft.id}/0`}>
                                                            <span>{ parentCateLeft.name }</span>
                                                        </Link>
                                                    </h3>
                                                </div>
                                                <div className="col-12 all_cate_child">
                                                    <div className={`left_click left_click_${parentCateLeft.id}`} >
                                                        <Button type="primary" icon={<LeftOutlined />} size={size} onClick={(e) => scrollLeft(parentCateLeft.id)}></Button>
                                                    </div>
                                                    <div className="scrollmenu" id={`slide_menu_${parentCateLeft.id}`}>
                                                        {
                                                            parentCateLeft.cate_child.map((cate_child) => (
                                                                <Link key={cate_child.id} to={`/news-react/cate-new/${cate_child.id}/1`}>
                                                                    <span>{ cate_child.name }</span>
                                                                </Link>
                                                            ))
                                                        }
                                                    </div>
                                                    <div className={`right_click right_click_${parentCateLeft.id}`}>
                                                        <Button type="primary" icon={<RightOutlined />} size={size} onClick={(e) => scrollRight(parentCateLeft.id)}></Button>
                                                    </div>
                                                </div>
                                                <div className="col-12 mt-2">
                                                    <div className="row">
                                                        {
                                                            parentCateLeft.hot_news_cate_first && (
                                                                <div className="col-md-5 col-12 info_new_first mb-2">
                                                                    <div className="new_img_first">
                                                                        <div className='wrapped_new'>
                                                                            <Link className="link_new_cate" to={`/news-react/detail/${parentCateLeft.hot_news_cate_first.id}`}>
                                                                                <img src={ parentCateLeft.hot_news_cate_first.image } alt="" width="100%"/>
                                                                            </Link>
                                                                        </div>
                                                                    </div>
                                                                    <div className="wrraped_info_new_first p-1">
                                                                        <div className="title_new_first">
                                                                            <Link className="link_new_cate" to={`/news-react/detail/${parentCateLeft.hot_news_cate_first.id}`}>
                                                                                <h6 className="mb-1">
                                                                                    <p className='mb-0 time_view_new'>
                                                                                        <span>{ parentCateLeft.hot_news_cate_first.created_at2 }
                                                                                            <span className='ml-2'>{ parentCateLeft.hot_news_cate_first.views } <EyeOutlined className='ml-1'/></span>
                                                                                            <span className='ml-2'>
                                                                                                { parentCateLeft.hot_news_cate_first.like_new }
                                                                                                {
                                                                                                    parentCateLeft.hot_news_cate_first.check_user_like ? (
                                                                                                        <img src={ parentCateLeft.hot_news_cate_first.check_user_like } className='ml-1' width="16px" />
                                                                                                    ) : (
                                                                                                        <LikeOutlined className='ml-1'/>
                                                                                                    )
                                                                                                }
                                                                                            </span>
                                                                                            {
                                                                                                parentCateLeft.hot_news_cate_first.checkDate && (
                                                                                                    <img className="icon_new" src={ parentCateLeft.hot_news_cate_first.checkDate } alt="" width="40px"/>
                                                                                                )
                                                                                            }
                                                                                        </span>
                                                                                    </p>
                                                                                    <Tooltip placement="bottom" color={color} title={ parentCateLeft.hot_news_cate_first.title }>
                                                                                        <strong className='title_new_cate_first'>{ parentCateLeft.hot_news_cate_first.title }</strong>
                                                                                    </Tooltip>
                                                                                </h6>
                                                                            </Link>
                                                                        </div>
                                                                        <div className="description_new_first">
                                                                            { parentCateLeft.hot_news_cate_first.description }
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            )
                                                        }
                                                        <div className='col-md-7 col-12 px-1'>
                                                        {
                                                            parentCateLeft.hot_news_of_cate_child.length > 0 && (
                                                                <>
                                                                    {
                                                                        parentCateLeft.hot_news_of_cate_child.map((new_cate) => (
                                                                            <div key={new_cate.id} className='row mb-2 mx-0'>
                                                                                <div className="col-5 px-1">
                                                                                    <div className='wrapped_new'>
                                                                                        <Link className="link_new_cate" to={`/news-react/detail/${new_cate.id}`}>
                                                                                            <img src={new_cate.image} alt="" width="100%"/>
                                                                                        </Link>
                                                                                    </div>
                                                                                </div>
                                                                                <div className="col-7 px-1">
                                                                                    <Link className="link_new_cate" to={`/news-react/detail/${new_cate.id}`}>
                                                                                        <h6 className="mb-1">
                                                                                            <p className='mb-0 time_view_new'>
                                                                                                <span>{ new_cate.created_at2 }
                                                                                                    <span className='ml-2'>{ new_cate.views } <EyeOutlined className='ml-1'/></span>
                                                                                                    <span className='ml-2'>
                                                                                                        { new_cate.like_new }
                                                                                                        {
                                                                                                            new_cate.check_user_like ? (
                                                                                                                <img src={ new_cate.check_user_like } className='ml-1' width="16px" />
                                                                                                            ) : (
                                                                                                                <LikeOutlined className='ml-1'/>
                                                                                                            )
                                                                                                        }
                                                                                                    </span>
                                                                                                    {
                                                                                                        new_cate.checkDate && (
                                                                                                            <img className="icon_new" src={ new_cate.checkDate } alt="" width="40px"/>
                                                                                                        )
                                                                                                    }
                                                                                                </span>
                                                                                            </p>
                                                                                            <Tooltip placement="bottom" color={color} title={ new_cate.title }>
                                                                                                <strong className='title_new_cate'>{ new_cate.title }</strong>
                                                                                            </Tooltip>
                                                                                        </h6>
                                                                                    </Link>
                                                                                </div>
                                                                            </div>
                                                                        ))
                                                                    }
                                                                </>
                                                            )
                                                        }
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ))
                                    )
                                }
                            </div>
                        </div>
                    )
                }
            </div>
            <NewsRight loading={loading} newsRight={newsRight} text={text}/>
        </div>
    );
};

export default News;
