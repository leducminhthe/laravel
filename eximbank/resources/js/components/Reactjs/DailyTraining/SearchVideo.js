import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { useLocation, Link } from 'react-router-dom';    
import InfiniteScroll from "react-infinite-scroll-component";
import { Input, Empty, Tooltip } from 'antd';
import {
    VideoCameraAddOutlined,  
    FolderAddOutlined,
    UserOutlined,
} from '@ant-design/icons';

const SearchVideo = ({ text }) => {
    const location = useLocation();
    const [search, setSearch] = useState(location.state.value);
    const [data, setData] = useState([]);
    const [videoView, setVideoView] = useState([]);
    const [videoNew, setVideoNew] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);
    const { Search } = Input;

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-daily-training?page=1&search=${search}`)
            .then((response) => {
                setData(response.data.videos.data),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-daily-training?page=${page}&search=${search}`)
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

    const fetchVideoViewNew = async () => {
        try {
            const items = await Axios.get(`/data-video-view-new`)
            .then((response) => {
                setVideoView(response.data.videos_view),
                setVideoNew(response.data.videos_new)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    };

    useEffect(() => {
        fetchVideoViewNew();
    }, []);

    useEffect(() => {
        fetchDataItem();
    }, [search]);

    const onSearch = (value) => {
        setSearch(value);
    }

    const handleKeypress = (e) => {
        setSearch(e.target.value);
    }

    return (
        <div className="container-fluid search_video_daily">
            <div className="row">
                <div className="col-md-12">
                    <div className="ibox-content forum-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to="/daily-training-react/0">{text.training_video}</Link>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{text.search_video}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div className="row mt-3">
                <Search placeholder={text.search_video} 
                    className="col-md-6 col-7 pr-0"
                    onSearch={onSearch}
                    onPressEnter={(e) => handleKeypress(e)}
                />
                <div className="col-md-6 col-5 text-right">
                    <div className="row m-0">
                        <div className="col-md-8 col-2"></div>
                        <div className="col-md-4 col-10 list_action">
                            <div className="row">
                                <div className="col-md-auto col-1">
                                    <Tooltip placement="bottom" title={text.add_video}>
                                        <Link to="/daily-training-react/create-video" className="" >
                                            <VideoCameraAddOutlined />
                                        </Link>
                                    </Tooltip>
                                </div>
                                {/* <div className="col-md-auto col-1">
                                    <Tooltip placement="bottom" title={'Danh mục'}>
                                        <BarsOutlined />
                                    </Tooltip>
                                </div> */}
                                <div className="col-md-auto col-1">
                                    <Tooltip placement="bottom" title={text.saved_video}>
                                        <Link to="/daily-training-react/2" className="">
                                            <FolderAddOutlined />
                                        </Link>
                                    </Tooltip>
                                </div>
                                <div className="col-md-auto col-1">
                                    <Tooltip placement="bottom" title={text.my_video}>
                                        <Link to="/daily-training-react/1" className="">
                                            <UserOutlined />
                                        </Link>
                                    </Tooltip>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            {
                loading ? (
                    <div className="row mt-3">
                        <div className="col-12 ajax-loading text-center mt-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only"></span>
                            </div>
                        </div> 
                    </div>
                ) : (
                    <div className="row mt-3">
                        <div className="col-md-7 col-12">
                            {
                                data.length > 0 ? (
                                    <InfiniteScroll className="mb-2"
                                        dataLength={data.length}
                                        next={fetchData}
                                        hasMore={hasMore}
                                        style={{ overflow: 'unset'}}
                                    >
                                    <>
                                        {
                                            data.map((video) => (
                                                <div key={video.id} className="col-12 wrraped_search_video">
                                                    <div className='row mb-2'>
                                                        <div className='col-5 pr-0'>
                                                            <Link to={`/daily-training-react/detail/${video.id}`} replace>
                                                                <img src={ video.avatar } alt="" className="related_video w-100"/>
                                                            </Link>
                                                        </div>
                                                        <div className='col-7 bg-white'>
                                                            <Link to={`/daily-training-react/detail/${video.id}`}>
                                                                <h3 className='title_related_video'>{ video.name }</h3> 
                                                            </Link>
                                                            <p className="my-1">
                                                                <span className='mr-2'>{ video.view } {text.view}</span>
                                                                <span>{ video.created_at2 }</span>
                                                            </p>
                                                            <p className="mt-1">
                                                                <img src={ video.profileAvatar } alt="" className="ml-0 mr-1 img_video_search" />
                                                                { video.profileName }
                                                            </p>
                                                        </div>
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
                        </div>
                        <div className="col-md-5 col-12 wrapped_video_hot">
                            <div className="row m-0">
                                <div className="col-12 mb-2">
                                    <h3>Video Hot</h3>
                                </div>
                                <div className="col-12">
                                {
                                    videoView.map((video_view) => (
                                        <div key={video_view.id} className="row mb-2">
                                            <div className="col-6 pr-0">
                                                <Link to={`/daily-training-react/detail/${video_view.id}`} replace>
                                                    <img src={ video_view.avatar } alt="" className="related_video w-100"/>
                                                </Link>
                                            </div>
                                            <div className="col-6">
                                                <Link to={`/daily-training-react/detail/${video_view.id}`}>
                                                    <span className='title_related_video'>{ video_view.name }</span> 
                                                </Link>
                                                <p className="mb-0">{ video_view.view } {text.view}</p>
                                                <p className="mb-0">{ video_view.created_at2 }</p>
                                            </div>
                                        </div>
                                    ))
                                }
                                {
                                    videoNew.map((video_new) => (
                                        <div key={video_new.id} className="row mb-2">
                                            <div className="col-6 pr-0">
                                                <Link to={`/daily-training-react/detail/${video_new.id}`} replace>
                                                    <img src={ video_new.avatar } alt="" className="related_video w-100"/>
                                                </Link>
                                            </div>
                                            <div className="col-6">
                                                <Link to={`/daily-training-react/detail/${video_new.id}`}>
                                                    <span className='title_related_video'>{ video_new.name }</span> 
                                                </Link>
                                                <p className="mb-0">{ video_new.view } {text.view}</p>
                                                <p className="mb-0">{ video_new.created_at2 }</p>
                                            </div>
                                        </div>
                                    ))
                                }
                                </div>
                            </div>
                            
                        </div>
                    </div>
                )
            }
        </div>
    );
};

export default SearchVideo;