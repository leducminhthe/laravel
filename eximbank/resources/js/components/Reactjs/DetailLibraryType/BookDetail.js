import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Link, useParams } from 'react-router-dom';    

const BookDetail = ({ nameType, type, text }) => {
    const { id } = useParams();
    const [item, setItem] = useState('');
    const [relatedDetail, setRelatedDetail] = useState('');
    const [loading, setLoading] = useState(true);
    const [quantity, setQuantity] = useState(1);
    const [register, setRegister] = useState(0)

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const getItem = await Axios.get(`/detail-library-book/${id}`)
                .then((response) => {
                    setItem(response.data.item),
                    setRelatedDetail(response.data.related_libraries),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        fetchDataItem();
    }, [id]);

    const submitHandler = async (e) => {
        e.preventDefault();
        try {
            const registerBook = await Axios.post(`/register-book-library/${id}`,{ quantity })
            .then((response) => {
                $('.current_book').html(response.data.current_number);
                show_message(response.data.message, response.data.status);
                setRegister(1);
            })
        } catch (error) {
            console.log(error);
        }
        
    }
    
    const setQuantityRegsiter = (value) => {
        if (value == 0) {
            var quantity = $('#quantity');
            var num = parseInt(quantity.val());
            if (num > 1){
                num -= 1;
                quantity.val(num);
            }
        } else {
            var quantity = $('#quantity'),
            num = parseInt(quantity.val()),
            current = parseInt($('.current_book').text());
            if (num < 10 && num < current){
                num += 1;
                quantity.val(num);
            }else{
                Swal.fire({
                    title: 'Số lượng sách phải nhỏ hơn số sách còn lại và tối đa là 10 quyển.'
                })
            }
        }
        setQuantity(num);
    }

    const changeNumber = () => {
        var quantity = $('#quantity');
        var num = parseInt(quantity.val());
        var current = parseInt($('.current_book').text());
        if(current < num){
            Swal.fire({
                title: 'Số lượng sách phải nhỏ hơn số sách còn lại và tối đa là 10 quyển.'
            });
            quantity.val(current);
        }
        setQuantity(num);
    }

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
                                            <div className={relatedDetail.length > 0  ? `col-md-9` : `col-md-12`}>
                                                <div className="row">
                                                    <div className="col-md-4 pr-0 mt-3">
                                                        <div className="img-library">
                                                            <img src={ item.image } alt="" width="100%"/>
                                                        </div>
                                                    </div>
                                                    <div className="col-md-8 mt-3">
                                                        <div className="library-container">
                                                            <form onSubmit={submitHandler} className="">
                                                                <h3>{ item.name }</h3>
                                                                <div className="_ttl121_custom">
                                                                    <div className="_ttl123_custom">{text.num_books_remaining}: 
                                                                        <span className="current_book"> { item.current_number > 0 ? item.current_number : text.it_over }</span>
                                                                    </div>
                                                                </div>
                                                                <div className="_ttl121_custom">
                                                                    <div className="_ttl123_custom">
                                                                        <div className="quantity">
                                                                            <span>{text.amount} :</span>
                                                                            <input type="button" value="-" className="minus" onClick={() => setQuantityRegsiter(0)}/>
                                                                            <input id="quantity" type="number" value={quantity} onChange={changeNumber} step="1" min="1" max="99" title="số lượng sản phẩm muốn mua" className="input-text qty text" size="4" inputMode="number" />
                                                                            <input type="button" value="+" className="plus" onClick={() => setQuantityRegsiter(1)}/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="_ttl121_custom">
                                                                    <div className="_ttl123_custom">
                                                                        <span className="text-danger">* {text.note_time_borrow_book}.</span>
                                                                    </div>
                                                                    <div className="_ttl123_custom">
                                                                        <span className="text-danger">* {text.note_give_back_book}.</span>
                                                                    </div>
                                                                    <div className="_ttl123_custom">
                                                                        <span className="text-danger">* {text.note_contact_give_back_book} : {item.phone_contact}</span>
                                                                    </div>
                                                                </div>
                                                                <div className="_ttl121_custom">
                                                                    <div className="_ttl123_custom">
                                                                        <button type="submit" className="btn btn_adcart register_book">
                                                                            {
                                                                                (item.check_register == 1 || register == 1) ? (
                                                                                    <span>{text.registered}</span>
                                                                                ) : (
                                                                                    <span>{text.register}</span>
                                                                                )
                                                                            }
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {
                                                relatedDetail && (
                                                    <div className="col-md-3 col-12">
                                                        <div className="col-12 my-2 pl-0">
                                                            <h3 className="related_title">
                                                                <span>{text.book_same_category}</span>
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
                                        <div className="row mt-20">
                                            <div className="col-md-12">
                                                <h2 className="crse14s mb-2">
                                                    <span className="description_detail_library">{text.description}</span>
                                                </h2>
                                                <div className="text-justify descriptipn_libraries" dangerouslySetInnerHTML={{ __html: item.description }}>
                                                </div>
                                            </div>
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

export default BookDetail;