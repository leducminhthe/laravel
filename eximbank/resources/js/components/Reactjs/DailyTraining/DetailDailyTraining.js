import React, { useState, useEffect } from 'react';
import { Link, useParams, useNavigate } from 'react-router-dom';
import Axios from 'axios';
import { Input, Empty, Tooltip, message } from 'antd';
import InputEmoji from "react-input-emoji";
import {
    VideoCameraAddOutlined,
    BarsOutlined,
    UserOutlined,
    FolderAddOutlined,
    AppstoreAddOutlined
} from '@ant-design/icons';

const DetailDailyTraining = ({ text }) => {
    let navigate = useNavigate();
    const { id } = useParams();
    const [video, setVideo] = useState([]);
    const [loading, setLoading] = useState(true);
    const [userComments, setUserComments] = useState([]);
    const [countComment, setCountComment] = useState('true');
    const [comment, setComment] = useState('');
    const [relatedVideos, setRelatedVideos] = useState([]);
    const [checkUserSave, setCheckUserSave] = useState(0);
    const { Search } = Input;

    const handleLikeDislikeVideo = async (type, category_id) => {
        try {
            const items = await Axios.post(`/like-dislike-video-daily-training/${id}`,{ type, category_id })
            .then((response) => {
                $('#like').text(response.data.count_like),
                $('#dislike').text(response.data.count_dislike);
                if (response.data.check) {
                    $('.like-dislike').removeClass('text-primary')
                    $('.'+response.data.check).find('i').addClass('text-primary')
                } else {
                    $('.like-dislike').removeClass('text-primary')
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleLikeDislikeComment = async (type, comment_id) => {
        console.log(type, comment_id);
        try {
            const items = await Axios.post(`/like-dislike-comment-daily-training/${id}`,{ type, comment_id })
            .then((response) => {
                $('.like-comment-' + comment_id).text(response.data.count_like_comment);
                $('.dislike-comment-' + comment_id).text(response.data.count_dislike_comment);
                if (response.data.check) {
                    $('.user-like-dislike-comment-'+comment_id).removeClass('text-primary')
                    $('.'+response.data.check).find('i').addClass('text-primary')
                } else {
                    $('.user-like-dislike-comment-'+comment_id).removeClass('text-primary')
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleOnEnter = async (comment) =>  {
        if (comment) {
            try {
                const items = await Axios.post(`/comment-daily-training/${id}`,{ comment })
                .then((response) => {
                    if (response.data.status == 'success') {
                        fetchDataComment()
                    } else {
                        show_message(response.data.status, response.data.message)
                    }
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
    }

    const fetchDataComment = async () => {
        try {
            const items = await Axios.get(`/detail-comment-daily-training/${id}`)
            .then((response) => {
                setUserComments(response.data.comments),
                setCountComment(response.data.count_comment)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataRelatedVideo = async () => {
        try {
            const items = await Axios.get(`/related-video-daily-training/${id}`)
            .then((response) => {
                setRelatedVideos(response.data.related_videos)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/detail-daily-training/${id}`)
            .then((response) => {
                setVideo(response.data.video),
                setCheckUserSave(response.data.video.check_save),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataRelatedVideo();
        fetchDataComment();
        fetchDataItem();
    }, [id]);

    const onSearch = (value) => {
        navigate('/daily-training-react/search-video',{ state:{value: value} });
    }

    const handleKeypress = (e) => {
        navigate('/daily-training-react/search-video', { state:{value: e.target.value} });
    }

    const userSaveVideo = async (id, type) => {
        try {
            const items = await Axios.post(`/user-save-video/`,{ id, type })
            .then((response) => {
                setCheckUserSave(!checkUserSave);
                if (response.data.status == 'success') {
                    message.success(response.data.message);
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    return (
        <div className="container-fluid" id="detail-video">
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
                            <span className="font-weight-bold">{ video.name }</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div className="row">
                <Search placeholder={text.search_video}
                    className="col-md-6 col-7 pr-0"
                    onSearch={onSearch}
                    onPressEnter={(e) => handleKeypress(e)}
                />
                <div className="col-md-6 col-5 text-right">
                    <div className="row m-0">
                        <div className="col-md-8 col-2"></div>
                        <div className="col-md-4 col-10 list_action">
                            <div className="row wrapped_list">
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
                    <div className="row mt-2">
                        <div className="col-12 ajax-loading text-center mb-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                ) : (
                <>
                    <div className="row mx-0 pb-3 pt-3">
                        <div className="col-md-7 col-12">
                            <div className='row'>
                                <div className='col-12 p-0'>
                                    <video className="w-100" controls>
                                        <source src={ video.linkPlay } type="video/mp4"/>
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div className='col-12'>
                                    <div className='row info_detail'>
                                        <div className='col-md-4 col-6 opts_account d-flex pr-0'>
                                            <img src={ video.profileAvatar } alt="" className="" />
                                            <p className="text-mute ml-1">
                                                { video.profileName } <br/>
                                                { video.created_at2 }
                                            </p>
                                        </div>
                                        <div className='col-md-8 col-6 pl-1 pr-0'>
                                            <h4 className="st_title bold"><b>{ video.name }</b></h4>
                                            <p className="text-primary mb-0">
                                                { video.hashtag }
                                            </p>
                                            <p className="text-mute">
                                                <span className="mr-3">
                                                    { video.view } {text.view}
                                                </span>
                                                <span className="text-muted wrraped_like mr-2" onClick={(e) => handleLikeDislikeVideo(1, video.category_id)}>
                                                    <span className='like-video'>
                                                        <i className={`like-dislike uil uil-thumbs-up coler-like `+(video.like ? 'text-primary' : '')}></i>
                                                    </span>
                                                    <span id="like">{ video.countLike }</span>
                                                </span>
                                                <span className="text-muted wrraped_dislike mr-3" onClick={(e) => handleLikeDislikeVideo(2, video.category_id)}>
                                                    <span className='dislike-video'>
                                                        <i className={`like-dislike uil uil-thumbs-down coler-like `+ (video.dislike ? 'text-primary' : '')}></i>
                                                    </span>
                                                    <span id="dislike">{ video.countDislike }</span>
                                                    <span className='ml-1'>{text.dislike}</span>
                                                </span>
                                                {
                                                    (checkUserSave == 0 ) ? (
                                                        <span className="save_video_like" onClick={(e) => userSaveVideo(video.id, 1)}>
                                                            <AppstoreAddOutlined />
                                                            <span className='ml-1'>{text.save}</span>
                                                        </span>
                                                    ) : (
                                                        <span className="save_video_like" onClick={(e) => userSaveVideo(video.id, 0)}>
                                                            <AppstoreAddOutlined />
                                                            <span className='ml-1'>{text.saved}</span>
                                                        </span>
                                                    )
                                                }
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-5 col-12">
                            <h3 className=''>{text.video_same_category}</h3>
                            <div className='row wrraped_related_video'>
                            {
                                relatedVideos.length > 0 ? (
                                <>
                                {
                                    relatedVideos.map((relatedVideo) => (
                                        <div key={relatedVideo.id} className='col-12 mb-2'>
                                            <div className='row'>
                                                <div className='col-5 pr-0'>
                                                    <Link to={`/daily-training-react/detail/${relatedVideo.id}`} replace>
                                                        <img src={ relatedVideo.avatar } alt="" className="related_video w-100"/>
                                                    </Link>
                                                </div>
                                                <div className='col-7'>
                                                    <Link to={`/daily-training-react/detail/${relatedVideo.id}`}>
                                                        <span className='title_related_video'>{ relatedVideo.name }</span>
                                                    </Link>
                                                    <p className="view_realted_video mb-0">{ relatedVideo.view } {text.view}</p>
                                                    <p className="created_at_realted_video mb-0">{ relatedVideo.created_at2 }</p>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                }
                                </>
                                ) : (
                                    <div className='col-12'>
                                        <Empty />
                                    </div>
                                )
                            }
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-12">
                            <div className="review_right">
                                <div className="review_right_heading">
                                    <h6>{text.comment} ({ countComment })</h6>
                                </div>
                            </div>
                            <div className="review_all120">
                                <div id="list-comment">
                                {
                                    userComments.map((comment) => (
                                        <div key={comment.id} className="card shadow border-0 mt-3">
                                            <div className="card-body">
                                                <div className="row align-items-center">
                                                    <div className="col-auto pr-0 opts_account">
                                                        <img src={ comment.profileAvatar } alt="" className=""/>
                                                    </div>
                                                    <div className="col-auto align-self-center">
                                                        <h6 className="font-weight-normal mb-1">
                                                            { comment.profileName }
                                                        </h6>
                                                        <p className="text-mute text-secondary">
                                                            { comment.created_at2 }
                                                        </p>
                                                    </div>
                                                    <div className="col-auto" id={`commit${ comment.id }`}>
                                                        <span className="mr-2 like-comment-video text-muted" onClick={() => handleLikeDislikeComment(1, comment.id)}>
                                                            <span className={`user-like-comment-${ comment.id }`}>
                                                                <i className={`user-like-dislike-comment-${ comment.id } uil uil-thumbs-up ${ comment.like_comment ? ' text-primary' : '' } `}></i>
                                                            </span>
                                                            <span className={`like-comment-${ comment.id }`}>{ comment.count_like_comment }</span>
                                                        </span>
                                                        <span className="mr-2 dislike-comment-video text-muted" onClick={() => handleLikeDislikeComment(0, comment.id)}>
                                                            <span className={`user-dislike-comment-${ comment.id }`}>
                                                                <i className={`user-like-dislike-comment-${ comment.id } uil uil-thumbs-down ${ comment.dislike_comment ? ' text-primary' : '' } `}></i>
                                                            </span>
                                                            <span className={`dislike-comment-${ comment.id }`}>{ comment.count_dislike_comment }</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div className="row m-0">
                                                    <div className="col-12 align-self-center user_comment" dangerouslySetInnerHTML={{ __html: comment.content }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                }
                                </div>
                                <br/>
                                <div className="form-group mb-5">
                                    <InputEmoji
                                        value={comment}
                                        onChange={setComment}
                                        cleanOnEnter
                                        onEnter={handleOnEnter}
                                        placeholder={text.write_comment}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </>
                )
            }
        </div>
    );
};

export default DetailDailyTraining;
