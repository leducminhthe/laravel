import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import { Input, Select, Empty, Tooltip } from 'antd';
import {
    SearchOutlined  
} from '@ant-design/icons';

const DailyTraining = ({ text }) => {
    const { type } = useParams();
    const { Option } = Select;
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [searchCate, setSearchCate] = useState('');
    const [categories, setCategories] = useState([]);
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);

    const disableVideo = async (id) => {
        try {
            const items = await Axios.post(`/user-disable-video`, { id })
            .then((response) => {
                if(response.data.status == 'success') {
                    $('#video_' + id).remove();
                    show_message(response.data.message, response.data.status);
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    
    const selectHandel = (e) => {
        setSearchCate(e);
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value) 
    }

    useEffect(() => {
        const fetchDataCategory = async () => {
            try {
                const items = await Axios.get(`/category-daily-training`)
                .then((response) => {
                    setCategories(response.data.categories)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        fetchDataCategory();
    }, []);

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-daily-training?page=1&search=${search}&searchCate=${searchCate}&type=${type}`)
                .then((response) => {
                    setData(response.data.videos.data),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, [search, searchCate, type]);

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-daily-training?page=${page}&search=${search}&searchCate=${searchCate}&type=${type}`)
        return res;
    };

    const fetchData = async () => {
        const dataFormServer = await fetchDataScroll();
        setData([...data, ...dataFormServer.data.videos.data]);
        if (dataFormServer.data.videos.data.length === 0 || dataFormServer.data.videos.data.length < 6) {
          sethasMore(false);
        }
        setPage(page + 1);
    };

    return (
        <div className="container-fluid">
            <div className="row">
                <div className="col-md-12">
                    <div className="_14d25">
                        <div className="row">
                            <div className="col-md-12">
                                <div className="ibox-content forum-container">
                                    <h2 className="st_title">
                                        <a href="/">
                                            <i className="uil uil-apps"></i>
                                            <span>{text.home_page}</span>
                                        </a>
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{text.training_video}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div className="row my-3">
                            <div className="col-md-7 col-12">
                                <div className="row m-0">
                                    <Input className="col-12 col-md-6 mr-1 mb-2" 
                                        placeholder={text.enter_name_video} 
                                        prefix={<SearchOutlined />}
                                        onPressEnter={(e) => handleKeypress(e)} 
                                    />
                                    <Select className="col-12 col-md-5 mb-2"
                                        showSearch
                                        allowClear
                                        placeholder={text.category}
                                        onChange={selectHandel}
                                        filterOption={(input, option) =>
                                            option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                        }
                                    >   
										<Option value={1}>Mặc định</Option>
                                    {
                                        categories.map((category) => (
                                            <Option key={category.id} value={category.id}>{category.name}</Option>
                                        ))
                                    }
                                    </Select>
                                </div>
                            </div>
                            <div className="col-md-5 col-12">
                            {
                                type == 0 ? (
                                    <Link to="/daily-training-react/1" className="btn position-relative add_video mb-2">
                                        <i className="uil uil-plus-circle"></i> {text.my_video}
                                    </Link>
                                ) : (
                                    <Link to="/daily-training-react/0" className="btn position-relative add_video mb-2">
                                        <i className="uil uil-plus-circle"></i> {text.all_video}
                                    </Link>
                                )
                            }
                                <Link to="/daily-training-react/2" className="btn position-relative add_video mb-2" >
                                    <i className="uil uil-plus-circle"></i> {text.saved_video}
                                </Link>
                                <Link to="/daily-training-react/create-video" className="btn position-relative add_video mb-2" >
                                    <i className="uil uil-plus-circle"></i> {text.add_video}
                                </Link>
                            </div>
                        </div>
                        <div className="row m-0">
                            <div className="col-md-12">
                                <div className="_14d25">
                                {
                                    loading ? (
                                        <div className="row">
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
                                            <InfiniteScroll className="row"
                                                dataLength={data.length}
                                                next={fetchData}
                                                hasMore={hasMore}
                                                style={{ overflow: 'unset'}}
                                            >
                                            <>
                                                {
                                                    data.map((video) => (
                                                        <div key={video.id} className="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1" id={`video_${video.id}`}>
                                                            <div className="row">
                                                                <div className="col-12">
                                                                    <Link to={`/daily-training-react/detail/${video.id}`}>
                                                                        <img src={ video.avatar } alt="" className="w-100 video_daily_image" height={'250px'}/>
                                                                    </Link>
                                                                </div>
                                                            </div>
                                                            <div className="row mx-0 mb-4 mt-1">
                                                                <div className="col-3 avatar_account_daily p-1">
                                                                    <img src={ video.profileAvatar } alt="" className="ml-0 w-100" />
                                                                </div>
                                                                <div className={ (video.checkCreatedBy == 1 ? 'col-7' : 'col-8') + ` pl-1 pr-0`}>
                                                                    <Link to={`/daily-training-react/detail/${video.id}`} className="crse14s link_daily_training">
                                                                        <Tooltip placement="bottom" title={ video.name }>
                                                                            <span className="daily_name_training">{ video.name }</span>
                                                                        </Tooltip>
                                                                    </Link>
                                                                    <p className="text-mute small mb-1">{ video.profileName + ' - ' + video.view + ' ' + text.view }</p>
                                                                    <p className="text-mute small mb-1">
                                                                        <span className='mr-2'>{ video.created_at2 }</span>
                                                                        <img src={ video.check_approve } alt="" width={'15px'} height={'15px'}/>
                                                                    </p>
                                                                </div>
                                                                {
                                                                    video.checkCreatedBy == 1 ? (
                                                                        <div className="col-2 p-0">
                                                                            <span className="text-danger pr-2">
                                                                                <img src={ video.iconHeart } alt="" width="15px" height="15px"/>
                                                                            </span>
                                                                            <div className="eps_dots more_dropdown">
                                                                                <a href=""><i className="uil uil-ellipsis-v"></i></a>
                                                                                <div className="dropdown-content">
                                                                                    <span className="disable-video text-danger" onClick={(e) => disableVideo(video.id)}>
                                                                                    <i className="uil uil-ban"></i> {text.delete}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ) : (
                                                                        <div className="col-1 p-0">
                                                                            <div className="float-right">
                                                                                <span className="text-danger pr-2">
                                                                                    <img src={ video.iconHeart } alt="" width={'15px'} height={'15px'}/>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    )
                                                                }
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
        </div>
    );
};

export default DailyTraining;