import React, { useState, useEffect } from 'react';
import { Table, DatePicker, Input, Checkbox, message } from 'antd';
import { Link } from 'react-router-dom';
import Axios from 'axios';
import {
    SearchOutlined
} from '@ant-design/icons';

const Suggest = ({text}) => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [search, setSearch] = useState('');

    const changeDateFrom = (date, dateString) => {
        setDateFrom(dateString);
    }

    const changeDateTo = (date, dateString) => {
        setDateTo(dateString);
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value)
    }

    const columns = [
        {
            key:"1",
            title: text.suggest,
            dataIndex: 'name',
        },
        {
            key:"2",
            title: text.date_created,
            dataIndex: 'created_at2',
            align: 'center',
            width: 150,
        },
        {
            key:"3",
            title: text.comment,
            align: 'center',
            width: 100,
            render:(completed) => {
                return (
                    <Link to={`/suggest-react/comment-suggest/${completed.id}`}><i className="uil uil-comment"></i></Link>
                )
            }
        },
        {
            key:"4",
            title: text.answered,
            align: 'center',
            width: 100,
            render:(completed) => {
                if(completed.checked_reply == 1) {
                    return (
                        <Checkbox defaultChecked onChange={(e) => onChangeCheckBox(completed.id, e)}></Checkbox>
                    )
                } else {
                    return (
                        <Checkbox onChange={(e) => onChangeCheckBox(completed.id, e)}></Checkbox>
                    )
                }
            }
        },
    ];

    const onChangeCheckBox = async (id, e) => {
        var checked = e.target.checked
        try {
            const items = await Axios.post('/save-check-reply-suggest',{ id, checked })
            .then((response) => {
                message.success(response.data.message);
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const rowSelection = {
        onChange: (selectedRowKeys, selectedRows) => {
            console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
        },
    };

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/get-suggest?dateFrom=${dateFrom}&dateTo=${dateTo}&search=${search}`)
                .then((response) => {
                    setData(response.data.get_suggests),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }

        fetchDataItem();
    }, [dateFrom, dateTo, search]);

    return (
        <div className="container-fluid suggest_container">
            <div className="row">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content suggest-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{text.suggest}</span>
                        </h2>
                        <div className="row search-course pb-2 m-0">
                            <div className="col-12 my-3">
                                <form className="mb-2 row" id="form-search">
                                    <Input className="col-12 col-md-3 m-1"
                                        placeholder={text.enter_suggest}
                                        prefix={<SearchOutlined />}
                                        allowClear
                                        onPressEnter={(e) => handleKeypress(e)}
                                    />
                                    <DatePicker className="col-12 col-md-2 m-1" onChange={changeDateFrom} />
                                    <DatePicker className="col-12 col-md-2 m-1" onChange={changeDateTo} />
                                </form>
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
    );
};

export default Suggest;
