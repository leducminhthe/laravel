import ReactDOM from 'react-dom';
import React, { useState, useEffect } from 'react';
import { Table } from 'antd';
import Axios from 'axios';
import { DatePicker } from 'antd';

const AppNote = (text) => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [checked, setChecked] = useState([]);
    const [dateSearch, setDateSearch] = useState('');
    const [searchYear, setSearchYear] = useState('');

    const columns = [
        {
            key:"2",
            title: text.date_created,
            dataIndex: 'date_time',
            width: 130,
        },
        {
            key:"3",
            title: text.note,
            dataIndex: 'content',
        },
    ];

    function selectDateHandle(date, dateString) {
        setDateSearch(dateString);
    }
      
    function selectYearHandle(date, dateString) {
        setSearchYear(dateString);
    }

    const rowSelection = {
        onChange: (selectedRowKeys, selectedRows) => {
            if (selectedRows.length > 0 ) {
                $('#delete').prop('disabled',false);
            } else {
                $('#delete').prop('disabled',true);
            }
            console.log('selectedRows: ', selectedRows);
            setChecked(selectedRows);
        },
    };

    const deletedHandle = async () => {
        setLoading(true)
        try {
            const items = await Axios.post(`/remove-note-user`,{ checked })
            .then((response) => {
                setLoading(false),
                setData(response.data.data),
                show_message(response.data.message, response.data.status)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        var btnDelete = $("#delete");
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-note-user?dateSearch=${dateSearch}&searchYear=${searchYear}`)
                .then((response) => {
                    setData(response.data.rows),
                    setLoading(false),
                    btnDelete.prop('disabled', true);
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, [dateSearch, searchYear]);
    
    return(
        <>
            <div className="container-fluid note_container">
                <div className="row">
                    <div className="col-xl-12 col-lg-12 col-md-12">
                        <div className="ibox-content suggest-container">
                            <h2 className="st_title">
                                <a href="/">
                                    <i className="uil uil-apps"></i>
                                    <span>{text.home_page}</span>
                                </a>
                                <i className="uil uil-angle-right"></i>
                                <span className="font-weight-bold">{text.note}</span>
                            </h2>
                            <div className="row my-4">
                                <div className='col-md-3 col-12 mb-1'>
                                    <DatePicker className='w-100' onChange={selectDateHandle} placeholder={text.date_created} />
                                </div>
                                <div className='col-md-3 col-12 mb-1'>
                                    <DatePicker className='w-100' onChange={selectYearHandle} picker="year" placeholder={text.year}/>
                                </div>
                                <div className="col-12 col-md-6 text-right act-btns mb-1">
                                    <div className="pull-right">
                                        <button className="btn cursor_pointer" onClick={deletedHandle} id="delete">
                                            <i className="fa fa-trash"></i> {text.delete}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <Table loading={loading}
                                rowSelection={rowSelection} 
                                columns={columns} 
                                dataSource={data} 
                                pagination={{ pageSize: 20 }} 
                                rowKey="id"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default AppNote

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppNote {...text}/>, document.getElementById('react'));
} 
