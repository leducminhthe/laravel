import ReactDOM from 'react-dom';
import React, { useState, useEffect } from 'react';
import { Input, Empty } from 'antd';
import Axios from 'axios';
import {
    SearchOutlined
} from '@ant-design/icons';

const AppPromotion = (text) => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-faq`)
            .then((response) => {
                setData(response.data.faqs),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
    }, []);

    return(
        <div className="container faq_body">
            <div id="faq">
                <div className="row m-0">
                    <div className="col-12 text-center m-2">
                        <h1 className="title-faq"><i className="far fa-comments"></i> {text.faq}</h1>
                    </div>
                    <Input className="col-12 my-4"
                        placeholder={text.name_faq}
                        prefix={<SearchOutlined/>}
                        allowClear
                        onChange={(e) => setSearch(e.target.value)}
                    />
                    <div className="col-12 p-1">
                        {
                            loading ? (
                                <div className='row'>
                                    <div className="col-12 ajax-loading text-center mb-5">
                                        <div className="spinner-border" role="status">
                                            <span className="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                            <>
                            {
                                data.length > 0 ? (
                                    <div className="row m-0">
                                        {
                                            data.filter((val) => {
                                                return (
                                                    val.name.toLowerCase().includes(search.toLocaleLowerCase())
                                                )
                                            }).map(item => (
                                                <div key={item.id} className="col-12 p-0 card mb-2 border-0 shadow-sm">
                                                    <a href="" className="" data-toggle="collapse" data-target={`#question${ item.id }`} aria-expanded="true" aria-controls={`question${ item.id }`}>
                                                        <div className="card-header py-2">
                                                            { item.name }
                                                        </div>
                                                    </a>
                                                    <div id={`question${ item.id }`} className="collapse" data-parent="#faq">
                                                        <div className="card-body text-justify" dangerouslySetInnerHTML={{ __html: item.content }}>
                                                        </div>
                                                    </div>
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
        </div>
    )
}

export default AppPromotion

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppPromotion {...text}/>, document.getElementById('react'));
}
