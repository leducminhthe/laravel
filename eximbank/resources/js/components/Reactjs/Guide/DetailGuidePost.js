import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';    
import Axios from 'axios';
import { Spin } from 'antd';

const DetailGuidePost = ({text}) => {
    const { id } = useParams();
    const [guide, setGuide] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-post-detail/${id}`)
                .then((response) => {
                    setGuide(response.data.guide),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    return (
        <div className="container-fluid guide-container">
            <div className="row">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content guide-container">
                        <h2 className="st_title mb-4">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to="/guide-react/3" className="font-weight-bold">{text.guide_post}</Link>
                        </h2>
                        {
                            loading ? (
                            <div className='row'>
                                <div className="col-12 text-center mb-5">
                                    <Spin />
                                </div> 
                            </div>
                            ) : (
                            <>
                                <h2 className="mt-1">{ guide.name }</h2>
                                <div dangerouslySetInnerHTML={{ __html: guide.attach }}>
                                </div>
                            </>
                            )
                        }
                        
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DetailGuidePost;