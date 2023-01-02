import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import Dropzone from "dropzone";
import { Tooltip, Modal, Button, Upload, message, Select, Empty, Input, Dropdown, Menu, Spin } from 'antd';
import InfiniteScroll from "react-infinite-scroll-component";
import InputEmoji from "react-input-emoji";
import {
    EllipsisOutlined,
    SearchOutlined,
    CheckCircleOutlined,
    CheckCircleFilled
} from '@ant-design/icons';
import { Player } from 'video-react';
import { Link } from 'react-router-dom';    
import LikePost from '../component/LikePost';
import Comment from '../component/Comment';
import ImagePost from './ImagePost';
import LazyLoad from 'react-lazyload';

const Post = ({ auth, dataNews, fetchData, loading, hasMore, users, listFriend }) => {
    const [status, setStatus] = useState('1');
    const [video, setVideo] = useState('');
    const [listImage, setListImage] = useState([]);
    const [titleNew, setTitleNew] = useState('');
    const [type, setType] = useState(0);
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [showUploadImage, setShowUploadImage] = useState(false);
    const [showUploadVideo, setShowUploadVideo] = useState(false);
    const [fileList, setFileList] = useState([]);
    const [showCommentNew, setShowCommentNew] = useState([]);
    const [showCommentNewId, setShowCommentNewId] = useState('');
    const [chooseFriend, setChooseFriend] = useState([]);
    const [listFriendChoose, setListFriendChoose] = useState([]);
    const [searchFriend, setSearchFriend] = useState('');
    const { Option } = Select;

    useEffect(() => {
        if(listFriend.length > 0) {
            setListFriendChoose(listFriend)
        }
    }, [listFriend])

    const [isModalChooseFriendVisible, setIsModalChooseFriendVisible] = useState(false);

    const uploadImage = () => {
        setShowUploadImage(!showUploadImage)
        setShowUploadVideo(false)
        setType(1)
    }

    const props = {
        name: 'file',
        action: '/upload-image-network',
        beforeUpload: file => {
            var error = '';
            if (file.type != 'image/jpeg' && file.type != 'image/png') {
                error = 1;
                message.error(`${file.name} phải có định dạng là jpg, png`);
            } 
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isLt2M) {
                error = 1;
                message.error(`${file.name} phải bé hơn 2mb`);
            }
            return (error != 1) ? true : Upload.LIST_IGNORE;
        },
        onRemove: info => {
            var list_image = listImage.filter(i => i != info.response.new_path)
            setListImage(list_image);
        },
        onChange: info => {
            setFileList(info.fileList);
            if (info.file.status === "done") {
                setListImage([...listImage, info.file.response.new_path]);
            } else if (info.file.status === "error") {
                message.error("Error uploading the file");
            }
        },
    };

    const showVideoUpload = () => {
        setShowUploadVideo(!showUploadVideo)
        setShowUploadImage(false)
        setType(2)
    }

    useEffect(() => {
        if(showUploadVideo == true) {
            Dropzone.autoDiscover = false
            new Dropzone("#dropzone", {
                url: "/upload-video-network",  
                paramName: "file",
                uploadMultiple: false,
                parallelUploads: 5,
                timeout: 0,
                init: function () {
                    var _this = this; 
                    this.on("sending", function(files) {
                        $('.ajax-loading-video').show()
                    });
                },
                chunking: true,
                forceChunking: true,
                chunkSize: 5242880, 
                retryChunks: true,   
                retryChunksLimit: 3,
                chunksUploaded: function (file, done) {
                    if (done) {
                        $('.ajax-loading-video').hide();
                        $(".video_upload").show();

                        var path = JSON.parse(file.xhr.response).path;
                        var src_video = JSON.parse(file.xhr.response).src_video;

                        setVideo(path)
                        $(".video_upload").html('<source src="'+ src_video +'" type="video/mp4"></source>' )
                    }
                }
            });
        }
    }, [showUploadVideo]);

    const showListComment = (id) => {
        var index = showCommentNew.indexOf(id)
        if (index !== -1) {
            showCommentNew.splice(index, 1);
            setShowCommentNew([...showCommentNew]);
        } else {
            setShowCommentNew([...showCommentNew, id])
            setShowCommentNewId(id)
        }
    }

    const shareNew = (id) => {

    }

    const showModal = () => {
        setIsModalVisible(true);
    };

    const handleCancel = () => {
        setIsModalVisible(false);
    };

    function handleChange(value) {
        if (value == 2) {
            setIsModalChooseFriendVisible(true);
        }
        setStatus(value)
    }

    const handleChooseFriendCancel = () => {
        setIsModalChooseFriendVisible(false);
    };


    const addNew = async () => {
        try {
            const items = await Axios.post(`/add-new-network`, { status, video, listImage, titleNew, type, chooseFriend })
            .then((response) => {
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    setIsModalVisible(false);
                    setListImage([]);
                    setFileList([]);
                    setStatus('1');
                    setVideo('');
                    setTitleNew('');
                    $('.video_upload').html('');
                    $('.video_upload').hide();
                } 
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    };

    const handleChooseFriend = (friendId) => {
        if (chooseFriend.includes(friendId)) {
            setChooseFriend((chooseFriend) => chooseFriend.filter(id => id != friendId))
        } else {
            setChooseFriend(chooseFriend => [...chooseFriend, friendId])
        }
    }

    const LoadingLazy = () => (
        <div className="post loading">
            <h5>Loading...</h5>
        </div>
    )

    const onEnterHandleSearchFriend = (e) => {
        setSearchFriend(e.target.value)
    }

    return (
        <div>
            <div className="add_new">
                <Link to={`/social-network/info/${auth.user_id}`}>
                    <img className='image_profile' src={ auth.avatar } alt="" width={'35px'} height="35px"/>
                </Link>
                <Button onClick={showModal}>Bạn đang nghĩ gì</Button>
                <Modal className='modalAddNew' 
                    title="Tạo bài viết" 
                    visible={isModalVisible} 
                    onCancel={handleCancel}
                    footer={<Button type="primary" onClick={addNew}>Đăng</Button>}
                >
                    <div className="profile_name_type">
                        <img className='image_profile' src={ auth.avatar } alt="" width={'50px'} height="50px"/>
                        <div className='profile_name ml-2'>
                            <div><span>{ auth.firstname }</span></div>
                            <Select value={ status } style={{ width: 150 }} onSelect={handleChange}>
                                <Option value="1"><i className="fas fa-globe-americas mr-1"></i>Công khái</Option>
                                <Option value="2"><i className="fas fa-user-friends mr-1"></i>Bạn bè cụ thể</Option>
                                <Option value="3"><i className="fas fa-lock mr-1"></i>Chỉ mình tôi</Option>
                            </Select>
                            <Modal className='choose_friend' title="Bạn bè cụ thể" 
                                visible={isModalChooseFriendVisible} 
                                onCancel={handleChooseFriendCancel}
                                footer={[
                                    <Button key={'submit'} onClick={handleChooseFriendCancel} type="primary">Lưu</Button>
                                ]}
                            >
                                <Input placeholder="Tìm kiếm bạn bè" prefix={<SearchOutlined />} allowClear onEnter={(e) => onEnterHandleSearchFriend}/>
                                <h3 className='mt-2 mb-1 pl-1'>Bạn bè</h3>
                                <div className="list_friend">
                                    {
                                        listFriendChoose.map((friend) => (
                                            <div key={friend.id_chat} className='row cursor_pointer friend' onClick={(e) => handleChooseFriend(friend.id_chat)}>
                                                <div className="col-11 pl-1">
                                                    <img className='image_profile' src={friend.avatar} alt="" width={'35px'} height="35px"/>
                                                    <span className='ml-2'>{ friend.user_name }</span>
                                                </div>
                                                <div className="col-1 pr-1 text-right check">
                                                    {
                                                        chooseFriend.includes(friend.id_chat) ? (
                                                            <CheckCircleFilled />
                                                        ) : (
                                                            <CheckCircleOutlined />
                                                        )
                                                    }
                                                </div>
                                            </div>
                                        ))
                                    }
                                </div>
                            </Modal>
                        </div>
                    </div>
                    <div className='mb-3'>
                        <InputEmoji
                            value={titleNew}
                            onChange={setTitleNew}
                            cleanOnEnter
                            placeholder="Bạn đang nghĩ gì thế"
                        />
                    </div>
                    {
                        showUploadImage == true && (
                            <Upload
                                accept='image/jpeg, image/png'
                                maxCount={3}
                                {...props}
                                listType="picture"
                                fileList={fileList}
                                multiple
                                >
                                <Button className='w-100'>Đăng ảnh</Button>
                            </Upload>
                        )
                    }
                    {
                        showUploadVideo == true && (
                            <>
                                <Button className='w-100' id='dropzone'>Đăng video</Button>
                                <div className="col-12 ajax-loading-video text-center my-2">
                                    <div className="spinner-border" role="status">
                                        <span className="sr-only">Loading...</span>
                                    </div>
                                </div> 
                                <video className='video_upload w-100' controls="controls">

                                </video>
                            </>
                            
                        )
                    }
                    <div className='choose_type_upload mt-2'>
                        <div className='upload_image' onClick={uploadImage}>
                            <svg xmlns="http://www.w3.org/2000/svg"  version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 410.309 410.309"><g><g><path style={{fill:"#00ACEA"}} d="M382.955,58.96c16.936,2.176,29.014,17.507,27.167,34.482L386.09,306.079    c-1.848,16.923-17.066,29.144-33.989,27.295c-0.339-0.037-0.677-0.08-1.015-0.128h-1.567V138.372    c0-17.312-14.035-31.347-31.347-31.347H56.947l5.747-52.245c2.179-17.223,17.742-29.535,35.004-27.69L382.955,58.96z"/><path style={{fill:"#00CEB4"}} d="M349.518,333.246v18.808c0,17.312-14.035,31.347-31.347,31.347H31.347    C14.035,383.401,0,369.366,0,352.054v-43.886l86.204-62.694c13.668-10.37,32.794-9.491,45.453,2.09l57.469,50.155    c12.046,10.215,29.238,11.683,42.841,3.657l117.551-68.963V333.246z"/><path style={{fill:"#00EFD1"}} d="M349.518,138.372v94.041l-117.551,68.963c-13.603,8.026-30.795,6.558-42.841-3.657l-57.469-50.155    c-12.659-11.58-31.785-12.46-45.453-2.09L0,308.168V138.372c0-17.312,14.035-31.347,31.347-31.347h286.824    C335.484,107.026,349.518,121.06,349.518,138.372z"/></g><circle style={{fill:"#D4E1F4"}} cx="208.98" cy="192.707" r="33.437"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                        </div>
                        <div className='ml-2 upload_video' onClick={showVideoUpload}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><g id="_37_Upload_video" data-name="37 Upload video"><path d="M51,36H45a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Z" style={{fill:"#bddbff"}}/><path d="M47.0007,30c-.3443.0017-.6712.0289-1.0007.0521V11a1,1,0,0,0-1-1H9a1,1,0,0,0-1,1V47a1,1,0,0,0,1,1H36.472c1.8173,3.4227,5.319,5.973,10.528,6C62.9993,53.9193,63.0007,30.0828,47.0007,30ZM36.0952,31.7461,20.0024,41.9873A3.2723,3.2723,0,0,1,15,39.2412V18.7588a3.2727,3.2727,0,0,1,5.002-2.7461L36.0957,26.2539A3.2759,3.2759,0,0,1,36.0952,31.7461ZM52,42H50v8a1,1,0,0,1-1,1H45a1,1,0,0,1-1-1V41.9756L42.0122,42a1.0072,1.0072,0,0,1-.8262-1.5811l5-7a1.0379,1.0379,0,0,1,1.628,0l5,7A1.007,1.007,0,0,1,52,42Z" style={{fill:"#bddbff"}}/></g><g id="Layer_32" data-name="Layer 32"><path d="M36.0957,26.2539,20.002,16.0127A3.2727,3.2727,0,0,0,15,18.7588V39.2412a3.2723,3.2723,0,0,0,5.0024,2.7461L36.0952,31.7461A3.2759,3.2759,0,0,0,36.0957,26.2539Zm-1.0742,3.8047L18.9287,40.3A1.2618,1.2618,0,0,1,17,39.2412V18.7588A1.2614,1.2614,0,0,1,18.9282,17.7L35.022,27.9414A1.261,1.261,0,0,1,35.0215,30.0586Zm12.7925,3.36a1.0379,1.0379,0,0,0-1.628,0l-5,7A1.0072,1.0072,0,0,0,42.0122,42L44,41.9756V50a1,1,0,0,0,1,1h4a1,1,0,0,0,1-1V42h2a1.007,1.007,0,0,0,.814-1.5811ZM49,40a1,1,0,0,0-1,1v8H46V40.9639a.9956.9956,0,0,0-1.0122-1l-1.0278.0127L47,35.7207,50.0566,40Zm3-9.0427V13a3.0088,3.0088,0,0,0-3-3H5a3.0088,3.0088,0,0,0-3,3V45a3.0088,3.0088,0,0,0,3,3H36.472c1.8173,3.4227,5.319,5.973,10.528,6C61.1064,53.9288,62.7684,35.4,52,30.9573ZM47.0007,30c-.3443.0017-.6712.0289-1.0007.0521V30h4v.3307A14.1965,14.1965,0,0,0,47.0007,30ZM46,24h4v4H46Zm4-2H46V18h4Zm0-9v3H46V12h3A1.0029,1.0029,0,0,1,50,13ZM8,30v4H4V30ZM4,28V24H8v4Zm4-6H4V18H8ZM4,36H8v4H4ZM5,12H8v4H4V13A1.0029,1.0029,0,0,1,5,12ZM4,45V42H8v4H5A1.0029,1.0029,0,0,1,4,45Zm6,1V12H44V30.3307C36.4315,32.0009,33.6445,39.8,35.63,46Zm37.0009,6C33.7715,52.181,33.7717,31.8205,47,32,60.2285,31.8188,60.2283,52.1794,47.0009,52Z" style={{fill:"#3d9ae2"}}/></g></svg>
                        </div>
                    </div>
                </Modal>
            </div>
            <div className="all_news mt-3">
                {
                    loading ? (
                        <div className="col-12 ajax-loading text-center m-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div> 
                    ) : (
                        <>
                        {
                            dataNews.length > 0 ? (
                                <InfiniteScroll className="wrapped_all_new"
                                    dataLength={dataNews.length}
                                    next={fetchData}
                                    hasMore={hasMore}
                                    style={{ overflow: 'unset'}}
                                >
                                    <div>
                                        {
                                            dataNews.map((data) => (
                                                <LazyLoad key={data.id} placeholder={<LoadingLazy />}>
                                                    {
                                                        ((data.show != 0)) && (
                                                        <div className='new mb-2 p-2 bg-white'>
                                                            <div className="top_new row">
                                                                <div className="wrapped_user col-10">
                                                                    <div className="icon_user mr-2">
                                                                        <Link to={`/social-network/info/${data.user_id}`}>
                                                                            <img className='image_profile' src={ data.avatar } alt="" width={'40px'} height="40px"/>
                                                                        </Link>
                                                                    </div>
                                                                    <div className='name_user'>
                                                                        <Link to={`/social-network/info/${data.user_id}`}>
                                                                            <div>
                                                                                <span><strong>{ data.user_name }</strong></span>
                                                                            </div>
                                                                        </Link>
                                                                        <div className="time_add_new">
                                                                            <span>{ data.created_at2 }</span>
                                                                            <span className='ml-2'>
                                                                                {
                                                                                    data.status == 1 ? (
                                                                                        <Tooltip title="Công khai">
                                                                                            <i className="fas fa-globe-americas"></i>
                                                                                        </Tooltip>
                                                                                    ) : data.status == 2 ? (
                                                                                        <Tooltip title="Bạn bè">
                                                                                            <i className="fas fa-user-friends mr-1"></i>
                                                                                        </Tooltip>
                                                                                    ) : (
                                                                                        <Tooltip title="Cá nhân">
                                                                                            <i className="fas fa-lock mr-1"></i>
                                                                                        </Tooltip>
                                                                                    )
                                                                                }
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="setting col-2 text-right cursor_pointer">
                                                                    <Dropdown trigger={['click']}
                                                                        placement="bottomRight"
                                                                        overlay={
                                                                            <Menu>
                                                                                <Menu.Item key="0">
                                                                                    <span>Lưu bài viết</span>
                                                                                </Menu.Item>
                                                                                <Menu.Item key="1">
                                                                                    <span>Ẩn bài viết</span>
                                                                                </Menu.Item>
                                                                                {
                                                                                    auth.user_id == data.user_id && (
                                                                                        <>
                                                                                            <Menu.Divider />
                                                                                            <Menu.Item key="3">Xóa bài viết</Menu.Item>
                                                                                            <Menu.Item key="4">Chỉnh sữa đối tướng</Menu.Item>
                                                                                        </>
                                                                                    )
                                                                                }
                                                                            </Menu>
                                                                        }
                                                                    >
                                                                        <EllipsisOutlined className='mr-3'/>
                                                                    </Dropdown>
                                                                </div>
                                                            </div>
                                                            <div className="text_new pl-2 my-2">
                                                                <span>{ data.title_new }</span>
                                                            </div>
                                                            <hr />
                                                            {
                                                                data.type == 1 ? (
                                                                    <div className="image_new">
                                                                        <LazyLoad once={true}>
                                                                            <ImagePost images={data.images} idPost={data.id}/>
                                                                        </LazyLoad>
                                                                    </div>
                                                                ) : data.type == 2 ? (
                                                                    <div className="video_new">
                                                                        <LazyLoad once={true}>
                                                                            <Player>
                                                                                <source src={ data.video } />
                                                                            </Player>
                                                                        </LazyLoad>
                                                                    </div>
                                                                ) : (
                                                                    <div></div>
                                                                )
                                                            }
                                                            
                                                            <div className="wrapped_view_like_comment_share row mt-2 mx-0">
                                                                <div className="col-6 total_like pr-0 pl-1">
                                                                    <i className="fas fa-thumbs-up"></i>
                                                                    <span className={`ml-1 total_like_new_${data.id}`}>
                                                                        { 
                                                                            (data.id_like_new == data.id && data.check_like && data.total_like > 1) ? (
                                                                                <span>Bạn và {data.total_like} người khác</span>
                                                                            ) : (data.id_like_new == data.id && data.check_like && data.total_like == 1) ? (
                                                                                <span>Bạn</span>  
                                                                            ) : (
                                                                                <span>{data.total_like}</span>  
                                                                            )
                                                                        }
                                                                    </span>
                                                                </div>
                                                                <div className="col-6 wrapped_comment_share pr-1 pl-0">
                                                                    <div className='total_comment'>{ data.total_comment } bình luận</div>
                                                                    <div className='ml-2 total_share'>{ data.total_share } lượt chia sẽ</div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div className='row m-0'>
                                                                <LikePost data_id={data.id} data_check_like={data.check_like} data_id_like_new={data.id_like_new}/>
                                                                <div className={`col-4 comment_new text-center cursor_pointer comment_new_${data.id}`} onClick={(e) => showListComment(data.id)}>
                                                                    <i className="far fa-comment-alt"></i>
                                                                    <span className='ml-1'>Bình luận</span>
                                                                </div>
                                                                <div className={`col-4 share_new text-center cursor_pointer share_new_${data.id}`} onClick={(e) => shareNew(data.id)}>
                                                                    <i className="far fa-share-square"></i>
                                                                    <span className='ml-1'>Chia sẽ</span>
                                                                </div>
                                                            </div>
                                                            <div className={`all_comment all_comment_${data.id}`}>
                                                                <Comment showCommentNewId={showCommentNewId} showCommentNew={showCommentNew} data_id={data.id} auth={auth}/>
                                                            </div>
                                                        </div>
                                                        )
                                                    }
                                                </LazyLoad>
                                            ))
                                        }
                                    </div>
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
    );
};

export default Post;