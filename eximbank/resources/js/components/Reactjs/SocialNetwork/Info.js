import React, { useState, useEffect } from 'react';
import { Menu, Dropdown, Button } from 'antd';
import {
    EditFilled,  
} from '@ant-design/icons';
import { useParams } from 'react-router-dom';    
import Axios from 'axios';
import InfoPost from './InfoPost';
import InfoAbout from './InfoAbout';
import InfoFriends from './InfoFriends';
import InfoPhotos from './InfoPhotos';
import InfoVideos from './InfoVideos';

const Info = ({ auth, listFriend }) => {
    const { userId } = useParams();
    const [authUser, setAuthUser] = useState('');
    const [selectedFile, setSelectedFile] = useState('')
    const [saveImageCover, setSaveImageCover] = useState(false)
    const [imageCover, setImageCover] = useState('')
    const [type, setType] = useState(1);
    const [loading, setLoaing] = useState(false);
    const [listFriendFormat, setListFriendFormat] = useState([]);

    const fetchDataAuthUser = async () => {
        try {
            const items = await Axios.get(`/data-auth?userId=${userId}`)
            .then((response) => {
                setAuthUser(response.data.profile)
                localStorage.setItem("authUser", JSON.stringify(response.data.profile))
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataListFriendUser = async () => {
        try {
            const items = await Axios.get(`/data-list-friend/${userId}`)
            .then((response) => {
                setListFriendFormat(response.data.list_friends.data)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataImageCover = async () => {
        try {
            const items = await Axios.get(`/data-image-cover/${userId}`)
            .then((response) => {
                setImageCover(response.data.image_cover)
                setLoaing(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        setLoaing(true)
        if (userId != auth.user_id) {
            var get_auth_user = JSON.parse(localStorage.getItem("authUser"))
            if (localStorage.getItem("authUser") != null && get_auth_user.user_id == userId) {
                setAuthUser(get_auth_user)
            } else {
                fetchDataAuthUser()
            }
            fetchDataListFriendUser()
        } else {
            setListFriendFormat(listFriend)
        }
        fetchDataImageCover()
    }, [userId]);

    const uploadImageCoverHandle = (e) => {
        if (!e.target.files || e.target.files.length === 0) {
            setSelectedFile('')
            return
        }
        setSelectedFile(e.target.files[0])
        setSaveImageCover(true)
    }

    const cancelUploadImageCover = () => {
        setSelectedFile('')
    }

    const config = {     
        headers: { 'content-type': 'multipart/form-data' }
    }

    const saveUploadImageCover = async () => {
        const data = new FormData() 
        data.append('image_cover', selectedFile)
        data.append('userId', userId)
        try {
            const items = await Axios.post(`/save-image-cover`, data, config)
            .then((response) => {
                setSaveImageCover(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const choose_image_cover_dropdown = (
        <Menu>
            <Menu.Item key="0">
                <label htmlFor="upload-photo" className='cursor_pointer'><i className="fas fa-upload"></i> Tải ảnh lên</label>
                <input type="file" onChange={uploadImageCoverHandle} className="upload_image_cover" id="upload-photo"/>
            </Menu.Item>
            <Menu.Divider />
            <Menu.Item key="3"><i className="fas fa-trash mr-1"></i> Gỡ</Menu.Item>
        </Menu>
    );

    return (
        <div className="container-fluid">
            {
                loading ? (
                    <div id='info'>
                        <div className="wrapped_info row">
                            <div className="col-12 ajax-loading text-center m-5">
                                <div className="spinner-border" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div> 
                        </div>
                    </div>
                ) : (
                <>
                    <div className="row" id='info'>
                        {
                            saveImageCover && (
                                <div className="col-12 save_image_cover">
                                    <div className="row">
                                        <div className="col-6 public d_flex_align">
                                            <i className="fas fa-globe-asia"></i>
                                            <span className='ml-2'>Ảnh bìa của bạn hiển thị công khai</span>
                                        </div>
                                        <div className="col-6 text-right">
                                            <Button onClick={cancelUploadImageCover}>
                                                Hủy
                                            </Button>
                                            <Button onClick={saveUploadImageCover} type='primary' className='ml-2'>
                                                Lưu thay đổi
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            )
                        }
                        <div className='col-12'>
                            <div className="wrapped_info row">
                                <div className="col-12">
                                    <div className="row">
                                        <div className="col-12">
                                            <div className="image_cover">
                                                {
                                                    selectedFile ? (
                                                        <img src={selectedFile ? URL.createObjectURL(selectedFile) : ''} alt="" width="100%" height={'300px'}/>
                                                    ) : (
                                                        <img src={imageCover} alt="" width="100%" height={'300px'}/>
                                                    )
                                                }
                                                {
                                                    (userId == auth.user_id && !saveImageCover) && (
                                                        <Dropdown overlay={choose_image_cover_dropdown} trigger={['click']} placement="bottomRight">
                                                            <div className='choose_image_cover'>
                                                                <span><i className="fas fa-camera"></i> Chỉnh sửa ảnh bìa</span>
                                                            </div>
                                                        </Dropdown>
                                                    )
                                                }
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row mx-4 wrapped_image_setting_info">
                                        <div className="col-2 pl-1 image_profile_info">
                                            <img className='image_profile' src={ (authUser && userId != auth.user_id) ? authUser.avatar : auth.avatar } alt="" width={'100%'} height="150px"/>
                                            {
                                                userId == auth.user_id ? (
                                                    <div className='choose_image_info'>
                                                        <i className="fas fa-camera"></i>
                                                    </div>
                                                ) : (
                                                    <div className='check_online'></div>
                                                )
                                            }
                                        </div>
                                        <div className="col-4 pl-1 mt-3">
                                            <div className="info_name">
                                                <h3>
                                                    { (authUser && userId != auth.user_id) ? authUser.firstname : auth.firstname }
                                                </h3>
                                            </div>
                                            <div className='total_friend'>
                                                <span>{ listFriendFormat.length } bạn bè</span>
                                            </div>
                                            <div className="image_list_friend mt-1 d_flex_align">
                                                {
                                                    listFriendFormat.map((friend, key) => (
                                                        <div key={friend.id_chat}>
                                                        {
                                                            key < 9 && (
                                                                <img  className={`image_profile ${key > 0 && 'image_friend'}`} src={ friend.avatar } alt="" width={'30px'} height="30px"/>
                                                            )
                                                        }
                                                        </div>
                                                    ))
                                                }
                                            </div>
                                        </div>
                                        <div className="col-6 wrapped_setting_info pr-1">
                                            {
                                                userId == auth.user_id ? (
                                                    <></>
                                                    // <Button icon={<EditFilled />}>
                                                    //     Chỉnh sửa trang cá nhân
                                                    // </Button>
                                                ) : (
                                                    <>
                                                        <Button type='primary' icon={<i className="fas fa-user-check mr-1"></i>}>
                                                            Bạn bè
                                                        </Button>
                                                        <Button className='ml-2' icon={<i className="fas fa-comment mr-1"></i>}>
                                                            Nhắn tin
                                                        </Button>
                                                    </>
                                                )
                                            }
                                            
                                        </div>
                                    </div>
                                    <div className="row mx-2 wrapped_list_option">
                                        <div className="col-12">
                                            <ul className='ul_list_option'>
                                                <li className={`${type == 1 ? 'active_option' : ''}`}>
                                                    <span onClick={(e) => setType(1)}>Bài biết</span>
                                                </li>
                                                <li className={`${type == 2 ? 'active_option' : ''}`}>
                                                    <span onClick={(e) => setType(2)}>Giới Thiệu</span>
                                                </li>
                                                <li className={`${type == 3 ? 'active_option' : ''}`}>
                                                    <span onClick={(e) => setType(3)}>Bạn bè</span>
                                                </li>
                                                <li className={`${type == 4 ? 'active_option' : ''}`}>
                                                    <span onClick={(e) => setType(4)}>Ảnh</span>
                                                </li>
                                                <li className={`${type == 5 ? 'active_option' : ''}`}>
                                                    <span onClick={(e) => setType(5)}>Video</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {(() => {
                        if (type == 1) {
                            return (
                                <InfoPost auth={auth} listFriend={listFriendFormat} setType={setType}/>
                            )
                        } else if (type == 2) {
                            return (
                                <InfoAbout auth={auth} listFriend={listFriendFormat} />
                            )
                        } else if (type == 3) {
                            return (
                                <InfoFriends auth={auth} listFriend={listFriendFormat}/>
                            )
                        } else if (type == 4) {
                            return (
                                <InfoPhotos auth={auth} userId={userId}/>
                            )
                        } else {
                            return (
                                <InfoVideos auth={auth}/>
                            )
                        }
                    })()}
                </>
                )
            }
            
        </div>
    );
};

export default Info;