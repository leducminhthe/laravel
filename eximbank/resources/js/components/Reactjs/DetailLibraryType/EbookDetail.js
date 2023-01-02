import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Link, useParams } from 'react-router-dom';

const EbookDetail = ({nameType, type, text }) => {
    const { id } = useParams();
    const [item, setItem] = useState('');
    const [relatedDetail, setRelatedDetail] = useState('');
    const [loading, setLoading] = useState(true);
    const [statusLibraryObject, setStatusLibraryObject] = useState(1);

    const downloadFile = async () => {
        try {
            const download = await Axios.get(`/count-download-library/${id}`)
            .then((response) => {
                $('#count_download').html(`Tải về : <span>`+ response.data.count_download +`</span>`)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const viewFile = async () => {
        try {
            const download = await Axios.get(`/view-file-library/${id}`)
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const getItem = await Axios.get(`/detail-library-ebook/${id}`)
                .then((response) => {
                    setItem(response.data.item),
                    setRelatedDetail(response.data.related_libraries),
                    setStatusLibraryObject(response.data.check_status_libraries_obj),
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
                            <div className="row">
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
                                            <div className={relatedDetail.length > 0 ? `col-md-9` : `col-md-12`}>
                                                <div className="row">
                                                    <div className="col-md-4 pr-0 mt-3">
                                                        <div className="img-library">
                                                            <img src={ item.image } alt="" width="100%"/>
                                                        </div>
                                                    </div>
                                                    <div className="col-md-8 mt-3">
                                                        <div className="library-container">
                                                            <h3>{ item.name }</h3>
                                                            <div className="_ttl121_custom">
                                                                <div className="_ttl123_custom">{text.view} : <span>{ item.views }</span>
                                                                </div>
                                                            </div>
                                                            <div className="_ttl121_custom">
                                                                <div className="_ttl123_custom" id="count_download">
                                                                    {text.download} : <span>{ item.download }</span>
                                                                </div>
                                                            </div>
                                                            <div className="_ttl121_custom">
                                                                <div className="_ttl123_custom">
                                                                    {
                                                                        item.attachment && statusLibraryObject != 1 && (
                                                                            <a href={ item.getLinkDownload } className="btn btn_adcart" onClick={downloadFile} target="_blank">
                                                                                <i className="fa fa-download"></i> {text.download}
                                                                            </a>
                                                                        )
                                                                    }
                                                                    {
                                                                        statusLibraryObject != 2 && (
                                                                            <>
                                                                                {
                                                                                    item.checkIsFilePdf ? (
                                                                                        <a href={ `/libraries/view-pdf/${item.id}` } onClick={viewFile} target="_blank" className={`btn btn_adcart click-view-doc `+ statusLibraryObject} data-id={item.id} >
                                                                                            <i className="fa fa-eye"></i> {text.view}
                                                                                        </a>
                                                                                    ) : (
                                                                                        item.link_view && (
                                                                                            <a href={`${item.link_view}`} onClick={viewFile} target="_blank" className={`btn btn_adcart click-view-doc `+ statusLibraryObject} data-id={item.id} >
                                                                                            <i className="fa fa-eye"></i> {text.view}
                                                                                            </a>
                                                                                        )
                                                                                    )
                                                                                }
                                                                            </>
                                                                        )
                                                                    }
                                                                    {
                                                                        item.link_file_zip && (
                                                                            <a href={ item.link_file_zip } className="btn btn_adcart" target="_blank">
                                                                                <i className="fa fa-eye"></i> Online
                                                                            </a>
                                                                        )
                                                                    }
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="row mt-20">
                                                    <div className="col-md-12">
                                                        <h2 className="crse14s">
                                                            <span className="description_detail_library">{text.description}</span>
                                                        </h2>
                                                        <div className='descriptipn_libraries' dangerouslySetInnerHTML={{ __html: item.description }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {
                                                relatedDetail && (
                                                    <div className="col-md-3 col-12">
                                                        <div className="col-12 my-2 pl-0">
                                                            <h3 className="related_title">
                                                                {
                                                                    type == 2 ? (
                                                                        <span>{text.ebook_same_category}</span>
                                                                    ) : (
                                                                        <span>{text.document_same_category}</span>
                                                                    )
                                                                }
                                                            </h3>
                                                        </div>
                                                        <div className="row mr-0 related_libraries">
                                                            {
                                                                relatedDetail.map((related) => (
                                                                    <div key={related.id} className="col-12">
                                                                        <div className="img-library">
                                                                            <Link to={`/library/detail-library/${type}/${related.id}`}>
                                                                                <img src={ related.image } alt="" width="100%" height="auto" />
                                                                                <div className="name_related_detail">
                                                                                    { related.name }
                                                                                </div>
                                                                            </Link>
                                                                        </div>
                                                                    </div>
                                                                ))
                                                            }
                                                        </div>
                                                    </div>
                                                )
                                            }
                                        </div>
                                    </>
                                )
                            }
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default EbookDetail;
