import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';    
import Axios from 'axios';
import serialize from 'form-serialize';

const CreateThread = ({text}) => {
    const { topic_id } = useParams();
    let navigate = useNavigate();
    const [topic, setTopic] = useState('');
    const [loading, setLoading] = useState(true);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const form = e.currentTarget
        const body = serialize(form, {hash: true, empty: true})
        console.log('submitted!', body)
        try {
            const items = await Axios.post(`/save-thread/${topic_id}`,body)
            .then((response) => {
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    navigate(`/forums-react/topic/${topic.id}`);
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/create-thread/${topic_id}`)
            .then((response) => {
                setTopic(response.data.topic),
                setLoading(false),
                runCkeditor();
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const runCkeditor = () => {
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    }

    useEffect(() => {
        fetchDataItem();
    }, []);

    return (
        <div className="container-fluid">
            <div className="content-main" id="content-main">
                <div className="row">
                    <div className="col-md-12">
                        <div className="ibox-content forum-container">
                            <h2 className="st_title">
                                <a href="/">
                                    <i className="uil uil-apps"></i>
                                    <span>{text.home_page}</span>
                                </a>
                                <i className="uil uil-angle-right"></i>
                                <Link to="/forums-react">{text.forum}</Link>
                                <i className="uil uil-angle-right"></i>
                                {
                                    !loading && (
                                    <>
                                        <Link to={`/forums-react/topic/${topic.id}`}>
                                            { topic.name }
                                        </Link>
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{text.add_post}</span>
                                    </>
                                    )
                                }
                            </h2>
                        </div>
                    </div>
                </div>
                {
                    loading ? (
                        <div className='row m-4'>
                            <div className="col-12 ajax-loading text-center mb-5">
                                <div className="spinner-border" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div> 
                        </div>
                    ) : (
                        <div className="row my-4">
                            <div id="article" className="col-12">
                                <form onSubmit={handleSubmit}>
                                    <input type="hidden" name="type" className="form-control" defaultValue="0" />
                                    <div className="form-group">
                                        <input type="text" name="title" className="form-control" defaultValue="" placeholder={text.title_post}/>
                                    </div>
                                    <div className="form-group">
                                        <input type="text" name="hashtag" className="form-control" defaultValue="" placeholder='hashtag'/>
                                    </div>
                                    <div className="form-group">
                                        <textarea rows="8" id="content" name="content" className="form-control" defaultValue="" />
                                    </div>
                                    <div className="form-group">
                                        <button type="submit" className="btn btn_adcart">{text.send_post}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    )
                }
            </div>
        </div>
    );
};

export default CreateThread;