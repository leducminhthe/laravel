import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';
import Axios from 'axios';

const CommentSuggest = ({text}) => {
    const { id } = useParams();
    const [suggest, setSuggest] = useState([]);
    const [comments, setComments] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/get-comments-suggest/${id}`)
                .then((response) => {
                    setSuggest(response.data.suggest),
                    setComments(response.data.comments),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    const addComment = async () => {
        var content = $('#comment_content').val();
        if (content.length > 0){
            try {
                const items = await Axios.post(`/save-user-comment/${id}`,{ content })
                .then((response) => {
                    $("textarea[name=content]").val('');
                    const comment = {
                        profile_full_name: response.data.profile_full_name,
                        profile_code: response.data.profile_code,
                        created_at2: response.data.created_at2,
                        content: response.data.content,
                    };
                    setComments(comments => [...comments, comment])
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        } else {
            show_message('Mời nhập nội dung', 'error');
            return false;
        }
    }

    return (
        <div className="container-fluid suggest-container sa4d25">
            <div className="row">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content suggest-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to={'/suggest-react'} >{text.suggest}</Link>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{text.comment}</span>
                        </h2>
                        {
                            loading ? (
                                <div className='row'>
                                    <div className="col-12 ajax-loading text-center m-5">
                                        <div className="spinner-border" role="status">
                                            <span className="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div className="comment-suggest">
                                    <div className="card border-primary my-3">
                                        <div className="card-header text-white bg-comment-suggest">
                                            { suggest.name }
                                        </div>
                                        <div className="card-body">
                                            <p className="card-text">{ suggest.content }</p>
                                        </div>
                                    </div>
                                    <div className="form-group row">
                                        <div className="col-md-12" id="list-comment">
                                            {
                                                comments.length > 0 && (
                                                    <>
                                                        {
                                                            comments.map((comment, key) => (
                                                                <div key={key} className="card mb-3">
                                                                    <div className="card-header">
                                                                        <div className="row">
                                                                            <div className="col-md-6">
                                                                                <span className='info_user_comment'>{ comment.profile_full_name } - { comment.profile_code }</span>
                                                                            </div>

                                                                            <div className="col-md-6">
                                                                                <div className="time-comment text-right">
                                                                                    <time>{text.time}: { comment.created_at2 }</time>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="card-body">
                                                                        <p className="card-text">{ comment.content }</p>
                                                                    </div>
                                                                </div>
                                                            ))
                                                        }
                                                    </>
                                                )
                                            }
                                        </div>
                                    </div>
                                    <div className="form-group row">
                                        <div className="col-md-12">
                                            <textarea className="form-control content" id='comment_content' name="content" placeholder={text.content}></textarea>
                                        </div>
                                    </div>
                                    <div className="form-group row">
                                        <div className="col-md-9">
                                        </div>
                                        <div className="col-md-3 text-right">
                                            <button type="text" className="btn" onClick={addComment} id="add-comment">{text.comment}</button>
                                        </div>
                                    </div>
                                </div>
                            )
                        }
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CommentSuggest;
