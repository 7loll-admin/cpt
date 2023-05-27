import React, { useState } from 'react';
import {Form, Button, Input, Col, Select, Row, Typography} from 'antd';
import {useStateValue} from "../Utils/StateProvider";
import * as Types from "../Utils/actionType";

const {
    Title,
    Paragraph
} = Typography;

const PostTypesAndMetaFields = () => {

    const [ stateValue, dispatch ] = useStateValue();

    let selectedType = Object.entries( stateValue.options.selected_post_types );

    const defauylFields  = [
        [
            {
                value: '',
                label: '',
            },
            {
                value: '',
                label: '',
            }
        ]
    ];

    const getFields = selectedType.map(([key, value]) =>{
        return [
            {
                value: key,
                label: key,
            },
            {
                value: value,
                label: value,
            }
        ];
    });

    const fields = selectedType.length ? getFields : defauylFields;

    const handleChangePostType = ( value, index, field ) => {
        let selectedPostTypes = stateValue.options.selected_post_types;
        const keys = Object.keys( selectedPostTypes );
        const oldKey = keys[index];

        selectedPostTypes = Object.fromEntries(
            Object.entries( selectedPostTypes ).map(([key, val]) => {
                if (key === oldKey) {
                    return [value, ''];
                }
                return [key, val];
            })
        );
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selectedPostTypes
            }
        });

    };

    // Change Meta Key for price.
    const handleChangePostMeta = ( value, index, field ) => {
        let selectedPostTypes = stateValue.options.selected_post_types;
        const keys = Object.keys( selectedPostTypes );
        const currentKey = keys[index];
        selectedPostTypes[currentKey] = value
        // selectedPostTypes
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selectedPostTypes
            }
        });

    };


    const handleAddField = () => {
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: {
                    ...stateValue.options.selected_post_types,
                    '': '',
                }
            }
        });
    };

    const handleRemoveField = postype => {
        const selected_post_types = stateValue.options.selected_post_types;
        delete selected_post_types[postype];
        dispatch({
            type: Types.UPDATE_OPTIONS,
            options: {
                ...stateValue.options,
                selected_post_types: selected_post_types
            }
        });
    };


    return (
        <>
            { fields.map( ( field, index) => (
                <Row key={index} gutter={16} style={{ marginBottom: '16px'}}>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Post Type
                        </Paragraph>
                        <Select
                            size="large"
                            allowClear = {true}
                            placeholder='Post Type'
                            value={field[0].value}
                            style={{ width: '100%', height: '40px' }}
                            options={stateValue.generalData.postTypes}
                            onChange={ ( value ) => handleChangePostType( value, index, field ) }
                        />
                    </Col>
                    <Col className="gutter-row" span={10}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px'}}>
                            Meta Kye for Price
                        </Paragraph>
                        <Select
                            showSearch
                            size="large"
                            allowClear = {true}
                            placeholder='Select Post Meta Key'
                            style={{ width: '100%', height: '40px', padding: 0 }}
                            value={field[1].value}
                            onChange={ ( value ) => handleChangePostMeta( value, index, field ) }
                            options={stateValue.generalData.postTypesMeta}
                        />
                    </Col>
                    <Col className="gutter-row" span={4}>
                        <Paragraph type="secondary" style={{ fontSize: '15px', marginBottom: '7px', opacity: 0 }}>
                          Remove
                        </Paragraph>
                        <Button
                            style={{ width: '100%', height: '40px' }}
                            onClick={() => handleRemoveField( field[0].value )}
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

export default PostTypesAndMetaFields;
