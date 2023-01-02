import React, { useState, useEffect } from 'react';
import { Switch, Button, Input, Modal } from 'antd';
import {
    PlusCircleOutlined,  
    SearchOutlined,
} from '@ant-design/icons';
import { Link, useParams } from 'react-router-dom';    
import Post from './component/Post';
import Axios from 'axios';
import LazyLoad from 'react-lazyload';

const InfoPost = ({ auth, listFriend, setType }) => {
    const { userId } = useParams();
    const [dataNews, setDataNews] = useState([]);
    const [dataPhotos, setDataPhoto] = useState([]);
    const [loading, setLoading] = useState(true);
    const [hasMore, sethasMore] = useState(true);
    const [showStory, setShowStory] = useState(false)
    const [valueStory, setValueStory] = useState('')
    const [userHaveStory, setUserHaveStory] = useState(false)
    const [isModalVisibleEditDetail, setIsModalVisibleEditDetail] = useState(false);
    const [isModalVisibleInterests, setIsModalVisibleInterests] = useState(false);
    const [workPlace, setWorkPlace] = useState('');
    const [school, setSchool] = useState('');
    const [city, setCity] = useState('');
    const [university, setUniversity] = useState('');
    const [country, setCountry] = useState('');
    const [page, setPage] = useState(2);
    const { TextArea } = Input;

    const fetchDataSocialNetworkNews = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-news-network?page=1&type=0&authUser=${userId}`)
            .then((response) => {
                setDataNews(response.data.news.data)
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-news-network?page=${page}&type=0&authUser=${userId}`)
        return res
    };

    const fetchData = async () => {
        if (dataNews.length > 0) {
            const dataFormServer = await fetchDataScroll()
            setDataNews([...dataNews, ...dataFormServer.data.news.data])
            if (dataFormServer.data.news.data.length === 0 || dataFormServer.data.news.data.length.length < 6) {
                sethasMore(false)
            }
            setPage(page + 1)
        }
    };

    const storyHandle = () => {
        setShowStory(true)
    }
    
    const showModalEditDetailInfo = () => {
      setIsModalVisibleEditDetail(true)
    };
  
    const handleOk = () => {
      setIsModalVisibleEditDetail(false)
    };
  
    const handleCancel = () => {
      setIsModalVisibleEditDetail(false)
    };

    const showModalInterests = () => {
        setIsModalVisibleInterests(true)
    };

    const handleInterestsOk = () => {
        setIsModalVisibleInterests(false)
    };
    
    const handleInterestsCancel = () => {
        setIsModalVisibleInterests(false)
    };
    
    const onChange = (checked) => {
        console.log(`switch to ${checked}`);
    }

    const config = {     
        headers: { 'content-type': 'multipart/form-data' }
    }

    const fetchDataStory = async () => {
        try {
            const items = await Axios.get(`/data-story/${userId}`)
            .then((response) => {
                if(response.data.story) {
                    setUserHaveStory(true)
                }
                setValueStory(response.data.story)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const saveStory = async () => {
        const data = new FormData() 
        data.append('story', valueStory)
        data.append('userId', userId)
        try {
            const items = await Axios.post(`/save-story`, data, config)
            .then((response) => {
                setShowStory(false)
                fetchDataStory()
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
                    setWorkPlace(response.data.work_place)
                }
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
                    setSchool(response.data.study)
                }
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
                    setCity(response.data.city)
                }
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
                    setUniversity(response.data.study)
                }
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
                    setCountry(response.data.country)
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataUserImage = async () => {
        try {
            const items = await Axios.get(`/data-user-image-network/${userId}?page=1`)
            .then((response) => {
                if(response.data.getPhotoByNew.data) {
                    setDataPhoto(response.data.getPhotoByNew.data)
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataSocialNetworkNews()
        fetchDataStory()
        fetchDataWorkPlace()
        fetchDataSchool()
        fetchDataCity()
        fetchDataUniversity()
        fetchDataCountry()
        fetchDataUserImage()
    }, [userId]);

    const onChangeWorkPlace = (checked) => {
        console.log(`switch to ${checked}`);
    }

    const onChangeSchool = (checked) => {
        console.log(`switch to ${checked}`);
    }

    const onChangeUniversity = (checked) => {
        console.log(`switch to ${checked}`);
    }

    const onChangeCity = (checked) => {
        console.log(`switch to ${checked}`);
    }

    const onChangeCountry = (checked) => {
        console.log(`switch to ${checked}`);
    }

    return (
        <div className="row mx-2 wrraped_content_option">
            <div className="col-12 content content_info_post">
                <div className="row">
                    <div className="col-5 content_post_left">
                        <div className="row info">
                            <div className="col-12 p-3">
                                <h3><strong>Giới Thiệu</strong></h3>
                            </div>
                            <div className="col-12 text-center mb-2">
                                {
                                    (userHaveStory && !showStory) && (
                                        <p>{valueStory}</p>
                                    )
                                }
                            </div>
                            <div className="col-12 mb-3">
                                {
                                    userId == auth.user_id && (
                                        <>
                                        {
                                            showStory ? (
                                                <div className='mb-2'>
                                                    <TextArea
                                                        value={valueStory}
                                                        onChange={(e) => setValueStory(e.target.value)}
                                                        placeholder="Mô tả về bạn"
                                                        autoSize={{ minRows: 2, maxRows: 5 }}
                                                        maxLength={101}
                                                        showCount
                                                    />
                                                    <div className='row m-0 w-100'>
                                                        <div className="public col-6 d_flex_align pl-0">
                                                            <i className="fas fa-globe-asia mr-2"></i>
                                                            <span>Công khai</span>
                                                        </div>
                                                        <div className="col-6 text-right pr-0">
                                                            <Button className='mr-2' onClick={(e) => setShowStory(false)}>Hủy</Button>
                                                            <Button onClick={(e) => saveStory(false)}>Lưu</Button>
                                                        </div>
                                                    </div>
                                                </div>
                                            ) : (
                                                <Button className='w-100' onClick={storyHandle}>{userHaveStory ? 'Chỉnh sửa tiểu sử' : 'Thêm tiểu sử'}</Button>
                                            )
                                        }
                                        </>
                                    )
                                }
                            </div>
                            <div className="col-12 mb-3">
                                {
                                    workPlace && (
                                        <div className="mb-3 d_flex_align">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            <span className='ml-2'>{workPlace.position} tại {workPlace.company}</span>
                                        </div>
                                    )
                                }
                                {
                                    school && (
                                        <div className="mb-3 d_flex_align">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            <span className='ml-2'>Đã học tại {school.name}</span>
                                        </div>
                                    )
                                }
                                {
                                    university && (
                                        <div className="mb-3 d_flex_align">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            <span className='ml-2'>Đã học tại {university.name}</span>
                                        </div>
                                    )
                                }
                                {
                                    city && (
                                        <div className="mb-3 d_flex_align">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            <span className='ml-2'>Sống tại {city.city}</span>
                                        </div>
                                    )
                                }
                                {
                                    country && (
                                        <div className="mb-3 d_flex_align">
                                            <svg xmlns="http://www.w3.org/2000/svg" id="color" enableBackground="new 0 0 24 24" height="25" viewBox="0 0 24 24" width="25"><path d="m15 6.5c-.552 0-1-.448-1-1v-1.5h-4v1.5c0 .552-.448 1-1 1s-1-.448-1-1v-1.5c0-1.103.897-2 2-2h4c1.103 0 2 .897 2 2v1.5c0 .552-.448 1-1 1z" fill="#455a64"/><path d="m12.24 13.96c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v9.21c0 1.52 1.23 2.75 2.75 2.75h18.5c1.52 0 2.75-1.23 2.75-2.75v-9.21z" fill="#607d8b"/><path d="m24 7.75v2.29l-11.76 3.92c-.08.03-.16.04-.24.04s-.16-.01-.24-.04l-11.76-3.92v-2.29c0-1.52 1.23-2.75 2.75-2.75h18.5c1.52 0 2.75 1.23 2.75 2.75z" fill="#78909c"/></svg>
                                            <span className='ml-2'>Đến từ {country.country}</span>
                                        </div>
                                    )
                                }
                                {
                                    userId == auth.user_id && (
                                        <Button className='w-100' onClick={showModalEditDetailInfo}>Chỉnh sửa chi tiết</Button>
                                    ) 
                                }
                                <Modal className='editDetailInfo' 
                                    title="Chỉnh sửa chi tiết" 
                                    visible={isModalVisibleEditDetail} 
                                    onOk={handleOk} 
                                    onCancel={handleCancel}
                                    footer={[
                                        <Button key="back" onClick={handleCancel}>
                                            Hủy
                                        </Button>,
                                        <Button key="submit" type="primary" onClick={handleOk}>
                                            Lưu
                                        </Button>,
                                    ]}
                                >
                                    <div className='info mb-4'>
                                        <h4 className='mb-0'>Chỉnh sửa phần giới thiệu</h4>
                                        <p>Chi tiết bạn chọn sẽ hiển thị công khai</p>
                                    </div>
                                    <div className='work mb-4'>
                                        <h4 className='mb-0'>Công việc</h4>
                                        <div className="row mx-0 mb-3 mt-2">
                                            {
                                                workPlace &&  (
                                                    <div className="mt-2">
                                                        {
                                                            workPlace.type == 1 ? (
                                                                <Switch defaultChecked onChange={onChangeWorkPlace} />
                                                            ) : (
                                                                <Switch onChange={onChangeWorkPlace} />
                                                            )
                                                        }
                                                        <span className="ml-2">{workPlace.position} tại {workPlace.company}</span>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <div className='add_place_work'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm nơi làm việc</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className='study mb-4'>
                                        <h4 className='mb-0'>Học vấn</h4>
                                        <div className='row mx-0 mb-3 mt-2'>
                                            {
                                                school &&  (
                                                    <div className="mt-2">
                                                        {
                                                            school.type == 1 ? (
                                                                <Switch defaultChecked onChange={onChangeSchool} />
                                                            ) : (
                                                                <Switch onChange={onChangeSchool} />
                                                            )
                                                        }
                                                        <span className="ml-2">Đã học tại {school.name}</span>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <div className='row mx-0 mb-3 mt-2'>
                                            {
                                                university &&  (
                                                    <div className="mt-2">
                                                        {
                                                            university.type == 1 ? (
                                                                <Switch defaultChecked onChange={onChangeUniversity} />
                                                            ) : (
                                                                <Switch onChange={onChangeUniversity} />
                                                            )
                                                        }
                                                        <span className="ml-2">Đã học tại {university.name}</span>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <div className='add_school'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm trường trung học</span>
                                            </div>
                                        </div>
                                        <div className='add_school'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm trường cao đẳng/dại học</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className='city mb-4'>
                                        <h4 className='mb-0'>Tỉnh/Thành phố hiện tại</h4>
                                        <div className='row mx-0 mb-3 mt-2'>
                                            {
                                                city &&  (
                                                    <div className="mt-2">
                                                        {
                                                            city.type == 1 ? (
                                                                <Switch defaultChecked onChange={onChangeCity} />
                                                            ) : (
                                                                <Switch onChange={onChangeCity} />
                                                            )
                                                        }
                                                        <span className="ml-2">Sống tại {city.city}</span>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <div className='add_city'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm Thành phố</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className='country mb-4'>
                                        <h4 className='mb-0'>Quê quán</h4>
                                        <div className='row mx-0 mb-3 mt-2'>
                                            {
                                                country &&  (
                                                    <div className="mt-2">
                                                        {
                                                            country.type == 1 ? (
                                                                <Switch defaultChecked onChange={onChangeCountry} />
                                                            ) : (
                                                                <Switch onChange={onChangeCountry} />
                                                            )
                                                        }
                                                        <span className="ml-2">Đến từ {country.country}</span>
                                                    </div>
                                                )
                                            }
                                        </div>
                                        <div className='add_country'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm Quê quán</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className='relationship mb-4'>
                                        <h4 className='mb-0'>Mối quan hệ</h4>
                                        <div className='add_country'>
                                            <div className='d_flex_align my-2 cursor_pointer add_about' onClick={() => setType(2)}>
                                                <PlusCircleOutlined /> <span className='ml-2'>Thêm tình trạng mối quan hệ</span>
                                            </div>
                                        </div>
                                    </div>
                                </Modal>
                            </div>
                            <div className="col-12 mb-3">
                                {
                                    userId == auth.user_id && (
                                        <Button className='w-100' onClick={showModalInterests}>Thêm sở thích</Button>
                                    ) 
                                }
                                <Modal className='interestsModal' 
                                    title="Sở thích" 
                                    visible={isModalVisibleInterests} 
                                    onOk={handleInterestsOk} 
                                    onCancel={handleInterestsCancel}
                                    footer={[
                                        <Button key="back" onClick={handleInterestsCancel}>
                                            Hủy
                                        </Button>,
                                        <Button key="submit" type="primary" onClick={handleInterestsOk}>
                                            Lưu
                                        </Button>,
                                    ]}
                                >
                                    <Input placeholder="Bạn làm gì để giải trí" prefix={<SearchOutlined />} />
                                    <hr />
                                    <p>SỞ THÍCH ĐÃ CHỌN</p>
                                    <div className='wrapped_choose_interests'>

                                    </div>
                                </Modal>
                            </div>
                        </div>
                        <div className="row photos">
                            <div className="col-12">
                                <div className='row'>
                                    <div className="col-6 d_flex_align">
                                        <h3 className='mb-0'><strong>Ảnh</strong></h3>
                                    </div>
                                    <div className="col-6">
                                        <div className='float-right cursor_pointer more_image' onClick={() => setType(4)}>
                                            <span>Xem tất cả ảnh</span>
                                        </div>
                                    </div>
                                </div>
                                <div className="row mx-0 mt-3">
                                    {
                                        dataPhotos.map((photo, key) => (
                                            <div key={key} className="col-4 p-1">
                                                {
                                                    key < 10 && (
                                                        <LazyLoad>
                                                            <img src={photo.image} alt="" width={'100%'} height={'110px'}/>
                                                        </LazyLoad>
                                                    )
                                                }
                                            </div>
                                        ))
                                    }
                                </div>
                            </div>
                        </div>
                        <div className="row friends">
                            <div className="col-12">
                                <div className='row'>
                                    <div className="col-6 d_flex_align">
                                        <h3 className='mb-0'><strong>Bạn bè</strong></h3>
                                    </div>
                                    <div className="col-6">
                                        <div className='float-right cursor_pointer more_friend' onClick={() => setType(3)}>
                                            <span>Xem tất cả bạn bè</span>
                                        </div>
                                    </div>
                                    <div className="col-12 mb-3">
                                        <span>{listFriend.length} người bạn</span>
                                    </div>
                                    <div className="col-12 list_image_friend">
                                        <div className="row">
                                            {
                                                listFriend.map((friend, key) => (
                                                    <div key={ friend.id_chat } className="col-4">
                                                        {
                                                            key < 7 && (
                                                                <Link to={`/social-network/info/${friend.id_chat}`}>
                                                                    <LazyLoad>
                                                                        <img className='image_friend' src={ friend.avatar } alt="" width={'100%'}/>
                                                                    </LazyLoad>
                                                                    <p>{ friend.user_name }</p>
                                                                </Link>
                                                            )
                                                        }
                                                    </div>
                                                ))
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-7 content_post_right content">
                        <Post auth={auth} dataNews={dataNews} fetchData={fetchData} loading={loading} hasMore={hasMore} listFriend={listFriend}/>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default InfoPost;