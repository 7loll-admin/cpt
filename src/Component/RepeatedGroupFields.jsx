import React, { useState } from 'react';
import {Form, Button, Input, Col, Select, Row} from 'antd';
import {useStateValue} from "../Utils/StateProvider";

const RepeatedGroupFields = () => {

    const [stateValue, dispatch] = useStateValue();

    const [fields, setFields] = useState([]);

    const handleAddField = () => {
        setFields(prevFields => [...prevFields, {}]);
    };

    const handleRemoveField = index => {
        setFields(prevFields => prevFields.filter((field, i) => i !== index));
    };

    return (
        <>
            {fields.map((field, index) => (
                <Row key={index} gutter={16} style={{ marginBottom: '16px'}}>
                    <Col className="gutter-row" span={10}>
                        <Select
                            size="large"
                            allowClear = {true}
                            placeholder='Post Type'
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                        />
                    </Col>
                    <Col className="gutter-row" span={10}>
                        <Select
                            size="large"
                            allowClear = {true}
                            placeholder='Select Post Meta Key'
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                        />
                    </Col>
                    <Col className="gutter-row" span={4}>
                        <Button
                            style={{ width: '100%', height: '40px' }}
                            onClick={() => handleRemoveField(index)}
                        >Remove</Button>
                    </Col>
                </Row>
            ))}
            <Row gutter={16}>
                <Col className="gutter-row" span={12}>
                    <Button
                        style={{ height: '40px' }}
                        onClick={handleAddField}
                    >Add Field</Button>
                </Col>
            </Row>
        </>
    );
};

export default RepeatedGroupFields;
