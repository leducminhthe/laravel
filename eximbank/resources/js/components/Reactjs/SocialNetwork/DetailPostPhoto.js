import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Link, useParams, useNavigate } from 'react-router-dom';    
import { Image, Popover, Spin, Tooltip } from 'antd';
import { EllipsisOutlined } from '@ant-design/icons';
import FacebookEmoji from 'react-facebook-emoji';
import LikeComment from './component/LikeComment';
import Reply from './component/Reply';
import InputEmoji from "react-input-emoji";

const DetailPostPhoto = ({ auth }) => {
    const navigate = useNavigate();
    const [dataNew, setDataNew] = useState('');
    const [dataImage, setDataImage] = useState('');
    const [loading, setLoading] = useState(true);
    const { id } = useParams()
    const { idImage } = useParams()
    const [typeLike, setTypeLike] = useState('');
    const [idLikeNew, setIdLikeNew] = useState('');
    const [showLike, setShowLike] = useState(false);
    const [dataNewComments, setDataNewComments] = useState([]);
    const [loadingComment, setLoadingComment] = useState(true);
    const [lastPage, setLastPage] = useState('');
    const [currentPage, setCurrentPage] = useState('');
    const [totalPage, setTotalPage] = useState('');
    const [page, setPage] = useState(2);
    const [comment, setComment] = useState('');
    const [showReply, setShowReply] = useState([]);
    const [dataAddNewComments, setDataAddNewComments] = useState([]);

    const fetchDataDetail = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-detail-post-photo/${id}/${idImage}`)
            .then((response) => {
                setDataNew(response.data.detailNew)
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataImage = async () => {
        try {
            const items = await Axios.get(`/data-image-post-photo/${id}/${idImage}`)
            .then((response) => {
                setDataImage(response.data.imageDetailNew)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataListComment = async () => {
        setLoadingComment(true)
        try {
            const items = await Axios.get(`/show-comment/${dataNew.id}?page=1`)
            .then((response) => {
                setDataNewComments(response.data.comments.data)
                setLastPage(response.data.comments.last_page)
                setCurrentPage(response.data.comments.current_page)
                setTotalPage(response.data.comments.total)
                setLoadingComment(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchComment = async () => {
        const res = await Axios.get(`/show-comment/${dataNew.id}?page=${page}`)
        const dataFormServer = res;
        setDataNewComments([...dataNewComments, ...dataFormServer.data.comments.data]);
        setPage(page + 1)
        setCurrentPage(currentPage + 1)
    };

    const handleOnEnter = async (id, comment) =>  {
        if (comment) {
            try {
                const items = await Axios.post(`/user-comment-network`,{ id, comment })
                .then((response) => {
                    $('.total_comment').html(response.data.total_comment + ' bình luận');
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
    }

    const showReplyHandle = (comment_id) => {
        setShowReply(showReply => [...showReply, comment_id]);
    }

    const handleHoverChange = (e) => {
        setShowLike(e)
    }

    const likeNew = async (id, type) => {
        try {
            const items = await Axios.post(`/like-new-network`, { id, type })
            .then((response) => {
                if (response.data.status == 'success') {
                    setShowLike(false)
                    setTypeLike(type);
                    setIdLikeNew(id);
                    $('.like_new').addClass('active_like_'+ type)
                    $('.total_like_new').html(response.data.total_like)
                } 
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    
    useEffect(() => {
        var channel = window.Echo.join("social")
        channel.listen("SocialNetWorkComment",(event) => {
            setDataAddNewComments(dataAddNewComments => [...dataAddNewComments, event])
        })

        fetchDataDetail();
    }, []);

    useEffect(() => {
        fetchDataImage();
    }, [idImage]);

    useEffect(() => {
        if (dataNew) {
            fetchDataListComment()
        }
    }, [dataNew]);

    return (
        <div className='col-12 wrraped_detail pr-1'>
            <div className="row">
                <div className="col-8 content_left">
                    <div className="close_detail cursor_pointer" onClick={() => navigate(-1)}><i className="fas fa-times"></i></div>
                    {
                        dataImage.prev && (
                            <div className="prev_detail cursor_pointer">
                                <Link to={`/social-network/detail/photo/${dataNew.id}/${dataImage.prev}`}>
                                    <i className="fas fa-angle-left"></i>
                                </Link>
                            </div>
                        ) 
                    }
                    {
                        !loading && (
                            <Image
                                width={'90%'}
                                height={'100%'}
                                src={dataImage.image}
                            />
                        )
                    }
                    {
                        dataImage.next && (
                            <div className="next_detail cursor_pointer">
                                <Link to={`/social-network/detail/photo/${dataNew.id}/${dataImage.next}`}>
                                    <i className="fas fa-angle-right"></i>
                                </Link>
                            </div>
                        )
                    }
                </div>
                <div className="col-4 content_right bg-white">
                    <div className="row">
                        <div className="col-12 wrapped_info_comment">
                            <div className="info_new row pt-2">
                                <div className="wrapped_user col-10 d_flex_align">
                                    <div className="icon_user mr-2">
                                        <Link to={`/social-network/info/${dataNew.user_id}`}>
                                            <img className='image_profile' src={ dataNew.avatar } alt="" width={'40px'} height="40px"/>
                                        </Link>
                                    </div>
                                    <div className='name_user'>
                                        <Link to={`/social-network/info/${dataNew.user_id}`}>
                                            <div>
                                                <span><strong>{ dataNew.user_name }</strong></span>
                                            </div>
                                        </Link>
                                        <div className="time_add_new">
                                            <span>{ dataNew.created_at2 }</span>
                                            <span className='ml-2'>
                                                {
                                                    dataNew.status == 1 ? (
                                                        <Tooltip title="Công khai">
                                                            <i className="fas fa-globe-americas"></i>
                                                        </Tooltip>
                                                    ) : dataNew.status == 2 ? (
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
                                    <EllipsisOutlined className='mr-3'/>
                                </div>
                            </div>
                            <div className="text_new pl-2 mb-3 mt-2">
                                <span>{ dataNew.title_new }</span>
                            </div>
                            <div className="wrapped_view_like_comment_share row mt-2 mx-0">
                                <div className="col-5 total_like pr-0 pl-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 152 152" width="20px"><g id="Layer_2" data-name="Layer 2"><g id="Color"><g id="_05.Like" data-name="05.Like"><circle id="Background" cx="76" cy="76" fill="#4a6ea9" r="76"/><path d="m56.57 110.74a4.88 4.88 0 0 1 -4.87 4.84h-13.34a4.87 4.87 0 0 1 -4.86-4.84v-38a4.88 4.88 0 0 1 4.86-4.85h13.34a4.88 4.88 0 0 1 4.87 4.85zm56.79-29.81a6.63 6.63 0 0 1 -2.66 11.63 6.62 6.62 0 0 1 -2.64 11.63 6.64 6.64 0 0 1 -4.13 11.81l-37.62-.42a4.86 4.86 0 0 1 -4.84-4.84v-38c0-6 19.19-17.08 20.22-23 .64-3.92-.21-13.74 2.43-13.74 4.48 0 10.23 1.72 10.23 11.67 0 8.78-5.75 20.17-5.75 20.17h23.27a6.63 6.63 0 0 1 1.49 13.09z" fill="#fff"/></g></g></g></svg>
                                    <span className={`ml-1 total_like_new`}>
                                        <span>{dataNew.total_like}</span>  
                                    </span>
                                </div>
                                <div className="col-7 wrapped_comment_share pr-1 pl-0">
                                    <div className='total_comment'>{ dataNew.total_comment } bình luận</div>
                                    <div className='ml-2 total_share'>{ dataNew.total_share } lượt chia sẽ</div>
                                </div>
                            </div>
                            <hr />
                            <div className='row m-0'>
                                <Popover 
                                    visible={showLike}
                                    onVisibleChange={(e) => handleHoverChange(e)}
                                    content={
                                        <div className='d_flex_align list_emoji_like'>
                                            <span className='m-1 cursor_pointer emoji_like' onClick={(e) => likeNew(dataNew.id, 1)}>
                                                <FacebookEmoji type="like" size="sm"/>
                                            </span>
                                            <span className='m-1 cursor_pointer emoji_love' onClick={(e) => likeNew(dataNew.id, 2)}>
                                                <FacebookEmoji type="love" size="sm"/>
                                            </span>
                                            <span className='m-1 cursor_pointer emoji_wow' onClick={(e) => likeNew(dataNew.id, 3)}>
                                                <FacebookEmoji type="wow" size="sm"/>
                                            </span>
                                            <span className='m-1 cursor_pointer emoji_angry' onClick={(e) => likeNew(dataNew.id, 4)}>
                                                <FacebookEmoji type="angry" size="sm"/>
                                            </span>
                                            <span className='m-1 cursor_pointer emoji_haha' onClick={(e) => likeNew(dataNew.id, 5)}>
                                                <FacebookEmoji type="haha" size="sm"/>
                                            </span>
                                            <span className='m-1 cursor_pointer emoji_sad' onClick={(e) => likeNew(dataNew.id, 6)}>
                                                <FacebookEmoji type="sad" size="sm"/>
                                            </span>
                                        </div>
                                    }
                                >
                                    <div className={`col-4 like_new text-center cursor_pointer like_new ${(dataNew.check_like ? `active_like_${dataNew.check_like}` : '')}`} onClick={(e) => likeNew(dataNew.id, 1)}>
                                        <span className='icon_like_new'>
                                            {(() => {
                                                if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 1) || (typeLike == 1 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <i className="fas fa-thumbs-up"></i>
                                                            <span className='ml-1'>Thích</span>
                                                        </>
                                                    )
                                                } else if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 2) || (typeLike == 2 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 1497.8 1500"><path fill="#fff" d="M541.7 1092.6H376.6c-13 0-23.6-10.6-23.6-23.6V689.9c0-13 10.6-23.6 23.6-23.6h165.1c13 0 23.6 10.6 23.6 23.6V1069c-.1 13-10.7 23.6-23.6 23.6zM622.9 1003.5V731.9c0-66.3 18.9-132.9 54.1-189.2 21.5-34.4 69.7-89.5 96.7-118 6-6.4 27.8-25.2 27.8-35.5 0-13.2 1.5-34.5 2-74.2.3-25.2 20.8-45.9 46-45.7h1.1c44.1.8 58.2 41.6 58.2 41.6s37.7 74.4 2.5 165.4c-29.7 76.9-35.7 83.1-35.7 83.1s-9.6 13.9 20.8 13.3c0 0 185.6-.8 192-.8 13.7 0 57.4 12.5 54.9 68.2-1.8 41.2-27.4 55.6-40.5 60.3-2.6.9-2.9 4.5-.5 5.9 13.4 7.8 40.8 27.5 40.2 57.7-.8 36.6-15.5 50.1-46.1 58.5-2.8.8-3.3 4.5-.8 5.9 11.6 6.6 31.5 22.7 30.3 55.3-1.2 33.2-25.2 44.9-38.3 48.9-2.6.8-3.1 4.2-.8 5.8 8.3 5.7 20.6 18.6 20 45.1-.3 14-5 24.2-10.9 31.5-9.3 11.5-23.9 17.5-38.7 17.6l-411.8.8c-.1.1-22.5.1-22.5-29.9z"/><ellipse cx="748.9" cy="750" fill="#ed5168" rx="748.9" ry="750"/><path fill="#fff" d="M748.9 541.9C715.4 338.7 318.4 323.2 318.4 628c0 270.1 430.5 519.1 430.5 519.1s430.5-252.3 430.5-519.1c0-304.8-397-289.3-430.5-86.1z"/></svg>
                                                            <span className='ml-1'>Yêu Thích</span>
                                                        </>
                                                    )
                                                } else if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 3) || (typeLike == 3 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16"><g clipPath="url(#clip0)"><path fill="url(#paint0_linear)" d="M16 8C16 10.1217 15.1571 12.1566 13.6569 13.6569C12.1566 15.1571 10.1217 16 8 16C5.87827 16 3.84344 15.1571 2.34315 13.6569C0.842855 12.1566 0 10.1217 0 8C0 5.87827 0.842855 3.84344 2.34315 2.34315C3.84344 0.842855 5.87827 0 8 0C10.1217 0 12.1566 0.842855 13.6569 2.34315C15.1571 3.84344 16 5.87827 16 8"/><path fill="url(#paint1_linear)" d="M5.6431 10.888C5.4851 12.733 6.3691 14 8.0001 14C9.6301 14 10.5151 12.733 10.3571 10.888C10.2001 9.042 9.2421 8 8.0001 8C6.7581 8 5.8001 9.042 5.6431 10.888Z"/><path fill="url(#paint2_linear)" d="M3.5 5.5C3.5 4.672 4.059 4 4.75 4C5.441 4 6 4.672 6 5.5C6 6.329 5.441 7 4.75 7C4.059 7 3.5 6.329 3.5 5.5ZM10 5.5C10 4.672 10.56 4 11.25 4C11.941 4 12.5 4.672 12.5 5.5C12.5 6.329 11.941 7 11.25 7C10.56 7 10 6.329 10 5.5Z"/><path fill="#000" d="M3.5 5.5C3.5 4.672 4.059 4 4.75 4C5.441 4 6 4.672 6 5.5C6 6.329 5.441 7 4.75 7C4.059 7 3.5 6.329 3.5 5.5ZM10 5.5C10 4.672 10.56 4 11.25 4C11.941 4 12.5 4.672 12.5 5.5C12.5 6.329 11.941 7 11.25 7C10.56 7 10 6.329 10 5.5Z" filter="url(#filter0_i)"/><path fill="#4E506A" d="M4.48146 4.56717C4.66746 4.60917 4.77146 4.81917 4.71346 5.03617C4.65646 5.25417 4.45946 5.39617 4.27346 5.35417C4.08746 5.31217 3.98346 5.10217 4.04146 4.88417C4.09846 4.66817 4.29546 4.52417 4.48146 4.56717ZM11.1395 4.63017C11.3455 4.67717 11.4615 4.91017 11.3975 5.15017C11.3335 5.39317 11.1155 5.55017 10.9085 5.50417C10.7025 5.45817 10.5865 5.22417 10.6505 4.98317C10.7135 4.74117 10.9325 4.58317 11.1405 4.63017H11.1395Z"/><path fill="#000" d="M11.0682 1.69583C11.1202 1.69083 11.1722 1.68883 11.2252 1.68883C11.7122 1.68883 12.2152 1.89283 12.5972 2.25083C12.6661 2.31649 12.7069 2.4063 12.711 2.50139C12.7151 2.59649 12.6822 2.68948 12.6192 2.76083C12.5888 2.79582 12.5515 2.8243 12.5098 2.84454C12.4681 2.86478 12.4227 2.87637 12.3763 2.87861C12.33 2.88085 12.2837 2.8737 12.2402 2.85758C12.1967 2.84147 12.1569 2.81672 12.1232 2.78483C11.8482 2.52583 11.4672 2.38483 11.1312 2.41583C11.0147 2.42455 10.9014 2.45871 10.7995 2.51589C10.6976 2.57308 10.6094 2.65191 10.5412 2.74683C10.5138 2.78403 10.4792 2.81537 10.4395 2.839C10.3998 2.86263 10.3558 2.87807 10.3101 2.88441C10.2643 2.89074 10.2177 2.88785 10.1731 2.8759C10.1285 2.86395 10.0867 2.84319 10.0502 2.81483C9.9755 2.75549 9.92675 2.66945 9.91425 2.57487C9.90175 2.48028 9.92647 2.38454 9.98321 2.30783C10.1092 2.13357 10.2714 1.98864 10.4587 1.883C10.646 1.77736 10.8539 1.71351 11.0682 1.69583V1.69583ZM3.40321 2.25083C3.77398 1.89822 4.26362 1.69765 4.77521 1.68883C5.0169 1.68359 5.25625 1.73719 5.47261 1.84503C5.68898 1.95286 5.87588 2.11169 6.01721 2.30783C6.07381 2.3845 6.09855 2.4801 6.08625 2.57461C6.07394 2.66912 6.02555 2.7552 5.95121 2.81483C5.91465 2.84321 5.87279 2.864 5.82808 2.87596C5.78338 2.88792 5.73673 2.89082 5.69088 2.88448C5.64504 2.87815 5.60092 2.86271 5.56114 2.83906C5.52135 2.81542 5.48669 2.78406 5.45921 2.74683C5.39097 2.65195 5.30279 2.57316 5.20087 2.51598C5.09895 2.4588 4.98574 2.42462 4.86921 2.41583C4.53421 2.38483 4.15221 2.52583 3.87721 2.78483C3.84352 2.81672 3.80373 2.84147 3.76023 2.85758C3.71673 2.8737 3.67042 2.88085 3.62409 2.87861C3.57776 2.87637 3.53235 2.86478 3.49061 2.84454C3.44887 2.8243 3.41166 2.79582 3.38121 2.76083C3.31821 2.68948 3.2853 2.59649 3.2894 2.50139C3.29351 2.4063 3.3343 2.31649 3.40321 2.25083V2.25083Z" filter="url(#filter1_d)"/><path fill="url(#paint3_linear)" d="M11.0682 1.69583C11.1202 1.69083 11.1722 1.68883 11.2252 1.68883C11.7122 1.68883 12.2152 1.89283 12.5972 2.25083C12.6661 2.31649 12.7069 2.4063 12.711 2.50139C12.7151 2.59649 12.6822 2.68948 12.6192 2.76083C12.5888 2.79582 12.5515 2.8243 12.5098 2.84454C12.4681 2.86478 12.4227 2.87637 12.3763 2.87861C12.33 2.88085 12.2837 2.8737 12.2402 2.85758C12.1967 2.84147 12.1569 2.81672 12.1232 2.78483C11.8482 2.52583 11.4672 2.38483 11.1312 2.41583C11.0147 2.42455 10.9014 2.45871 10.7995 2.51589C10.6976 2.57308 10.6094 2.65191 10.5412 2.74683C10.5138 2.78403 10.4792 2.81537 10.4395 2.839C10.3998 2.86263 10.3558 2.87807 10.3101 2.88441C10.2643 2.89074 10.2177 2.88785 10.1731 2.8759C10.1285 2.86395 10.0867 2.84319 10.0502 2.81483C9.9755 2.75549 9.92675 2.66945 9.91425 2.57487C9.90175 2.48028 9.92647 2.38454 9.98321 2.30783C10.1092 2.13357 10.2714 1.98864 10.4587 1.883C10.646 1.77736 10.8539 1.71351 11.0682 1.69583V1.69583ZM3.40321 2.25083C3.77398 1.89822 4.26362 1.69765 4.77521 1.68883C5.0169 1.68359 5.25625 1.73719 5.47261 1.84503C5.68898 1.95286 5.87588 2.11169 6.01721 2.30783C6.07381 2.3845 6.09855 2.4801 6.08625 2.57461C6.07394 2.66912 6.02555 2.7552 5.95121 2.81483C5.91465 2.84321 5.87279 2.864 5.82808 2.87596C5.78338 2.88792 5.73673 2.89082 5.69088 2.88448C5.64504 2.87815 5.60092 2.86271 5.56114 2.83906C5.52135 2.81542 5.48669 2.78406 5.45921 2.74683C5.39097 2.65195 5.30279 2.57316 5.20087 2.51598C5.09895 2.4588 4.98574 2.42462 4.86921 2.41583C4.53421 2.38483 4.15221 2.52583 3.87721 2.78483C3.84352 2.81672 3.80373 2.84147 3.76023 2.85758C3.71673 2.8737 3.67042 2.88085 3.62409 2.87861C3.57776 2.87637 3.53235 2.86478 3.49061 2.84454C3.44887 2.8243 3.41166 2.79582 3.38121 2.76083C3.31821 2.68948 3.2853 2.59649 3.2894 2.50139C3.29351 2.4063 3.3343 2.31649 3.40321 2.25083V2.25083Z"/></g><defs><linearGradient id="paint0_linear" x1="8" x2="8" y1="1.64" y2="16" gradientUnits="userSpaceOnUse"><stop stopColor="#FEEA70"/><stop offset="1" stopColor="#F69B30"/></linearGradient><linearGradient id="paint1_linear" x1="8" x2="8" y1="8" y2="14" gradientUnits="userSpaceOnUse"><stop stopColor="#472315"/><stop offset="1" stopColor="#8B3A0E"/></linearGradient><linearGradient id="paint2_linear" x1="8" x2="8" y1="4" y2="7" gradientUnits="userSpaceOnUse"><stop stopColor="#191A33"/><stop offset=".872" stopColor="#3B426A"/></linearGradient><linearGradient id="paint3_linear" x1="8" x2="8" y1="1.688" y2="2.888" gradientUnits="userSpaceOnUse"><stop stopColor="#E78E0D"/><stop offset="1" stopColor="#CB6000"/></linearGradient><filter id="filter0_i" width="9" height="3" x="3.5" y="4" colorInterpolationFilters="sRGB" filterUnits="userSpaceOnUse"><feFlood floodOpacity="0" result="BackgroundImageFix"/><feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/><feColorMatrix in="SourceAlpha" result="hardAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/><feOffset/><feGaussianBlur stdDeviation=".5"/><feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"/><feColorMatrix values="0 0 0 0 0.0980392 0 0 0 0 0.101961 0 0 0 0 0.2 0 0 0 0.819684 0"/><feBlend in2="shape" result="effect1_innerShadow"/></filter><filter id="filter1_d" width="15.422" height="7.199" x=".289" y="-.312" colorInterpolationFilters="sRGB" filterUnits="userSpaceOnUse"><feFlood floodOpacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/><feOffset dy="1"/><feGaussianBlur stdDeviation="1.5"/><feColorMatrix values="0 0 0 0 0.803922 0 0 0 0 0.388235 0 0 0 0 0.00392157 0 0 0 0.145679 0"/><feBlend in2="BackgroundImageFix" result="effect1_dropShadow"/><feBlend in="SourceGraphic" in2="effect1_dropShadow" result="shape"/></filter><clipPath id="clip0"><rect width="16" height="16" fill="#fff"/></clipPath></defs></svg>
                                                            <span className='ml-1'>Wow</span>
                                                        </>
                                                    )
                                                } else if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 4) || (typeLike == 4 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" data-name="Layer 1" viewBox="0 0 512 512"><defs><linearGradient id="a" x1="266.39" x2="266.39" y1="496.01" y2="15.99" gradientUnits="userSpaceOnUse"><stop offset="0" stopColor="#ffde6e"/><stop offset="1" stopColor="#ff4c41"/></linearGradient></defs><path fill="url(#a)" d="M266.39 16c-132.56 0-240 107.45-240 240s107.45 240 240 240 240-107.45 240-240-107.45-240-240-240zM161.17 342a25.53 25.53 0 0 1-21.59-39.26c-30.51-9-51.41-22.53-52.45-23.21a11.27 11.27 0 0 1 12.35-18.85 198 198 0 0 0 63.6 24.66c36.67 7.05 64.92 4.1 65.21 4.08a11.39 11.39 0 0 1 12.43 10 11.26 11.26 0 0 1-9.95 12.43 179.86 179.86 0 0 1-18.56.8c-7.05 0-15.93-.36-25.94-1.29a25.58 25.58 0 0 1-25.1 30.64zm103.57 70.88c-42.15 0-74.21-2.09-74.21-12.21s34.17-18.32 76.33-18.32 76.32 8.21 76.32 18.32-36.29 12.2-78.44 12.2zm180.91-133.36c-1 .68-21.94 14.19-52.46 23.21a25.58 25.58 0 1 1-46.68 8.6c-10 .93-18.89 1.29-25.94 1.29a179.64 179.64 0 0 1-18.56-.8 11.25 11.25 0 0 1-9.95-12.43 11.38 11.38 0 0 1 12.43-10c.28 0 28.54 3 65.21-4.08a197.87 197.87 0 0 0 63.59-24.66 11.27 11.27 0 0 1 12.36 18.85z"/><path fill="#102236" d="M448.89 263.92a11.25 11.25 0 00-15.6-3.25 197.87 197.87 0 01-63.59 24.66c-36.67 7.05-64.93 4.1-65.21 4.08a11.38 11.38 0 00-12.43 10 11.25 11.25 0 009.94 12.41 179.64 179.64 0 0018.56.8c7.05 0 15.93-.36 25.94-1.29a25.61 25.61 0 1046.68-8.6c30.52-9 51.42-22.53 52.46-23.21a11.26 11.26 0 003.25-15.6zM212.21 312.62a179.86 179.86 0 0018.56-.8 11.26 11.26 0 009.95-12.43 11.39 11.39 0 00-12.43-10c-.29 0-28.54 3-65.21-4.08a198 198 0 01-63.6-24.66 11.27 11.27 0 00-12.35 18.85c1 .68 21.94 14.19 52.45 23.21a25.58 25.58 0 1046.69 8.6c10.01.95 18.89 1.31 25.94 1.31zM266.86 382.34c-42.16 0-76.33 8.21-76.33 18.32s32.06 12.21 74.21 12.21 78.44-2.09 78.44-12.21-34.18-18.32-76.32-18.32z"/></svg>
                                                            <span className='ml-1'>Phẫn nộ</span>
                                                        </>
                                                    )
                                                } else if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 5) || (typeLike == 5 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16"><path fill="url(#paint0_linear)" d="M16 8C16 10.1217 15.1571 12.1566 13.6569 13.6569C12.1566 15.1571 10.1217 16 8 16C5.87827 16 3.84344 15.1571 2.34315 13.6569C0.842855 12.1566 0 10.1217 0 8C0 5.87827 0.842855 3.84344 2.34315 2.34315C3.84344 0.842855 5.87827 0 8 0C10.1217 0 12.1566 0.842855 13.6569 2.34315C15.1571 3.84344 16 5.87827 16 8"/><path fill="url(#paint1_linear)" d="M3 8.008C3 10.023 4.006 14 8 14C11.993 14 13 10.023 13 8.008C13 7.849 11.39 7 8 7C4.61 7 3 7.849 3 8.008Z"/><path fill="url(#paint2_linear)" d="M4.54102 12.5C5.34502 13.495 6.44802 14 8.01002 14C9.57302 14 10.665 13.495 11.469 12.5C10.918 11.912 9.87002 11 8.01002 11C6.15002 11 5.09302 11.912 4.54102 12.5Z"/><path fill="#2A3755" d="M6.21297 4.14378C6.47597 4.33178 6.71497 4.59878 6.62297 4.93178C6.55197 5.18578 6.42897 5.30078 6.20097 5.30278C5.42097 5.31378 4.49297 5.55778 3.69497 5.91478C3.62997 5.94378 3.49797 6.00278 3.36297 5.99978C3.23897 5.99678 3.11197 5.94178 3.03597 5.76278C2.96897 5.60578 2.96297 5.37478 3.31197 5.16478C3.85697 4.83478 4.56897 4.68478 5.22097 4.56078C4.81154 4.25823 4.37065 4.00074 3.90597 3.79278C3.47897 3.59878 3.52597 3.33578 3.58297 3.19278C3.70997 2.87578 4.19197 2.99678 4.66097 3.21878C5.20802 3.47428 5.72797 3.78417 6.21297 4.14378V4.14378ZM9.78997 4.14378C10.2742 3.78402 10.7935 3.47411 11.34 3.21878C11.81 2.99678 12.29 2.87578 12.418 3.19278C12.475 3.33578 12.522 3.59878 12.095 3.79278C11.6308 4.00053 11.1906 4.25804 10.782 4.56078C11.432 4.68378 12.145 4.83478 12.689 5.16478C13.038 5.37478 13.031 5.60478 12.965 5.76278C12.888 5.94278 12.762 5.99678 12.638 5.99978C12.503 6.00278 12.371 5.94378 12.306 5.91478C11.509 5.55778 10.581 5.31478 9.80197 5.30278C9.57397 5.30078 9.45097 5.18578 9.37997 4.93278C9.28897 4.59978 9.52697 4.33278 9.78997 4.14478V4.14378Z"/><defs><linearGradient id="paint0_linear" x1="8" x2="8" y1="1.64" y2="16" gradientUnits="userSpaceOnUse"><stop stopColor="#FEEA70"/><stop offset="1" stopColor="#F69B30"/></linearGradient><linearGradient id="paint1_linear" x1="8" x2="8" y1="7" y2="14" gradientUnits="userSpaceOnUse"><stop stopColor="#472315"/><stop offset="1" stopColor="#8B3A0E"/></linearGradient><linearGradient id="paint2_linear" x1="8.005" x2="8.005" y1="11" y2="13.457" gradientUnits="userSpaceOnUse"><stop stopColor="#FC607C"/><stop offset="1" stopColor="#D91F3A"/></linearGradient></defs></svg>
                                                            <span className='ml-1'>Ha ha</span>
                                                        </>
                                                    )
                                                } else if ((idLikeNew != dataNew.id_like_new && dataNew.check_like == 6) || (typeLike == 6 && idLikeNew == dataNew.id)) {
                                                    return (
                                                        <>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 16 16"><path fill="url(#paint0_linear)" d="M16 8C16 10.1217 15.1571 12.1566 13.6569 13.6569C12.1566 15.1571 10.1217 16 8 16C5.87827 16 3.84344 15.1571 2.34315 13.6569C0.842855 12.1566 0 10.1217 0 8C0 5.87827 0.842855 3.84344 2.34315 2.34315C3.84344 0.842855 5.87827 0 8 0C10.1217 0 12.1566 0.842855 13.6569 2.34315C15.1571 3.84344 16 5.87827 16 8"/><path fill="url(#paint1_linear)" d="M5.33301 12.765C5.33301 12.902 5.42701 13 5.58301 13C5.93401 13 6.41901 12.375 8.00001 12.375C9.58101 12.375 10.067 13 10.417 13C10.573 13 10.667 12.902 10.667 12.765C10.667 12.368 9.82801 11 8.00001 11C6.17201 11 5.33301 12.368 5.33301 12.765Z"/><path fill="url(#paint2_linear)" d="M3.59872 8.79998C3.59872 7.98998 4.10772 7.33398 4.73272 7.33398C5.35972 7.33398 5.86672 7.98998 5.86672 8.79998C5.86672 9.13798 5.77772 9.44998 5.62872 9.69798C5.56211 9.80986 5.45387 9.89077 5.32772 9.92298C5.18772 9.95998 4.97472 9.99998 4.73272 9.99998C4.48972 9.99998 4.27972 9.95998 4.13772 9.92298C4.01184 9.89073 3.90393 9.8098 3.83772 9.69798C3.67817 9.42583 3.59556 9.11544 3.59872 8.79998V8.79998ZM10.1327 8.79998C10.1327 7.98998 10.6407 7.33398 11.2657 7.33398C11.8927 7.33398 12.3997 7.98998 12.3997 8.79998C12.3997 9.13798 12.3107 9.44998 12.1617 9.69798C12.1288 9.75345 12.0853 9.80188 12.0336 9.84049C11.982 9.8791 11.9232 9.90713 11.8607 9.92298C11.4708 10.024 11.0616 10.024 10.6717 9.92298C10.6092 9.90713 10.5504 9.8791 10.4988 9.84049C10.4471 9.80188 10.4036 9.75345 10.3707 9.69798C10.2115 9.42575 10.1292 9.11536 10.1327 8.79998V8.79998Z"/><path fill="#000" d="M3.59872 8.79998C3.59872 7.98998 4.10772 7.33398 4.73272 7.33398C5.35972 7.33398 5.86672 7.98998 5.86672 8.79998C5.86672 9.13798 5.77772 9.44998 5.62872 9.69798C5.56211 9.80986 5.45387 9.89077 5.32772 9.92298C5.18772 9.95998 4.97472 9.99998 4.73272 9.99998C4.48972 9.99998 4.27972 9.95998 4.13772 9.92298C4.01184 9.89073 3.90393 9.8098 3.83772 9.69798C3.67817 9.42583 3.59556 9.11544 3.59872 8.79998V8.79998ZM10.1327 8.79998C10.1327 7.98998 10.6407 7.33398 11.2657 7.33398C11.8927 7.33398 12.3997 7.98998 12.3997 8.79998C12.3997 9.13798 12.3107 9.44998 12.1617 9.69798C12.1288 9.75345 12.0853 9.80188 12.0336 9.84049C11.982 9.8791 11.9232 9.90713 11.8607 9.92298C11.4708 10.024 11.0616 10.024 10.6717 9.92298C10.6092 9.90713 10.5504 9.8791 10.4988 9.84049C10.4471 9.80188 10.4036 9.75345 10.3707 9.69798C10.2115 9.42575 10.1292 9.11536 10.1327 8.79998V8.79998Z" filter="url(#filter0_i)"/><path fill="#4E506A" d="M4.61595 7.98556C4.74395 8.11056 4.75195 8.35756 4.63295 8.53656C4.51295 8.71456 4.31295 8.75856 4.18495 8.63256C4.05695 8.50756 4.04995 8.26056 4.16795 8.08256C4.28795 7.90356 4.48795 7.86056 4.61595 7.98556V7.98556ZM11.105 7.98556C11.233 8.11056 11.241 8.35756 11.123 8.53656C11.003 8.71456 10.803 8.75856 10.673 8.63256C10.546 8.50756 10.539 8.26056 10.658 8.08256C10.777 7.90356 10.977 7.86056 11.105 7.98556V7.98556Z"/><path fill="url(#paint3_linear)" d="M4.1572 5.15259C4.4892 4.99959 4.7532 4.93359 4.9582 4.93359C5.2352 4.93359 5.4092 5.05259 5.5082 5.23959C5.6832 5.56859 5.6042 5.64059 5.3102 5.69859C4.2042 5.92259 3.0932 6.64059 2.6112 7.08859C2.3102 7.36859 2.0222 7.05859 2.1752 6.81459C2.3292 6.57059 2.9492 5.70959 4.1572 5.15259V5.15259ZM10.4922 5.23959C10.5912 5.05259 10.7652 4.93359 11.0422 4.93359C11.2482 4.93359 11.5112 4.99959 11.8432 5.15259C13.0512 5.70959 13.6712 6.57059 13.8242 6.81459C13.9772 7.05859 13.6902 7.36859 13.3892 7.08859C12.9062 6.64059 11.7962 5.92259 10.6892 5.69859C10.3952 5.64059 10.3182 5.56859 10.4922 5.23959V5.23959Z"/><path fill="url(#paint4_linear)" d="M13.5 16C12.672 16 12 15.252 12 14.329C12 13.407 12.356 12.784 12.643 12.182C13.241 10.924 13.359 10.75 13.5 10.75C13.641 10.75 13.759 10.924 14.357 12.182C14.644 12.784 15 13.407 15 14.329C15 15.252 14.328 16 13.5 16Z"/><path fill="url(#paint5_linear)" d="M13.5002 13.6063C13.1722 13.6063 12.9062 13.3103 12.9062 12.9463C12.9062 12.5803 13.0473 12.3333 13.1613 12.0943C13.3973 11.5963 13.4442 11.5283 13.5002 11.5283C13.5562 11.5283 13.6032 11.5963 13.8392 12.0943C13.9532 12.3343 14.0942 12.5803 14.0942 12.9453C14.0942 13.3103 13.8282 13.6063 13.5002 13.6063"/><defs><linearGradient id="paint0_linear" x1="8" x2="8" y1="1.64" y2="16" gradientUnits="userSpaceOnUse"><stop stopColor="#FEEA70"/><stop offset="1" stopColor="#F69B30"/></linearGradient><linearGradient id="paint1_linear" x1="8" x2="8" y1="11" y2="13" gradientUnits="userSpaceOnUse"><stop stopColor="#472315"/><stop offset="1" stopColor="#8B3A0E"/></linearGradient><linearGradient id="paint2_linear" x1="7.999" x2="7.999" y1="7.334" y2="10" gradientUnits="userSpaceOnUse"><stop stopColor="#191A33"/><stop offset=".872" stopColor="#3B426A"/></linearGradient><linearGradient id="paint3_linear" x1="8" x2="8" y1="4.934" y2="7.199" gradientUnits="userSpaceOnUse"><stop stopColor="#E78E0D"/><stop offset="1" stopColor="#CB6000"/></linearGradient><linearGradient id="paint4_linear" x1="13.5" x2="13.5" y1="15.05" y2="11.692" gradientUnits="userSpaceOnUse"><stop stopColor="#35CAFC"/><stop offset="1" stopColor="#007EDB"/></linearGradient><linearGradient id="paint5_linear" x1="13.5" x2="13.5" y1="11.528" y2="13.606" gradientUnits="userSpaceOnUse"><stop stopColor="#6AE1FF" stopOpacity=".287"/><stop offset="1" stopColor="#A8E3FF" stopOpacity=".799"/></linearGradient><filter id="filter0_i" width="8.801" height="2.666" x="3.599" y="7.334" colorInterpolationFilters="sRGB" filterUnits="userSpaceOnUse"><feFlood floodOpacity="0" result="BackgroundImageFix"/><feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/><feColorMatrix in="SourceAlpha" result="hardAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/><feOffset/><feGaussianBlur stdDeviation=".5"/><feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"/><feColorMatrix values="0 0 0 0 0.0411227 0 0 0 0 0.0430885 0 0 0 0 0.0922353 0 0 0 0.819684 0"/><feBlend in2="shape" result="effect1_innerShadow"/></filter></defs></svg>
                                                            <span className='ml-1'>Buồn</span>
                                                        </>
                                                    )
                                                } else {
                                                    return (
                                                        <>
                                                            <i className="far fa-thumbs-up"></i>
                                                            <span className='ml-1'>Thích</span>
                                                        </>
                                                    )
                                                }
                                            })()}
                                        </span>
                                    </div>
                                </Popover>
                                <div className={`col-4 comment_new text-center cursor_pointer`}>
                                    <i className="far fa-comment-alt"></i>
                                    <span className='ml-1'>Bình luận</span>
                                </div>
                                <div className={`col-4 share_new text-center cursor_pointer`}>
                                    <i className="far fa-share-square"></i>
                                    <span className='ml-1'>Chia sẽ</span>
                                </div>
                            </div>
                            <hr />
                            <div className="all_comment">
                                {
                                    loadingComment ? (
                                        <div className="col-12 text-center my-2">
                                            <Spin />
                                        </div>
                                    ) : (
                                        <div className='row'>
                                            {
                                                dataAddNewComments.length > 0 && (
                                                    <div>
                                                        {
                                                            dataAddNewComments.reverse().map((addComment) => (
                                                                <div key={addComment.id} className="mb-3 col-12">
                                                                    <div  className="d-flex">
                                                                        <img className='image_profile' src={ addComment.avatar } alt="" width={'35px'} height={'35ps'}/>
                                                                        <div className='ml-2'>
                                                                            <div className='profile_user_comment'>
                                                                                <span><strong>{ addComment.user_name }</strong></span>
                                                                                <div><span>{ addComment.comment }</span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div className='set_comment ml-5'>
                                                                        <LikeComment dataIdLike={''} id={addComment.id} checkLike={""} type={0}/>
                                                                        <span className='ml-2 cursor_pointer' onClick={(e) => showReplyHandle(addComment.id)}>
                                                                            { addComment.total_reply }
                                                                            <span className='ml-1'>Phản hồi</span>
                                                                        </span>
                                                                    </div>
                                                                    <Reply showReply={showReply} comment_id={addComment.id} data_id={dataNew.id} auth={auth}/>
                                                                </div>
                                                            ))
                                                        }
                                                    </div>
                                                )
                                            }
                                            {
                                                dataNewComments.map((comment) => (
                                                    <div key={comment.id} className="mb-3 col-12">
                                                        <div  className="d-flex">
                                                            <img className='image_profile' src={ comment.avatar } alt="" width={'35px'} height={'35ps'}/>
                                                            <div className='ml-2'>
                                                                <div className='profile_user_comment'>
                                                                    <span><strong>{ comment.user_name }</strong></span>
                                                                    <div><span>{ comment.comment }</span></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className='set_comment ml-5'>
                                                            <LikeComment dataIdLike={comment.id_like_comment} id={comment.id} checkLike={comment.check_like} type={0}/>
                                                            <span className='ml-2 cursor_pointer' onClick={(e) => showReplyHandle(comment.id)}>
                                                                { comment.total_reply }
                                                                <span className='ml-1'>Phản hồi</span>
                                                            </span>
                                                        </div>
                                                        <Reply showReply={showReply} comment_id={comment.id} data_id={dataNew.id} auth={auth}/>
                                                    </div>
                                                ))
                                            }
                                            {
                                                currentPage < lastPage && (
                                                    <h4 className='ml-3 pb-3 cursor_pointer see_more_comment' onClick={fetchComment}>Xem thêm bình { totalPage - dataNewComments.length } luận</h4>
                                                )
                                            }
                                        </div>
                                    )
                                }
                            </div>
                        </div>
                    </div>
                    <div className="row wrapped_input_comment">
                        <div className="col-12">
                            <div className='auth_comment d_flex_align'>
                                <img className='image_profile' src={ auth.avatar } alt="" width={'35px'} height="35px"/>
                                <InputEmoji
                                    value={comment}
                                    onChange={setComment}
                                    cleanOnEnter
                                    onEnter={(e) => handleOnEnter(dataNew.id, e)}
                                    placeholder="Viết bình luận"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DetailPostPhoto;