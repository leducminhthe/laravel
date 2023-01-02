import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';    
import Axios from 'axios';
import { Empty, Spin, Table } from 'antd';

const Guide = ({text}) => {
    const { type } = useParams();
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-guide/${type}`)
                .then((response) => {
                    setData(response.data.guides),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, []);

    const columns = [
        {
            key:"1",
            title: text.guide,
            dataIndex: 'name',
        },
        {
            key:"2",
            title: text.download,
            dataIndex: 'link_download',
            align: 'center',
            render: (complete) => (
                <a href={complete} target="_blank"><i className="fa fa-download"></i></a>
            ),
        },
        {
            key:"3",
            title: text.watch_online,
            dataIndex: 'path',
            align: 'center',
            render: (complete) => <a href={complete} target="_blank"><i className="fa fa-eye"></i></a>,
        },
    ];

    return (
        <div className="container-fluid guide-container">
            <div className="row">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content guide-container">
                        <h2 className="st_title mb-3">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{text.guide}</span>
                        </h2>
                        {(() => {
                            if (type == 1) {
                                return (
                                    <Table loading={loading}
                                        columns={columns} 
                                        dataSource={data} 
                                        pagination={{ pageSize: 20 }} 
                                        rowKey="id"
                                    />
                                )
                            } else if (type == 2) {
                                return (
                                    <>
                                    {
                                        loading ? (
                                            <div className='row'>
                                                <div className="col-12 text-center mb-5">
                                                    <Spin />
                                                </div> 
                                            </div>
                                        ) : (
                                            <>
                                            {
                                                data.length > 0 ? (
                                                    <>
                                                    {
                                                        data.map((guide) => (
                                                            <center key={guide.id}>
                                                                <h3 className="mt-3">{ guide.name }</h3>
                                                                <video width="70%" height="auto" controls>
                                                                    <source src={ guide.video } type="video/mp4" />
                                                                </video>
                                                            </center>
                                                        ))
                                                    }
                                                    </>
                                                ) : (
                                                    <div className='mb-4'>
                                                        <Empty />
                                                    </div>
                                                )
                                                
                                            }
                                            </>
                                        )
                                    }
                                    </>
                                )
                            } else {
                                return (
                                    <>
                                    {
                                        loading ? (
                                            <div className='row'>
                                                <div className="col-12 text-center mb-5">
                                                    <Spin />
                                                </div> 
                                            </div>
                                        ) : (
                                            <>
                                            {
                                                data.length > 0 ? (
                                                    <>
                                                    {   
                                                        data.map((guide) => (
                                                            <Link key={guide.id} to={`/guide-react/detail-post/${guide.id}`}>
                                                                <div className="all_guide_posts">
                                                                    <h3 className="mb-0">{ guide.name }</h3>
                                                                    <p></p>
                                                                </div>
                                                            </Link>
                                                        ))
                                                    }
                                                    </>
                                                ) : (
                                                    <div className='mb-4'>
                                                        <Empty />
                                                    </div>
                                                )
                                            }
                                            </>
                                        )
                                        
                                    }
                                    </>
                                )
                            }
                        })()}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Guide;