import React, { useState } from 'react';
import {Form, Button, Input, Col, Select, Row, Typography} from 'antd';
import {useStateValue} from "../Utils/StateProvider";
import * as Types from "../Utils/actionType";

const {
    Title,
    Paragraph
} = Typography;

const RepeatedGroupFields = () => {

    const [ stateValue, dispatch ] = useStateValue();

    const defauylFields  = [
        {
            postType: '',
            metaValue: '',
        }
    ];

    const fields = [ ...stateValue.options.selected_post_types, ...defauylFields ]

    // selected_post_types
    console.log( fields )

    const handleAddField = () => {
        const changeValue = [
            {
                postType: 'ONe',
                metaValue: 'ONe',
            }
        ]
        // console.log( changeValue )
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: [
                    ...stateValue.options.selected_post_types,
                    ...changeValue
                ],
                isLoading: false,
            }
        });
    };

    const handleRemoveField = index => {
        // setFields(prevFields => prevFields.filter((field, i) => i !== index));
    };

    return (
        <>
            { fields.map((field, index) => (
                <Row key={index} gutter={16} style={{ marginBottom: '16px'}}>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Post Type
                        </Paragraph>
                        <Select
                            size="large"
                            allowClear = {true}
                            placeholder='Post Type'
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                        />
                    </Col>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Meta Kye for Price
                        </Paragraph>
                        <Select
                            size="large"
                            allowClear = {true}
                            placeholder='Select Post Meta Key'
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                        />
                    </Col>
                    <Col className="gutter-row" span={4}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px', opacity: 0 }}>
                          Remove
                        </Paragraph>
                        <Button
                            style={{ width: '100%', height: '40px' }}
                            onClick={() => handleRemoveField(index)}
                        >Remove</Button>
                    </Col>
                </Row>
            )) }
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
