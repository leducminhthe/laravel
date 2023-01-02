import React, { useState, useEffect } from 'react';
import {Card, Empty} from 'antd';
import Axios from 'axios';
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import {Link} from "react-router-dom";

const UserMaxPoint = ({text}) => {
    const [loading, setLoading] = useState(true);
    const [imagePoromotion, setImagePoromotion] = useState('');
    const [dataUserMaxPoint, setDataUserMaxPoint] = useState([]);

    const fetchDataUserMaxPoint = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-user-max-point?limit=10`)
                .then((response) => {
                    setDataUserMaxPoint(response.data.rows),
                    setImagePoromotion(response.data.image_promotion),
                    setLoading(false)
                })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataUserMaxPoint();
    }, []);

    return(
        <div className="container-fluid" id='promotion'>
            <div className="row">
                <div className="col-12">
                    <div className="row m-0 pt-3">
                        <div className="col-12 ibox-content forum-container">
                            <h2 className="st_title">
                                <a href="/">
                                    <i className="uil uil-apps"></i>
                                    <span>{text.home_page}</span>
                                </a>
                                <i className="uil uil-angle-right"></i>
                                <Link to={`/promotion-react`}>
                                    <span className="font-weight-bold">{text.promotion}</span>
                                </Link>
                            </h2>
                        </div>
                    </div>
                </div>
                <div className="col-md-12 mb-4 mt-3">
                    {
                        loading ? (
                            <div className="col-12 ajax-loading text-center mb-5">
                                <div className="spinner-border" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div>
                        ) : (
                        <>
                        <div className="row">
                            <div className='col-10 mb-2 mt-2'>
                                <span className="font-weight-bold">Top 10 {text.student}</span> {text.high_cumulative_points}
                            </div>
                        </div>
                        {
                            dataUserMaxPoint.length > 0 ? (
                                <div className="row card_promotions">
                                    {
                                        dataUserMaxPoint.map(item => (
                                            <div key={item.user_id} className='col-12 col-md-3 mb-2'>
                                                <Card>
                                                    <div className='text-center'>
                                                        <img className="w-50 rounded-circle" src={item.image_avatar} alt="" height="auto"/>
                                                        <p className='mt-2'>{item.full_name}</p>
                                                        <p>
                                                            <span className='mr-1 point_promotion'> {item.point} </span>
                                                            <img className="point w-5" src={imagePoromotion} alt="" />
                                                        </p>
                                                    </div>
                                                </Card>
                                            </div>
                                        ))
                                    }
                                </div>
                            ) : (
                                <div className='mb-4'>
                                    <Empty />
                                </div>
                            )
                        }
                        </>
                        )
                    }
                </div>
            </div>
        </div>
    )
}

export default UserMaxPoint
