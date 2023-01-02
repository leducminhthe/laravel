import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import { Input } from 'antd';

const SituationDetail = ({text}) => {
    const { id } = useParams();
    const { topic_id } = useParams();
    const [situation, setSituation] = useState([]);
    const [topic, setTopic] = useState('');
    const [loading, setLoading] = useState(true);
    const [userComments, setUserComments] = useState([]);
    const [replyComment, setReplyComment] = useState("");
    const [comment, setComment] = useState("");
    const [showReply, setShowReply] = useState('');
    const [profile, setProfile] = useState('');
    const { TextArea } = Input;

    const handleLikeComment = async (comment_id) => {
        try {
            const items = await Axios.post(`/user-like-comment-situation`,{ comment_id })
            .then((response) => {
                console.log(response);
                if (response.data.check_like == 1) {
                    $('#show_like_comment_'+comment_id).html(`<div class="show_like">
                                                                <span class="thumb_like pr-1"><i class="fas fa-thumbs-up"></i></span>
                                                                <span class="count_like_`+comment_id+`">`+ response.data.view_like +`</span>
                                                            </div>`);
                    $('.like_comment_id_'+comment_id).addClass('color_blue');
                } else if (response.data.check_like == 0 && response.data.view_like == 0) {
                    $('#show_like_comment_'+comment_id).html('');
                    $('.like_comment_id_'+comment_id).removeClass('color_blue');
                } else {
                    $('.count_like_'+comment_id).html(response.data.view_like);
                    $('.like_comment_id_'+comment_id).removeClass('color_blue');
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleDeleteComment = async (id,type) => {
        try {
            const items = await Axios.post(`/user-delete-comment-situation`,{ id, type })
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

    const handleLikeReply = async (reply_id) => {
        try {
            const items = await Axios.post(`/user-like-reply-situation`,{ reply_id })
            .then((response) => {
                console.log(response);
                if (response.data.check_like == 1) {
                    $('#show_like_reply_'+reply_id).html(`<div class="show_like">
                                                                <span class="thumb_like pr-1"><i class="fas fa-thumbs-up"></i></span>
                                                                <span class="count_like_`+reply_id+`">`+ response.data.view_like +`</span>
                                                            </div>`);
                    $('.like_reply_id_'+reply_id).addClass('color_blue');
                } else if (response.data.check_like == 0 && response.data.view_like == 0) {
                    $('#show_like_reply_'+reply_id).html('');
                    $('.like_reply_id_'+reply_id).removeClass('color_blue');
                } else {
                    $('.count_like_reply_'+reply_id).html(response.data.view_like);
                    $('.like_reply_id_'+reply_id).removeClass('color_blue');
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleReplyComment = (comment_id) => {
        setShowReply(comment_id);
    }

    const handleChange = (e,type) => {
        if (type == 0) {
            setComment(e.target.value);
        } else {
            setReplyComment(e.target.value);
        }
    };

    const handleKeypress = async (e, type, comment_id) => {
        if (type == 0) {
            if (e.key === "Enter" && e.shiftKey == false && e.target.value) {
                e.preventDefault();
                setComment('');
                var comment = e.target.value;
                try {
                    const items = await Axios.post(`/user-comment-situation`,{ comment_id, comment, topic_id, id })
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
        } else {
            if (e.key === "Enter" && e.shiftKey == false && e.target.value) {
                e.preventDefault();
                setReplyComment('');
                setShowReply('');
                var replyComment = e.target.value;
                try {
                    const items = await Axios.post(`/user-reply-comment-situation`,{ comment_id, replyComment })
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
    };

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-situation-detail/${topic_id}/${id}`)
                .then((response) => {
                    setSituation(response.data.situation),
                    setTopic(response.data.topic),
                    setProfile(response.data.profile),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, []);

    const fetchDataComment = async () => {
        try {
            const items = await Axios.get(`/data-comment-situation/${topic_id}/${id}`)
            .then((response) => {
                setUserComments(response.data.comments)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataComment();
    }, []);

    return (
        <div className="container-fluid">
            <div className="row mb-3">
                <div className="col-md-12">
                    <div className="ibox-content forum-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to="/topic-situation-react" className="font-weight-bold">{text.topic_situation}</Link>
                            <i className="uil uil-angle-right"></i>
                            {
                                !loading && (
                                <>
                                    <Link to={`/topic-situation-react/situation/${topic.id}`} className="font-weight-bold">{topic.name}</Link>
                                    <i className="uil uil-angle-right"></i>
                                    <span className="font-weight-bold">{ situation.name }</span>
                                </>
                                )
                            }
                            
                        </h2>
                    </div>
                </div>
            </div>
            <div className="row m-0 detail_situation">
            {
                loading ? (
                    <div className="col-12 ajax-loading text-center mb-5">
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div> 
                ) : (
                <>
                    <div className="col-md-12">
                        <div className="wrapped_detail">
                            <ul className="situation_view_like_time">
                                <li>
                                    <p>{ situation.view } <i className="fas fa-eye"></i></p>
                                </li>
                                <li>
                                    <p>{ situation.like } <i className="far fa-thumbs-up"></i></p>
                                </li>
                                <li>
                                    <p><i className='fa fa-calendar'></i> { situation.created_at2 }</p>
                                </li>
                            </ul>
                            <div className="situation_name pb-1 pt-2">
                                <h3>{ situation.name }</h3>
                            </div>
                            <div className="situation_code py-1">
                                <span>{text.code}: { situation.code }</span>
                            </div>

                        </div>
                    </div>
                    <div className="col-12 text-justify">
                        <div className="situation_description mt-2">
                            <span>{text.description}</span>
                            <div className="des_html" dangerouslySetInnerHTML={{ __html: situation.description }}></div>
                        </div>
                    </div>
                    <div className="col-12">
                        <div className="student_comment_situation">
                            <div className="row all_comment_situation">
                                <div className="col-12">
                                    <div className='user_comment'>
                                        <img className='mr-2 avartar_user_comment' src={profile.profile_avatar} alt="" width="45px" height="45px"/>
                                        <TextArea
                                            className="text_user_comment"
                                            placeholder={text.write_comment}
                                            autoSize
                                            allowClear
                                            rows="1"
                                            value={comment}
                                            onChange={(e) => handleChange(e,0)}
                                            onPressEnter={(e) => handleKeypress(e,0)}
                                        /> 
                                    </div>
                                </div>
                                <div className="col-lg-12">
                                {
                                    userComments.map((comment) => (
                                        <div key={comment.id} className="review_item">
                                            <div className="review_usr_dt">
                                                <img src={comment.profile_avatar} alt="" />
                                                <div className="rv1458">
                                                    <p className="name_user_comment">{ comment.fullname }</p>
                                                    <p className="mb-1 content_user_comment">{ comment.comment }</p>
                                                    <div id={`show_like_comment_${comment.id}`}>
                                                    {
                                                        comment.like_comment > 0 && (
                                                            <div className="show_like">
                                                                <span className="thumb_like pr-1"><i className="fas fa-thumbs-up"></i></span>
                                                                <span className={`count_like count_like_${comment.id}`}>{ comment.like_comment }</span>
                                                            </div>
                                                        )
                                                    }
                                                    </div>
                                                </div>
                                                {
                                                    comment.user_comment == 1 && (
                                                        <div className='delete_comment'>
                                                            <i onClick={() => handleDeleteComment(comment.id,0)} className="fas fa-trash text-danger"></i>
                                                        </div>
                                                    )
                                                }
                                            </div>  
                                            <div className="set_comment">
                                                <span className={`like_comment_id_${comment.id} like_comment mr-2`+ (comment.check_like == 1 ?  ` color_blue` : ``) } 
                                                    onClick={() => handleLikeComment(comment.id)}
                                                >
                                                    {text.like}
                                                </span>
                                                {
                                                    comment.user_comment == 0 && (
                                                        <span className="reply_comment mr-2" onClick={() => handleReplyComment(comment.id)}>{text.replied}</span>
                                                    )
                                                }
                                                <span>{ comment.created_at2 }</span>
                                            </div>   
                                            {
                                                showReply == comment.id ? (
                                                    <TextArea
                                                        className={`my-2 text_reply_comment reply_comment_${comment.id}`}
                                                        placeholder={text.write_comment}
                                                        autoSize
                                                        allowClear
                                                        rows="1"
                                                        value={replyComment}
                                                        onChange={(e) => handleChange(e,1)}
                                                        onPressEnter={(e) => handleKeypress(e, 1, comment.id)}
                                                    /> 
                                                ) : ('')
                                            }
                                            {
                                                comment.reply_comments.length > 0 && (
                                                <>
                                                {
                                                    comment.reply_comments.map((reply) => (
                                                        <div key={reply.id} className='user_reply_comment'>
                                                            <div className="review_usr_dt">
                                                                <img src={reply.profile_avatar} alt="" />
                                                                <div className="rv1458">
                                                                    <p className="name_user_comment">{ reply.profile_full_name }</p>
                                                                    <p className="mb-1 content_user_comment">{ reply.comment }</p>
                                                                    <div id={`show_like_reply_${reply.id}`}>
                                                                    {
                                                                        reply.like > 0 && (
                                                                            <div className="show_like">
                                                                                <span className="thumb_like pr-1"><i className="fas fa-thumbs-up"></i></span>
                                                                                <span className={`count_like_reply_${reply.id}`}>{ reply.like }</span>
                                                                            </div>
                                                                        )
                                                                    }
                                                                    </div>
                                                                </div>
                                                                {
                                                                    comment.user_reply == 1 && (
                                                                        <div className='delete_comment'>
                                                                            <i onClick={() => handleDeleteComment(reply.id,1)} className="fas fa-trash text-danger"></i>
                                                                        </div>
                                                                    )
                                                                }
                                                            </div>  
                                                            <div className="set_comment">
                                                                <span className={`like_reply_id_${reply.id} like_comment mr-2`+ (reply.check_like == 1 ?  ` color_blue` : ``) } 
                                                                    onClick={() => handleLikeReply(reply.id)}
                                                                >
                                                                    {text.like}
                                                                </span>
                                                                <span>{ reply.created_at2 }</span>
                                                            </div>  
                                                        </div>
                                                    ))
                                                }
                                                </>
                                                )
                                            }
                                        </div>
                                    ))
                                }
                                </div>
                            </div>
                        </div>
                    </div>
                </>
                )
            }
            </div>
        </div>
    );
};

export default SituationDetail;