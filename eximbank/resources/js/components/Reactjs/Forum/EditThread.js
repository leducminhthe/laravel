import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';    
import Axios from 'axios';
import serialize from 'form-serialize';

const EditThread = ({text}) => {
    const { id } = useParams();
    let navigate = useNavigate();
    const [dataThread, setDataThread] = useState('');
    const [loading, setLoading] = useState(true);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const form = e.currentTarget
        const body = serialize(form, {hash: true, empty: true})
        console.log('submitted!', body)
        try {
            const items = await Axios.post(`/save-thread/${id}`,body)
            .then((response) => {
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    navigate(`/forums-react/topic/${dataThread.forum_id}`);
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/edit-thread/${id}`)
            .then((response) => {
                setDataThread(response.data.thread),
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
                                        <Link to={`/forums-react/topic/${dataThread.forum_id}`}>
                                            { dataThread.forum_category }
                                        </Link>
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{text.edit_post}</span>
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
                                    <input type="hidden" name="type" className="form-control" defaultValue="1" />
                                    <div className="form-group">
                                        <input type="text" name="title" className="form-control" defaultValue={ dataThread.title } />
                                    </div>
                                    <div className="form-group">
                                        <input type="text" name="hashtag" className="form-control" defaultValue={ dataThread.hashtag }/>
                                    </div>
                                    <div className="form-group">
                                        <textarea rows="8" id="content" name="content" className="form-control" defaultValue={ dataThread.content } />
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

export default EditThread;