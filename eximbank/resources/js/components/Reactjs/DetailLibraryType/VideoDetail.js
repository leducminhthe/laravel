import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Link, useParams } from 'react-router-dom';   

const VideoDetail = ({nameType, type, text }) => {
    const { id } = useParams();
    const [item, setItem] = useState('');
    const [relatedDetail, setRelatedDetail] = useState('');
    const [loading, setLoading] = useState(true);
    const [librariesVideo, setLibrariesVideo] = useState();

    const selectVideo = (id) => {
        var get_attachment = $('#get_attachment_audiobook_'+id).val();
        $('#video').html(`<video class="w-100" controls autoPlay>
                        <source src="`+ get_attachment +`" type="video/mp4">
                        </video>`);
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const getItem = await Axios.get(`/detail-library-video/${id}`)
                .then((response) => {
                    setItem(response.data.item),
                    setRelatedDetail(response.data.related_libraries),
                    setLibrariesVideo(response.data.libraries_videos),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        fetchDataItem();
    }, [id]);

    
    return (
        <>
            <div className="container-fluid" id='detail_libraries'>
                <div className="row">
                    <div className="fcrse_2 mx-3">
                        <div className="_14d25 mb-5">
                            <div className="row mb-2">
                                <div className="col-md-12">
                                    <h2 className="st_title">
                                        <a href="/">
                                            <i className="uil uil-apps"></i>    
                                            <span>{text.home_page}</span>
                                        </a>
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{text.library}</span> 
                                        <i className="uil uil-angle-right"></i>
                                        <Link to={`/library/${type}`} className="font-weight-bold">{nameType}</Link> 
                                        <i className="uil uil-angle-right"></i>
                                        <span className="font-weight-bold">{item.name}</span>
                                    </h2>
                                </div>
                            </div>
                            {
                                loading ? (
                                    <div className="row m-0">
                                        <div className="col-12 ajax-loading text-center mb-5">
                                            <div className="spinner-border" role="status">
                                                <span className="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                ) : (
                                    <>
                                        <div className="row">
                                            <div className="col-md-7">
                                                <div className="library-container row">
                                                    <h1 className="col-9 pl-0">{ item.name }</h1>
                                                    <div className="_ttl121_custom col-3">
                                                        <div className="_ttl123_custom">{text.view} : <span>{ item.views }</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="img-library" id="video">
                                                    <video className="w-100" controls autoPlay>
                                                        <source src={ item.getLinkPlay } type="video/mp4" />
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            </div>
                                            <div className="col-md-5 another_videos">
                                                <input type="hidden" id={`get_attachment_video_`+item.id} defaultValue={ item.attachment} />
                                                <div className="row wrraped_video" onClick={() => selectVideo(item.id)}>
                                                    <div className="col-6">
                                                        <video className="w-100">
                                                            <source src={ item.getLinkPlay } type="video/mp4" />
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                    <div className="col-6">
                                                        <h3 className="name_videos">{ item.name }</h3>
                                                    </div>
                                                </div>
                                                {
                                                    librariesVideo && (
                                                        <>
                                                            {
                                                                librariesVideo.map((value) => (
                                                                    <div key={value.id}>
                                                                        <input type="hidden" id={`get_attachment_video_` + value.id} defaultValue={value.attachment} />
                                                                        <div className="row wrraped_video" id={`videos-` + value.id} onClick={() => selectVideo(value.id)}>
                                                                            <div className="col-6">
                                                                                <video className="w-100">
                                                                                    <source src={ value.getLinkPlay } type="video/mp4" />
                                                                                    Your browser does not support the video tag.
                                                                                </video>
                                                                            </div>
                                                                            <div className="col-sm-6 col-md-6">
                                                                                <h3 className="name_videos">{value.name_video}</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                ))
                                                            }
                                                        </>
                                                    )
                                                }
                                            </div>
                                        </div>
                                        <div className="row mt-10 ml-0">
                                            <div className="col-md-12 description_library_video">
                                                <h2 className="crse14s">
                                                    <span className="description_detail_library">{text.description}</span>
                                                </h2>
                                                <div className='descriptipn_libraries' dangerouslySetInnerHTML={{ __html: item.description }}>
                                                </div>
                                            </div>
                                        </div>
                                    </>
                                )
                            }
                            {
                                relatedDetail.length > 0 && (
                                    <div className="row mt-4 ml-0">
                                        <div className="col-12 mb-2">
                                            <h3 className="related_video_title">
                                                <span>{text.video_same_category}</span>
                                            </h3>
                                        </div>
                                        <>
                                            {
                                                relatedDetail.map((related) => (
                                                    <div key={related.id} className="col-md-3 col-6 p-0">
                                                        <div className="img-library">
                                                            <Link to={`/library/detail-library/${type}/${related.id}`}>
                                                                <img src={ related.image } alt="" width="100%" height="auto" />
                                                                <div className="name_related_detail_video">
                                                                    { related.name }
                                                                </div>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                ))
                                            }
                                        </>
                                    </div>
                                )
                            }
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default VideoDetail;