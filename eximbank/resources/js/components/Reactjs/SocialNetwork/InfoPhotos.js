import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import LazyLoad from 'react-lazyload';
import { Link } from 'react-router-dom';    
import InfiniteScroll from "react-infinite-scroll-component";

const InfoPhotos = ({ auth, userId }) => {
    const [dataPhotos, setDataPhoto] = useState([]);
    const [hasMore, sethasMore] = useState(true);
    const [page, setPage] = useState(2);

    const fetchDataUserImage = async () => {
        try {
            const items = await Axios.get(`/data-user-image-network/${userId}?page=1`)
            .then((response) => {
                if(response.data.getPhotoByNew.data) {
                    setDataPhoto(response.data.getPhotoByNew.data)
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-user-image-network/${userId}?page=${page}`)
        return res
    };

    const fetchData = async () => {
        if (dataPhotos.length > 0) {
            const dataFormServer = await fetchDataScroll()
            setDataPhoto([...dataPhotos, ...dataFormServer.data.getPhotoByNew.data])
            if (dataFormServer.data.getPhotoByNew.data.length === 0 || dataFormServer.data.getPhotoByNew.data.length < 6) {
                sethasMore(false)
            }
            setPage(page + 1)
        }
    };

    useEffect(() => {
        fetchDataUserImage()
    }, [userId]);

    return (
        <div className="row mx-4 wrraped_content_option">
            <div className="col-12 content_photo bg-white py-3">
                <div className="row">
                    <div className="col-7 d_flex_align">
                        <h3 className='mb-0'><strong>Ảnh</strong></h3>
                    </div>
                    <div className="col-5">
                        <ul className='setting'>
                            <li className='pr-3'>Thêm ảnh</li>
                            <li className='pr-3'><i className="fas fa-ellipsis-h"></i></li>
                        </ul>
                    </div>
                    <div className="col-12">
                        <InfiniteScroll className="row wrapped_all_photo mx-0 mt-3"
                            dataLength={dataPhotos.length}
                            next={fetchData}
                            hasMore={hasMore}
                            style={{ overflow: 'unset'}}
                        >
                        {
                            dataPhotos.map((photo, key) => (
                                <div key={key} className="col-3 p-1">
                                    <LazyLoad>
                                        <Link to={`/social-network/detail/photo/${photo.social_network_new_id}/${photo.id}`}>
                                            <img className='cursor_pointer' src={photo.image} alt="" width={'100%'}/>
                                        </Link>
                                    </LazyLoad>
                                </div>
                            ))
                        }
                        </InfiniteScroll>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default InfoPhotos;