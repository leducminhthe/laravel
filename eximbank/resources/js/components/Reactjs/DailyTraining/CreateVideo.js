import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';    
import Axios from 'axios';
import { Select, Button } from 'antd';

const CreateVideo = ({ text }) => {
    let navigate = useNavigate();
    const { Option } = Select;
    const [categories, setCategories] = useState([]);
    const [category, setCategory] = useState('');
    const [name, setName] = useState('');
    const [hashtag, setHashtag] = useState('');
    const [loading, setLoading] = useState(0);
    const [video, setVideo] = useState(0);

    const selectHandel = (e) => {
        setCategory(e);
    }

    const handleSubmit = async (e) => {
        setLoading(1);
        e.preventDefault();
        try {
            const items = await Axios.post(`/create-video-daily-training`, { category, video, name, hashtag })
            .then((response) => {
                if (response.data.status == 'success') {
                    show_message(response.data.message, response.data.status);
                    navigate('/daily-training-react/0');
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataCategory = async () => {
        try {
            const items = await Axios.get(`/category-daily-training`)
            .then((response) => {
                setCategories(response.data.categories)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataCategory();
        run();
    }, []);

    const run = () => {
        Dropzone.autoDiscover = false;
        $('#dropzone').dropzone({
            url: "/upload-video-daily-training",  
            paramName: "file",
            uploadMultiple: false,
            parallelUploads: 5,
            timeout: 0,
            init: function () {
                var _this = this; 
                this.on("sending", function(files) {
                    $('#file-name').html('Đang xử lý...');
                });
            },
            chunking: true,
            forceChunking: true,
            chunkSize: 5242880, 
            retryChunks: true,   
            retryChunksLimit: 3,
            chunksUploaded: function (file, done) {
                if (done) {
                    var path = JSON.parse(file.xhr.response).path;
                    var path2 =  path.split("/");

                    // var video = $('#video').val(path);
                    savePathVideo(path)
                    $('#file-name').html(path2[path2.length - 1]);
                    $('#save-video').prop('disabled', false);
                }
            }
        });
    }
    
    const savePathVideo = (video) => {
        setVideo(video)
    }

    return (
        <div className="container-fluid add_video_daily_training">
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
                            <span className="font-weight-bold">{text.add_video}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <form onSubmit={handleSubmit} className='pb-4'>
                <div className="form-group">
                    <Select className="col-12" name="category_id"
                        showSearch
                        allowClear
                        placeholder={text.category}
                        onChange={selectHandel}
                        filterOption={(input, option) =>
                            option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
                        }
                    >   
						<Option value={1}>Mặc định</Option>
                    {
                        categories.map((category) => (
                            <Option key={category.id} value={category.id}>{category.name}</Option>
                        ))
                    }
                    </Select>
                </div>
                <div className="form-group">
                    <input type="text" name="name" className="form-control" placeholder={text.enter_name} required value={name} onChange={(e) => setName(e.target.value)}/>
                </div>
                <div className="form-group">
                    <input type="text" name="hashtag" className="form-control" placeholder="hashtag" required value={hashtag} onChange={(e) => setHashtag(e.target.value)}/>
                </div>

                <div className="form-group">
                    <button type="button" className="btn" id='dropzone'><i className="fas fa-upload"></i> Upload</button>
                    <span id="file-name"></span>
                </div>
                <input type="hidden" name="video" defaultValue="" id="video" />

                <div className="form-group">
                    {
                        loading == 0 ? (
                            <button type="submit" className="btn" id="save-video">{text.save}</button>
                        ) : (
                            <Button type="primary" loading>
                                Loading
                            </Button>
                        )
                    }
                </div>
            </form>
        </div>
    );
};

export default CreateVideo;