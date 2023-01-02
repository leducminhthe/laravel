import { Col, Row } from "antd";
import React from "react";
import { useLocation, useParams } from "react-router-dom";
const Wait = () => {
    const location = useLocation();
    // const { name } = useParams;
    return (
        <div className="identify">
            <div className="ELhfo"></div>
            <div className="jwZLlr"></div>
            <div className="content">
                <Row
                    justify="center"
                    align="middle"
                    style={{ minHeight: "100vh" }}
                >
                    <Col span={24}>
                        <Row justify="center">
                            <Col span={8} offset={8}>
                                Chào {location.state.name}
                            </Col>
                        </Row>
                        <Row justify="center">
                            <Col span={8} offset={8}>
                                <h3>Vui lòng chờ ...</h3>
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </div>
        </div>
    );
};
export default Wait;
